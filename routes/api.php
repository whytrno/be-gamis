<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MultipleChoiceController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\ArticleController;
use App\Models\MultipleChoice;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');


Route::group(['prefix' => 'auth'], function () {
    Route::get('/redirect/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/callback/google', [AuthController::class, 'handleGoogleCallback']);
});
Route::get('/leaderboards', [AuthController::class, 'leaderboards']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('multiple_choices', MultipleChoiceController::class);
    // Route::apiResource('sentences', SentenceController::class);
    Route::get('/sentences/{count?}', [SentenceController::class, 'index']);
    Route::apiResource('articles', ArticleController::class);
    Route::post('/answer/multiple_choice', [MultipleChoice::class, 'answer']);
    Route::post('/answer/sentence', [SentenceController::class, 'answer']);
    Route::get('/history', [HistoryController::class, 'index']);
    Route::post('/history', [HistoryController::class, 'create']);
});
