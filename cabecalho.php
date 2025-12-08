<?php
include_once "session.php";
include_once "config.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Cadastro - <?php echo isset($_SESSION['versao']) ? $_SESSION['versao'] : 'v2.0'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/all.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .navbar-brand img { height: 40px; width: auto; }
        /* Ajuste para inputs ficarem consistentes */
        .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #86b7fe; }
    </style>

    <script>
        $(document).ready(function(){
            // Máscaras
            $('.valor').mask('000.000.000.000.000,00', {reverse: true});
            $('#cnpj').mask('00.000.000/0000-00');
            $('#cpf').mask('000.000.000-00');
            $("#cep").mask("00000-000");
            $("#telefone").mask("(00) 0000-0000");
            $("#celular").mask("(00) 00000-0000");

            // Máscaras de data
            $(".data-mask").mask("00/00/0000"); // Adicione a classe .data-mask nos seus inputs de data

            // Lógica de CPF/CNPJ
            function mostrarCampoDocumento() {
                var tipo = $('#tipoDocumento').val();
                if(tipo === 'CPF'){
                    $('#campoCPF').show(); $('#campoCNPJ').hide();
                    $('#cpf').prop('disabled', false); $('#cnpj').prop('disabled', true);
                } else if(tipo === 'CNPJ'){
                    $('#campoCNPJ').show(); $('#campoCPF').hide();
                    $('#cnpj').prop('disabled', false); $('#cpf').prop('disabled', true);
                } else {
                    $('#campoCPF').hide(); $('#campoCNPJ').hide();
                }
            }
            $('#tipoDocumento').change(mostrarCampoDocumento);
            mostrarCampoDocumento(); // Executa ao carregar
        });

        // Função de Busca de CEP (ViaCEP)
        function limpa_formulário_cep() {
            $("#rua").val(""); $("#bairro").val(""); $("#cidade").val(""); $("#uf").val("");
        }

        function pesquisacep(valor) {
            var cep = valor.replace(/\D/g, '');
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if(validacep.test(cep)) {
                    $("#rua").val("..."); $("#bairro").val("..."); $("#cidade").val("..."); $("#uf").val("...");
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#uf").val(dados.uf);
                        } else {
                            limpa_formulário_cep(); alert("CEP não encontrado.");
                        }
                    });
                } else {
                    limpa_formulário_cep(); alert("Formato de CEP inválido.");
                }
            } else {
                limpa_formulário_cep();
            }
        }

        // Funções de Confirmação de Exclusão
        function confirmarExclusao(msg) {
            return confirm(msg);
        }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <?php if(isset($imagem) && !empty($imagem)): ?>
                <img src="logo/<?php echo $imagem; ?>" alt="Logo">
            <?php else: ?>
                <i class="fas fa-cube"></i> Sistema
            <?php endif; ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuTopo" aria-controls="menuTopo" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuTopo">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-users"></i> Clientes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="form_cliente.php"><i class="fas fa-user-plus"></i> Novo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index_ordens.php"><i class="fas fa-clipboard-list"></i> Pedidos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index_produtos.php"><i class="fas fa-box-open"></i> Produtos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index_historico.php"><i class="fas fa-history"></i> Históricos</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if(isset($admin) && $admin == "show"): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropConfig" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs"></i> Config
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropConfig">
                            <li><a class="dropdown-item" href="form_empresa_editar.php"><i class="fas fa-building"></i> Empresa</a></li>
                            <li><a class="dropdown-item" href="index_categoria.php"><i class="fas fa-tags"></i> Categorias</a></li>
                            <li><a class="dropdown-item" href="admin.php"><i class="fas fa-users-cog"></i> Usuários</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="bac.php"><i class="fas fa-database"></i> Backup</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="dropUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?php echo isset($log) ? $log : 'Usuário'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropUser">
                        <li><a class="dropdown-item text-danger" href="sair.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">