<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<h3>Pesquisa</h3>
<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="20%" style="text-align:left;">Nome</th>
       <th width="10%" style="text-align:center">Documento</th>
       <th width="10%" style="text-align:center">Celular</th>
     </tr>
   </thead>
<?php
//
    $busca = filter_var($_GET['busca'], FILTER_SANITIZE_ADD_SLASHES);
    $mes= date("m"); // pega mes atual
    $hojea = date('d/m'); // pega dia e mes
//
    $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
    mysqli_set_charset($conn,"utf8");
$result_nomes = "SELECT * FROM tbclientes WHERE nome LIKE '%$busca%' or documento LIKE '%$busca%' or idc LIKE '%$busca%' Order By nome DESC limit 10";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    $conta = mysqli_num_rows($resultado_nomes); // conta registro
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
echo '<tbody>
      <tr>
         <td scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["nome"].'</a></td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.substr($rows_nomes["documento"],0,10).'***</a></td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["celular"].'</td>
      </tr>
     </tbody>';
 }
echo '</table>';
?>
<div><h3>Total: <?php echo $conta;?></h3></div>
<?php include_once"rodape.php";?>