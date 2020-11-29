<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\Arbor\ArborImportController;
use \App\Http\Controllers\TeachingGroupController;
use App\Http\Controllers\TestController;

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
Route::get('/', function() {
    return Inertia::render('Index');
});

Route::get('/arbor-import', [ArborImportController::class, 'import_wizard'])->name('arbor-import');
Route::patch('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::post('/arbor-import', [ArborImportController::class, 'import']);

Route::get('/teaching-groups', [TeachingGroupController::class, 'index']);
Route::get('/teaching-groups/{teaching_group}', [TeachingGroupController::class, 'show']);

Route::get('/tests', [TestController::class, 'index'])->name('tests.show');
Route::post('tests', [TestController::class, 'store']);
Route::get('/tests/create', [TestController::class, 'create']);
Route::get('/tests/{test}', [TestController::class, 'show']);
//Front end isn't playing ball. Can't be arsed to look at it anymore, it's making me angry. TODO: fix this before production.
//Route::get('/tests/{test}/edit', [TestController::class, 'edit']);
Route::delete('/tests/{test}', [TestController::class, 'destroy']);
Route::get('/tests/{test}/assign', [TestController::class, 'show_assign'])->name('tests.assign.show');
Route::patch('/tests/{test}/assign', [TestController::class, 'assign'])->name('tests.assign');
Route::delete('/tests/{test}/assign', [TestController::class, 'assign'])->name('tests.assign.delete');
