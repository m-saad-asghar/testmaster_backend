<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\ExamGroupController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-levels', [LevelController::class, 'index']);
Route::get('/get-subjects', [SubjectController::class, 'index']);
Route::get('/get-syllabus', [SyllabusController::class, 'index']);
Route::get('/get-units', [UnitController::class, 'index']);
Route::get('/get-years', [YearController::class, 'index']);
Route::get('/get-exam-years', [YearController::class, 'get_exam_years']);
Route::get('/get-exam-groups', [ExamGroupController::class, 'index']);
Route::get('/get-specific-subjects/{id}', [SubjectController::class, 'get_specific_subject']);
Route::post('/get-specific-units', [UnitController::class, 'get_specific_units']);
Route::get('/get-units-of-book/{id}', [UnitController::class, 'get_units_of_book']);
Route::post('/add-question', [QuestionController::class, 'add_question']);
Route::post('/add_new_book', [BookController::class, 'add_new_book']);
Route::get('/get-books', [BookController::class, 'get_books']);
Route::get('/get-topics-of-unit/{id}', [TopicController::class, 'get_topics_of_unit']);
