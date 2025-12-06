<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'isTeacher'])->group(function () {
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/teacher', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/teacher/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/teacher/{id}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
    Route::get('/teacher/{id}/students', [TeacherController::class, 'showStudents'])->name('teacher.students');
});

Route::middleware(['auth', 'isStudent'])->group(function () {
    Route::get('/student/courses', [StudentController::class, 'index'])->name('student.courses');
    Route::post('/student/courses/{id}/enroll', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::post('/student/courses/{id}/unenroll', [StudentController::class, 'unenroll'])->name('student.unenroll');
    Route::get('/student/my-courses', [StudentController::class, 'myCourses'])->name('student.myCourses');
});