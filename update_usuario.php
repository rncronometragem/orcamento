<?php include_once"session.php";?>
<?php
include_once "config.php";
//
$id = $_POST['id'];
$nome = $_POST['nome'];
$status = $_POST['status'];
$email = $_POST['email'];

$sql = "UPDATE tbusuarios SET nome=?, email=?, status=? WHERE id=?";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
  mysqli_stmt_bind_param($stmt, "sssi", $nome, $email, $status, $id);

  if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('$nome Salvo com sucesso!'); window.location = 'admin.php';</script>";
  } else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>