<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitterController;
use Illuminate\Support\Facades\Route;

// Öffentliche Routen — kein Login nötig
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sitter-Suche und Profile sind öffentlich sichtbar
Route::get('/sitters', [SearchController::class, 'search']);
Route::get('/sitters/{sitter}', [SitterController::class, 'show']);
Route::get('/sitters/{sitter}/reviews', [ReviewController::class, 'index']);

// Geschützte Routen — Login erforderlich (Sanctum Token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Sitter-Profil verwalten
    Route::post('/sitter/profile', [SitterController::class, 'store']);
    Route::put('/sitter/profile', [SitterController::class, 'update']);

    // Buchungen
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::put('/bookings/{booking}/confirm', [BookingController::class, 'confirm']);
    Route::put('/bookings/{booking}/reject', [BookingController::class, 'reject']);

    // Bewertungen (nur nach abgeschlossener Buchung möglich)
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store']);
});
