<?php include_once "session.php"; ?>
<?php include_once "config.php"; ?>
<?php include_once "cabecalho.php"; ?>
<?php include_once "calendar.php"; ?>
<html>
<head>
    <title>Cadastro de Clientes</title>
</head>
<body>
    <script>
        function show_delall() {
            if(confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? Ao deletar você vai apagar também todos os históricos desse Cliente e NÃO TEM como recuperar os dados depois."))
                document.forms[0].submit();
            else 
                return false;
        }
    </script>
    <br>
    <div>
        <?php 
        if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        } 
        ?>
    </div>
    <div style="text-align:center">
        <form method="get" action="index_busca.php" autocomplete="off">
            <div style="display: inline-block; margin-right: 10px;"><h4>Filtrar</h4></div>
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
        $mes = date("m"); // pega mes atual
        $hojea = date('d/m'); // pega dia de hoje

        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

        $numerodepagina = 15;
        $offset = ($pagina - 1) * $numerodepagina;

        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
        mysqli_set_charset($conn, "utf8");

        // Check connection
        if (mysqli_connect_errno()){
            echo "Conexao falhou: " . mysqli_connect_error();
            die();
        }

        // Construção da query
        $sql = "SELECT * FROM tbclientes WHERE 1=1";
        if ($categoria != '') {
            $sql .= " AND categoria = '$categoria'";
        }
        $sql .= " ORDER BY idc DESC LIMIT $offset, $numerodepagina";

        $total_paginas_sql = "SELECT COUNT(*) FROM tbclientes WHERE 1=1";
        if ($categoria != '') {
            $total_paginas_sql .= " AND categoria = '$categoria'";
        }

        $result = mysqli_query($conn, $total_paginas_sql);
        $total_linhas = mysqli_fetch_array($result)[0];
        $total_paginas = ceil($total_linhas / $numerodepagina);

        $resgata_dados = mysqli_query($conn, $sql);

        while($rows_nomes = mysqli_fetch_array($resgata_dados)){
            
            echo '<tbody>
                  <tr>
                     <td scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["nome"].'</a></td>
                     <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.date('d/m/Y', strtotime($rows_nomes["datareg"])).'</td>
                     <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.substr($rows_nomes["documento"], 0, 12).'***</a></td>
                        <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["celular"].'</td>
                        <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc='.$rows_nomes["idc"].'" title="Editar">'.$rows_nomes["categoria"].'</td>
                  </tr>
                 </tbody>';
        }
        echo '</table>';
        echo 'Total: '.$total_linhas.'';

        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
      // Cria a URL para o link de geração do Excel com os filtros
        $excel_url = "form_gera_excel_filtro.php?categoria=" . urlencode($categoria);

        echo '<a href="'.$excel_url.'" title="Gerar Excel dos Cadastros"> <i class="bi bi-file-earmark-spreadsheet"></i></a>';
        mysqli_close($conn);
        ?>
<?php include_once "paginar.php"; ?>
<?php include_once "rodape.php"; ?>
</body>
</html>
