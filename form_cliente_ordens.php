<?php
// Garante que variáveis essenciais existam
$hoje = date('d/m/Y');
// Pega o IDC do include pai ou da URL se necessário
$idc = isset($idc) ? $idc : (isset($_GET['idc']) ? $_GET['idc'] : 0);

// Busca nome do cliente se não vier do include
if(!isset($nome) || empty($nome)){
    // Conexão já deve existir do config.php incluído no pai
    if($idc) {
        $result_cli = mysqli_query($conn, "SELECT nome FROM tbclientes WHERE idc='$idc' LIMIT 1");
        if($result_cli && $row_cli = mysqli_fetch_assoc($result_cli)){
            $nome = $row_cli['nome'];
        }
    } else {
        $nome = "Cliente não identificado";
    }
}
?>

<style>
    /* Ajustes para o Select2 parecer nativo do Bootstrap 5 */
    .select2-container--bootstrap-5 .select2-selection {
        border-color: #dee2e6;
    }
    /* Garante que o dropdown do Select2 fique acima da Modal */
    .select2-container {
        z-index: 9999 !important;
    }
    /* Ajuste de altura da modal para telas pequenas */
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body">
        <h6 class="card-title text-muted mb-3 fw-bold text-uppercase small"><i class="fas fa-plus-circle"></i> Novo Pedido</h6>
        <form method="post" action="insert_ordens.php" class="row g-3 align-items-end">
            <input type="hidden" name="nome" value="<?php echo $nome; ?>">
            <input type="hidden" name="log" value="<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'user'; ?>">
            <input type="hidden" name="idc" value="<?php echo $idc; ?>">
            <input type="hidden" name="status_ordem" value="pendente">

            <div class="col-md-3">
                <label class="form-label fw-bold small">Data do Registro</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                    <input type="text" class="form-control" name="data" value="<?php echo $hoje; ?>" id="dataordem" required>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Nome do Evento</label>
                <input type="text" class="form-control" name="nome_evento" placeholder="Ex: Casamento Silva" required>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold small">Data do Evento</label>
                <input type="date" class="form-control" name="data_evento">
            </div>

            <div class="col-md-9">
                <label class="form-label fw-bold small">Local do Evento</label>
                <input type="text" class="form-control" name="local_evento" placeholder="Ex: Salão de Festas Central">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                    <i class="fas fa-check"></i> Criar Pedido
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold py-3 border-bottom">
        <i class="fas fa-list"></i> Lista de Pedidos
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th class="ps-3">Data</th>
                <th>Nº Pedido</th>
                <th class="text-center">Status</th>
                <th class="text-center">Itens</th>
                <th class="text-center">Usuário</th>
                <th class="text-end pe-3">Opções</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Paginação
            $numerodepagina = 5;
            $offset = ($pagina - 1) * $numerodepagina;

            // Se não tiver IDC, não busca nada (evita erro SQL)
            if($idc) {
                $sql_count = "SELECT COUNT(*) FROM tbordens WHERE idc='$idc'";
                $res_count = mysqli_query($conn, $sql_count);
                $row_count = mysqli_fetch_array($res_count);
                $total_linhas = $row_count[0];
                $total_paginas = ceil($total_linhas / $numerodepagina);

                // Busca Pedidos
                $sql_ordens = "SELECT * FROM tbordens WHERE idc='$idc' ORDER BY data DESC LIMIT $offset, $numerodepagina";
                $res_ordens = mysqli_query($conn, $sql_ordens);
            } else {
                $total_paginas = 0;
                $res_ordens = false;
            }

            if($res_ordens && mysqli_num_rows($res_ordens) > 0) {
                while ($row = mysqli_fetch_array($res_ordens)) {
                    $ido = $row['ido'];
                    $status = $row['status_ordem'];

                    // Badge de Status
                    $badge_class = 'bg-secondary';
                    if (stripos($status, 'pendente') !== false) $badge_class = 'bg-warning text-dark';
                    if (stripos($status, 'concluido') !== false) $badge_class = 'bg-success';
                    ?>
                    <tr>
                        <td class="ps-3"><?php echo date('d/m/Y H:i', strtotime($row["data"])); ?></td>
                        <td class="fw-bold">#<?php echo $ido; ?></td>
                        <td class="text-center">
                            <span class="badge <?php echo $badge_class; ?> rounded-pill px-3"><?php echo ucfirst($status); ?></span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalVerItens<?php echo $ido; ?>">
                                <i class="fas fa-box-open"></i> Ver Itens
                            </button>
                        </td>
                        <td class="text-center text-muted small"><?php echo $row["log"]; ?></td>
                        <td class="text-end pe-3">
                            <div class="btn-group">
                                <form method="post" action="imprimir_pedido.php" target="_blank" class="d-inline">
                                    <input type="hidden" name="ido" value="<?php echo $ido; ?>">
                                    <input type="hidden" name="idc" value="<?php echo $idc; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Imprimir"><i class="fas fa-print"></i></button>
                                </form>

                                <form method="post" action="delete_ordens.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja EXCLUIR este pedido?');">
                                    <input type="hidden" name="ido" value="<?php echo $ido; ?>">
                                    <input type="hidden" name="idc" value="<?php echo $idc; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalVerItens<?php echo $ido; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title"><i class="fas fa-clipboard-list"></i> Pedido #<?php echo $ido; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body bg-light">

                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-body p-0">
                                            <div id="tabelaItens<?php echo $ido; ?>">
<!--                                                <table class="table table-striped mb-0 table-sm align-middle">-->
<!--                                                    <thead class="table-secondary">-->
<!--                                                    <tr>-->
<!--                                                        <th class="ps-3">Item</th>-->
<!--                                                        <th class="text-center">Qtd</th>-->
<!--                                                        <th class="text-end">Valor</th>-->
<!--                                                        <th class="text-end">Subtotal</th>-->
<!--                                                        <th class="text-center">#</th>-->
<!--                                                    </tr>-->
<!--                                                    </thead>-->
<!--                                                    <tbody>-->
<!--                                                    --><?php
//                                                    $sql_itens = "SELECT * FROM tbitens WHERE ido='$ido'";
//                                                    $res_itens = mysqli_query($conn, $sql_itens);
//                                                    $totalGeral = 0;
//                                                    if(mysqli_num_rows($res_itens) > 0) {
//                                                        while($item = mysqli_fetch_array($res_itens)){
//                                                            $subtotal = $item['quantidade'] * $item['preco'];
//                                                            $totalGeral += $subtotal;
//                                                            echo "<tr>
//                                                                <td class='ps-3'>{$item['descricao']}</td>
//                                                                <td class='text-center'>{$item['quantidade']}</td>
//                                                                <td class='text-end'>R$ ".number_format($item['preco'], 2, ',', '.')."</td>
//                                                                <td class='text-end fw-bold text-muted'>R$ ".number_format($subtotal, 2, ',', '.')."</td>
//                                                                <td class='text-center'>
//                                                                    <button type='button' class='btn btn-sm text-danger p-0 btn-deletar' data-ido='$ido' data-idi='{$item['idi']}'><i class='fas fa-times'></i></button>
//                                                                </td>
//                                                            </tr>";
//                                                        }
//                                                    } else {
//                                                        echo "<tr><td colspan='5' class='text-center py-3 text-muted small'>Nenhum item adicionado.</td></tr>";
//                                                    }
//                                                    ?>
<!--                                                    </tbody>-->
<!--                                                    <tfoot>-->
<!--                                                    <tr>-->
<!--                                                        <th colspan="3" class="text-end">Total Geral:</th>-->
<!--                                                        <th class="text-end fw-bold text-success">R$ --><?php //echo number_format($totalGeral, 2, ',', '.'); ?><!--</th>-->
<!--                                                        <td></td>-->
<!--                                                    </tr>-->
<!--                                                    </tfoot>-->
<!--                                                </table>-->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold mb-2 small text-uppercase text-primary">Adicionar Item</h6>
                                            <form id="formItens<?php echo $ido; ?>" class="row g-2">

                                                <input type="hidden" name="ido" value="<?php echo $ido; ?>">

                                                <div class="col-md-6">
                                                    <select name="descricao" class="form-select tbproduto" style="width: 100%;">
                                                        <option value="">Buscar Produto...</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" name="quantidade" class="form-control form-control-sm" value="1" placeholder="Qtd" min="1" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="preco" class="form-control form-control-sm valor" id="preco<?php echo $ido; ?>" placeholder="R$ 0,00" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary btn-sm w-100" onclick="submitForm(<?php echo $ido; ?>)">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer justify-content-between">
                                    <form id="formStatus<?php echo $ido; ?>" class="d-flex align-items-center">
                                        <input type="hidden" name="ido" value="<?php echo $ido; ?>">
                                        <label class="me-2 fw-bold small text-muted">Status:</label>
                                        <select name="status" class="form-select form-select-sm w-auto" onchange="updateStatus(<?php echo $ido; ?>)">
                                            <option value="pendente" <?php echo ($status == 'pendente' ? 'selected' : ''); ?>>Pendente</option>
                                            <option value="concluido" <?php echo ($status == 'concluido' ? 'selected' : ''); ?>>Concluído</option>
                                        </select>
                                    </form>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                } // Fim While
            } else {
                echo '<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-folder-open fa-2x mb-2"></i><br>Nenhum pedido encontrado.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php if($total_paginas > 1): ?>
        <div class="card-footer bg-white py-3">
            <nav aria-label="Navegação">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <?php
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        $active = ($i == $pagina) ? 'active' : '';
                        echo '<li class="page-item '.$active.'"><a class="page-link" href="?pagina='.$i.'&idc='.$idc.'">'.$i.'</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // 1. Carregar produtos UMA vez para memória (evita múltiplas requisições)
        var produtosData = [];
        $.ajax({
            url: 'buscar_produtos.php',
            dataType: 'json',
            data: { term: '' },
            success: function(data) {
                // Formata para o padrão do Select2
                produtosData = data.map(function(item) {
                    return {
                        id: item.produto, // Valor a enviar
                        text: item.produto,  // Texto a exibir
                        valor: item.valor // Dado extra
                    };
                });
            }
        });

        // 2. Inicializar Select2 APENAS quando a modal abrir
        // Isso corrige o problema do campo de busca travado
        $('.modal').on('shown.bs.modal', function () {
            var modal = $(this);
            var select = modal.find('.tbproduto');

            // Se já não estiver inicializado
            if (!select.hasClass("select2-hidden-accessible")) {
                select.select2({
                    dropdownParent: modal, // ESSENCIAL: Vincula o dropdown à modal
                    theme: 'bootstrap-5',
                    width: '100%',
                    data: produtosData, // Usa os dados carregados
                    placeholder: "Selecione um produto",
                    allowClear: true
                });

                // Evento ao selecionar: Preencher Preço
                select.on('select2:select', function (e) {
                    var data = e.params.data;
                    // Acha o campo de preço vizinho dentro do mesmo formulário
                    var form = $(this).closest('form');
                    form.find('.valor').val(data.valor);
                });
            }
        });
    });

    // FUNÇÕES AJAX (Modernizadas)

    // Adicionar Item
    function submitForm(ido) {
        var form = $("#formItens" + ido);
        var produto = form.find('.tbproduto').val();

        if (!produto) {
            alert('Por favor, selecione um produto.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'insert_itens_ajax.php',
            data: form.serialize(),
            success: function(response) {
                if(response.trim() === "success") {
                    // Atualiza a tabela chamando o arquivo PHP externo
                    recarregarTabelaItens(ido);

                    // Limpa os campos
                    form.find('.tbproduto').val(null).trigger('change');
                    form.find('input[name="quantidade"]').val(1);
                    form.find('input[name="preco"]').val('');
                } else {
                    alert('Erro ao adicionar: ' + response);
                }
            },
            error: function() {
                alert('Erro de conexão ao adicionar item.');
            }
        });
    }

    // Recarregar Tabela
    function recarregarTabelaItens(ido) {
        $.get('recarregar_tabela_itens.php', { ido: ido }, function(data) {
            $("#tabelaItens" + ido).html(data);
        });
    }

    // Deletar Item
    $(document).on('click', '.btn-deletar', function(e) {
        e.preventDefault();
        var btn = $(this);
        var idi = btn.data('idi');
        var ido = btn.data('ido');

        if(!confirm("Remover este item?")) return;

        $.post('delete_item.php', { idi: idi }, function(resp) {
            if(resp.trim() === "success") {
                recarregarTabelaItens(ido);
            } else {
                alert("Erro ao deletar.");
            }
        });
    });

    // Atualizar Status
    function updateStatus(ido) {
        var form = $("#formStatus" + ido);
        $.post('update_status.php', form.serialize(), function(data) {
            if(data.trim() === "success") {
                // Recarrega a página para atualizar o badge na tabela principal
                location.reload();
            } else {
                alert('Erro ao atualizar status.');
            }
        });
    }
</script>