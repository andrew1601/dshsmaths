<?php

namespace App\Http\Controllers;

use App\Models\AssessmentSource;
use App\Models\Cohort;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Test;
use App\Models\TeachingGroup;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::with(['assessment_source', 'papers', 'papers.questions', 'teaching_groups'])->get();
        return Inertia::render('Tests/Index', [
            'tests' => $tests
        ]);
    }

    public function create()
    {
        $assessment_sources = AssessmentSource::all();
        $cohorts = Cohort::all();

        return Inertia::render('Tests/Create', [
            'assessmentSources' => $assessment_sources,
            'cohorts' => $cohorts
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
            'cohort' => 'required|integer|exists:cohorts,id',
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
            'cohort_id' => $validated_data['cohort']
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
            $rank = 0;
            foreach($_paper['questions'] as $_question) {
                $paper->questions()->create([
                    'rank' => $rank++,
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
        // Thanks for playing, see you again soon.
        return redirect()->route('tests.index');
    }

    public function show_assign(Test $test)
    {

        $test->load(['assessment_source', 'assessment_source.teaching_groups', 'teaching_groups']);

        // We are only returning the teaching groups associated with the same assessment source as the test.
        // But we only want the teaching groups not currently assigned.
        $teaching_groups = $test->assessment_source->teaching_groups;
        $teaching_groups = $teaching_groups->filter(function ($teaching_group) use ($test) {
            return !($test->teaching_groups->contains($teaching_group));
        })->values(); // Call values() to re-key the underlying array with consecutive keys starting at 0. This prevents front end bug where it was treating a collection with a single teaching groups as an object.

        return Inertia::render('Tests/Assign', [
            'test' => $test,
            'teachingGroups' => $teaching_groups,
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
        // NOTE: the check on assessment sources matching between test and teaching group is not performed. Shouldn't be a problem, that's only for effective filtering of
        // a large amount of possible teaching groups.
        $test->teaching_groups()->toggle($teaching_group);


        // Should be done...
        // Redirect back to the assignment page for this test
        return redirect()->route('tests.assign.show', [$test]);
    }

    public function edit(Test $test)
    {
        $test->load(['papers', 'papers.questions', 'grade_boundaries']);
        $assessment_sources = AssessmentSource::all();

        return Inertia::render('Tests/Create', [
            'assessmentSources' => $assessment_sources,
            'editing' => true,
            'test' => $test,
        ]);
    }

    public function destroy(Test $test)
    {
        // We want to check the delete was confirmed, there should be a confirmText parameter in the query string
        if(request()->has('confirmText') && request()->query('confirmText') == "DELETE")
        {
            $test->delete();
            return redirect()->route('tests.index');
        }
    }

}
