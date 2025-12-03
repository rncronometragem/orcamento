<?php
include 'config.php'; // Inclua sua conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar entradas para evitar SQL Injection
    $ido = mysqli_real_escape_string($conn, $_POST['ido']);
    $novo_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Atualiza o status no banco de dados
    $query = "UPDATE tbordens SET status_ordem='$novo_status' WHERE ido='$ido'";
    $resultado = mysqli_query($conn, $query);

    // Verifica se a query foi executada com sucesso
    if ($resultado) {
        echo "success"; // Resposta para indicar sucesso
    } else {
        echo "error"; // Resposta para indicar erro
    }
} else {
    echo "error"; // Resposta para indicar erro se o método não for POST
}
?>
