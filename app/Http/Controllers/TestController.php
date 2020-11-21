<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Test;
use App\Models\Paper;
use App\Models\Question;
use App\Models\GradeBoundary;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::all();
        return Inertia::render('Tests/Index', [
            'tests' => $tests
        ]);
    }

    public function create()
    {
        return Inertia::render('Tests/Create');
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
         *   papers: [ {name, questions: [ { number, area, topic, marks } ] } ]
         *   gradeBoundaries: [ { grade, marks } ]
         *
         *
         * Let's validate!
         */

        // TODO: old input??
        $validated_data = request()->validate([
            'name' => 'required|string',
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
            'name' => $validated_data['name']
        ]);

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
        dd('done');
    }
}
