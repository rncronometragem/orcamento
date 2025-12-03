<?php
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn,"utf8");
// pesquisa empresa 
$result_empresa = "SELECT * FROM tbempresa";
  $resultado_empresa = mysqli_query($conn, $result_empresa);
    while($rows_empre = mysqli_fetch_array($resultado_empresa)){
$elogo = $rows_empre['imagem'];   
$enome = $rows_empre['nome'];
$ecnpj = $rows_empre['cnpj'];
$einsc = $rows_empre['inscestadual'];
$eend = $rows_empre['rua'];
$enum = $rows_empre['num'];
$enumcomp = $rows_empre['numcomp'];
$ebairro = $rows_empre['bairro'];
$ecidade = $rows_empre['cidade'];
$euf = $rows_empre['uf'];
$ecep = $rows_empre['cep'];
$etelcom = $rows_empre['telcom'];
$etelcel = $rows_empre['celular'];
$esite = $rows_empre['site'];
$eemail = $rows_empre['email'];
}
?>
<style>
        .linha {
            border-top: 1px solid #000; /* Cor da linha */
            margin: 20px 0; /* Espaçamento acima e abaixo */
        }
    </style>
<table>
  <tr>
    <td style="width:150px;"><img  style="padding: 5px 15px 5px 5px;" align="right" src="./logo/<?php echo $elogo;?>" width="130" height="130" ></td>
  <td style="font-size:13px;"><?php echo $enome;?></br>
   <?php echo $ecnpj;?>  <?php echo $einsc;?></br>
   <?php echo $ecep;?> <?php echo $eend;?> - <?php echo $enum;?> <?php echo $enumcomp;?> - <?php echo $ebairro;?> -
      <?php echo $ecidade;?> - <?php echo $euf;?> </br>
    <?php echo $etelcom;?> <?php echo $etelcel;?> <?php echo $esite;?> <?php echo $eemail;?></td> 
  </tr>
</table>
<div class="linha"></div>