<?php include_once"session.php";?>
<?php
  include_once"config.php";
  //
  $nome = $_POST['nome'];
  $tipodoc = $_POST['tipodoc'];
  $documento = $_POST['documento'];
  $categoria = $_POST['categoria'];
  $datareg = date('Y-m-d'); // hoje
  $cep = $_POST['cep'];
  $rua =  $_POST['rua'];
  $cidade = $_POST['cidade'];
  $bairro = $_POST['bairro'];
  $uf = $_POST['uf'];
  $telefone = $_POST['telefone'];
  $celular = $_POST['celular']; 
  $email = $_POST['email'];
  $obs = $_POST['obs'];
  $datalog = date("Y-m-d H:i:s");
    //
 $sql = "INSERT INTO tbclientes (nome, tipodoc, documento, datareg, cep, rua, cidade, bairro, uf, telefone, celular, email, obs, log, datalog, categoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $nome, $tipodoc, $documento, $datareg, $cep, $rua, $cidade, $bairro, $uf, $telefone, $celular, $email, $obs, $log, $datalog, $categoria);

if (mysqli_stmt_execute($stmt)) {
  header("Location: busca.php?busca=$nome");
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>