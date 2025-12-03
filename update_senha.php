<?php include_once"session.php";?>
<?php
include_once"config.php";
//
if(!empty($_SESSION['usuario'] === 'admin')){
}else{
$_SESSION['msg'] = "Área restrita";
echo "<script>location.href='index.php';</script>";
}
?>
<?php
include_once "config.php";

$id = $_POST['id'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$sql = "UPDATE tbusuarios SET senha=? WHERE id=?";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "si", $senha, $id);

  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Senha Alterada!'); window.location = 'admin.php';</script>";
  } else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
