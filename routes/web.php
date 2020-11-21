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

Route::get('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::patch('/arbor-import', [ArborImportController::class, 'import_wizard']);
Route::post('/arbor-import', [ArborImportController::class, 'import']);

Route::get('/teaching-groups', [TeachingGroupController::class, 'index']);
Route::get('/teaching-groups/{teaching_group}', [TeachingGroupController::class, 'show']);

Route::get('/tests', [TestController::class, 'index']);
Route::post('tests', [TestController::class, 'store']);
Route::get('/tests/{test}', [TestController::class, 'show']);

Route::get('/tests/create', [TestController::class, 'create']);
