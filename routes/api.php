<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieCastController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Autenticación (registro/login público)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Géneros (solo lectura)
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{id}', [GenreController::class, 'show']);

// Actores (solo lectura)
Route::get('/actors', [ActorController::class, 'index']);
Route::get('/actors/{id}', [ActorController::class, 'show']);

// Directores (solo lectura)
Route::get('/directors', [DirectorController::class, 'index']);
Route::get('/directors/{id}', [DirectorController::class, 'show']);

// Películas (acceso público de solo lectura)
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::get('/movies/search', [MovieController::class, 'search']);
Route::get('/movies/popular', [MovieController::class, 'getPopular']);
Route::get('/movies/{id}/statistics', [MovieController::class, 'getStatistics']);

// Reseñas de películas (acceso de lectura público)
Route::get('/movies/{movieId}/reviews', [ReviewController::class, 'getReviewsByMovie']);

// Documentación Swagger
Route::get('/docs', function () {
    return redirect('/api/documentation');
});


Route::middleware('auth:sanctum')->group(function () {

    // Autenticación y perfil de usuario
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Reseñas (CRUD completo para usuarios autenticados)
    Route::apiResource('reviews', ReviewController::class);
    Route::get('/reviews/my-reviews', [ReviewController::class, 'getMyReviews']);
    Route::get('/movies/{movieId}/review-status', [ReviewController::class, 'checkReviewStatus']);

    // Géneros (usuarios autenticados pueden actualizar)
    Route::put('/genres/{id}', [GenreController::class, 'update']);

    // Actores (usuarios autenticados pueden actualizar)
    Route::put('/actors/{id}', [ActorController::class, 'update']);

    // Directores (usuarios autenticados pueden actualizar)
    Route::put('/directors/{id}', [DirectorController::class, 'update']);

});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    // Películas (CRUD completo - solo admin)
    Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
    // Nota: index, show, search, popular, statistics son públicos arriba

    // Reparto de películas (CRUD completo - solo admin)
    Route::apiResource('movie-casts', MovieCastController::class);

    // Géneros (crear/eliminar - solo admin)
    Route::post('/genres', [GenreController::class, 'store']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);

    // Actores (crear/eliminar - solo admin)
    Route::post('/actors', [ActorController::class, 'store']);
    Route::delete('/actors/{id}', [ActorController::class, 'destroy']);

    // Directores (crear/eliminar - solo admin)
    Route::post('/directors', [DirectorController::class, 'store']);
    Route::delete('/directors/{id}', [DirectorController::class, 'destroy']);

});