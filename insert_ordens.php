<?php
include_once "session.php";
include_once "config.php";

$hora = date("H:i");
$idc = $_POST['idc'];
$nome = $_POST['nome']; // Nome do cliente
$data = $_POST['data']; // Data do registro (hoje)

// Tratamento da Data de Registro (PT-BR -> EN)
$result = explode('/', $data);
$dia = $result[0];
$mes = $result[1];
$ano = $result[2];
$data_registro = $ano . '-' . $mes . '-' . $dia . ' ' . $hora;

// Novos Campos
$nome_evento  = $_POST['nome_evento'] ?? '';
$local_evento = $_POST['local_evento'] ?? '';
$data_evento  = $_POST['data_evento'] ?? NULL; // Já vem no formato Y-m-d do input type="date"

$status_ordem = $_POST['status_ordem'];
$log = $_POST['log'] ?? 'admin'; // Garante que log tenha valor

// SQL Atualizado
$sql = "INSERT INTO tbordens (idc, data, nome, log, status_ordem, nome_evento, data_evento, local_evento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// s = string, i = integer
// Ordem: idc(i), data(s), nome(s), log(s), status(s), nome_evento(s), data_evento(s), local_evento(s)
mysqli_stmt_bind_param($stmt, 'isssssss', $idc, $data_registro, $nome, $log, $status_ordem, $nome_evento, $data_evento, $local_evento);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Pedido Criado com Sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>alert('Erro ao criar pedido'); window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>