<?php
include_once "config.php";
include_once "session.php";

$hora = date("H:i"); // hora da atualização
$nome = $_POST['nome'];
$idc = $_POST['idc'];
$idh = $_POST['idh'];
$descricao = $_POST['descricao'];
$data = $_POST['data'];

// Formate a data corretamente
$result = explode('/', $data);
$dia = $result[0];
$mes = $result[1];
$ano = $result[2];
$data = $ano . '-' . $mes . '-' . $dia . ' ' . $hora;

// Prepare a statement
$stmt = mysqli_prepare($conn, "UPDATE tbhistoricos SET descricao=?, data=? WHERE idh=?");

// Verifique se ocorreu um erro na preparação da declaração
if ($stmt === false) {
    die('Erro na preparação da declaração: ' . mysqli_error($conn));
}

// Vincule os parâmetros à declaração
mysqli_stmt_bind_param($stmt, 'ssi', $descricao, $data, $idh);

// Executa o statement
if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Histórico Salvo com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}

// Fecha a conexão
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>