<?php
include_once "session.php";
include_once "config.php";

$hora = date("H:i");
$idc = $_POST['idc'];
$nome = $_POST['nome'];
$data = $_POST['data'];
$result = explode('/', $data);
$dia = $result[0];
$mes = $result[1];
$ano = $result[2];
$data = $ano . '-' . $mes . '-' . $dia . ' ' . $hora;
$status_ordem = $_POST['status_ordem'];
$sql = "INSERT INTO tbordens (idc, data, nome, log, status_ordem) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, 'issss', $idc, $data, $nome, $log, $status_ordem);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Número de Ordem Criado!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>