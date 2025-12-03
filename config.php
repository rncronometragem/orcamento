<?php
 // alterar
 $servidor = "localhost";
 $dbusuario = "thia8868_orcamento";
 $dbsenha = "orcamentorncrono123";
 $dbname = "thia8868_orcamentos";
 // fim
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn, "utf8mb4");
  setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
 date_default_timezone_set('America/Sao_Paulo');
   $sql_empresa = "SELECT * FROM tbempresa WHERE ide='1'";
 $result_sql = mysqli_query($conn, $sql_empresa);
     while($rows_l = mysqli_fetch_array($result_sql)){
  $barra = $rows_l['barra'];
  $letra = $rows_l['letra'];
  $imagem = $rows_l['imagem'];
 }
?>