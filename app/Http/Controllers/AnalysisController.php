<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Test;
use App\Models\Student;
use App\Models\TeachingGroup;
use App\Models\Mark;

class AnalysisController extends Controller
{

    public function index() {
        $tests = Test::with(['papers', 'teaching_groups', 'teaching_groups.students'])->get();

        return Inertia::render('Analysis/Index', [
            'tests' => $tests
        ]);
    }

    public function show(Test $test, TeachingGroup $teaching_group, Student $student) {
        $tests = Test::with(['papers', 'teaching_groups', 'teaching_groups.students'])->get();

        $test->load(['papers', 'papers.questions', 'grade_boundaries']);

        $student->baseline = $student->baseline_for_assessment_source($test->assessment_source);
        $total_marks = 0;
        $marks = [];



        foreach($test->papers as $paper) {
            $total_marks += Mark::forStudent($student)->forPaper($paper)->sum('mark');
            $marks[$paper->id] = Mark::forStudent($student)->forPaper($paper)->get();
        }

        $grade = $test->grade_from_total_marks($total_marks);

        $analysis = [
            'test' => $test,
            'teachingGroup' => $teaching_group,
            'student' => $student,
            'marks' => $marks,
            'total_marks' => $total_marks,
            'grade' => $grade,
        ];

        return Inertia::render('Analysis/Index', [
            'tests' => $tests,
            'analysis' => $analysis,
        ]);


    }
}
