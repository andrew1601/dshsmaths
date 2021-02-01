<?php

namespace App\Http\Controllers;

use App\Models\TeachingGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Cohort;

class CohortController extends Controller
{
    public function index()
    {
        $cohorts = Cohort::with('teaching_groups')->get();

        $orphaned_groups = TeachingGroup::doesntHave('cohort')->get();

        return Inertia::render('Cohorts/Index', ['cohorts' => $cohorts, 'orphanedGroups' => $orphaned_groups]);
    }

    public function store()
    {
        $validated_data = request()->validate([
            'name' => 'required|string'
        ]);

        $cohort = Cohort::create($validated_data);

        return redirect()->route('cohorts.index');
    }

    public function destroy(Cohort $cohort)
    {
        $cohort->delete();

        return redirect()->route('cohorts.index');
    }

    public function update(Cohort $cohort)
    {
        $validated_data = request()->validate([
            'teaching_group' => 'required'
        ]);

        $teaching_group = TeachingGroup::findOrFail($validated_data['teaching_group']);

        $cohort->teaching_groups()->save($teaching_group);

        return redirect()->route('cohorts.index');
    }
}
