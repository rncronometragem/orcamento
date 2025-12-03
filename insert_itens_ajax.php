<?php
include_once "session.php";
include_once "config.php";

$idc = $_POST['idc'];
$ido = $_POST['ido'];
$data = date('Y-m-d'); // Pegue a data atual
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];
$preco = str_replace(',', '.', str_replace('.', '', $_POST['preco']));

$sql = "INSERT INTO tbitens (idc, ido, data, descricao, quantidade, preco) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, 'isssss', $idc, $ido, $data, $descricao, $quantidade, $preco);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
