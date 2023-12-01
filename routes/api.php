<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MultipleChoiceController;
use App\Http\Controllers\SentenceController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('multiple_choices', MultipleChoiceController::class);
    Route::apiResource('sentences', SentenceController::class);
    Route::apiResource('articles', ArticleController::class);
});
