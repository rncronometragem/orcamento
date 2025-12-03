<?php
// Conexão com o banco de dados (se ainda não estiver conectado)
include 'config.php';

// Verifica se o parâmetro 'idi' foi enviado via POST
if (isset($_POST['idi'])) {
    // Sanitize para evitar SQL Injection
    $idi = mysqli_real_escape_string($conn, $_POST['idi']);

    // Query para deletar o item do banco de dados
    $query_delete = "DELETE FROM tbitens WHERE idi='$idi'";
    $resultado_delete = mysqli_query($conn, $query_delete);

    // Verifica se a query foi executada com sucesso
    if ($resultado_delete) {
        echo "success"; // Resposta para indicar sucesso
    } else {
        echo "error"; // Resposta para indicar erro
    }
} else {
    echo "error"; // Resposta para indicar erro se 'idi' não foi enviado
}
?>