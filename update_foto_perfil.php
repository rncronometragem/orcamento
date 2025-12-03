<?php include_once"session.php";?>
<?php
include_once "config.php";
$idc = $_POST['idc'];

// Verifica se foi enviado um arquivo
if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0) {
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $nome = $_FILES['arquivo']['name'];

    // Obtém informações sobre o tipo de arquivo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tipo_arquivo = finfo_file($finfo, $arquivo_tmp);

    // Verifica se o tipo de arquivo é uma imagem
    if(strpos($tipo_arquivo, 'image/') === 0) {
        // Se tiver tudo ok, deleta a imagem antiga
        $result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc'";
        $resultado_nomes = mysqli_query($conn, $result_nomes);
        while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
            $idc = $rows_nomes['idc'];
            $imagem = $rows_nomes['imagem'];
        }
        unlink("./docs/$imagem");

        // Cria um nome único para esta imagem
        // Evita que duplique as imagens no servidor.
        $extensao = pathinfo($nome, PATHINFO_EXTENSION);
        $novoNome = 'idc'.$idc.'_'.md5(microtime()).'.'.$extensao;

        // Concatena a pasta com o nome
        $destino = './docs/' . $novoNome;
        $dir = './docs/';

        // Tenta mover o arquivo para o destino
        if(@move_uploaded_file($arquivo_tmp, $destino)) {
            $sql = "UPDATE tbclientes SET imagem='$novoNome' WHERE idc='$idc'";
            mysqli_query($conn, $sql);
            echo "<script>alert('Salvo com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
        } else {
            echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";
        }
    } else {
        echo "<script>alert('Apenas arquivos de imagem são permitidos.'); window.location = 'javascript:history.back()';</script>";
    }
} else {
    echo "<script>alert('Nenhum arquivo enviado.'); window.location = 'javascript:history.back()';</script>";
}

$origem = 'img/padrao.png';
$destino = 'docs/padrao.png';
copy($origem, $destino);
?>