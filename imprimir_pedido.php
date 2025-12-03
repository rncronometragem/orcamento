<?php include_once"session.php";?>
<header>
<script type="text/javascript">setTimeout("window.close();", 1000);</script>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/all.css">
</header>
<?php
include_once('config.php'); // Certifique-se de incluir seu arquivo de conexão com o banco de dados
if (isset($_POST['ido'])) {
    $ido = $_POST['ido'];
    $idc = $_POST['idc'];

    // Buscando os dados do pedido
    $query_pedido = "SELECT * FROM tbordens WHERE ido='$ido' LIMIT 1";
    $result_pedido = mysqli_query($conn, $query_pedido);
    $pedido = mysqli_fetch_assoc($result_pedido);

    // Buscando os itens do pedido
    $query_itens = "SELECT * FROM tbitens WHERE ido='$ido'";
    $result_itens = mysqli_query($conn, $query_itens);
}
?>
<?php include_once"imprimir_empresa.php";?>
<?php include_once"imprimir_pedido_dados.php";?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .pedido-info {
            margin-bottom: 20px;
        }
        .pedido-info p {
            margin: 5px 0;
        }
    
        hr {
            border: none;
            border-top: 1px solid #ccc; /* Cor cinza */
            margin: 20px 0; /* Espaçamento */
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h4>Detalhes do Pedido Nº <?php echo $pedido['ido']; ?></h4></center>
        <div class="pedido-info">
            <p><strong>Data Emissão:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['data'])); ?></p>
            <p><strong>Status:</strong> <?php echo $pedido['status_ordem']; ?></p>
        </div>
<style type="text/css">
.descricao-col {
    padding: 3px;
    width: 400px;
    font-size: 16px;
    border: 2px solid #000;
}
.qtde-col {
    padding: 3px;
    width: 50px;
    font-size: 16px;
    border: 2px solid #000;
    text-align: center;
}
.valorunit-col {
    padding: 3px;
    width: 100px;
    font-size: 16px;
    border: 2px solid #000;
}
.subtotal-col {
    padding: 3px;
    width: 100px;
    font-size: 16px;
    border: 2px solid #000;
}
.subtotala-col {
    padding: 3px;
    width: 100px;
    font-size: 18px;
    border: 2px solid #000;
}
</style>

        <table style="undefined;table-layout: fixed; width: 750px">
<thead>
  <tr>
    <th class="descricao-col"><center>Descrição</center></th>
    <th class="qtde-col">Qtde</th>
    <th class="valorunit-col">Valor Unit</th>
    <th class="subtotal-col">Subtotal</th>
  </tr></thead>
            <tbody>
                <?php
                $totalGeral = 0;
                while ($item = mysqli_fetch_assoc($result_itens)) {
                    $subtotal = $item['quantidade'] * $item['preco'];
                    $totalGeral += $subtotal;
                    echo '<tr>
    <td class="descricao-col">' . $item['descricao'] . '</td>
    <td class="qtde-col">' . $item['quantidade'] . '</td>
    <td class="valorunit-col">R$ ' . number_format($item['preco'], 2, ',', '.') . '</td>
    <td class="subtotal-col">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>
  </tr>';
} 
  ?>
  <tr>
    <td></td>
    <td></td>
    <td class="valorunit-col">Total Geral > </td>
    <td class="subtotala-col"> R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></td>
  </tr>
</tbody>
</table>
<script type="text/javascript">
window.print();
</script>
</body>
</html>

