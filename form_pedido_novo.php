<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";

$hoje = date('d/m/Y');
?>

    <style>
        /* Ajustes para o Select2 parecer nativo do Bootstrap 5 */
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #dee2e6;
            height: 38px;
            padding-top: 4px;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
        }
    </style>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-secondary mb-0"><i class="fas fa-file-invoice"></i> Novo Pedido</h2>
                    <a href="index_ordens.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fas fa-plus-circle"></i> Iniciar Ordem de Serviço
                    </div>
                    <div class="card-body p-4">

                        <form method="post" action="insert_ordens.php" id="formNovoPedido" autocomplete="off">

                            <input type="hidden" name="log" value="<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'user'; ?>">
                            <input type="hidden" name="status_ordem" value="pendente">
                            <input type="hidden" name="nome" id="inputNomeCliente">

                            <div class="mb-4">
                                <label for="selectCliente" class="form-label fw-bold">Selecione o Cliente</label>
                                <select name="idc" id="selectCliente" class="form-select" required style="width: 100%;">
                                    <option value="">Busque por nome ou documento...</option>
                                    <?php
                                    // Carrega todos os clientes para o Select2
                                    // Se tiver MUITOS clientes (milhares), ideal seria fazer via AJAX (como fizemos nos produtos)
                                    $sql_cli = "SELECT idc, nome, documento FROM tbclientes ORDER BY nome ASC";
                                    $res_cli = mysqli_query($conn, $sql_cli);

                                    if($res_cli){
                                        while($row = mysqli_fetch_assoc($res_cli)){
                                            // O atributo data-nome será usado para preencher o input hidden
                                            $doc = $row['documento'] ? " - " . $row['documento'] : "";
                                            echo "<option value='{$row['idc']}' data-nome='{$row['nome']}'>{$row['nome']}{$doc}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="form-text">Comece a digitar para pesquisar na lista.</div>
                            </div>
                            <div class="row mb-4">
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
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                    <i class="fas fa-check"></i> Criar e Adicionar Itens
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">Cliente não cadastrado? <a href="form_cliente.php">Cadastre aqui</a>.</small>
                </div>

            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // 1. Inicializar Select2 com tema Bootstrap 5
            $('#selectCliente').select2({
                theme: 'bootstrap-5',
                placeholder: "Selecione ou pesquise um cliente",
                allowClear: true
            });

            // 2. Atualizar o campo hidden "nome" quando trocar o cliente
            // O insert_ordens.php precisa do nome escrito, além do ID
            $('#selectCliente').on('select2:select', function (e) {
                var nomeCliente = $(this).find(':selected').data('nome');
                $('#inputNomeCliente').val(nomeCliente);
            });

            // 3. Validação antes de enviar
            $('#formNovoPedido').on('submit', function(e){
                if($('#selectCliente').val() == "") {
                    e.preventDefault();
                    alert("Por favor, selecione um cliente para continuar.");
                }
            });

            // Máscara de data (se o plugin estiver carregado no cabecalho)
            if($.fn.mask) {
                $('#data').mask('00/00/0000');
            }
        });
    </script>

<?php include_once "rodape.php"; ?>