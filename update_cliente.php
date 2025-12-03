<?php
include_once "config.php";
//
$idc = $_POST['idc'];
$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$datareg = $_POST['datareg'];
$result = explode('/', $datareg);
$diar = $result[2];
$mesr = $result[1];
$anor = $result[0];
$datareg = $diar . '-' . $mesr . '-' . $anor;
$tipodoc = $_POST['tipodoc'];
$documento = $_POST['documento'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$cidade = $_POST['cidade'];
$bairro = $_POST['bairro'];
$uf = $_POST['uf'];
$telefone = $_POST['telefone'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$obs = $_POST['obs'];
$datalog = date('Y-m-d H:i:s');

$sql = "UPDATE tbclientes SET datareg=?, nome=?, tipodoc=?, documento=?, cep=?, rua=?, cidade=?, bairro=?, uf=?, telefone=?, celular=?, email=?, obs=?, log=?, datalog=?, categoria=? WHERE idc=?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $datareg, $nome, $tipodoc, $documento, $cep, $rua, $cidade, $bairro, $uf, $telefone, $celular, $email, $obs, $log, $datalog, $categoria, $idc);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('$nome Salvo com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>