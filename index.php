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
    window.setInterval('refresh()', 60000);     
    function refresh() {
        window .location.reload();
    }
</script>
    <script>
function show_delall() {
  if(confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? Ao deletar você vai apagar também os todos históricos desse Cliente e NÃO TEM como recuperar os dados depois."))
    document.forms[0].submit();
  else 
    return false;}
</script>
<br>
<div>
    <?php if(isset($_SESSION['msg'])){
echo $_SESSION['msg'];
unset($_SESSION['msg']);} ?>
    
</div>
<div style="text-align:center">
    <form method="get" action="index_busca.php" autocomplete="off">
    <div style="display: inline-block; margin-right: 10px;"><h4>Filtrar</h4>
        </div>
    <div style="display: inline-block;">
            <select class="form-control" name="categoria" style="width:250px;margin-right: 10px;">
                <option value="">Categoria:.</option>
                <?php 
                $sql_cat = "SELECT * FROM tbcategorias ORDER BY categoria ASC";
                $resgata_cat = mysqli_query($conn, $sql_cat);
                if (!$resgata_cat) {
                    die("Falhou a conexao: " . mysqli_error($conn));
                }
                if (mysqli_num_rows($resgata_cat) > 0) {
                    while($rows_nomes = mysqli_fetch_array($resgata_cat)) {
                        echo '<option value="'.$rows_nomes['categoria'].'">'.$rows_nomes['categoria'].'</option>';
                    }
                } else {
                    echo "<option>Cadastre categoria</option>";
                }
                ?>
            </select>
        </div>
    <div style="display: inline-block;">
                            <button class="btn btn-default" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
    </form>
</div>
<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="20%" style="text-align:left;">Últimos Clientes</th>
       <th width="10%" style="text-align:center">Data Cadastro</th>
       <th width="10%" style="text-align:center">Documento</th>
       <th width="10%" style="text-align:center">Celular</th>
       <th width="10%" style="text-align:center">Categoria</th>
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
        $numerodepagina = 15;
        $offset = ($pagina-1) * $numerodepagina;

        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        mysqli_set_charset($conn,"utf8");
        // Check connection
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();
        }
        $total_paginas_sql = "SELECT COUNT(*) FROM tbclientes Order By idc Desc";
        $result = mysqli_query($conn,$total_paginas_sql);
        $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);
        $sql = "SELECT * FROM tbclientes Order By idc Desc LIMIT $offset, $numerodepagina";
        $resgata_dados = mysqli_query($conn,$sql);
        while($rows_nomes = mysqli_fetch_array($resgata_dados)){
// aniversários

// aniversários
echo '<tbody>
      <tr>
         <td scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["nome"].'</a></td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.date('d/m/Y', strtotime($rows_nomes["datareg"])).'</td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.substr($rows_nomes["documento"],0,11).'***</a></td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["celular"].'</td>
         <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["categoria"].'</td>
      </tr>
     </tbody>';
 }
echo '</table>';
echo 'Total: '.$total_linhas.'';
echo '<a href="form_gera_excel.php" style="display:'.$admin.'" title="Gerar Excel dos Cadastros"> <i class="bi bi-file-earmark-spreadsheet"></i></a>';
        mysqli_close($conn);
    ?>
<?php include_once"paginar.php";?>
<?php include_once"rodape.php";?>
</body>
</html>