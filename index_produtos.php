<?php
include_once "session.php";
include_once "config.php";
include_once "cabecalho.php";
// include_once "calendar.php"; // Desnecessário aqui

// Definição de paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$numerodepagina = 15; // Itens por página
$offset = ($pagina - 1) * $numerodepagina;
?>

    <div class="container my-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-secondary mb-0"><i class="fas fa-boxes"></i> Produtos e Serviços</h2>
                <p class="text-muted small mb-0">Gerencie seu catálogo de itens.</p>
            </div>
            <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionar">
                <i class="fas fa-plus-circle"></i> Novo Produto
            </button>
        </div>

        <div class="modal fade" id="modalAdicionar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-plus"></i> Adicionar Item</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="insert_produtos.php" autocomplete="off">
                            <div class="mb-3">
                                <label for="produto" class="form-label fw-bold">Descrição do Produto/Serviço</label>
                                <input type="text" class="form-control" name="produto" placeholder="Ex: Formatação, Peça X..." required>
                            </div>
                            <div class="mb-3">
                                <label for="valor" class="form-label fw-bold">Valor (R$)</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control valor" name="valor" placeholder="0,00" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Salvar</button>
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
                            <th scope="col" class="ps-4" style="width: 50%;">Descrição</th>
                            <th scope="col">Valor</th>
                            <th scope="col" class="text-center">Data Cadastro</th>
                            <th scope="col" class="text-center">Cadastrado por</th>
                            <th scope="col" class="text-end pe-4">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Conexão e Paginação
                        $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
                        mysqli_set_charset($conn, "utf8");

                        $total_paginas_sql = "SELECT COUNT(*) FROM tbprodutos";
                        $result_count = mysqli_query($conn, $total_paginas_sql);
                        $row_count = mysqli_fetch_array($result_count);
                        $total_linhas = $row_count[0];
                        $total_paginas = ceil($total_linhas / $numerodepagina);

                        $sql = "SELECT * FROM tbprodutos ORDER BY produto ASC LIMIT $offset, $numerodepagina";
                        $resgata_dados = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($resgata_dados) > 0) {
                            while ($linha = mysqli_fetch_array($resgata_dados)) {
                                $idp = $linha["idp"];
                                $valor_formatado = number_format($linha['valor'], 2, ',', '.');
                                $data_formatada = date('d/m/Y', strtotime($linha['data']));
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <a href="#" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $idp; ?>">
                                            <?php echo $linha["produto"]; ?>
                                        </a>
                                    </td>
                                    <td class="text-success fw-bold">R$ <?php echo $valor_formatado; ?></td>
                                    <td class="text-center text-muted small"><?php echo $data_formatada; ?></td>
                                    <td class="text-center text-muted small">
                                        <i class="fas fa-user-tag"></i> <?php echo $linha["log"]; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $idp; ?>" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form method="post" action="delete_produto.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja EXCLUIR este produto?');">
                                                <input type="hidden" name="idp" value="<?php echo $idp; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEditar<?php echo $idp; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Produto</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="update_produtos.php" autocomplete="off">
                                                    <input type="hidden" name="idp" value="<?php echo $idp; ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Descrição</label>
                                                        <input type="text" class="form-control" name="produto" value="<?php echo $linha["produto"]; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Valor</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">R$</span>
                                                            <input type="text" class="form-control valor" name="valor" value="<?php echo $valor_formatado; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center py-5 text-muted">Nenhum produto cadastrado.</td></tr>';
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

<?php include_once "rodape.php"; ?>