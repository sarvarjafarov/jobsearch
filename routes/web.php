<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobAlertController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/post-job', [JobController::class, 'create'])->name('jobs.create');
Route::post('/post-job', [JobController::class, 'store'])->name('jobs.store');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::post('/companies/{company}/reviews', [CompanyController::class, 'storeReview'])->name('companies.reviews.store');
Route::get('/resume', [ResumeController::class, 'form'])->name('resume.form');
Route::post('/resume/generate', [ResumeController::class, 'generate'])->name('resume.generate');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/notify-me', [JobAlertController::class, 'store'])->name('alerts.store');
