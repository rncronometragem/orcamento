<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<html>
<head>
    <title>Cadastro de Clintes</title>
</head>
<body>
    <script>
function show_delall() {
  if(confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? Ao deletar você vai apagar também os todos históricos desse Cliente e NÃO TEM como recuperar os dados depois."))
    document.forms[0].submit();
  else 
    return false;}
</script>
 <script type="text/javascript">
$(document).ready(function(e) {
    $("#data,#data2,#dataad").datepicker({
    setDate: "today",
    language: "pt-BR",
    todayHighlight: "true",
    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    dateFormat: 'dd/mm/yy',
    nextText: 'Próximo',
    prevText: 'Anterior'
  });
});
</script>
<br>
<h4><a href="indexh.php" title="Histórico"><i class="bi bi-chat-left"></i> Históricos</a> </h4>
<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="20%" style="text-align:left;">Pesquisa Históricos</th>
       <th width="20%" style="text-align:center"><form method="post"  name="dataForm" action="index_historico_busca.php">Data: <input type="text" name="data" value="<?php echo $hoje = date('d/m/Y');?>"id="data" autocomplete="off" required="true" style="width:120px;">
<button type="submit" title="OK"><i class="bi bi-search"></i></button>
</form></th>
       <th width="40%" style="text-align:center">Descrição</th>
       <th width="10%" style="text-align:center">Usuário</th>
     </tr>
   </thead>
    <?php
//
 $data = $_POST['data'];
 $data = explode("/", $data);
 list($dia, $mes, $ano) = $data;
 $data = "$ano-$mes-$dia";
    $mes= date("m"); // pega mes atual
    $hojea = date('d/m'); // pega dia de hoje

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        } else {
            $pagina = 1;
        }
        $numerodepagina = 15;
        $offset = ($pagina-1) * $numerodepagina;
//
        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        mysqli_set_charset($conn,"utf8");
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();
        }
//
        $total_paginas_sql = "SELECT COUNT(*) FROM tbhistoricos WHERE DATE(data) = '$data'";
        $result = mysqli_query($conn,$total_paginas_sql);
        $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);
  $sql = "SELECT * FROM tbhistoricos WHERE DATE(data) = '$data'Order By data desc LIMIT $offset, $numerodepagina";
        $resgata_dados = mysqli_query($conn,$sql);
        while($linha = mysqli_fetch_array($resgata_dados)){

echo '<tbody>
      <tr>
         <th scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["nome"].'</a></th>
         <td width="20%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.date('d/m/Y H:i', strtotime($linha["data"])).'</td>
         <td width="40%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["descricao"].'</td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["log"].'</td>
      </tr>
     </tbody>';
 }
echo '</table>';
        mysqli_close($conn);
?>
<?php include_once"rodape.php";?>
</body>
</html>