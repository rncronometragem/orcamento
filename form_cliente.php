<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
// include_once "calendar.php"; // Desnecessário com Bootstrap moderno
?>

    <script>
        $(document).ready(function() {
            $('#cpf').on('blur', function() {
                var cpf = $(this).val().replace(/\D/g, ''); // Remove pontos e traço

                // Validação básica de tamanho
                if (cpf.length > 0 && (cpf.length != 11 || !/^\d+$/.test(cpf))) {
                    alert('O CPF digitado é inválido.');
                    return false;
                }

                // Verifica no banco se já existe (AJAX)
                if(cpf.length === 11) {
                    $.post('busca_cpf.php', {cpf: cpf}, function(data) {
                        if (data.existe) {
                            var idc = data.idc;
                            alert('Este CPF já consta no sistema! Redirecionando para o cadastro existente...');
                            window.location.href = 'form_cliente_editar.php?idc=' + idc;
                        }
                    }, 'json');
                }
            });
        });
    </script>

    <div class="container my-5">

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-secondary mb-0"><i class="fas fa-user-plus"></i> Novo Cliente</h2>
                    <small class="text-muted"><i class="fas fa-info-circle"></i> Pesquise antes de cadastrar para evitar duplicidade.</small>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 small text-uppercase fw-bold">Preencha os dados abaixo</h5>
                    </div>

                    <div class="card-body p-4">
                        <form method="post" name="adiciona" action="insert_cliente.php" autocomplete="off">

                            <h6 class="text-muted border-bottom pb-2 mb-3">Dados de Identificação</h6>
                            <div class="row g-3 mb-4">

                                <div class="col-md-3">
                                    <label for="tipoDocumento" class="form-label fw-bold">Tipo de Pessoa</label>
                                    <select name="tipodoc" id="tipoDocumento" class="form-select">
                                        <option value="CNPJ">Pessoa Jurídica (CNPJ)</option>
                                        <option value="CPF">Pessoa Física (CPF)</option>
                                    </select>
                                </div>

                                <div class="col-md-4" id="campoCNPJ">
                                    <label for="cnpj" class="form-label fw-bold">CNPJ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                        <input type="text" name="documento" id="cnpj" class="form-control" placeholder="00.000.000/0000-00">
                                    </div>
                                </div>

                                <div class="col-md-4" id="campoCPF" style="display:none;">
                                    <label for="cpf" class="form-label fw-bold">CPF</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                        <input type="text" name="documento" id="cpf" class="form-control" placeholder="000.000.000-00">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label for="categoria" class="form-label fw-bold">Categoria</label>
                                    <select class="form-select" name="categoria" required>
                                        <option value="">Selecione...</option>
                                        <?php
                                        $sql_cat = "SELECT * FROM tbcategorias ORDER BY categoria ASC";
                                        $resgata_cat = mysqli_query($conn, $sql_cat);
                                        if ($resgata_cat) {
                                            while($rows_cat = mysqli_fetch_array($resgata_cat)){
                                                echo '<option value="'.$rows_cat['categoria'].'">'.$rows_cat['categoria'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="nome" class="form-label fw-bold">Nome Completo / Razão Social</label>
                                    <input type="text" class="form-control" name="nome" maxlength="150" required oninput="maiuscula(event)">
                                </div>
                            </div>

                            <h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Endereço</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" maxlength="9" onblur="pesquisacep(this.value);" placeholder="00000-000">
                                </div>
                                <div class="col-md-6">
                                    <label for="rua" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="rua" name="rua" maxlength="60">
                                </div>
                                <div class="col-md-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" maxlength="50">
                                </div>
                                <div class="col-md-9">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" maxlength="50">
                                </div>
                                <div class="col-md-3">
                                    <label for="uf" class="form-label">Estado (UF)</label>
                                    <input type="text" class="form-control" id="uf" name="uf" maxlength="5">
                                </div>
                            </div>

                            <h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Contato e Observações</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="telefone" class="form-label"><i class="fas fa-phone-alt small"></i> Telefone Fixo</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15">
                                </div>
                                <div class="col-md-3">
                                    <label for="celular" class="form-label"><i class="fas fa-mobile-alt small"></i> Celular / WhatsApp</label>
                                    <input type="text" class="form-control" id="celular" name="celular" maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label"><i class="far fa-envelope small"></i> E-mail</label>
                                    <input type="email" class="form-control" name="email" maxlength="60" oninput="minuscula(event)">
                                </div>
                                <div class="col-md-12">
                                    <label for="obs" class="form-label">Observações</label>
                                    <textarea class="form-control" name="obs" rows="2" maxlength="250"></textarea>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-save"></i> Salvar Cliente
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once "rodape.php"; ?>