<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BnccController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('email/verify', [VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Protected Routes
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Resource Routes
Route::resource('disciplines', DisciplineController::class)->middleware('auth');
Route::resource('units', UnitController::class)->middleware('auth');
Route::resource('knowledges', KnowledgeController::class)->middleware('auth');
Route::resource('topics', TopicController::class)->middleware('auth');
Route::resource('chapters', ChapterController::class)->middleware('auth');
Route::resource('subjects', SubjectController::class)->middleware('auth');
Route::resource('series', SerieController::class)->parameters(['series' => 'serie'])->middleware('auth');
Route::resource('bnccs', BnccController::class)->middleware('auth');
Route::resource('questions', QuestionController::class)->middleware('auth');
