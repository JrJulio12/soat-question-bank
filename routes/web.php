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

// Protected Routes - Dashboard and Home (any authenticated user with a role can access)
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// BNCC resource routes: register literal paths (create, edit) before parameterized (show) to avoid 404
// Manage group first so GET resource/create is matched before GET resource/{id}
Route::middleware(['auth', 'permission:manage disciplines'])->group(function () {
    Route::get('disciplines/create', [DisciplineController::class, 'create'])->name('disciplines.create');
    Route::post('disciplines', [DisciplineController::class, 'store'])->name('disciplines.store');
    Route::get('disciplines/{discipline}/edit', [DisciplineController::class, 'edit'])->name('disciplines.edit');
    Route::put('disciplines/{discipline}', [DisciplineController::class, 'update'])->name('disciplines.update');
    Route::delete('disciplines/{discipline}', [DisciplineController::class, 'destroy'])->name('disciplines.destroy');
});
Route::middleware(['auth', 'permission:view disciplines'])->group(function () {
    Route::get('disciplines', [DisciplineController::class, 'index'])->name('disciplines.index');
    Route::get('disciplines/{discipline}', [DisciplineController::class, 'show'])->name('disciplines.show');
});

Route::middleware(['auth', 'permission:manage units'])->group(function () {
    Route::get('units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('units', [UnitController::class, 'store'])->name('units.store');
    Route::get('units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
});
Route::middleware(['auth', 'permission:view units'])->group(function () {
    Route::get('units', [UnitController::class, 'index'])->name('units.index');
    Route::get('units/{unit}', [UnitController::class, 'show'])->name('units.show');
});

Route::middleware(['auth', 'permission:manage knowledges'])->group(function () {
    Route::get('knowledges/create', [KnowledgeController::class, 'create'])->name('knowledges.create');
    Route::post('knowledges', [KnowledgeController::class, 'store'])->name('knowledges.store');
    Route::get('knowledges/{knowledge}/edit', [KnowledgeController::class, 'edit'])->name('knowledges.edit');
    Route::put('knowledges/{knowledge}', [KnowledgeController::class, 'update'])->name('knowledges.update');
    Route::delete('knowledges/{knowledge}', [KnowledgeController::class, 'destroy'])->name('knowledges.destroy');
});
Route::middleware(['auth', 'permission:view knowledges'])->group(function () {
    Route::get('knowledges', [KnowledgeController::class, 'index'])->name('knowledges.index');
    Route::get('knowledges/{knowledge}', [KnowledgeController::class, 'show'])->name('knowledges.show');
});

Route::middleware(['auth', 'permission:manage topics'])->group(function () {
    Route::get('topics/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::put('topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
});
Route::middleware(['auth', 'permission:view topics'])->group(function () {
    Route::get('topics', [TopicController::class, 'index'])->name('topics.index');
    Route::get('topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
});

Route::middleware(['auth', 'permission:manage chapters'])->group(function () {
    Route::get('chapters/create', [ChapterController::class, 'create'])->name('chapters.create');
    Route::post('chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::get('chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
    Route::put('chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
});
Route::middleware(['auth', 'permission:view chapters'])->group(function () {
    Route::get('chapters', [ChapterController::class, 'index'])->name('chapters.index');
    Route::get('chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');
});

Route::middleware(['auth', 'permission:manage subjects'])->group(function () {
    Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
});
Route::middleware(['auth', 'permission:view subjects'])->group(function () {
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
});

Route::middleware(['auth', 'permission:manage series'])->group(function () {
    Route::get('series/create', [SerieController::class, 'create'])->name('series.create');
    Route::post('series', [SerieController::class, 'store'])->name('series.store');
    Route::get('series/{serie}/edit', [SerieController::class, 'edit'])->name('series.edit');
    Route::put('series/{serie}', [SerieController::class, 'update'])->name('series.update');
    Route::delete('series/{serie}', [SerieController::class, 'destroy'])->name('series.destroy');
});
Route::middleware(['auth', 'permission:view series'])->group(function () {
    Route::get('series', [SerieController::class, 'index'])->name('series.index');
    Route::get('series/{serie}', [SerieController::class, 'show'])->name('series.show');
});

Route::middleware(['auth', 'permission:manage bnccs'])->group(function () {
    Route::get('bnccs/create', [BnccController::class, 'create'])->name('bnccs.create');
    Route::post('bnccs', [BnccController::class, 'store'])->name('bnccs.store');
    Route::get('bnccs/{bncc}/edit', [BnccController::class, 'edit'])->name('bnccs.edit');
    Route::put('bnccs/{bncc}', [BnccController::class, 'update'])->name('bnccs.update');
    Route::delete('bnccs/{bncc}', [BnccController::class, 'destroy'])->name('bnccs.destroy');
});
Route::middleware(['auth', 'permission:view bnccs'])->group(function () {
    Route::get('bnccs', [BnccController::class, 'index'])->name('bnccs.index');
    Route::get('bnccs/{bncc}', [BnccController::class, 'show'])->name('bnccs.show');
});

// Questions
Route::middleware(['auth', 'permission:manage questions'])->group(function () {
    Route::get('questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('questions/ajax/units', [QuestionController::class, 'getUnitsByDisciplines'])->name('questions.ajax.units');
    Route::post('questions/ajax/knowledges', [QuestionController::class, 'getKnowledgesByUnits'])->name('questions.ajax.knowledges');
    Route::post('questions/ajax/bnccs', [QuestionController::class, 'getBnccs'])->name('questions.ajax.bnccs');
    Route::post('questions/ajax/topics', [QuestionController::class, 'getTopicsByDisciplines'])->name('questions.ajax.topics');
    Route::post('questions/ajax/chapters', [QuestionController::class, 'getChaptersByTopics'])->name('questions.ajax.chapters');
    Route::post('questions/ajax/subjects', [QuestionController::class, 'getSubjectsByChapters'])->name('questions.ajax.subjects');
});
Route::middleware(['auth', 'permission:view questions'])->group(function () {
    Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
});
