<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Rota da Tela Inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'exibe_login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rotas de Dashboard de Transações
Route::get('/dashboard', [TransactionController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
    
// Rota de Logout da Aplicação
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');