<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\Arbor\ArborImportController;

use \App\Models\TeachingGroup;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::patch('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::post('/arbor-import', [ArborImportController::class, 'import']);

Route::get('/teaching-groups', function() {
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
    ]);
});
