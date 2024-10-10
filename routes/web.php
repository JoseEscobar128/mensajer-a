<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return redirect()->route('login'); // Redirige a la vista de login
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/chat', function () {
    return view('chat');
})->name('chat');

// Ruta para mostrar el formulario de verificación
Route::get('/verification', function () {
    return view('auth.verify'); // Muestra la vista del formulario de verificación
})->name('verification.form');

// Ruta para procesar el código de verificación
Route::post('/verification', [LoginController::class, 'verifyCode'])->name('verification.verify');

Route::post('/register', [RegistroController::class, 'registro']);

Route::post('/login', [LoginController::class, 'login']);

// Rutas protegidas con Sanctum para el manejo de mensajes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send-message', [MessageController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [MessageController::class, 'readMessages']);
    Route::get('/load-inbox-messages', [MessageController::class, 'loadInboxMessages'])->middleware('auth:sanctum');
    Route::get('/messages/decrypt/{id}', [MessageController::class, 'decrypt']);
    Route::post('/messages/re-encrypt/{id}', [MessageController::class, 'reEncrypt']);

});


Route::middleware(['auth'])->group(function () {
    // Rutas protegidas
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


