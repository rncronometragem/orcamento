<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php
//
$id = $_POST['id'];
$nome = $_POST['nome'];
//
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
$sql = "DELETE FROM tbusuarios WHERE id='$id'";
//
if (mysqli_query($conn,$sql)){
//log
$dtloglog = date('d-m-Y H:i:s');
$arquivolog = fopen('docs/logs.txt','a+',0); 
$texto = 'data: ' .$dtloglog.' nome: '.$nome. ' id: '.$id.' usuario: '.$log.' deletou usuário'.":\n"; 
fwrite($arquivolog,$texto); 
fclose($arquivolog);
//log 
header("Location:admin.php");
}else{
echo "<script>window.location = 'javascript:history.back()';</script>";
}
?>