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
        if (confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? Ao deletar você vai apagar também os todos históricos desse Cliente e NÃO TEM como recuperar os dados depois.")) {
            document.forms[0].submit();
        } else {
            return false;
        }
    }
    </script>
    <br>
    <h4><a href="" title="Pedidos"><i class="bi bi-archive"></i> Pedidos</a> </h4>
    <div style="text-align:center;display:inline-block;">
    <form method="get" name="dataForm" action="index_ordens_busca_pedido.php">
        Nº Pedido:
        <input type="text" name="ido" value="" autocomplete="off" required="true" style="width:50px;">
         
        
        <button type="submit" title="OK"><i class="bi bi-search"></i></button>
    </form>
    </div>
    <div style="text-align:center;display:inline-block;">
    <form method="get" name="dataForm" action="index_ordens_busca.php">
        De: 
        <input type="text" name="data" value="<?php echo $_GET['data'];?>" id="dataordem" autocomplete="off" required="true" style="width:100px;">
        À: 
        <input type="text" name="data2" value="<?php echo $_GET['data2'];?>" id="dataordem2" autocomplete="off" required="true" style="width:100px;">
        Status: 
        <select name="status_ordem" id="status_ordem" required="true" style="width:150px;">
            <option value="<?php echo $_GET['status_ordem'];?>"> > <?php echo $_GET['status_ordem'];?></option>
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
        <tbody>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = $_GET['data']; // 00/00/0000
            $data = explode("/", $data);
            list($dia, $mes, $ano) = $data;
            $data_busca = "$ano-$mes-$dia"; // 0000-00-00

            $data2 = $_GET['data2']; // 00/00/0000
            $data2 = explode("/", $data2);
            list($dia2, $mes2, $ano2) = $data2;
            $data_busca2 = "$ano2-$mes2-$dia2"; // 0000-00-00

            $status_busca = $_GET['status_ordem'];

            $mes = date("m"); // pega mes atual
            $hojea = date('d/m'); // pega dia de hoje

            if (isset($_GET['pagina'])) {
                $pagina = $_GET['pagina'];
            } else {
                $pagina = 1;
            }
            $numerodepagina = 5;
            $offset = ($pagina - 1) * $numerodepagina;

            $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
            mysqli_set_charset($conn, "utf8");
            // Check connection
            if (mysqli_connect_errno()) {
                echo "Conexao falhou: " . mysqli_connect_error();
                die();
            }
            
            $total_paginas_sql = "SELECT COUNT(*) FROM tbordens WHERE DATE(data) BETWEEN '$data_busca' AND '$data_busca2' AND status_ordem='$status_busca'";
            $result = mysqli_query($conn, $total_paginas_sql);
            $total_linhas = mysqli_fetch_array($result)[0];
            $total_paginas = ceil($total_linhas / $numerodepagina);

            $sql = "SELECT * FROM tbordens WHERE DATE(data) BETWEEN '$data_busca' AND '$data_busca2' AND status_ordem='$status_busca' ORDER BY data DESC LIMIT $offset, $numerodepagina";
            $resgata_dados = mysqli_query($conn, $sql);

            while ($linha = mysqli_fetch_array($resgata_dados)) {
                $cor = $linha['status_ordem'] === 'pendente' ? 'class="btn btn-danger"' : 'class="btn btn-success"';
                echo '
                    <tr>
                        <td scope="row" width="10%" style="text-align:left"><a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar">' . $linha["ido"] . '</a></td>
                        <td scope="row" width="20%" style="text-align:left"><a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar">' . $linha["nome"] . '</a></td>
                        <td width="20%" style="text-align:center"><a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar">' . date('d/m/Y H:i', strtotime($linha["data"])) . '</a></td>
                        <td width="40%" style="text-align:center"><a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar"><span ' . $cor . '>' . $linha["status_ordem"] . '</span></a></td>
                        <td width="10%" style="text-align:center"><a href="form_cliente_editar.php?idc=' . $linha["idc"] . '" title="Editar">' . $linha["log"] . '</a></td>
                    </tr>';
            }

            mysqli_close($conn);
        }
        ?>
        </tbody>
    </table>
<!-- paginação nova -->
<div style="text-align:center;"><nav aria-label="...">
<ul class="pagination">
    <li><a href="?pagina=1&data=<?php echo implode('/', $data); ?>&data2=<?php echo implode('/', $data2); ?>&status_ordem=<?php echo $status_busca; ?>"><<</a></li>
<li class="">
    <a href="<?php if($pagina <= 1){ echo '#'; } else { echo "?pagina=".($pagina - 1)."&data=".implode('/', $data)."&data2=".implode('/', $data2)."&status_ordem=".$status_busca; } ?>"> < </a>
</li>
<li class="<?php if($pagina <= 1){ echo 'disabled'; } ?>">
    <a href="<?php if($pagina <= 1){ echo '#'; } else { echo "?pagina=".($pagina - 1)."&data=".implode('/', $data)."&data2=".implode('/', $data2)."&status_ordem=".$status_busca; } ?>"><?php echo $pagina - 1; ?></a>
</li>
<li class="active">
    <a href="#" class="p-3 mb-2 bg-primary text-white"><?php echo $pagina; ?></a>
</li>
<li class="<?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
    <a href="<?php if($pagina >= $total_paginas){ echo '#'; } else { echo "?pagina=".($pagina + 1)."&data=".implode('/', $data)."&data2=".implode('/', $data2)."&status_ordem=".$status_busca; } ?>"><?php echo $pagina + 1; ?></a>
</li>
<li class="<?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
    <a href="<?php if($pagina >= $total_paginas){ echo '#'; } else { echo "?pagina=".($pagina + 1)."&data=".implode('/', $data)."&data2=".implode('/', $data2)."&status_ordem=".$status_busca; } ?>"> > </a>
</li>
<li><a href="?pagina=<?php echo $total_paginas; ?>&data=<?php echo implode('/', $data); ?>&data2=<?php echo implode('/', $data2); ?>&status_ordem=<?php echo $status_busca; ?>"> >></a></li>

</ul>
</nav>
<!-- paginação nova -->
</div>
    <?php include_once "rodape.php"; ?>
</body>
</html>