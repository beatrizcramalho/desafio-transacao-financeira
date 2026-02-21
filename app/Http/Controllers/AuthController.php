<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Exibe a tela de login
    public function exibe_login()
    {
        return view('auth.login');
    }

    // Processa a tentativa de login
    public function login(Request $request)
    {
        // Valida se os campos foram preenchidos
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tenta logar o usuário
        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Se errar, retorna mensagem de erro
        return back()->withErrors([
            'erro_login' => 'E-mail ou senha inválidos.',
        ])->onlyInput('email');
    }
}
