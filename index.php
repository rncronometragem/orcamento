<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
// include_once "calendar.php"; // Descomente se for estritamente necessário, mas o Bootstrap lida bem sem ele.

// Definição de paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$numerodepagina = 15; // Itens por página
$offset = ($pagina - 1) * $numerodepagina;
?>

    <div class="container my-4">

        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> <?php echo $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <div class="row align-items-end mb-4">
            <div class="col-md-6">
                <h2 class="text-secondary mb-0"><i class="fas fa-users"></i> Clientes</h2>
                <p class="text-muted small">Gerenciamento e consulta de cadastro.</p>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-2">
                        <form method="get" action="index_busca.php" autocomplete="off" class="row g-2 align-items-center">
                            <div class="col-auto">
                                <span class="fw-bold text-muted small"><i class="fas fa-filter"></i> Filtrar:</span>
                            </div>
                            <div class="col">
                                <select class="form-select form-select-sm" name="categoria">
                                    <option value="">Todas as Categorias</option>
                                    <?php
                                    $sql_cat = "SELECT * FROM tbcategorias ORDER BY categoria ASC";
                                    $resgata_cat = mysqli_query($conn, $sql_cat);
                                    if ($resgata_cat && mysqli_num_rows($resgata_cat) > 0) {
                                        while($row_cat = mysqli_fetch_array($resgata_cat)) {
                                            echo '<option value="'.$row_cat['categoria'].'">'.$row_cat['categoria'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col" class="ps-3">Cliente</th>
                            <th scope="col" class="text-center">Data Cadastro</th>
                            <th scope="col" class="text-center">Documento</th>
                            <th scope="col" class="text-center">Contato</th>
                            <th scope="col" class="text-center">Categoria</th>
                            <th scope="col" class="text-end pe-4">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 1. Contar total para paginação
                        $total_paginas_sql = "SELECT COUNT(*) FROM tbclientes";
                        $result_count = mysqli_query($conn, $total_paginas_sql);
                        $row_count = mysqli_fetch_array($result_count);
                        $total_linhas = $row_count[0];
                        $total_paginas = ceil($total_linhas / $numerodepagina);

                        // 2. Buscar registros
                        $sql = "SELECT * FROM tbclientes ORDER BY idc DESC LIMIT $offset, $numerodepagina";
                        $resgata_dados = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($resgata_dados) > 0) {
                            while($row = mysqli_fetch_array($resgata_dados)){
                                // Formatação de dados
                                $data_reg = date('d/m/Y', strtotime($row["datareg"]));
                                // Máscara simples para documento para privacidade visual
                                $doc_display = strlen($row["documento"]) > 5 ? substr($row["documento"], 0, 11) . '...' : $row["documento"];
                                ?>
                                <tr onclick="window.location='form_cliente_editar.php?idc=<?php echo $row['idc']; ?>';" style="cursor:pointer;">
                                    <td class="ps-3 fw-bold text-primary">
                                        <?php echo $row["nome"]; ?>
                                    </td>
                                    <td class="text-center text-muted">
                                        <?php echo $data_reg; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border"><?php echo $doc_display; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $row["celular"]; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark"><?php echo $row["categoria"]; ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="form_cliente_editar.php?idc=<?php echo $row["idc"]; ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center py-5 text-muted">Nenhum cliente encontrado.</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
                <div class="text-muted small mb-2 mb-md-0">
                    Total de Registros: <strong><?php echo $total_linhas; ?></strong>

                    <?php if(isset($admin) && $admin == 'show'): ?>
                        <span class="ms-3 border-start ps-3">
                        <a href="form_gera_excel.php" class="btn btn-success btn-sm text-white" title="Exportar para Excel">
                            <i class="fas fa-file-excel"></i> Exportar
                        </a>
                    </span>
                    <?php endif; ?>
                </div>

                <nav aria-label="Navegação">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php if($pagina <= 1){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($pagina > 1){ echo "?pagina=".($pagina - 1); } else { echo "#"; } ?>">Anterior</a>
                        </li>

                        <?php
                        // Lógica visual de paginação (Mostra atual, +2 vizinhos e última)
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            if ($i == 1 || $i == $total_paginas || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                                $active = ($i == $pagina) ? 'active' : '';
                                echo '<li class="page-item '.$active.'"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
                            } elseif ($i == $pagina - 3 || $i == $pagina + 3) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        ?>

                        <li class="page-item <?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($pagina < $total_paginas){ echo "?pagina=".($pagina + 1); } else { echo "#"; } ?>">Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Atualiza a página a cada 60 segundos
        setTimeout(function(){
            window.location.reload();
        }, 60000);
    </script>

<?php include_once "rodape.php"; ?>