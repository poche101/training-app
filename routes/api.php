<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Public API ────────────────────────────────────────────────────────────
Route::post('/register',        [App\Http\Controllers\Auth\RegisteredUserController::class,    'store']);
Route::post('/login',           [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/logout',          [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/forgot-password', [\Illuminate\Auth\Listeners\SendEmailVerificationNotification::class, '__invoke'])->name('password.email');

Route::get('/livestreams',       [\App\Http\Controllers\Api\LivestreamApiController::class, 'index']);
Route::get('/livestreams/{id}',  [\App\Http\Controllers\Api\LivestreamApiController::class, 'show']);
Route::get('/events',            [\App\Http\Controllers\Api\EventApiController::class, 'index']);
Route::get('/resources',         [\App\Http\Controllers\Api\ResourceApiController::class, 'index']);

// ─── Auth Required ─────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $r) => $r->user());

    // ── Admin Only ─────────────────────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/analytics',        [\App\Http\Controllers\Api\AnalyticsApiController::class, 'index']);
        Route::get('/engagement',       [\App\Http\Controllers\Api\AnalyticsApiController::class, 'engagement']);
        Route::post('/livestreams',     [\App\Http\Controllers\Api\LivestreamApiController::class, 'store']);
        Route::put('/livestreams/{id}', [\App\Http\Controllers\Api\LivestreamApiController::class, 'update']);
        Route::post('/resources',       [\App\Http\Controllers\Api\ResourceApiController::class, 'store']);
        Route::delete('/resources/{id}',[\App\Http\Controllers\Api\ResourceApiController::class, 'destroy']);
        Route::post('/events',          [\App\Http\Controllers\Api\EventApiController::class, 'store']);
    });
});
