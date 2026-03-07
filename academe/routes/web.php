<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
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

Route::get('/', fn() => redirect('/courses'));

Route::resource('students', StudentController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::resource('courses', CourseController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::get('/enrollments/student/{id}', [EnrollmentController::class, 'byStudent'])->name('enrollments.byStudent');
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

Route::view('/architecture', 'architecture')->name('architecture');
