<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/post-job', [JobController::class, 'create'])->name('jobs.create');
Route::post('/post-job', [JobController::class, 'store'])->name('jobs.store');
