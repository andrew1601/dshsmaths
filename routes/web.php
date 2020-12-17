<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\Arbor\ArborImportController;
use \App\Http\Controllers\TeachingGroupController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DataEntryController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ExportController;

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
Route::get('/', function() {
    return Inertia::render('Index');
});

Route::get('/arbor-import', [ArborImportController::class, 'import_wizard'])->name('arbor-import.index');
Route::patch('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::post('/arbor-import', [ArborImportController::class, 'import']);

Route::get('/teaching-groups', [TeachingGroupController::class, 'index'])->name('teaching-groups.index');
Route::get('/teaching-groups/{teaching_group}', [TeachingGroupController::class, 'show'])->name('teaching-groups.show');

Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
Route::post('tests', [TestController::class, 'store']);
Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');
//Front end isn't playing ball. Can't be arsed to look at it anymore, it's making me angry. TODO: fix this before production.
//Route::get('/tests/{test}/edit', [TestController::class, 'edit']);
Route::delete('/tests/{test}', [TestController::class, 'destroy']);
Route::get('/tests/{test}/assign', [TestController::class, 'show_assign'])->name('tests.assign.show');
Route::patch('/tests/{test}/assign', [TestController::class, 'assign'])->name('tests.assign');
Route::delete('/tests/{test}/assign', [TestController::class, 'assign'])->name('tests.assign.delete');

Route::get('/data-entry', [DataEntryController::class, 'index'])->name('data-entry.index');
Route::get('/data-entry/paper/{paper}/teaching-group/{teaching_group}', [DataEntryController::class, 'show_spreadsheet'])->name('data-entry.spreadsheet.show');

Route::patch('/marks', [MarkController::class, 'save']);

Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
Route::get('/analysis/test/{test}/teaching-group/{teaching_group}/student/{student}', [AnalysisController::class, 'show'])->name('analysis.show');

Route::get('/pdf-test', [ExportController::class, 'test']);
