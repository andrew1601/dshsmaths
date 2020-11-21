<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\TeachingGroup;

class TeachingGroupController extends Controller
{
    public function index()
    {
        $teaching_groups = TeachingGroup::all();

        return Inertia::render('TeachingGroups/Index', [
            'teachingGroups' => $teaching_groups,
        ]);
    }

    public function show(TeachingGroup $teaching_group)
    {
        $teaching_group->load([
            'assessment_source',
            'students',
            'students.baselines' => function($query) use($teaching_group) {
                $query->where('assessment_source_id', $teaching_group->assessment_source_id);
            }
        ]);

        return Inertia::render('TeachingGroups/TeachingGroup', [
            'teachingGroup' => $teaching_group
        ]);
    }
}

/*
   function() {
    $teaching_groups = TeachingGroup::all();

    $teaching_group = null;

    if (request()->has('tg')) {
        $teaching_group = TeachingGroup::with(['assessment_source', 'students'])->where('id', request()->input('tg'))->firstOrFail();

        $teaching_group->load(['students.baselines' => function($query) use($teaching_group) {
            $query->where('assessment_source_id', $teaching_group->assessment_source->id);
        }]);
    }

    return Inertia::render('TeachingGroups/Index', [
        'teachingGroups' => $teaching_groups,
        'teachingGroup' => $teaching_group
 */
