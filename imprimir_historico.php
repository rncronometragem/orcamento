<?php include_once"session.php";?>
<?php include_once"config.php";?>
<header>
<script type="text/javascript">setTimeout("window.close();", 1000);</script>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/all.css">
</header>
<?php
$idc = $_POST['idc'];
$idh = $_POST['idh'];
?>
<?php include_once"imprimir_empresa.php";?>
<?php include_once"imprimir_cliente.php";?>
<center><h4>Histórico</h4></center>
<?php
$idc= $_POST['idc'];
$userlogado = $_SESSION['nome'];
//
$result_nomes = "SELECT * FROM tbhistoricos WHERE idc = '$idc' and idh = $idh";
  $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
//
$descricao = $rows_nomes['descricao'];
$datahistorico = date('d/m/Y ', strtotime($rows_nomes["data"]));
echo '<table class="table table-hover">
<tr>
<td width="150" align="left">'.$datahistorico.'</td>
<td width="100%">'.$rows_nomes['descricao'].'</td>
</tr>
</table>';
}
?>
<br>
<script type="text/javascript">
window.print();
 </script>
 <body onunload="window.opener.location.href='form_cliente_editar.php?idc=<?php echo $idc;?>'">
<br>
<hr>