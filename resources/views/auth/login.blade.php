<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>Login - Sistema Financeiro</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/login.css' )}}">
</head>
<body>

    <div class="login-card">
        <div class="card-header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="mb-3" style="height: 50px;">
            <h3 class="fw-bold mb-0">Sign In</h3>
            <div class="divider"></div>
        </div>
        
        <div class="card-body p-4">
            
            @if ($errors->has('erro_login'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    {{ $errors->first('erro_login') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold">Seu e-mail*</label>
                    <input type="email" name="email" id="email" 
                        class="form-control form-control-lg @error('email') is-invalid @enderror" 
                        placeholder="exemplo@email.com" value="{{ old('email') }}">
                    
                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-bold">Sua senha*</label>
                    <input type="password" name="password" id="password" 
                        class="form-control form-control-lg @error('password') is-invalid @enderror" 
                        placeholder="••••••••">
                    
                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Lembrar-me</label>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100">Entrar</button>

                <div class="text-center mt-4">
                    <a href="#" class="text-decoration-none small text-muted">
                        Esqueceu sua senha? <span class="text-primary fw-bold">Clique aqui!</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>