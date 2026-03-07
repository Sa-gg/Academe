<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('/enrollments', [EnrollmentController::class, 'index']);
Route::post('/enrollments', [EnrollmentController::class, 'store']);
Route::get('/enrollments/student/{id}', [EnrollmentController::class, 'byStudent']);
Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']);
Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy']);
