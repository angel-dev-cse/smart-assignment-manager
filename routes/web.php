<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Carbon\Traits\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('teacher.dashboard');
// })->middleware(['role:teacher'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/apply/teacher/delete', [TeachController::class, 'delete'])->name('teacher.application.delete');
    Route::get('/verification', function () {
        return view('auth.verify-account');
    })->name('verification.page');
    Route::post('/verification/reapply', [RegisteredUserController::class, 'reapply'])->name('verification.reapply');
    Route::get('/chats', 'ChatController@index')->name('chats.index');
    Route::post('/chats/{chat}/send', [ChatController::class, 'sendMessage'])->name('chats.send');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::get('/get-chat-id/{id}', [ChatController::class, 'getChatId'])->name('chats.getId');
    Route::get('/get-user-name/{id}', function ($id) {
        return Auth::user()->name;
    })->name('user.getName');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/registration/index', [AdminController::class, 'registrations'])->name('registration.index');
    Route::post('/registration/verify', [AdminController::class, 'verifyRegistration'])->name('registration.verify');
    Route::post('/enrollment/verify', [EnrollmentController::class, 'verify'])->name('enrollment.verify');
    Route::post('/teach/verify', [TeachController::class, 'verify'])->name('teach.verify');
    Route::get('/course/index', [CourseController::class, 'index'])->name('course.index');
    Route::post('/course/store', [CourseController::class, 'store'])->name('course.store');
    Route::post('/course/update', [CourseController::class, 'update'])->name('course.update');
    Route::post('/course/delete', [CourseController::class, 'destroy'])->name('course.delete');
    Route::get('/department/index', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
    Route::post('/department/update', [DepartmentController::class, 'update'])->name('department.update');
    Route::post('/department/delete', [DepartmentController::class, 'destroy'])->name('department.delete');
    Route::get('/application/index', [ApplicationController::class, 'index'])->name('application.index');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/apply/teacher', [TeachController::class, 'index'])->name('teacher.application');
    Route::post('/apply/teacher', [TeachController::class, 'store'])->name('teacher.application.store');
    Route::post('/assignment/create', [AssignmentController::class, 'store'])->name('assignment.create');
    Route::post('/assignment/update', [AssignmentController::class, 'update'])->name('assignment.update');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/apply/student', [EnrollmentController::class, 'index'])->name('student.application');
    Route::post('/apply/student', [EnrollmentController::class, 'store'])->name('student.application.store');
    Route::post('/apply/student/delete', [EnrollmentController::class, 'delete'])->name('student.application.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/notification/{notification}', [NotificationController::class, 'show'])->name('notification.show');
});

Route::middleware(['auth', 'assignment.access'])->group(function () {
    Route::get('/assignment/{id}', [AssignmentController::class, 'show'])->name('assignment.show');
    Route::post('/assignment/delete', [AssignmentController::class, 'delete'])->name('assignment.delete');
    Route::post('/submission/submit', [SubmissionController::class, 'submit'])->name('submission.submit');
});

Route::middleware(['auth', 'submission.access'])->group(function () {
    Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');
    Route::post('/submission/update', [SubmissionController::class, 'update'])->name('submission.update');
    Route::post('/review', [SubmissionController::class, 'submitReview'])->name('review.submit');
});

Route::middleware(['auth', 'course.access'])->group(function () {
    Route::get('/course/{id}', [CourseController::class, 'show'])->name('course.show');
});

// excluding from account verification
Route::middleware(['web_except'])->group(function () {

});



require __DIR__ . '/auth.php';