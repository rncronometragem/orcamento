<?php
include_once "config.php";
include_once "session.php";
$cad_status = $_POST['cad_status'];
// Verificar se a status já existe na tabela
$sql_verificar = "SELECT COUNT(*) FROM tbstatus WHERE cad_status = ?";
$stmt_verificar = mysqli_prepare($conn, $sql_verificar);
mysqli_stmt_bind_param($stmt_verificar, 's', $cad_status);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_bind_result($stmt_verificar, $num_rows);
mysqli_stmt_fetch($stmt_verificar);
mysqli_stmt_close($stmt_verificar);

if ($num_rows > 0) {
    echo "<script>alert('A Status já existe na tabela!'); window.location = 'index_status.php';</script>";
    exit(); 
}
$sql_inserir = "INSERT INTO tbstatus (cad_status, log) VALUES (?, ?)";
$stmt_inserir = mysqli_prepare($conn, $sql_inserir);
mysqli_stmt_bind_param($stmt_inserir, 'ss', $cad_status, $log);

if (mysqli_stmt_execute($stmt_inserir)) {
    echo "<script>alert('Status salva com sucesso!'); window.location = 'index_status.php';</script>";
} else {
    echo "Error: " . $sql_inserir . "<br>" . mysqli_error($conn);
}

mysqli_stmt_close($stmt_inserir);
mysqli_close($conn);
?>
