<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Busca todas as transações do banco de dados
        $transacoes = Transaction::all();

        // Retorna a view 'dashboard' passando a lista de transacoes
        return view('transactions.dashboard', compact('transacoes'));
    }
}