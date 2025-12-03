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
<br>
<h4><a href="" title="Pedidos"><i class="bi bi-card-checklist"></i> Pedidos</a></h4>
<div style="text-align:center;display:inline-block;">
    <form method="post" name="dataForm" action="index_ordens_busca_pedido.php">
        Nº Pedido:
        <input type="text" name="ido" value="" autocomplete="off" required="true" style="width:50px;">
         
        
        <button type="submit" title="OK"><i class="bi bi-search"></i></button>
    </form>
    </div>
    <div style="text-align:center;display:inline-block;">
    <form method="get" name="dataForm" action="index_ordens_busca.php">
        De: 
        <input type="text" name="data" value="<?php echo date('d/m/Y');?>" id="dataordem" autocomplete="off" required="true" style="width:100px;">
        À: 
        <input type="text" name="data2" value="<?php echo date('d/m/Y');?>" id="dataordem2" autocomplete="off" required="true" style="width:100px;">
        Status: 
        <select name="status_ordem" id="status_ordem" required="true" style="width:150px;">
            <option value="">Selecione</option>
            <option value="pendente">Pendente</option>
            <option value="concluido">Concluído</option>
        </select>
        <button type="submit" title="OK"><i class="bi bi-search"></i></button>
    </form>
   </div>
<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="10%" style="text-align:left;">Nº Pedido</th>
       <th width="20%" style="text-align:left;">Últimos Pedidos</th>
       <th width="20%" style="text-align:center">Data</th>
       <th width="40%" style="text-align:center">Status</th>
       <th width="10%" style="text-align:center">Usuário</th>
     </tr>
   </thead>
    <?php

    $mes= date("m"); // pega mes atual
    $hojea = date('d/m'); // pega dia de hoje

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        } else {
            $pagina = 1;
        }
        $numerodepagina = 5;
        $offset = ($pagina-1) * $numerodepagina;

        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        mysqli_set_charset($conn,"utf8");
        // Check connection
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();
        }
        $total_paginas_sql = "SELECT COUNT(*) FROM tbordens";
        $result = mysqli_query($conn,$total_paginas_sql);
        $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);
        $sql = "SELECT * FROM tbordens Order By data desc LIMIT $offset, $numerodepagina";
        $resgata_dados = mysqli_query($conn,$sql);
        while($linha = mysqli_fetch_array($resgata_dados)){
        if ($linha['status_ordem'] === 'pendente') {
        $cor = 'class="btn btn-danger"';
        } else {
        $cor = 'class="btn btn-success"';    
        }   

echo '<tbody>
      <tr>
        <td scope="row" width="10%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["ido"].'</a></td>
         <td scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["nome"].'</a></td>
         <td width="20%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.date('d/m/Y H:i', strtotime($linha["data"])).'</td>
         <td width="40%" style="text-align:center">
                <a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar">
                    <span ' . $cor . '>' . $linha["status_ordem"] . '</span>
                </a>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$linha["idc"].'" title="Editar">'.$linha["log"].'</td>
      </tr>
     </tbody>';
 }
echo '</table>';
        mysqli_close($conn);
    ?>
<?php include_once"paginar.php";?>
<?php include_once"rodape.php";?>
</body>
</html>