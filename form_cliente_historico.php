<?php
// inicio historico
$hoje = date('d/m/Y'); // pega dia de hoje
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn,"utf8");
$result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc' limit 1";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    $conta = mysqli_num_rows($resultado_nomes); // conta registro
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
$nome = $rows_nomes['nome'];
}
echo '<form method="post" action="insert_historico.php" enctype="multipart/form-data">
  <input type="hidden" name="nome" value="'.$nome.'">
  <input type="hidden" name="log" value="'.$log.'">
  <input type="hidden" class="form-control" name="idc" value="'.$idc.'">
  
  <div class="form-group col-md-12">
    <label for="campo2"><h3>Histórico</h3></label>
    <textarea align="justify" class="form-control" maxlength="500" name="descricao" rows="3" required></textarea>
  </div>

  <div class="form-group col-md-2">
    <label for="inputlg" for="name">Data</label>
    <input type="text" class="form-control" name="data" maxlength="15" value="'.$hoje.'" id="datahist" required="required" autocomplete="user-password">
  </div>

  <div class="form-group col-md-3">
    <label for="inputlg" for="name">Conteúdo do arquivo</label>
    <input type="text" class="form-control" name="conteudo_file" maxlength="150" value="" autocomplete="user-password">
  </div>

  <div class="form-group col-md-3">
    <label for="inputlg" for="name">Arquivo imagem ou pdf</label>
    <input type="file" name="arquivo" value="">
  </div>

  <div class="col-md-12">
    <button type="submit" class="btn btn-success btn-lg" title="Salvar Histórico"><i class="bi bi-save"></i></button>
  </div>
</form>';
echo '<table class="table table-hover">
<thead>
    <tr>
    <th width="10%" align="left">Data</th>
    <th width="60%" align="left">Descrição</th>
    <th width="10%" align="center">Arquivo</th>
    <th width="10%" align="center">Opções</th>
    </tr>
</thead>';

// começa paginação histórico
$idc = $_GET['idc'];
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn,"utf8");     
if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        } else {
            $pagina = 1;
        }
        $numerodepagina = 5;
        $offset = ($pagina-1) * $numerodepagina;
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();        }
  $total_paginas_sql = "SELECT COUNT(*) FROM tbhistoricos";
  $result = mysqli_query($conn,$total_paginas_sql);
  $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);
// termina paginação
$result_nomes = "SELECT * FROM tbhistoricos WHERE idc='$idc' ORDER BY data desc LIMIT $offset, $numerodepagina";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
$descricao = $rows_nomes['descricao'];
$idh = $rows_nomes['idh'];
$idc = $rows_nomes['idc'];
$logb = $rows_nomes['log'];
if ($logb == $log or $status == 'administrador') {
 $md = "data-toggle"; // modal
}else{
  $md = "";
}
if ($logb == $log or $status == 'administrador') {
  $delh = ""; //de
}else{
  $delh = "hidden";
}
$datah = date('d/m/Y', strtotime($rows_nomes["data"]));
echo '<tbody>
      <tr>
<td width="10%" align="left" valign="middle">'.date('d/m/Y H:i', strtotime($rows_nomes["data"])).'</td>
<td width="60%" align="left">
<!-- Botão para acionar modal -->
<div class="modal fade" id="'.$rows_nomes["idh"].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Histórico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     <form  method="post" action="update_historico.php">
  <input type="hidden"  class="form-control" name="idh" value="'.$idh.'">
  <input type="hidden"  class="form-control" name="nome" value="'.$nome.'">
  <input type="hidden"  class="form-control" name="idc" value="'.$idc.'">
  <input type="hidden"  name="log" value="'.$log.'">
      <textarea class="form-control" name="descricao" rows="4"  maxlength="500">'.$rows_nomes["descricao"].'</textarea><div class="btn-group">'.$rows_nomes["log"].'</div>
    </div>
      <div class="form-group col-md-10">
    <label for="campo2"><h5>Data</h5></label>
      <input type="text" name="data" value="'.$datah.'" id="datahist2" required="true" style="width:120px;">
      </div>  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
  </form>
  <!-- Botão para acionar modal -->
</div><a title="Editar" '.$md.'="modal" data-target="#'.$rows_nomes["idh"].'">'.$rows_nomes["descricao"].'</a> 
<p align="left">'.$rows_nomes["log"].' </p></td>
<td width="10%" align="left"><a href="'.$rows_nomes["pasta_file"].''.$rows_nomes["nome_file"].'" target="_blank">'.$rows_nomes["conteudo_file"].' </td>
<td width="10%"><div class="btn-group">
<form method="post"  name="dataForm" action="imprimir_historico.php" target="_blank">
<input type="hidden"  name="idh" value="'.$rows_nomes['idh'].'">
<input type="hidden"  name="nome" value="'.$nome.'">
<input type="hidden"  name="idc" value="'.$rows_nomes['idc'].'">
<button type="submit" title="Imprimir Histórico"><i class="bi bi-printer" style="color:blue"></i></button></form>
</div>
<div class="btn-group">
<form  method="post" id"form" name="dataForm" action="delete_historico.php">
<input type="hidden"  name="nome" value="'.$rows_nomes['nome'].'">
<input type="hidden"  name="idc" value="'.$rows_nomes['idc'].'">
<input type="hidden"  name="idh" value="'.$rows_nomes['idh'].'">
<button type="submit" title="Deletar" onclick="return deleta_historico();" '.$delh.'><i class="bi bi-trash" style="color:red" '.$delh.'></i></button></form></div></td>
</tr>
     </tbody>';
}
echo '</table>';
?>