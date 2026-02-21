<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>Transações | Sistema Financeiro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="d-flex">

    <div class="sidebar p-3 d-flex flex-column">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="mb-4" style="height: 30px; object-fit: contain;">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="bi bi-cash-stack me-2"></i> Transação
                </a>
            </li>
        </ul>
        <hr>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-link nav-link text-start w-100">Sair</button>
        </form>
    </div>

    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Transações</h2>
            <button class="btn btn-create px-4">Criar Transação</button>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="p-3 border-bottom">
                    <input type="text" class="form-control" placeholder="Buscar...">
                </div>

                <div class="list-group list-group-flush">
                    @forelse($transacoes as $transacao)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <span class="fw-bold text-dark">R$ {{ number_format($transacao->value, 2, ',', '.') }}</span>
                                <span class="mx-2">-</span>
                                <span class="badge rounded-pill {{ $transacao->status == 'Aprovada' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $transacao->status }}
                                </span>
                                <span class="mx-2">-</span>
                                <small class="text-muted">{{ $transacao->created_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                            
                            <div class="dropdown">
                                <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Ver</a></li>
                                    <li><a class="dropdown-item" href="#">Editar</a></li>
                                    <li><a class="dropdown-item text-danger" href="#">Excluir</a></li>
                                </ul>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            Nenhuma transação encontrada.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>