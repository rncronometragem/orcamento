<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php
//
$ide = $_GET['ide'];   
 //
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 //
 $result_nomes = "SELECT * FROM tbempresa WHERE ide='$ide'";
 $resultado_nomes = mysqli_query($conn, $result_nomes);
     while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
//
  $idc = $rows_nomes['ide'];
  $imagem = $rows_nomes['imagem'];
}
?>
<br>
<div style="text-align:center;">
      <a href="javascript:AlterImagem()"><img src="./logo/<?php echo $imagem; ?>" class="media-object" height="150" width="150"></a>
 </div>
 <br>
<form  method="post" enctype="multipart/form-data" name="adiciona" action="update_logo.php" autocomplete='off'>
    <input type="hidden"  class="form-control" hidden="true" name="ide" maxlength="50" value="<?php echo $ide; ?>">
    <div>
      <label for="inputlg" for="name"></label>
      <input type="file"  name="arquivo" accept="image/x-png" value="" required accept="image/png">
        <input type="submit" value="Enviar" onsubmit="Checkfiles(this)" class="btn btn-info btn-sm"></div>
</form>
<br>
<div style="text-align:center;">Enviar logo com extensão .png com fundo transparente</div>