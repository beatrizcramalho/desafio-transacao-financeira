<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Retorna a lista de transações para o Dashboard principal
    public function index()
    {
        $transacoes = Transaction::where('user_id', Auth::id())->get();

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
    
    // Visualizar detalhes da transação
    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) { abort(403); }

        return view('transactions.show', compact('transaction'));
    }

    // Visualizar detalhes da transação para edição
    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) { abort(403); }

        $transacoes = Transaction::where('user_id', Auth::id())->get();
        
        $editar_transacao = $transaction;

        return view('transactions.dashboard', compact('transacoes', 'editar_transacao'));
    }

    // Editar transação
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) { abort(403); }

        // 1. Limpeza dos dados (Sanitização)
        $valorLimpo = str_replace(['.', ','], ['', '.'], $request->value);
        $cpfLimpo = preg_replace('/[^0-9]/', '', $request->cpf);

        $request->merge([
            'value' => $valorLimpo,
            'cpf' => $cpfLimpo
        ]);

        // 2. Validação
        $regras = [
            'value' => 'required|numeric|min:0.01',
            'cpf' => 'required|string|size:11',
            'document_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ];

        $mensagens = [
            'value.required' => 'O valor é obrigatório.',
            'value.numeric' => 'O formato do valor é inválido.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve conter exatamente 11 números.',
            'document_path.mimes' => 'O comprovante deve ser um arquivo PDF, JPG ou PNG.',
        ];

        $request->validate($regras, $mensagens);

        // 3. Preparação dos dados para atualização
        $data = $request->only(['value', 'cpf']);

        // Se ele subir um novo comprovante, substitui o antigo
        if ($request->hasFile('document_path')) {
            $data['document_path'] = $request->file('document_path')->store('comprovantes', 'public');
        }

        $transaction->update($data);

        return redirect()->route('dashboard')->with('sucesso', 'Transação atualizada!');
    }

    // Excluir transação
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) { abort(403); }

        $transaction->delete();

        return redirect()->route('dashboard')->with('sucesso', 'Transação excluída com sucesso!');
    }
}