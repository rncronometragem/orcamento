<?php
$result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc'";
  $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
 $idc = $rows_nomes['idc'];
 $nome = $rows_nomes['nome'];
 $documento = $rows_nomes['documento'];
 $categoria = $rows_nomes['categoria'];
$datareg = date("d/m/Y", strtotime($rows_nomes['datareg']));
$cep = $rows_nomes['cep'];
$rua = $rows_nomes['rua'];
$cidade = $rows_nomes['cidade'];
$bairro = $rows_nomes['bairro'];
$uf = $rows_nomes['uf'];
$telefone = $rows_nomes['telefone'];
$celular = $rows_nomes['celular'];
$email = $rows_nomes['email'];
$obs = $rows_nomes['obs'];
}
?>
<table class="table table-hover">
  <tr>
  <td style="font-size:13px">CLIENTE: <?php echo $nome; ?></br>
    DOCUMENTO: <?php echo $documento; ?></br> 
    CEP: <?php echo $cep; ?> <?php echo $rua; ?> </br> BAIRRO: <?php echo $bairro; ?> CIDADE: <?php echo $cidade; ?> UF: <?php echo $uf; ?></br> TELEFONE: <?php echo $telefone; ?> CELULAR: <?php echo $celular; ?> E-MAIL: <?php echo $email; ?></br> OBSERVAÇÕES: <?php echo $obs; ?>
</td> 
  </tr>
</table>