<?php
include_once "session.php";
include_once "config.php";

$idc = $_POST['idc'];
$ido = $_POST['ido'];
$data = $_POST['data'];
$result = explode('/', $data);
$dia = $result[0];
$mes = $result[1];
$ano = $result[2];
$data = $ano . '-' . $mes . '-' . $dia;
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];

$sql = "INSERT INTO tbitens (idc, ido, data, descricao, quantidade, preco) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, 'isssss', $idc, $ido, $data, $descricao, $quantidade, $preco);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Número de Ordem Criado!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>