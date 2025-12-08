<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";

// Verifica e define a variável $idc (ID do Cliente) para ser usada globalmente nos includes
$idc = isset($_GET['idc']) ? $_GET['idc'] : null;

// Verifica paginação (necessário para os includes)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if (!$idc) {
    echo "<div class='alert alert-danger'>ID do cliente não fornecido.</div>";
    include_once "rodape.php";
    exit;
}
?>

    <div class="container my-5">

        <?php
        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        mysqli_set_charset($conn,"utf8");
        $query_header = mysqli_query($conn, "SELECT nome FROM tbclientes WHERE idc='$idc' LIMIT 1");
        $cliente_header = mysqli_fetch_assoc($query_header);
        ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-secondary mb-0"><i class="fas fa-user-edit"></i> Gerenciar Cliente</h2>
                <p class="text-muted small"><?php echo $cliente_header['nome']; ?> (ID: #<?php echo $idc; ?>)</p>
            </div>
            <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Voltar para Lista</a>
        </div>

        <ul class="nav nav-tabs mb-4" id="clienteTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dados-tab" data-bs-toggle="tab" data-bs-target="#dados" type="button" role="tab" aria-selected="true">
                    <i class="fas fa-id-card"></i> Dados Cadastrais
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pedidos-tab" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-shopping-cart"></i> Pedidos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab" aria-selected="false">
                    <i class="fas fa-history"></i> Histórico
                </button>
            </li>
        </ul>

        <div class="tab-content" id="clienteTabContent">

            <div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                <?php include_once "form_cliente_cliente.php"; ?>
            </div>

            <div class="tab-pane fade" id="pedidos" role="tabpanel" aria-labelledby="pedidos-tab">
                <?php include_once "form_cliente_ordens.php"; ?>
            </div>

            <div class="tab-pane fade" id="historico" role="tabpanel" aria-labelledby="historico-tab">
                <?php include_once "form_cliente_historico.php"; ?>
            </div>

        </div>
    </div>

<?php include_once "rodape.php"; ?>