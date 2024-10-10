<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;

// Ruta para manejar el registro mediante API
Route::post('/registro', [RegistroController::class, 'registro']);

// Ruta para manejar el login mediante API
Route::post('/login', [LoginController::class, 'login']);

// Rutas protegidas con Sanctum para el manejo de mensajes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send-message', [MessageController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [MessageController::class, 'readMessages']);
    Route::get('/messages/decrypt/{id}', [MessageController::class, 'decrypt']);
});
