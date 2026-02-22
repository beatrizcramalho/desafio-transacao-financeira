<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    // Salva a transação no banco
    public function store(Request $request)
    {
        // 1. Limpeza dos dados (Sanitização)
        if ($request->has('value')) {
            $valorLimpo = str_replace(['.', ','], ['', '.'], $request->value);
            $request->merge(['value' => $valorLimpo]);
        }

        if ($request->has('cpf')) {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $request->cpf);
            $request->merge(['cpf' => $cpfLimpo]);
        }

        // 2. Validação
        $dadosValidados = $request->validate([
            'value' => 'required|numeric|min:0.01',
            'cpf' => 'required|string|size:11',
            'document_path' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ], [
            'value.required' => 'O valor é obrigatório.',
            'value.numeric' => 'O formato do valor é inválido.',
            'cpf.exact_length' => 'O CPF deve conter exatamente 11 números.',
            'document_path.required' => 'O comprovante é indispensável.',
        ]);

        // 3. Upload do arquivo
        if ($request->hasFile('document_path')) {
            $caminhoArquivo = $request->file('document_path')->store('comprovantes', 'public');
            $dadosValidados['document_path'] = $caminhoArquivo;
        }

        // 4. Criar o registro no banco
        Transaction::create([
            'user_id' => Auth::id(),
            'value' => $dadosValidados['value'],
            'cpf' => $dadosValidados['cpf'],
            'document_path' => $dadosValidados['document_path'],
            'status' => 'Em processamento',
        ]);

        return redirect()->route('dashboard')->with('sucesso', 'Transação criada com sucesso!');
    }
}