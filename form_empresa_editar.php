<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
// O calendar.php foi removido pois estamos usando inputs nativos ou máscaras,
// e ele costuma conflitar com layouts modernos se não for essencial.
// Se precisar dele, pode descomentar a linha abaixo:
// include_once "calendar.php";

// Verificação de Segurança (Mantida a lógica original)
if(empty($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin'){
    $_SESSION['msg'] = "Área restrita";
    echo "<script>location.href='index.php';</script>";
    exit;
}

// Conexão e Busca de Dados
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn, "utf8");

$result_nomes = "SELECT * FROM tbempresa LIMIT 1";
$resultado_nomes = mysqli_query($conn, $result_nomes);

// Inicializa variáveis para evitar erros de 'undefined'
$ide = $nome = $cnpj = $inscestadual = $cep = $rua = $num = $numcomp = "";
$cidade = $bairro = $uf = $telefone = $telcom = $celular = $email = $site = "";
$imagem = "padrao.png";
$barra = "#000000";
$letra = "#ffffff";

if($rows_nomes = mysqli_fetch_array($resultado_nomes)){
    $ide = $rows_nomes['ide'];
    $nome = $rows_nomes['nome'];
    $cnpj = $rows_nomes['cnpj'];
    $inscestadual = $rows_nomes['inscestadual'];
    $cep = $rows_nomes['cep'];
    $rua =  $rows_nomes['rua'];
    $num = $rows_nomes['num'];
    $numcomp = $rows_nomes['numcomp'];
    $cidade = $rows_nomes['cidade'];
    $bairro = $rows_nomes['bairro'];
    $uf = $rows_nomes['uf'];
    $telefone = $rows_nomes['telefone'];
    $telcom = $rows_nomes['telcom'];
    $celular = $rows_nomes['celular'];
    $email = $rows_nomes['email'];
    $site = $rows_nomes['site'];
    $imagem = $rows_nomes['imagem'] ? $rows_nomes['imagem'] : 'padrao.png';
    $barra = $rows_nomes['barra'];
    $letra = $rows_nomes['letra'];
}
?>

    <div class="container my-5">

        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-secondary"><i class="fas fa-building"></i> Configurações da Empresa</h2>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">

                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted mb-3">Logotipo do Sistema</h5>
                        <div class="mb-3">
                            <img src="./logo/<?php echo $imagem; ?>" class="img-thumbnail rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalLogo">
                            <i class="fas fa-camera"></i> Alterar Logo
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-paint-brush"></i> Personalização
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Defina as cores da barra de navegação e textos.</p>
                        <div class="d-grid gap-2">
                            <label class="form-label small fw-bold">Cor da Barra (Menu)</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-8">
                <form method="post" name="editar" action="update_empresa.php" autocomplete="off">
                    <input type="hidden" name="ide" value="<?php echo $ide; ?>">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-info-circle"></i> Dados Cadastrais
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Nome da Empresa</label>
                                    <input type="text" class="form-control" name="nome" maxlength="150" value="<?php echo $nome; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18" value="<?php echo $cnpj; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Inscrição Estadual</label>
                                    <input type="text" class="form-control" name="inscestadual" maxlength="15" value="<?php echo $inscestadual; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            <i class="fas fa-map-marker-alt"></i> Endereço e Contato
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" maxlength="9" value="<?php echo $cep; ?>" onblur="pesquisacep(this.value);" placeholder="00000-000">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label">Endereço (Rua)</label>
                                    <input type="text" class="form-control" id="rua" name="rua" maxlength="60" value="<?php echo $rua; ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Nº</label>
                                    <input type="text" class="form-control" id="num" name="num" maxlength="10" value="<?php echo $num; ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="numcomp" name="numcomp" maxlength="10" value="<?php echo $numcomp; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" maxlength="20" value="<?php echo $bairro; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" maxlength="20" value="<?php echo $cidade; ?>">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">UF</label>
                                    <input type="text" class="form-control" id="uf" name="uf" maxlength="2" value="<?php echo $uf; ?>">
                                </div>

                                <hr class="my-4">

                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-phone"></i> Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" value="<?php echo $telefone; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-mobile-alt"></i> Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular" maxlength="20" value="<?php echo $celular; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-headset"></i> Tel. Comercial</label>
                                    <input type="text" class="form-control" name="telcom" maxlength="15" value="<?php echo $telcom; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                                    <input type="email" class="form-control" name="email" maxlength="60" value="<?php echo $email; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-globe"></i> Site</label>
                                    <input type="text" class="form-control" name="site" maxlength="60" value="<?php echo $site; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-palette"></i> Personalização de Cores</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="barra" class="form-label">Cor da Barra de Navegação</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="barra" name="barra" value="<?php echo $barra; ?>" title="Escolha a cor da barra">
                                        <input type="text" class="form-control" value="<?php echo $barra; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="letra" class="form-label">Cor da Fonte/Ícones</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="letra" name="letra" value="<?php echo $letra; ?>" title="Escolha a cor do texto">
                                        <input type="text" class="form-control" value="<?php echo $letra; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLogo" tabindex="-1" aria-labelledby="modalLogoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLogoLabel"><i class="fas fa-image"></i> Atualizar Logotipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./logo/<?php echo $imagem; ?>" class="img-fluid border rounded" style="max-height: 200px;">
                        <p class="text-muted mt-2 small">Imagem Atual</p>
                    </div>

                    <form method="post" enctype="multipart/form-data" action="update_logo.php">
                        <input type="hidden" name="ide" value="<?php echo $ide; ?>">

                        <div class="mb-3">
                            <label for="arquivo" class="form-label fw-bold">Selecionar Nova Imagem</label>
                            <input class="form-control" type="file" id="arquivo" name="arquivo" required accept="image/*">
                            <div class="form-text">Formatos recomendados: PNG ou JPG.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Enviar Nova Imagem
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include_once "rodape.php"; ?>