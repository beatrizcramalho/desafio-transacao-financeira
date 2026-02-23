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
        <img src="{{ asset('img/favicon.png') }}" alt="Logo" class="mb-4" style="height: 30px; object-fit: contain;">
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
                        <div class="list-group-item d-flex justify-content-between align-items-center p-0 linha_transacao" 
                            data-transaction='@json($transacao)'
                            style="cursor: pointer;">
                            <div class="d-flex flex-grow-1 p-3">
                                <div>
                                    <span class="fw-bold">R$ {{ number_format($transacao->value, 2, ',', '.') }}</span>
                                    <span class="mx-2">-</span>
                                    <span class="badge rounded-pill {{ $transacao->status == 'Aprovada' ? 'bg-success' : ($transacao->status == 'Negada' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $transacao->status }}
                                    </span>
                                    <span class="mx-2">-</span>
                                    <small class="text-muted">{{ $transacao->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>

                            <div class="dropdown pe-3">
                                <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li>
                                        <a class="dropdown-item btn-trigger-show" href="javascript:void(0)">
                                            <i class="bi bi-file-earmark-text me-2"></i> Detalhes
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('transactions.edit', $transacao->id) }}">
                                            <i class="bi bi-pencil me-2"></i> Editar
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button type="button" 
                                                class="dropdown-item text-danger btn_deletar_transacao" 
                                                data-url="{{ route('transactions.destroy', $transacao->id) }}">
                                            <i class="bi bi-trash me-2"></i> Excluir
                                        </button>
                                    </li>
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

    <!---------- MODAL CRIAR TRANSAÇÃO ---------->
    <div class="modal fade" id="modal_criar_transacao" tabindex="-1" aria-labelledby="titulo_modal_criar_transacao" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold" id="titulo_modal_criar_transacao">Nova Transação</h5>
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
                            <input type="file" name="document_path" class="form-control @error('document_path') is-invalid @enderror">
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

    <!---------- MODAL DETALHES DA TRANSAÇÃO ---------->
    <div class="modal fade" id="modal_exibe_transacao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold">Detalhes da Transação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">VALOR</label>
                        <p class="form-control-plaintext fw-bold" id="exibe_value"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">CPF DO PORTADOR</label>
                        <p class="form-control-plaintext" id="exibe_cpf"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">STATUS</label>
                        <p id="exibe_status"></p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">COMPROVANTE</label>
                        <br>
                        <a href="#" id="abrir_comprovante" target="_blank" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Abrir em nova guia
                        </a>
                    </div>
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!---------- MODAL EXCLUIR TRANSAÇÃO ---------->
    <div class="modal fade" id="modal_confirmar_exclusao_transacao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Confirmar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                    <p class="mt-2">Deseja realmente excluir esta transação?</p>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Não</button>
                    <form id="confirmar_exclusao_transacao" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
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

                // Evento ao clicar na linha da transação ou no botão Detalhes
                $('.linha_transacao, .btn-trigger-show').on('click', function(e) {
                    if ($(e.target).closest('.dropdown').length && !$(e.target).hasClass('btn-trigger-show') && !$(e.target).closest('.btn-trigger-show').length) {
                        return; 
                    }

                    e.preventDefault();
                    
                    let linha = $(this).hasClass('linha_transacao') ? $(this) : $(this).closest('.linha_transacao');
                    let transacao = linha.data('transaction');
                    
                    exibe_transacao(transacao);
                    
                    if ($(this).hasClass('btn-trigger-show')) {
                        $('.dropdown-toggle').dropdown('hide');
                    }
                });

                // Evento ao clicar no botão de excluir transação
                $('.btn_deletar_transacao').on('click', function() {
                    let url = $(this).data('url');
                    
                    $('#confirmar_exclusao_transacao').attr('action', url);

                    var modal_confirmar_exclusao = new bootstrap.Modal(document.getElementById('modal_confirmar_exclusao_transacao'));
                    modal_confirmar_exclusao.show();
                });
            });

            // Previne erros ao fechar modais
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal-backdrop').remove();
                $('body').css('overflow', 'auto');
                $('body').css('padding-right', '0');
            });

            // Garantia de que o modal Criar Transação abrirá sempre "limpo"
            $('#modal_criar_transacao').on('hidden.bs.modal', function () {
                $(this).find('form').trigger('reset');
            });

            function exibe_transacao(transacao) {
                let valor = parseFloat(transacao.value).toLocaleString('pt-br', {style: 'currency', currency: 'BRL'});
                $('#exibe_value').text(valor);

                let cpf = transacao.cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                $('#exibe_cpf').text(cpf);
                
                $('#exibe_status').text(transacao.status);

                if(transacao.document_path) {
                    $('#abrir_comprovante').attr('href', '/storage/' + transacao.document_path);
                    $('#div_comprovante').show();
                } else {
                    $('#div_comprovante').hide();
                }

                // Abre o modal de visualização
                var exibe_modal = new bootstrap.Modal(document.getElementById('modal_exibe_transacao'));
                exibe_modal.show();
            }
        </script>

        @if ($errors->any())
            <script>
                //Abre o modal Criar Transação automaticamente após algum erro no preenchimento
                window.addEventListener('load', function() {
                    var modal_criar_transacao = new bootstrap.Modal(document.getElementById('modal_criar_transacao'));
                    modal_criar_transacao.show();
                });
            </script>
        @endif
            
        @if(isset($editar_transacao))
            <script>
                $(document).ready(function() {
                    // 1. Identifica o modal de criação/edição
                    var modal_original = document.getElementById('modal_criar_transacao');
                    var modal_editado = new bootstrap.Modal(modal_original);
                    
                    // 2. Altera o título e o destino do formulário
                    $('#titulo_modal_criar_transacao').text('Editar Transação');
                    var form = $('#modal_criar_transacao').find('form');
                    form.attr('action', "{{ route('transactions.update', $editar_transacao->id) }}");
                    
                    // Adiciona o método PUT
                    if(form.find('input[name="_method"]').length === 0){
                        form.append('<input type="hidden" name="_method" value="PUT">');
                    }

                    // 3. Preenche os campos
                    $('input[name="value"]').val("{{ number_format($editar_transacao->value, 2, ',', '.') }}").trigger('input');
                    $('input[name="cpf"]').val("{{ $editar_transacao->cpf }}").trigger('input');

                    let comprovante_atual = "{{ $editar_transacao->document_path }}";
                    if(comprovante_atual) {
                        $('input[name="document_path"]').after(`
                            <div id="aviso_comprovante_preenchido" class="form-text text-primary mt-1">
                                <i class="bi bi-info-circle"></i> Já existe um comprovante salvo. 
                                Deixe vazio para manter o atual ou selecione um novo para substituir.
                            </div>
                        `);
                    }
                    
                    // 4. Exibe o modal
                    modal_editado.show();

                    $('#modal_criar_transacao').on('hidden.bs.modal', function () {
                        window.location.href = "{{ route('dashboard') }}";
                    });
                });
            </script>
        @endif
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"> </script>
    @stack('scripts')

</body>
</html>