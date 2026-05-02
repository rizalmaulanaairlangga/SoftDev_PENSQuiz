<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\MyQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('pages.public.landing'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

    Route::get('/quizzes', [DiscoverController::class, 'index'])->name('quizzes.index');
    Route::get('/quiz/{id}', [DiscoverController::class, 'show'])->name('quiz.show');

    Route::post('/quiz/{id}/start', [AttemptController::class, 'start'])->name('quiz.start');
    Route::get('/attempt/{id}', [AttemptController::class, 'play'])->name('attempt.play');

    Route::post('/attempt/save-answer', [AttemptController::class, 'saveAnswer'])->name('attempt.saveAnswer');
    Route::post('/attempt/exit', [AttemptController::class, 'exit'])->name('attempt.exit');
    Route::post('/attempt/submit', [AttemptController::class, 'submit'])->name('attempt.submit');

    
    Route::get('/my-quizzes', [MyQuizController::class, 'index'])
        ->name('my-quizzes.index');

    Route::get('/my-quizzes/create', [MyQuizController::class, 'create'])
        ->name('my-quizzes.create');

    Route::post('/my-quizzes', [MyQuizController::class, 'store'])
        ->name('my-quizzes.store');

    Route::get('/my-quizzes/{myquiz}/edit', [MyQuizController::class, 'edit'])
        ->name('my-quizzes.edit');

    Route::get('/my-quizzes/{myquiz}/statistics', [MyQuizController::class, 'statistics'])
        ->name('my-quizzes.statistics');

    Route::put('/my-quizzes/{myquiz}', [MyQuizController::class, 'update'])
        ->name('my-quizzes.update');

    Route::delete('/my-quizzes/{myquiz}', [MyQuizController::class, 'destroy'])
        ->name('my-quizzes.destroy');

    Route::get('/folders/{folder}', [\App\Http\Controllers\FolderController::class, 'show'])->name('folders.show');
    Route::post('/folders', [\App\Http\Controllers\FolderController::class, 'store'])->name('folders.store');
    Route::put('/folders/{folder}', [\App\Http\Controllers\FolderController::class, 'update'])->name('folders.update');
    Route::delete('/folders/{folder}', [\App\Http\Controllers\FolderController::class, 'destroy'])->name('folders.destroy');
    // Route::get('/quizzes', [QuizController::class, 'discover']);
    // Route::get('/quizzes/{id}', [QuizController::class, 'show']);

    // Route::get('/quiz/create', [QuizController::class, 'create']);
    // Route::post('/quiz', [QuizController::class, 'store']);

    // Route::get('/quiz/{id}/questions', [QuestionController::class, 'index']);

    // Route::post('/quiz/{id}/start', [AttemptController::class, 'start']);
    // Route::get('/attempt/{id}', [AttemptController::class, 'play']);

    // Route::get('/attempt/{id}/result', [AttemptController::class, 'result']);

    // Route::get('/history', [AttemptController::class, 'history']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route::get('/history', [AttemptController::class, 'history']);
});

require __DIR__.'/auth.php';
