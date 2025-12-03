<?php include_once"session.php";?>
<?php
include_once "config.php";

$idc = $_POST['idc'];
$nome = $_POST['nome'];
$data = date("Y-m-d H:i:s");
$conteudoc = $_POST['conteudoc'];

// Verifica se foi enviado um arquivo
if (isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0) {
  $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
  $nome_file = $_FILES['arquivo']['name'];

  // Verifica a extensão do arquivo
  $extensao = strtolower(pathinfo($nome_file, PATHINFO_EXTENSION));
  $extensoes_permitidas = array("jpg", "jpeg", "png", "pdf");

  if (in_array($extensao, $extensoes_permitidas)) {
    // Cria um nome único para o arquivo
    $novoNome = 'idc' . $idc . '_' . date("dmYhis") . '.' . $extensao;

    // Define o diretório de destino
    $destino = './docs/' . $novoNome;

    // Diretório para uploads de documentos e imagens
    $dir = './docs/';
    if (!is_dir($dir)) {
      // Cria o diretório se não existir e protege os arquivos
      mkdir($dir, 0755, true);
      copy('docs/index.php', $dir . 'index.php');
      copy('docs/.htaccess', $dir . '.htaccess');
    }

    // Move o arquivo para o destino
    if (move_uploaded_file($arquivo_tmp, $destino)) {
      // Insere os dados no banco de dados usando uma declaração preparada
      $sql = "INSERT INTO tbarquivosc (idc, data, nome, conteudoc, nomedoc, log) VALUES (?, ?, ?, ?, ?, ?)";

      $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
      mysqli_set_charset($conn, "utf8");

      $stmt = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt, $sql)) {
        $log = ''; // Defina o valor correto para a variável $log

        mysqli_stmt_bind_param($stmt, "ssssss", $idc, $data, $nome, $conteudoc, $novoNome, $log);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Log
        $dtloglog = date('d-m-Y H:i:s');
        $arquivolog = fopen('docs/logs.txt', 'a+', 0);
        $texto = 'data: ' . $dtloglog . ' cliente: ' . $nome . ' idc: ' . $idc . ' usuario: ' . $log . ' inseriu arquivo ' . $conteudoc . ' ' . $novoNome . ' ' . ":\n";
        fwrite($arquivolog, $texto);
        fclose($arquivolog);
        // Fim do log

        echo "<script>alert('Arquivo salvo com sucesso!'); window.location = 'form_cliente_arquivos.php?idc=$idc';</script>";
      } else {
        echo "<script>alert('Erro ao salvar o arquivo. Por favor, tente novamente.'); window.location = 'javascript:history.back()';</script>";
      }

      mysqli_close($conn);
    } else {
      echo "<script>alert('Erro ao mover o arquivo. Verifique as permissões de escrita.'); window.location = 'javascript:history.back()';</script>";
    }
  } else {
    echo "<script>alert('Apenas são permitidos arquivos nos formatos .jpg, .jpeg, .png e .pdf.'); window.location = 'javascript:history.back()';</script>";
  }
} else {
  echo "<script>alert('Nenhum arquivo enviado.'); window.location = 'javascript:history.back()';</script>";
}
?>