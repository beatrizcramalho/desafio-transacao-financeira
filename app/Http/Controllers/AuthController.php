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
        // Validação com mensagens customizadas
        $regras = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $mensagens = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'password.required' => 'A senha deve ser preenchida.',
        ];

        $credenciais = $request->validate($regras, $mensagens);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Se errar, retorna mensagem de erro
        return back()->withErrors([
            'erro_login' => 'E-mail ou senha inválidos.',
        ])->onlyInput('email');
    }

    // Faz o logout do usuário
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
