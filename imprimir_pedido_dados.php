<?php
$result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc'";
  $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
 $idc = $rows_nomes['idc'];
 $nome = $rows_nomes['nome'];
 $categoria = $rows_nomes['categoria'];
 $cad_status = $rows_nomes['cad_status'];
 $datareg = date("d/m/Y", strtotime($rows_nomes['datareg']));
 $documento = $rows_nomes['documento']; 
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
<center><h4>Dados do Cliente</h4></center>
  <div style="font-size:14px"> <b>CLIENTE:</b> <?php echo $nome; ?> <b>CPF/CNPJ:</b> <?php echo $documento; ?></br>
<b>ENDEREÇO:</b> <?php echo $rua; ?> <b>CEP:</b> <?php echo $cep; ?></br>
   <b>BAIRRO:</b> <?php echo $bairro; ?>  <b>CIDADE:</b> <?php echo $cidade; ?> <b>UF:</b> <?php echo $uf; ?></br>
    <b>TELEFONE:</b> <?php echo $telefone; ?> <b>CELULAR:</b> <?php echo $celular; ?> <b>E-MAIL:</b> <?php echo $email; ?>
 </div>
 <br>
