<?php

namespace App\Http\Controllers\Arbor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use GraphQL\Query;
use App\GraphQL\Client;

use App\Models\Student;
use App\Models\TeachingGroup;
use App\Models\AssessmentSource;
use App\Models\Baseline;

class ArborImportController extends Controller
{
    protected $gqlClient;

    public function __construct()
    {
        $this->gqlClient = resolve('App\GraphQL\Client');
    }

    public function import_wizard()
    {
        $query = (new Query())
            ->setSelectionSet([
                (new Query('AcademicYear'))
                ->setSelectionSet([
                    'displayName'
                ]),
                (new Query('Subject'))
                ->setSelectionSet([
                    'displayName'
                ])
            ]);

        $data = $this->gqlClient->runQuery($query)->getData();

        $academic_units = null;
        $assessment_sources = null;

        if (request()->has('academic_year') && request()->has('subjects')) {
            $query = (new Query())
                ->setSelectionSet([
                    (new Query('AcademicUnit'))
                        ->setArguments([
                            'academicYear__id' => request()->input('academic_year'),
                            'subject__id_in' => request()->input('subjects')
                        ])
                        ->setSelectionSet([
                            'academicUnitName',
                            'isBottomLevelUnit',
                            (new Query('subject'))
                                ->setSelectionSet(['id', 'displayName'])
                        ]),
                    (new Query('Assessment'))
                        ->setArguments([
                            'subject__id_in' => request()->input('subjects')
                        ])
                        ->setSelectionSet([
                            'assessmentName',
                            (new Query('subject'))
                                ->setSelectionSet(['id'])
                        ])
                ]);

            $data_2 = $this->gqlClient->runQuery($query)->getData();

            $academic_units = array_values(array_filter($data_2->AcademicUnit, function ($academic_unit) {
               return $academic_unit->isBottomLevelUnit;
            }));

            $assessment_sources = $data_2->Assessment;
        }


        return Inertia::render('ArborImportWizard/ArborImportWizard', [
            'academicYears' => $data->AcademicYear,
            'subjects' => $data->Subject,
            'teachingGroups' => $academic_units ? $academic_units : null,
            'assessmentSources' => $assessment_sources ? $assessment_sources : null
        ]);
    }

    public function import()
    {
        // pretend that the server is doing something to see front end effects
//        sleep(10);

        /* data looks like

        academic_year: int
        teaching_groups: [ids]
        assessment_sources: [tg_id => as_id]

        Let's validate!
        */

        $validatedInput = request()->validate([
            'academic_year' => 'required|int',
            'teaching_groups' => 'required|array',
            'teaching_groups.*' => 'required|int',
            'assessment_sources' => 'array',
            'assessment_sources.*' => 'int'
        ]);

        $academic_year = $validatedInput["academic_year"];
        $teaching_groups = $validatedInput["teaching_groups"];
        $assessment_sources = $validatedInput["assessment_sources"];

        // Step 1: Import teaching groups
        // TODO: implement paging - for now, testing will not produce more than one page of results, but in production this could occur.
        $query = (new Query())
            ->setSelectionSet([
                (new Query('AcademicUnit'))
                    ->setArguments([
                    'academicYear__id' => $academic_year,
                    'id_in' => $teaching_groups
                    ])
                    ->setSelectionSet([
                        'academicUnitName',
                        (new Query('students'))
                            ->setSelectionSet([
                                'upn',
                                'id',
                                'firstName',
                                'lastName'
                            ])
                    ]),
                (new Query('Assessment'))
                    ->setArguments([
                        'id_in' => array_values($assessment_sources)
                    ])
                    ->setSelectionSet([
                        'assessmentName'
                    ])
            ]);

        $query_data = $this->gqlClient->runQuery($query)->getData();
        $arbor_assessment_sources = $query_data->Assessment;
        foreach ($arbor_assessment_sources as $arbor_assessment_source)
        {
            // We're using arbor ids as primary keys in MySQL, so check if assessment is already imported, otherwise, import it.
            $assessment_source = AssessmentSource::where('id', $arbor_assessment_source->id)->firstOr(function() use ($arbor_assessment_source) {
                $assessment_source = new AssessmentSource([
                    'id' => $arbor_assessment_source->id,
                    'name' => $arbor_assessment_source->assessmentName
                ]);

                $assessment_source->save();
                return $assessment_source;
            });
        }


        $arbor_teaching_groups = $query_data->AcademicUnit;
        foreach ($arbor_teaching_groups as $arbor_teaching_group)
        {
            // We're using arbor ids as primary keys in MySQL, so check if teaching group is already imported, otherwise, import it.
            $teaching_group = TeachingGroup::where('id', $arbor_teaching_group->id)->firstOr(function () use ($arbor_teaching_group) {
                $teaching_group = new TeachingGroup([
                    'id' => $arbor_teaching_group->id,
                    'name' => $arbor_teaching_group->academicUnitName
                ]);

                $teaching_group->save();
                return $teaching_group;
            });

            $student_ids = [];
            // Now we need to loop through the students in this teaching group
            foreach ($arbor_teaching_group->students as $arbor_student) {
                // We're using upns as primary keys in MySQL, so check if student is already imported, otherwise, import them.
                $student = Student::where('upn', $arbor_student->upn)->firstOr(function () use ($arbor_student) {
                    $student = new Student([
                        'upn' => $arbor_student->upn,
                        'first_name' => $arbor_student->firstName,
                        'last_name' => $arbor_student->lastName
                    ]);

                    $student->save();
                    return $student;
                });

                // Attach this student to the current teaching group if they are not already in it.
                if (!$teaching_group->students->contains($student->upn)){
                    $teaching_group->students()->attach($student);
                }

                // Add student to ids array for baseline fetch
                array_push($student_ids, $arbor_student->id);
            }

            // Next, we need to associate the assessment source, if one was provided. Check $assessment_sources array.
            if (array_key_exists($teaching_group->id, $assessment_sources)) {
                // then we have an assessment source, fetch it
                // TODO: wrap in try-catch block for water tight graceful errors
                $assessment_source = AssessmentSource::find($assessment_sources[$teaching_group->id]);
                $teaching_group->assessment_source()->associate($assessment_source);
                $teaching_group->save();

                // Finally, we need to import the baselines for each student in the teaching group for this assessment source
                $baseline_query = (new Query())
                    ->setSelectionSet([
                        (new Query('StudentProgressBaseline'))
                            ->setArguments([
                                'academicYear__id' => $academic_year,
                                'assessment__id' => $assessment_source->id,
                                'student__id_in' => $student_ids,
                            ])
                            ->setSelectionSet([
                                'id',
                                (new Query('student'))
                                    ->setSelectionSet([
                                        'upn'
                                    ]),
                                (new Query('grade'))
                                    ->setSelectionSet([
                                        'grade'
                                    ])
                            ])
                    ]);

                $baseline_data = $this->gqlClient->runQuery($baseline_query)->getData();
                $arbor_baselines = $baseline_data->StudentProgressBaseline;

                foreach($arbor_baselines as $arbor_baseline)
                {
                    $baseline = Baseline::where('id', $arbor_baseline->id)->firstOr(function() use ($arbor_baseline) {
                        $baseline = new Baseline([
                            'id' => $arbor_baseline->id,
                            'grade' => $arbor_baseline->grade->grade
                        ]);

                        return $baseline;
                    });

                    // Now associate this baseline to the student.
                    // TODO: wrap in try-catch block for water tight graceful errors
                    $student = Student::find($arbor_baseline->student->upn);
                    $baseline->student()->associate($student);
                    $baseline->save();

                    // Now associate it with the assessment source.
                    $assessment_source->baselines()->save($baseline);
                    $assessment_source->save();

                }
            }
        }

        // With a bit of luck, everything should have imported. Moment of truth....
        return Inertia::render('ArborImportWizard/ArborImportDone');

        // TODO: some way of storing each import log... in a file probs, or new db table??
    }
}
