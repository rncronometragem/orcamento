<?php
include_once "config.php";
include_once "session.php";

// Verifica se o usuário é administrador
if (!empty($_SESSION['status']) && $_SESSION['status'] === 'administrador') {
    // Obtém os valores dos campos nome, idc e ido do formulário
    $nome = $_POST['nome'];
    $idc = $_POST['idc'];
    $ido = $_POST['ido'];

    // Deleta itens relacionados na tabela tbitens
    $sql = "DELETE FROM tbitens WHERE ido='$ido'";
    mysqli_query($conn, $sql);

    // Deleta ordens na tabela tbordens
    $sql = "DELETE FROM tbordens WHERE ido='$ido'";
    mysqli_query($conn, $sql);

    // Mostra uma mensagem de sucesso e redireciona
    echo "<script>alert('Excluído com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    // Se o usuário não for administrador, redireciona para a página inicial
    echo "<script>location.href='index.php';</script>";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>