<?php include_once"session.php";?>
<?php
include_once "config.php";
$ide = $_POST['ide'];

// Verifica se foi enviado um arquivo
if (isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0) {
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $nome = $_FILES['arquivo']['name'];
    $tipo = $_FILES['arquivo']['type'];

    // Verifica se o arquivo enviado é uma imagem
    if (exif_imagetype($arquivo_tmp)) {
        // Se tiver tudo OK, deleta a imagem antiga
        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        $result_nomes = "SELECT * FROM tbempresa WHERE ide='$ide'";
        $resultado_nomes = mysqli_query($conn, $result_nomes);
        while ($rows_nomes = mysqli_fetch_array($resultado_nomes)) {
            $imagem = $rows_nomes['imagem'];
        }
        unlink("./logo/$imagem");

        // Define um novo nome único para esta imagem
        $novoNome = date("s") . 'logo' . '.png';

        // Define o destino da imagem
        $destino = './logo/' . $novoNome;

        // Tenta mover o arquivo para o destino
        if (@move_uploaded_file($arquivo_tmp, $destino)) {
            $sql = "UPDATE tbempresa SET imagem='$novoNome' WHERE ide='$ide'";
            mysqli_query($conn, $sql);

            echo "<script>alert('Salvo com sucesso!'); window.location = 'form_empresa_editar.php?idc=$ide';</script>";
        } else {
            echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";
        }
    } else {
        echo "<script>alert('Apenas arquivos de imagem são permitidos'); window.location = 'javascript:history.back()';</script>";
    }
} else {
    echo "<script>alert('Nenhum arquivo enviado.'); window.location = 'javascript:history.back()';</script>";
}
?>
