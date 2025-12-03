<?php include_once"session.php";?>
<?php
include_once "config.php";
if (!empty($_SESSION['status']) && $_SESSION['status'] === 'administrador') {

}else{
$_SESSION['msg'] = "Administrador";
echo "<script>location.href='index.php';</script>";
exit;
}
$idc = $_GET['idc'];
$stmt = mysqli_stmt_init($conn);
$sql = "SELECT COUNT(*) FROM tbhistoricos WHERE idc = ?";
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "s", $idc);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $totalHistoricos);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  if ($totalHistoricos > 0) {
    echo "<script>alert('Você não pode deletar este Cliente porque existe Histórico ou Arquivo no cadastro.'); window.location = 'javascript:history.back()';</script>";
    exit;
  }

  $stmt = mysqli_stmt_init($conn);
  $sql = "SELECT nome FROM tbclientes WHERE idc = ?";
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $idc);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    if ($row = mysqli_fetch_assoc($resultado)) {
      $nome = $row['nome'];

      // Log
      $dtloglog = date('d-m-Y H:i:s');
      $arquivolog = fopen('docs/logs.txt', 'a+', 0);
      $texto = 'data: ' . $dtloglog . ' cliente: ' . $nome . ' idc: ' . $idc . ' usuario: ' . $log . ' deletou cadastro' . ":\n";
      fwrite($arquivolog, $texto);
      fclose($arquivolog);
      // Fim do log
      $stmt = mysqli_stmt_init($conn);
      $sql = "DELETE FROM tbclientes WHERE idc = ?";
      if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $idc);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['msg'] = "Cliente deletado com sucesso";
        echo "<script>location.href='index.php';</script>";
        exit;
      }
    }
  }
}

echo "<script>alert('Erro ao deletar o cliente. Por favor, tente novamente.'); window.location = 'javascript:history.back()';</script>";
?>