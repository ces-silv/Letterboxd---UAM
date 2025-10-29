<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Películas
    Route::apiResource('movies', MovieController::class);
    
    // Reseñas
    Route::apiResource('reviews', ReviewController::class);
    
    // Otras rutas de recursos pueden agregarse aquí
});

// Ruta para documentación Swagger
Route::get('/docs', function () {
    return redirect('/api/documentation');
});