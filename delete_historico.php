<?php
include_once "config.php";
include_once "session.php";
// deleta histórico e arquivo
if (!empty($_SESSION['status']) && $_SESSION['status'] === 'administrador') {
    // Se for um administrador, continuar com a exclusão
    $nome = $_POST['nome'];
    $idc = $_POST['idc'];
    $idh = $_POST['idh'];
    $result_nomes = "SELECT * FROM tbhistoricos WHERE idh='$idh'";
    $resultado_nomes = mysqli_query($conn, $result_nomes);

    while ($rows_nomes = mysqli_fetch_array($resultado_nomes)) {
        $idc = $rows_nomes['idc'];
        $nome_file = $rows_nomes['nome_file'];
        $pasta_file = $rows_nomes['pasta_file'];
        
        // Verifica se existe arquivo 
        if (!empty($nome_file) && file_exists("$pasta_file/$nome_file")) {
            unlink("$pasta_file/$nome_file");
        }
        // deleta histórico e arquivo se tiver arquivo
        $sql = "DELETE FROM tbhistoricos WHERE idh='$idh'";
        mysqli_query($conn, $sql);
    }

 echo "<script>alert('Excluído com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    // Se não for um administrador
    $_SESSION['msg'] = "Acesso restrito. Você deve ser um administrador.";
    echo "<script>location.href='index.php';</script>";
}

mysqli_close($conn);
?>