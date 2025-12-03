<?php
include_once "session.php";
include_once "config.php";

$hora = date("H:i");
$idc = $_POST['idc'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$data = $_POST['data'];
$result = explode('/', $data);
$dia = $result[0];
$mes = $result[1];
$ano = $result[2];
$data = $ano . '-' . $mes . '-' . $dia . ' ' . $hora;

$conteudo_file = $_POST['conteudo_file'];
$nome_file = $_FILES['arquivo']['name'];
$pasta_file = './docs/'.$ano.'/'.$mes.'/'.'idc'.$idc.'/'.$mes.'/';

if (!is_dir($pasta_file)) {
    mkdir($pasta_file, 0755, true);
    copy('./docs/index.php', $pasta_file . 'index.php');
    copy('./docs/.htaccess', $pasta_file . '.htaccess');
}

$upload_sucessful = false;

if (!empty($nome_file) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($nome_file, PATHINFO_EXTENSION);

   if (in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
    $ext = ($ext === 'pdf') ? 'pdf' : $ext; 
    $unique_filename = date('d_m_Y') . '_' . str_replace(' ', '_', $conteudo_file) . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $pasta_file . $unique_filename)) {
            //echo "arquivo enviado";
            $upload_sucessful = true;
        } else {
          echo "<script>window.location = 'javascript:history.back()';</script>";
        }
    } else {
        echo "<script>alert('Apenas imagens (jpg, jpeg, png) e PDF são permitidos.'); window.location = 'javascript:history.back()';</script>";
    }
}

$sql = "INSERT INTO tbhistoricos (descricao, idc, data, nome, log, nome_file, conteudo_file, pasta_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($upload_sucessful) {
    $nome_file = $unique_filename;
}

mysqli_stmt_bind_param($stmt, 'sissssss', $descricao, $idc, $data, $nome, $log, $nome_file, $conteudo_file, $pasta_file);

$stmt;

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Histórico Salvo com sucesso!'); window.location = 'form_cliente_editar.php?idc=$idc';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>