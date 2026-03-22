<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('pages.public.landing'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn() => view('pages.dashboard.index'))
        ->name('dashboard');

    // Route::get('/quizzes', [QuizController::class, 'discover']);
    // Route::get('/quizzes/{id}', [QuizController::class, 'show']);

    // Route::get('/quiz/create', [QuizController::class, 'create']);
    // Route::post('/quiz', [QuizController::class, 'store']);

    // Route::get('/quiz/{id}/questions', [QuestionController::class, 'index']);

    // Route::post('/quiz/{id}/start', [AttemptController::class, 'start']);
    // Route::get('/attempt/{id}', [AttemptController::class, 'play']);

    // Route::get('/attempt/{id}/result', [AttemptController::class, 'result']);

    // Route::get('/history', [AttemptController::class, 'history']);

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route::get('/history', [AttemptController::class, 'history']);
});

require __DIR__.'/auth.php';
