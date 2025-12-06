<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\authController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


//
Route::middleware('auth')->group(function () {
Route::get('books/index',[BookController::class,'index'])->name('books.index');

Route::get('books/create',[BookController::class,'create'])->name('books.create');

Route::post('books/store',[BookController::class,'store'])->name('books.store');

Route::get('books/{id}/edit',[BookController::class,'edit'])->name('books.edit');

Route::put('books/{id}',[BookController::class,'update'])->name('books.update');

Route::delete('books/{id}',[BookController::class,'destroy'])->name('books.destroy');

});