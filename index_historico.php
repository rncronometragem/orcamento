<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
// include_once "calendar.php"; // Não é estritamente necessário com o layout novo

// Definição de paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$numerodepagina = 15; // Itens por página
$offset = ($pagina - 1) * $numerodepagina;
?>

    <div class="container my-4">

        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap">
            <div>
                <h2 class="text-secondary mb-0"><i class="fas fa-history"></i> Histórico de Atendimentos</h2>
                <p class="text-muted small mb-0">Registro geral de atividades e contatos realizados.</p>
            </div>

            <div class="mt-3 mt-md-0">
                <form method="post" action="index_historico_busca.php" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" name="data" class="form-control" value="<?php echo date('d/m/Y'); ?>" placeholder="dd/mm/aaaa" autocomplete="off" id="dataBusca">
                        <button type="submit" class="btn btn-primary" title="Buscar por Data">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col" class="ps-4">Cliente</th>
                            <th scope="col" class="text-center">Data / Hora</th>
                            <th scope="col">Descrição</th>
                            <th scope="col" class="text-center">Usuário</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Conexão com o Banco
                        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
                        mysqli_set_charset($conn, "utf8");

                        // Lógica de Paginação (Total de páginas)
                        $total_paginas_sql = "SELECT COUNT(*) FROM tbhistoricos";
                        $result_count = mysqli_query($conn, $total_paginas_sql);
                        $row_count = mysqli_fetch_array($result_count);
                        $total_linhas = $row_count[0];
                        $total_paginas = ceil($total_linhas / $numerodepagina);

                        // Busca Registros (Com limite)
                        $sql = "SELECT * FROM tbhistoricos ORDER BY data DESC LIMIT $offset, $numerodepagina";
                        $resgata_dados = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($resgata_dados) > 0) {
                            while ($linha = mysqli_fetch_array($resgata_dados)) {
                                $data_formatada = date('d/m/Y H:i', strtotime($linha["data"]));
                                // Limita descrição visualmente se for muito longa
                                $descricao_curta = strlen($linha["descricao"]) > 100 ? substr($linha["descricao"], 0, 100) . '...' : $linha["descricao"];
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        <a href="form_cliente_editar.php?idc=<?php echo $linha["idc"]; ?>" class="text-decoration-none text-primary">
                                            <?php echo $linha["nome"]; ?>
                                        </a>
                                    </td>
                                    <td class="text-center text-muted small">
                                        <?php echo $data_formatada; ?>
                                    </td>
                                    <td class="text-muted">
                                        <a href="form_cliente_editar.php?idc=<?php echo $linha["idc"]; ?>" class="text-decoration-none text-secondary">
                                            <?php echo $descricao_curta; ?>
                                        </a>
                                    </td>
                                    <td class="text-center small">
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-user"></i> <?php echo $linha["log"]; ?>
                                    </span>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center py-5 text-muted">Nenhum histórico encontrado.</td></tr>';
                        }
                        mysqli_close($conn);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <nav aria-label="Navegação">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item <?php if($pagina <= 1){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($pagina > 1){ echo "?pagina=".($pagina - 1); } else { echo "#"; } ?>">Anterior</a>
                        </li>

                        <?php
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            // Exibe apenas páginas próximas, primeira e última para não poluir
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
        $(document).ready(function(){
            // Verifica se o plugin de máscara existe (do cabeçalho) antes de aplicar
            if($.fn.mask) {
                $('#dataBusca').mask('00/00/0000');
            }
        });
    </script>

<?php include_once "rodape.php"; ?>