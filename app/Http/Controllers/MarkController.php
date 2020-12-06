<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Question;

class MarkController extends Controller
{
    public function save()
    {
        /*
         * Data looks like
         * {marks: [{student_upn, question_id, mark?}]
         *
         * Let's validate!
         */

        $validated_data = request()->validate([
            'marks' => 'required|array',
            'marks.*.student_upn' => 'required|string|exists:students,upn',
            'marks.*.question_id' => 'required|integer|exists:questions,id',
            'marks.*.mark' => 'present|nullable'
        ]);
        $marks = $validated_data['marks'];
        // Loop through marks
        foreach($marks as $mark)
        {
            // Get Student and Question
            $student = Student::find($mark['student_upn']);
            $question = Question::find($mark['question_id']);
            // First, see if there is a mark there already.
            $mark_count = Mark::forStudent($student)->forQuestion($question)->count();
            if ($mark_count == 1) {
                // A mark already exists, change it.
                $_mark = Mark::forStudent($student)->forQuestion($question)->first();
                // If the new mark is null, we don't need it in the database.
                if ($mark['mark'] == null)
                    $_mark->delete();
                else {
                    $_mark->mark = $mark['mark'];
                    $_mark->save();
                }
            } else if ($mark_count == 0) {
                // No mark exists, create it
                // but only if the mark isn't null
                if ($mark['mark'] != null) {
                    $_mark = Mark::create([
                        'student_upn' => $mark['student_upn'],
                        'question_id' => $mark['question_id'],
                        'mark' => $mark['mark']
                    ]);
                    $_mark->save();
                }
            } else {
                // Something has gone very wrong, we shouldn't have more than 1 mark for a student per question throw a 500
                return response()->json(['error' => 'More than 1 mark for that student on that question!'], 500);
            }
        }

        // Should be done. Send a success response.
        return response()->json(['success' => 'Mark added.'], 200);

    }
}
