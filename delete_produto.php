<?php
include_once "session.php";
include_once "config.php";

// Obtém o ID do produto a ser deletado
$idp = $_POST['idp'];

// Conecta ao banco de dados
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);

// Verifica a conexão
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Prepara a declaração SQL para deletar o produto
$sql = "DELETE FROM tbprodutos WHERE idp = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Vincula o parâmetro ID ao SQL
    mysqli_stmt_bind_param($stmt, "i", $idp);

    // Executa a declaração
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Deletado com sucesso!'); window.location = 'index_produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao deletar o produto!'); window.location = 'javascript:history.back()';</script>";
    }

    // Fecha a declaração
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Erro ao preparar a declaração SQL!'); window.location = 'javascript:history.back()';</script>";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
