<?php

namespace App\Http\Controllers;

use App\Models\AssessmentSource;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Test;
use App\Models\TeachingGroup;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with(['papers', 'papers.questions'])->get();
        return Inertia::render('Tests/Index', [
            'tests' => $tests
        ]);
    }

    public function create()
    {
        $assessment_sources = AssessmentSource::all();

        return Inertia::render('Tests/Create', [
            'assessmentSources' => $assessment_sources
        ]);
    }

    public function show(Test $test)
    {
        $test->load(['papers', 'papers.questions', 'grade_boundaries']);
        return Inertia::render('Tests/Show', [
            'test' => $test
        ]);
    }

    public function store()
    {
        /*
         * Data looks like
         *   name
         *   assessmentSource
         *   papers: [ {name, questions: [ { number, area, topic, marks } ] } ]
         *   gradeBoundaries: [ { grade, marks } ]
         *
         *
         * Let's validate!
         */

        // TODO: old input??
        $validated_data = request()->validate([
            'name' => 'required|string',
            'assessmentSource' => 'required|integer|exists:assessment_sources,id',
            'papers' => 'required|array',
            'papers.*.name' => 'string',
            'papers.*.questions' => 'required|array',
            'papers.*.questions.*.number' => 'required|string',
            'papers.*.questions.*.area' => 'required|string',
            'papers.*.questions.*.topic' => 'required|string',
            'papers.*.questions.*.marks' => 'required|integer', // this will probably fail if the data comes through as a string
            'gradeBoundaries' => 'required|array',
            'gradeBoundaries.*.grade' => 'required|string',
            'gradeBoundaries.*.marks' => 'required|integer', // this will probably fail for the same reason as papers.questions.marks
        ]);

        // Create the test
        $test = Test::create([
            'name' => $validated_data['name'],
        ]);

        // Get the assessment source
        $assessment_source = AssessmentSource::findOrFail($validated_data['assessmentSource']);

        // Associate it with the test
        $test->assessment_source()->associate($assessment_source);
        $test->save();

        // Create the papers

        foreach($validated_data['papers'] as $_paper) {
            $paper = $test->papers()->create([
                'name' => $_paper['name'],
            ]);

            // Store the questions
            foreach($_paper['questions'] as $_question) {
                $paper->questions()->create([
                   'number' => $_question['number'],
                    'area' => $_question['area'],
                    'topic' => $_question['topic'],
                    'marks' => $_question['marks']
                ]);
            }
        }

        // Create the grade boundaries

        foreach($validated_data['gradeBoundaries'] as $_grade_boundary) {
            $test->grade_boundaries()->create([
                'grade' => $_grade_boundary['grade'],
                'mark' => $_grade_boundary['marks']
            ]);
        }

        // Should be done...
        return redirect()->route('tests.show');
    }

    public function show_assign(Test $test)
    {

        $test->load(['assessment_source', 'assessment_source.teaching_groups', 'teaching_groups']);
//
//        dd($test);


        return Inertia::render('Tests/Assign', [
            'test' => $test,
            'teachingGroups' => $test->assessment_source->teaching_groups,
        ]);
    }

    public function assign(Test $test)
    {
        /*
         * Data looks like
         * { teachingGroupId }
         *
         * Let's validate!
         */

        $validated_data = request()->validate([
            'teachingGroupId' => 'required|integer',
        ]);

        $teaching_group = TeachingGroup::findOrFail($validated_data['teachingGroupId']);

        // This will create the association if it doesn't already exist, or delete it if it does.
        $test->teaching_groups()->toggle($teaching_group);


        // Should be done...
        // Redirect back to the assignment page for this test
        return redirect()->route('tests.assign.show', [$test]);
    }

}
