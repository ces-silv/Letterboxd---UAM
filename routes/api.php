<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas de géneros (solo lectura)
Route::get('/genres', [GenreController::class, 'index']);
Route::get('/genres/{id}', [GenreController::class, 'show']);

// Rutas públicas de actores (solo lectura)
Route::get('/actors', [ActorController::class, 'index']);
Route::get('/actors/{id}', [ActorController::class, 'show']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Gestión de perfil del usuario
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    
    // Películas
    Route::apiResource('movies', MovieController::class);
    
    // Reseñas
    Route::apiResource('reviews', ReviewController::class);
    
    // Rutas de géneros que requieren autenticación
    Route::put('/genres/{id}', [GenreController::class, 'update']);

    // Rutas de actores que requieren autenticación
    Route::put('/actors/{id}', [ActorController::class, 'update']);
});

// Rutas de administrador para géneros (crear y eliminar)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/genres', [GenreController::class, 'store']);
    Route::delete('/genres/{id}', [GenreController::class, 'destroy']);
});

// Rutas de administrador para actores (crear y eliminar)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/actors', [ActorController::class, 'store']);
    Route::delete('/actors/{id}', [ActorController::class, 'destroy']);
});

// Ruta para documentación Swagger
Route::get('/docs', function () {
    return redirect('/api/documentation');
});