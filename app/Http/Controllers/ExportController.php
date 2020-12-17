<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

use App\Models\Student;
use App\Models\Test;
use App\Models\TeachingGroup;

class ExportController extends Controller
{
    public function test()
    {
        $teaching_group = TeachingGroup::find(846);
        $student = Student::find("D207000018054");
        $test = Test::find(1);

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



        $pdf = PDF::loadView('pdf', ['analysis' => $analysis]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }
}
