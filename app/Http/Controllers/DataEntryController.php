<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Test;
use App\Models\TeachingGroup;
use App\Models\Mark;

class DataEntryController extends Controller
{
    public function index()
    {
        $tests = Test::with(['teaching_groups', 'papers', 'papers.questions'])->get();

        return Inertia::render('DataEntry/Index', [
            'tests' => $tests
        ]);
    }

    public function show_spreadsheet(Paper $paper, TeachingGroup $teaching_group)
    {
         $paper->load(['test', 'questions']);
         $teaching_group->load(['students']);

//         $marks = Mark::forStudents($teaching_group->students)->forPaper($paper)->get();
        $marks = [];
        $data_schema = [];

        foreach($paper->questions as $question) {
            $data_schema[$question->id] = null;
        }

        // Generate spreadsheet
        foreach($teaching_group->students as $student) {
            $student_row = ["upn" => $student->upn];

            foreach($paper->questions as $question) {
                 $mark = Mark::forStudent($student)->forQuestion($question)->firstOr(function () {
                    return null;
                });

                $student_row[$question->id] = $mark ? $mark->mark : null;
            }
            array_push($marks, $student_row);
        }


         return Inertia::render('DataEntry/Spreadsheet', [
             'paper' => $paper,
             'teachingGroup' => $teaching_group,
             'marks' => $marks,
             'dataSchema' => $data_schema
         ]);
    }
}
