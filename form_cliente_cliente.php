<?php
// Conexão e Busca
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn, "utf8");
$result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc' LIMIT 1";
$resultado_nomes = mysqli_query($conn, $result_nomes);

if($rows = mysqli_fetch_array($resultado_nomes)){
    $nome = $rows['nome'];
    $categoria = $rows['categoria'];
    $datareg = date("d/m/Y", strtotime($rows['datareg']));
    $tipodoc = $rows['tipodoc'];
    $documento = $rows['documento'];
    $cep = $rows['cep'];
    $rua =  $rows['rua'];
    $cidade = $rows['cidade'];
    $bairro = $rows['bairro'];
    $uf = $rows['uf'];
    $telefone = $rows['telefone'];
    $celular = $rows['celular'];
    $email = $rows['email'];
    $obs = $rows['obs'];
}
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-pen"></i> Editar Informações
    </div>
    <div class="card-body">
        <form method="post" name="editar" action="update_cliente.php" autocomplete="off">
            <input type="hidden" name="idc" value="<?php echo $idc; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nome do Cliente</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $nome; ?>" required oninput="maiuscula(event)">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo Doc.</label>
                    <select name="tipodoc" id="tipoDocumento" class="form-select">
                        <option value="<?php echo $tipodoc; ?>" selected><?php echo $tipodoc; ?></option>
                        <option value="CNPJ">CNPJ</option>
                        <option value="CPF">CPF</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Documento</label>
                    <input type="text" name="documento" id="documento_geral" value="<?php echo $documento; ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Categoria</label>
                    <select class="form-select" name="categoria">
                        <option value="<?php echo $categoria; ?>" selected><?php echo $categoria; ?></option>
                        <?php
                        $sql_cat = "SELECT * FROM tbcategorias ORDER BY categoria ASC";
                        $res_cat = mysqli_query($conn, $sql_cat);
                        while ($r_cat = mysqli_fetch_array($res_cat)) {
                            echo '<option value="'.$r_cat['categoria'].'">'.$r_cat['categoria'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data Cadastro</label>
                    <input type="text" class="form-control data-mask" name="datareg" value="<?php echo $datareg; ?>">
                </div>

                <div class="col-md-12"><hr class="text-muted"></div>

                <div class="col-md-3">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>" onblur="pesquisacep(this.value);">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="rua" name="rua" value="<?php echo $rua; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>">
                </div>
                <div class="col-md-9">
                    <label class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">UF</label>
                    <input type="text" class="form-control" id="uf" name="uf" value="<?php echo $uf; ?>">
                </div>

                <div class="col-md-12"><hr class="text-muted"></div>

                <div class="col-md-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $telefone; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Celular</label>
                    <input type="text" class="form-control" id="celular" name="celular" value="<?php echo $celular; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" oninput="minuscula(event)">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Observações</label>
                    <textarea class="form-control" name="obs" oninput="maiuscula(event)"><?php echo $obs; ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar Alterações</button>
                <a href="imprimir_atendimentos.php?idc=<?php echo $idc;?>" target="_blank" class="btn btn-info text-white"><i class="fas fa-print"></i> Imprimir</a>
                <a href="delete_cliente.php?idc=<?php echo $idc;?>" onclick="return confirm('Tem certeza que deseja DELETAR este cliente?');" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</a>
            </div>
        </form>
    </div>
</div>