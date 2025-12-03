<nav class="navbar navbar-expand-lg navbar-light" style="background-color: <?php echo $barra;?>;">
    <div class="container-fluid">
        <!-- Logo/Brand -->
        <a class="navbar-brand" href="index.php">
            <img src="logo/<?php echo $barra;?>" alt="Em Geral" width="50" height="50">
        </a>
        
        <!-- Toggle button para mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbar">
            <!-- Menu principal -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php" title="Clientes">
                        <i class="bi bi-folder" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Clientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form_cliente_cpf.php" title="Novo">
                        <i class="bi bi-file-earmark-person" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Novo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index_ordens.php" title="Pedidos">
                        <i class="bi bi-card-checklist" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Pedidos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index_produtos.php" title="Produtos">
                        <i class="bi bi-card-list" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Produtos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index_historico.php" title="Históricos">
                        <i class="bi bi-chat-left" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Históricos</span>
                    </a>
                </li>
            </ul>
            
            <!-- Barra de pesquisa -->
            <form class="d-flex mb-2 mb-lg-0 me-3" method="get" action="busca.php" autocomplete="off">
                <div class="input-group">
                    <input type="text" class="form-control" name="busca" id="busca" placeholder="nome ou documento" required onchange="this.form.submit()" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- Menu do usuário -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-person-badge" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> <?php echo $log;?></span>
                    </a>
                </li>
                
                <!-- Dropdown de configurações -->
                <li class="nav-item dropdown" style="display:<?php echo $admin;?>">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Configurações</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="admin.php"><i class="bi bi-people-fill"></i> USUÁRIOS</a></li>
                        <li><a class="dropdown-item" href="form_empresa_editar.php" title="Empresa" style="display:<?php echo $admin;?>"><i class="bi bi-bar-chart-steps"></i> Empresa</a></li>
                        <li><a class="dropdown-item" href="index_categoria.php" title="Categorias"><i class="bi bi-card-list"></i> Categorias</a></li>
                        <li><a class="dropdown-item" href="bac.php"><i class="bi bi-layer-backward"></i> Backup</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="sair.php">
                        <i class="bi bi-x-lg" style="color:<?php echo $letra;?>;"></i>
                        <span style="color:<?php echo $letra;?>;"> Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>