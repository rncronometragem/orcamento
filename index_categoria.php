<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<br>
<div>
</div>
<!-- categoria começa -->
  <form  method="post" action="insert_categoria.php">   
 <div class="form-group col-md-4">
      <label for="inputlg" for="name">Adicionar Categoria</label>
      <input type="text"  class="form-control"  name="categoria" id="categoria" maxlength="150" value="" required="required" oninput="maiuscula(event)"/>
    </div>
     <div class="col-md-12">
      <button type="submit" class="btn btn-info btn-lg" title="Salvar Categoria"><i class="bi bi-save"></i></button>
    </div>
</form>
<table class="table table-hover">
<thead>
    <tr>
<th width="70%" align="left">Categorias</th>
<th width="10%" align="left">Usuário</th>
<th width="20%" align="left">Deletar</th>
</tr>
   </thead>
<?php
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn,"utf8");     
if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        } else {
            $pagina = 1;
        }
        $numerodepagina = 15;
        $offset = ($pagina-1) * $numerodepagina;
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();        }
  $total_paginas_sql = "SELECT COUNT(*) FROM tbcategorias";
  $result = mysqli_query($conn,$total_paginas_sql);
  $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);
// termina paginação
$result_nomes = "SELECT * FROM tbcategorias ORDER BY categoria ASC LIMIT $offset, $numerodepagina";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
$idcat = $rows_nomes['idcat'];
$categoria = $rows_nomes['categoria'];
echo '<tbody>
      <tr>
<td width="70%" align="left">'.$rows_nomes["categoria"].'</td>
<td width="10%" align="left">'.$rows_nomes["log"].'</td>
<td width="10%" align="left"><form  method="post" id="form" name="dataForm" action="delete_categoria.php" onsubmit="return deleta_categoria();">
<input type="hidden"  name="categoria" value="'.$categoria.'">
<input type="hidden"  name="idcat" value="'.$idcat.'">
<input type="hidden"  name="log" value="'.$log.'">
<button type="submit" title="Deletar"><i class="bi bi-trash" style="color:red"></i></button>
</form></td>
</tr>
<tbody>';
}
echo '</table>';
?>
</div>
<!-- categoria termina-->
</div>
<?php include_once"paginar.php";?>
<?php include_once"rodape.php";?>
      