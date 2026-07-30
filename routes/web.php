<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamSubmitController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubdivisionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LibraryController::class, 'index'])->name('library.index');

Route::get('/api/global-search', [SearchController::class, 'query'])->name('search.query');

Route::post('/interaction/rate', [InteractionController::class, 'rate'])->name('interaction.rate');
Route::post('/interaction/flag', [InteractionController::class, 'flag'])->name('interaction.flag');

Route::get('/robots.txt', function () {
    $sitemap = url('/sitemap.xml');

    return response("User-agent: *\nAllow: /\nSitemap: {$sitemap}\n", 200, [
        'Content-Type' => 'text/plain',
    ]);
});

Route::post('/exam/submit-answer', [ExamSubmitController::class, 'submitAnswer'])
    ->name('exam.submit');

Route::redirect('/subject', '/teacher-certification-and-licensure-exam-prep');
Route::redirect(
    '/quiz',
    '/teacher-certification-and-licensure-exam-prep/praxis-core-and-subject-assessment-practice-tests/praxis-5001-quiz-1'
);

Route::get('/{subdivision}/courses/{course}', function (string $subdivision, string $course) {
    return redirect()->route('course.show', [
        'subdivision' => $subdivision,
        'course' => $course,
    ], 301);
});

Route::get('/{subdivision}/{course}/{exam}', [ExamController::class, 'show'])
    ->name('exam.show');

Route::get('/{subdivision}/{course}', [CourseController::class, 'show'])
    ->name('course.show');

Route::get('/{subdivision}', [SubdivisionController::class, 'show'])
    ->name('subdivision.show');
