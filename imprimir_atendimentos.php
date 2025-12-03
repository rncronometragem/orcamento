<?php include_once"session.php";?>
<?php include_once"config.php";?>
<header>
<script type="text/javascript">setTimeout("window.close();", 1000);</script>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/all.css">
</header>
<?php
$idc = $_GET['idc'];
?>
<?php include_once"imprimir_empresa.php";?>
<?php include_once"imprimir_cliente.php";?>
<center><h4>Atendimento</h4></center>
<?php
$idc= $_GET['idc'];
$userlogado = $_SESSION['nome'];
// começa a consulta vinda do mindex
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn,"utf8");
$result_nomes = "SELECT * FROM tbhistoricos WHERE idc = '$idc'";
  $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
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
<br><br><br><br>
<div align="right"></div><p>
<br>
<script type="text/javascript">
window.print();
 </script>
 <body onunload="window.opener.location.href='form_cliente_editar.php?idc=<?php echo $idc;?>'">
<br>
<hr>