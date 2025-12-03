<?php include_once "session.php"; ?>
<?php include_once "config.php"; ?>
<?php include_once "cabecalho.php"; ?>
<?php include_once "calendar.php"; ?>
<html>
<head>
    <title>Cadastro de Clientes</title>
</head>
<body>
<script>
function show_delall() {
    if (confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? Ao deletar você vai apagar também os todos históricos desse Cliente e NÃO TEM como recuperar os dados depois."))
        document.forms[0].submit();
    else
        return false;
}
</script>
<br>
<div class="container-fluid">
<h4><a href="" data-toggle="modal" data-target="#produtoModal" title="Produto"><i class="bi bi-card-list"></i> Adicionar </a></h4>

<!-- Modal para adicionar produto -->
<div class="modal fade" id="produtoModal" tabindex="-1" role="dialog" aria-labelledby="produtoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="produtoModalLabel">Adicionar Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="insert_produtos.php">
                    <div class="form-group">
                        <label for="produto">Descrição</label>
                        <input type="text" class="form-control" id="produto" name="produto" placeholder="Produto ou serviço">
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <input type="text" class="form-control valor" name="valor" placeholder="Valor do produto">
                    </div>
               
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="60%" style="text-align:left;">Descrição</th>
       <th width="auto" style="text-align:left;">Valor</th>
       <th width="15%" style="text-align:left;">Data</th>
       <th width="15%" style="text-align:left;">Usuário</th>
       <th width="auto" style="text-align:left;">Delete</th>
     </tr>
   </thead>
   <tbody>
    <?php

    $mes = date("m"); // pega mês atual
    $hojea = date('d/m'); // pega dia de hoje

    if (isset($_GET['pagina'])) {
        $pagina = $_GET['pagina'];
    } else {
        $pagina = 1;
    }
    $numerodepagina = 15;
    $offset = ($pagina - 1) * $numerodepagina;

    $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
    mysqli_set_charset($conn, "utf8");
    // Check connection
    if (mysqli_connect_errno()) {
        echo "Conexao falhou: " . mysqli_connect_error();
        die();
    }
    $total_paginas_sql = "SELECT COUNT(*) FROM tbprodutos";
    $result = mysqli_query($conn, $total_paginas_sql);
    $total_linhas = mysqli_fetch_array($result)[0];
    $total_paginas = ceil($total_linhas / $numerodepagina);
    $sql = "SELECT * FROM tbprodutos ORDER BY produto ASC LIMIT $offset, $numerodepagina";
    $resgata_dados = mysqli_query($conn, $sql);
    while ($linha = mysqli_fetch_array($resgata_dados)) {
        // Modal para editar produto
        echo '<div class="modal fade" id="produtoModalEditar' . $linha["idp"] . '" tabindex="-1" role="dialog" aria-labelledby="produtoModalLabel' . $linha["idp"] . '" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="produtoModalLabel' . $linha["idp"] . '">Editar Produto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="update_produtos.php">
                                <input type="hidden" name="idp" value="' . $linha["idp"] . '">
                                <div class="form-group">
                                    <label for="produto">Descrição</label>
                                    <input type="text" class="form-control" id="produto" name="produto" value="' . $linha["produto"] . '" placeholder="Produto ou serviço">
                                </div>
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <input type="text" class="form-control valor"  name="valor" value="' . number_format($linha['valor'], 2, ',', '.') . '" placeholder="Valor do produto">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';

        echo '<tr>
                <td width="60%" style="text-align:left"><a href="" data-toggle="modal" data-target="#produtoModalEditar' . $linha["idp"] . '" title="Editar">' . $linha["produto"] . '</a></td>
                <td width="auto" style="text-align:left">' . number_format($linha['valor'], 2, ',', '.') . '</td>
                <td width="15%" style="text-align:left">' . date('d/m/Y', strtotime($linha['data'])) . '</td>
                <td width="15%" style="text-align:left">' . $linha["log"] . '</td>
                <td width="auto" style="text-align:left"><form method="post" id="form" name="dataForm" action="delete_produto.php">
                   <input type="hidden" name="idp" value="' . $linha['idp'] . '">
                   <button type="submit" title="Deletar" onclick="return deleta_produto();"><i class="bi bi-trash" style="color:red"></i></button>
                </form></td>
             </tr>';
    }
    ?>
   </tbody>
</table>
</div>
<?php
    mysqli_close($conn);
?>
<!-- Incluindo jQuery e Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<?php include_once "paginar.php"; ?>
<?php include_once "rodape.php"; ?>
</body>
</html>
