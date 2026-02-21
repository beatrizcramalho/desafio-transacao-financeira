<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rota da tela inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'exibe_login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);