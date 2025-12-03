<?php include_once"session.php";?>
<?php
include_once "config.php";
// precisa ser administrador
if (!empty($_SESSION['status']) && $_SESSION['status'] === 'administrador') {
}else{
$_SESSION['msg'] = "Administrador";
echo "<script>location.href='index.php';</script>";
exit;
}
$idcat = $_POST['idcat'];
$categoria = $_POST['categoria'];
  $stmt = mysqli_stmt_init($conn);
  $sql = "DELETE FROM tbcategorias WHERE idcat = ?";
  if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $idcat);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['msg'] = "Categoria deletado com sucesso";
        echo "<script>location.href='index_categoria.php';</script>";
        exit;
      }
echo "<script>alert('Erro ao deletar o Categoria. Por favor, tente novamente.'); window.location = 'javascript:history.back()';</script>";
?>