<?php include_once"session.php";?>
<?php
  include_once"config.php";
  //
  $produto = trim($_POST['produto']);

  $valor = str_replace(',', '.', str_replace('.', '', $_POST['valor']));
  $data= date('Y-m-d'); // hoje
    //
 $sql = "INSERT INTO tbprodutos (produto, valor, data, log) VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $produto, $valor, $data, $log);

if (mysqli_stmt_execute($stmt)) {
   header("Location: index_produtos.php");
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
//
copy($origem, $destino);
?>