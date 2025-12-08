<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
include_once "calendar.php"; // Mantendo seu include de calendário
include_once "menudomeio.php";

// Definição de paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$numerodepagina = 5; // Itens por página
$offset = ($pagina - 1) * $numerodepagina;
?>

    <div class="container my-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-secondary"><i class="fas fa-file-invoice-dollar"></i> Gerenciar Pedidos</h2>
                <p class="text-muted mb-0">Consulte e gerencie todos os orçamentos e ordens.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a type="button" href="form_pedido_novo.php" class="btn btn-success shadow-sm" >
                    <i class="fas fa-plus-circle"></i> Novo Pedido
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0 bg-light">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <form method="post" action="index_ordens_busca_pedido.php" class="d-flex gap-2 align-items-end">
                            <div class="w-100">
                                <label class="form-label fw-bold small text-uppercase">Buscar ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" name="ido" class="form-control" placeholder="Nº Pedido" required autocomplete="off">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <form method="get" action="index_ordens_busca.php">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-uppercase">De</label>
                                    <input type="text" name="data" class="form-control" value="<?php echo date('d/m/Y'); ?>" id="dataordem" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-uppercase">Até</label>
                                    <input type="text" name="data2" class="form-control" value="<?php echo date('d/m/Y'); ?>" id="dataordem2" autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Status</label>
                                    <select name="status_ordem" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="concluido">Concluído</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-dark w-100"><i class="fas fa-filter"></i> Filtrar</button>
                                </div>
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
                            <th scope="col" class="ps-4">Nº Pedido</th>
                            <th scope="col">Cliente / Últimos Pedidos</th>
                            <th scope="col" class="text-center">Data</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Usuário</th>
                            <th scope="col" class="text-end pe-4">Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // 1. Contar total de registros para paginação
                        // Usamos $conn que vem do config.php
                        $total_paginas_sql = "SELECT COUNT(*) FROM tbordens";
                        $result_count = mysqli_query($conn, $total_paginas_sql);
                        $row_count = mysqli_fetch_array($result_count);
                        $total_linhas = $row_count[0];
                        $total_paginas = ceil($total_linhas / $numerodepagina);

                        // 2. Buscar registros com limite
                        $sql = "SELECT * FROM tbordens ORDER BY data DESC LIMIT $offset, $numerodepagina";
                        $resgata_dados = mysqli_query($conn, $sql);

                        // Verifica se tem registros
                        if (mysqli_num_rows($resgata_dados) > 0) {
                            while ($linha = mysqli_fetch_array($resgata_dados)) {

                                // Lógica de cor do status
                                $status_badge = 'bg-secondary';
                                if (stripos($linha['status_ordem'], 'pendente') !== false) {
                                    $status_badge = 'bg-warning text-dark';
                                } elseif (stripos($linha['status_ordem'], 'concluido') !== false || stripos($linha['status_ordem'], 'aprovado') !== false) {
                                    $status_badge = 'bg-success';
                                } elseif (stripos($linha['status_ordem'], 'cancelado') !== false) {
                                    $status_badge = 'bg-danger';
                                }
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#<?php echo $linha["ido"]; ?></td>
                                    <td>
                                        <a href="form_cliente_editar.php?idc=<?php echo $linha["idc"]; ?>" class="text-decoration-none fw-bold text-dark">
                                            <?php echo $linha["nome"]; ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($linha["data"])); ?>
                                    </td>
                                    <td class="text-center">
                                    <span class="badge <?php echo $status_badge; ?> rounded-pill px-3">
                                        <?php echo ucfirst($linha["status_ordem"]); ?>
                                    </span>
                                    </td>
                                    <td class="text-center text-muted small">
                                        <i class="fas fa-user-circle"></i> <?php echo $linha["log"]; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="form_cliente_editar.php?idc=<?php echo $linha["idc"]; ?>" class="btn btn-sm btn-outline-primary" title="Editar / Ver Detalhes">
                                            <i class="fas fa-edit"></i> Detalhes
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center py-5 text-muted">Nenhum pedido encontrado.</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <nav aria-label="Navegação de página">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item <?php if($pagina <= 1){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($pagina <= 1){ echo '#'; } else { echo "?pagina=".($pagina - 1); } ?>">Anterior</a>
                        </li>

                        <?php
                        // Lógica simplificada de paginação para manter compatibilidade
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            // Mostra apenas algumas páginas próximas para não quebrar layout se houver muitas
                            if ($i == 1 || $i == $total_paginas || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                                $active = ($i == $pagina) ? 'active' : '';
                                echo '<li class="page-item '.$active.'"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
                            } elseif ($i == $pagina - 3 || $i == $pagina + 3) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        ?>

                        <li class="page-item <?php if($pagina >= $total_paginas){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($pagina >= $total_paginas){ echo '#'; } else { echo "?pagina=".($pagina + 1); } ?>">Próximo</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

<?php
include_once "rodape.php";
?>