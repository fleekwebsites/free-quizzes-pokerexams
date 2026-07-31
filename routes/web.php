<?php

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

Route::get('/{subdivision}/{course}/{exam}', [ExamController::class, 'show'])
    ->name('exam.show');

Route::get('/{subdivision}', [SubdivisionController::class, 'show'])
    ->name('subdivision.show');
