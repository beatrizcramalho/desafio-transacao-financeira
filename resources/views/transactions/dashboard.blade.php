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
            <button type="button" class="btn btn-create px-4" data-bs-toggle="modal" data-bs-target="#modal_criar_transacao">
                Criar Transação
            </button>
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
                                <span class="badge rounded-pill 
                                    {{ $transacao->status == 'Aprovada' ? 'bg-success' : ($transacao->status == 'Negada' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $transacao->status }}
                                </span>
                                <span class="mx-2">-</span>
                                <small class="text-muted">{{ $transacao->created_at->format('d/m/Y H:i') }}</small>
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

    <div class="modal fade" id="modal_criar_transacao" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold" id="modalLabel">Nova Transação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Valor (R$)*</label>
                            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}" required>
                            @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">CPF do Portador*</label>
                            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
                            @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Comprovante (PDF, JPG ou PNG)*</label>
                            <input type="file" name="document_path" class="form-control @error('document_path') is-invalid @enderror" required>
                            @error('document_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-create w-100">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <script>
            $(document).ready(function(){
                // Máscara para CPF
                $('input[name="cpf"]').mask('000.000.000-00');
                
                // Máscara para Valor (Dinheiro)
                $('input[name="value"]').mask("#.##0,00", {reverse: true});
            });
        </script>

        
        @if ($errors->any())
            <script>
                //Abre o modal automaticamente após algum erro no preenchimento
                window.addEventListener('load', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('modal_criar_transacao'));
                    myModal.show();
                });
            </script>
        @endif

        <script>
            // Garantia de que o modal abrirá sempre "limpo"
            $('#modal_criar_transacao').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });
        </script>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"> </script>
    @stack('scripts')

</body>
</html>