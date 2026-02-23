<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Rota da Tela Inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'exibeLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rota de Logout da Aplicação
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas de Dashboard de Transações
Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard')->middleware('auth');

// Rota para Criar Transação
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store')->middleware('auth');

// Rotas para Ações de Transação
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show')->middleware('auth');
Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit')->middleware('auth');
Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update')->middleware('auth');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy')->middleware('auth');