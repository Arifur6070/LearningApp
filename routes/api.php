<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SendEmailController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'

    //displayCourseFromSearchKey
], function ($router) {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/create-user', [RegistrationController::class, 'createUser']);
    Route::post('/refresh', [LoginController::class, 'refresh']);
    Route::get('/user-profile', [LoginController::class, 'userProfile']);
    Route::post('/change-password', [TeacherController::class, 'updatePassword']);
    Route::put('/update-profile/{id}', [TeacherController::class, 'updateProfile']);
    Route::get('/get-user/{id}', [UserController::class, 'getUser']);
    Route::post('/enroll-student', [StudentController::class, 'postEnrolledValue']);//enrolling in a course
    Route::get('/courses', [CourseController::class, 'showAllCourses']);
    Route::post('/courses', [CourseController::class, 'createCourse']);
    Route::get('/courses/course-search-result/{searchKey}', [CourseController::class, 'displayCourseFromSearchKey']);
    Route::get('/courses/{id}', [CourseController::class, 'displayCourseDetails']);
    Route::get('/class-schedule/{id}', [StudentController::class, 'showEnrolledCourses']);
    Route::get('/taught-courses/{id}', [TeacherController::class, 'showTeachingCourses']);
    Route::post('/upload', [TeacherController::class, 'storeDocument']);
    Route::post('/create-course', [CourseController::class, 'createCourse']);
    Route::get('/subjects', [CourseController::class, 'getAllSubjects']);
    // Route::get('/send-email', [SendEmailController::class, 'sendEmail']);
}
);
