<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;

//use App\Http\Controllers\Manager\LessonController as ManagerSetController;
//use App\Http\Controllers\Manager\CourseController as ManagerFolderController;
//use App\Http\Controllers\Manager\UserController as ManagerUserController;
//use App\Http\Controllers\Manager\SchoolController as ManagerSchoolController;
//use App\Http\Controllers\Manager\ClassController as ManagerClassController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\LessonController;
use App\Http\Controllers\Public\CourseController;
use App\Http\Controllers\Public\ClassController;
use App\Http\Controllers\Public\UserController;
use App\Http\Controllers\Public\QuizController;
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

/**
 * Route for authentication.
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [Authentication::class, 'login']);
    Route::post('register', [Authentication::class, 'register']);
    Route::post('forgot-password', [Authentication::class, 'verify']);
    Route::prefix('logout')->middleware('auth:sanctum')->group(function () {
        Route::post('current', [Authentication::class, 'logout']);
        Route::post('all', [Authentication::class, 'logoutAll']);
    });
    Route::prefix('email')->group(function () {
        Route::get('verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
        Route::post('handle-verify/{id}/{hash}', [VerificationController::class, 'handleVerifyEmail'])->middleware(['auth:sanctum']);
        Route::post('resend', [VerificationController::class, 'resendVerifyEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    });
    Route::post('forgot-password', [Authentication::class, 'forgotPassword']);
    Route::post('reset-password', [Authentication::class, 'resetPassword']);
});
/**
 * Route to get meta data.
 */
Route::get('/meta', [SettingController::class, 'meta']);

/**
 * Route for authenticated user.
 */
Route::middleware('auth:sanctum')->group(function () {
    // Route for admin
    Route::prefix('admin')->group(function () {
        Route::get('analytics', [AnalyticsController::class, 'getAnalytics']);
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']);
            Route::post('/{key}', [SettingController::class, 'update']);
        });
        Route::resource('lesson', AdminLessonController::class);
        Route::resource('course', AdminCourseController::class);
        Route::resource('user', AdminUserController::class);
        Route::resource('role', AdminRoleController::class);
        Route::resource('school', AdminSchoolController::class);
        //Route::resource('class.js', AdminClassController::class.js);
    })->middleware(['permissions:admin.dashboard']);

    // Route for school
//    Route::prefix('school')->group(function () {
//        Route::resource('user', ManagerUserController::class.js)->only(['index', 'store']);
//        Route::resource('class.js', ManagerClassController::class.js);
//        Route::resource('school', ManagerSchoolController::class.js)->only(['show', 'update']);
//        Route::resource('lesson', ManagerSetController::class.js);
//        Route::resource('folder', ManagerFolderController::class.js);
//    })->middleware(['permissions:manager.dashboard']);

    // Route lesson
    Route::prefix('lesson')->group(function () {
        Route::post('/quiz', [QuizController::class, 'store']);
        Route::get('/quiz/{id}', [QuizController::class, 'show']);
        Route::put('/quiz/{id}', [QuizController::class, 'update']);
        Route::delete('/quiz/{id}', [QuizController::class, 'destroy']);
        Route::get('/quiz/export/{id}/', [QuizController::class, 'exportToExcel']);
        Route::get('/learn/{id}', [LessonController::class, 'learn']);
        Route::post('/', [LessonController::class, 'store']);
        Route::get('/{id}', [LessonController::class, 'show']);
        Route::put('/{id}', [LessonController::class, 'update']);
        Route::delete('/{id}', [LessonController::class, 'destroy']);
        Route::post('/import', [LessonController::class, 'import']);
        Route::get('/export/{id}', [LessonController::class, 'export']);
    });
    // Route course
    Route::prefix('course')->group(function () {
        Route::post('/', [CourseController::class, 'store']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::put('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
        Route::get('/{id}/lesson', [LessonController::class, 'showAllLessonByCourseId']);
        Route::post('/add/{course_id}/{lesson_id}', [CourseController::class, 'addLessonToCourse']);
        Route::delete('/remove/{course_id}/{lesson_id}', [CourseController::class, 'removeLessonFromCourse']);
    });
    // Route user
    Route::resource('user', UserController::class)->only(['show', 'update', 'destroy']);
});
