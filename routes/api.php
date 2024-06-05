<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\ExamGroupController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::group(['prefix' => 'auth'], function($router){
    Route::any('/login', [AuthController::class, 'login'])->name("login");
    Route::get('/logout', [AuthController::class, 'logout'])->name("logout");
    Route::any('/register', [AuthController::class, 'register'])->name("register");
    
});

Route::group(['middleware' => 'auth:api'], function($router){
// User Panel Routes Start
Route::get('/get_subjects', [SubjectController::class, 'get_subjects']);
Route::post('/get_practice_question', [QuestionController::class, 'get_practice_question']);
Route::post('/get_units', [QuestionController::class, 'get_units']);
Route::get('/get_all_subjects', [SubjectController::class, 'get_all_subjects']);
Route::post('/get_selected_books', [BookController::class, 'get_selected_books']);
Route::post('/add_test', [SettingController::class, 'add_test']);
Route::get('/get_setting', [SettingController::class, 'get_setting']);
Route::get('/get_setting_with_units', [SettingController::class, 'get_setting_with_units']);
Route::post('/save_test_logs', [LogController::class, 'save_test_logs']);
 // User Panel Routes End
});  

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-levels', [LevelController::class, 'index']);
Route::get('/get-subjects', [SubjectController::class, 'index']);
Route::get('/get-syllabus', [SyllabusController::class, 'index']);
Route::get('/get-units', [UnitController::class, 'index']);
Route::POST('/add-new-unit', [UnitController::class, 'add_unit_of_book']);
Route::get('/get-years', [YearController::class, 'index']);
Route::get('/get-exam-years', [YearController::class, 'get_exam_years']);
Route::post('/add-new-year', [YearController::class, 'addYear']);
Route::get('/get-exam-groups', [ExamGroupController::class, 'index']);
Route::get('/get-groups', [ExamGroupController::class, 'getGroups']);
Route::get('/get-specific-subjects/{id}', [SubjectController::class, 'get_specific_subject']);
Route::post('/get-specific-units', [UnitController::class, 'get_specific_units']);
Route::get('/get-units-of-book/{id}', [UnitController::class, 'get_units_of_book']);
Route::post('/add-question', [QuestionController::class, 'add_question']);
Route::post('/add-question-of-book', [QuestionController::class, 'addQuestion']);
Route::post('/add_new_book', [BookController::class, 'add_new_book']);
Route::post('/get_books', [BookController::class, 'get_books']);
Route::get('/get-books', [BookController::class, 'getBooks']);
Route::put('/change_status_book/{id}', [BookController::class, 'change_status_book']);
Route::put('/update_book/{id}', [BookController::class, 'update_book']);
Route::POST('/add-new-topic', [TopicController::class, 'add_topic_of_unit']);
Route::GET('/get-topics-of-unit/{unitId}', [TopicController::class, 'get_topics_of_unit']);
Route::POST('/add-new-board', [BoardController::class, 'add_new_board']);
Route::get('/get-boards', [BoardController::class, 'getBoards']);
Route::get('/get-sessions', [SessionController::class, 'getSessions']);

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});
