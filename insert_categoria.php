<?php
include_once "config.php";
include_once "session.php";
$categoria = $_POST['categoria'];
// Verificar se a categoria já existe na tabela
$sql_verificar = "SELECT COUNT(*) FROM tbcategorias WHERE categoria = ?";
$stmt_verificar = mysqli_prepare($conn, $sql_verificar);
mysqli_stmt_bind_param($stmt_verificar, 's', $categoria);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_bind_result($stmt_verificar, $num_rows);
mysqli_stmt_fetch($stmt_verificar);
mysqli_stmt_close($stmt_verificar);

if ($num_rows > 0) {
    echo "<script>alert('A categoria já existe na tabela!'); window.location = 'index_categoria.php';</script>";
    exit(); 
}
$sql_inserir = "INSERT INTO tbcategorias (categoria, log) VALUES (?, ?)";
$stmt_inserir = mysqli_prepare($conn, $sql_inserir);
mysqli_stmt_bind_param($stmt_inserir, 'ss', $categoria, $log);

if (mysqli_stmt_execute($stmt_inserir)) {
    echo "<script>alert('Categoria salva com sucesso!'); window.location = 'index_categoria.php';</script>";
} else {
    echo "Error: " . $sql_inserir . "<br>" . mysqli_error($conn);
}

mysqli_stmt_close($stmt_inserir);
mysqli_close($conn);
?>
