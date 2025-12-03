<?php
//
$idc= $_GET['idc'];   
 //
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn,"utf8");
 $result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc' limit 1";
 $resultado_nomes = mysqli_query($conn, $result_nomes);
     while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
  //
  $idc = $rows_nomes['idc'];
  $nome = $rows_nomes['nome'];
  $cad_status = $rows_nomes['cad_status'];
  $categoria = $rows_nomes['categoria'];
  $datareg = date("d/m/Y", strtotime($rows_nomes['datareg'])); 
  $tipodoc = $rows_nomes['tipodoc'];
  $documento = $rows_nomes['documento'];
  $cep = $rows_nomes['cep'];
  $rua =  $rows_nomes['rua'];
  $cidade = $rows_nomes['cidade'];
  $bairro = $rows_nomes['bairro'];
  $uf = $rows_nomes['uf'];
  $telefone = $rows_nomes['telefone'];
  $celular = $rows_nomes['celular']; 
  $email = $rows_nomes['email'];
  $obs = $rows_nomes['obs'];
  $datalog = $rows_nomes['datalog'];
 }
 ?>
<br>
<div class="container">
  <form method="post" name="editar" action="update_cliente.php" autocomplete="off">
    <input type="hidden" name="idc" value="<?php echo $idc; ?>">

    <div class="row">
      <div class="form-group col-md-6">
        <label for="name">CLIENTE</label>
        <input type="text" class="form-control" name="nome" maxlength="150" value="<?php echo $nome; ?>" required="required" autocomplete="user-password" oninput="maiuscula(event)" />
      </div>

      <div class="form-group col-md-2">
        <label for="tipoDocumento">Documento:</label>
        <select name="tipodoc" id="tipoDocumento" class="form-control">
          <option value="<?php echo $tipodoc; ?>" selected>> <?php echo $tipodoc; ?></option>
          <option value="CNPJ">CNPJ</option>
          <option value="CPF">CPF</option>
        </select>
      </div>

      <div class="form-group col-md-2" id="campoCPF" style="display:none;">
        <label for="cpf">CPF:</label>
        <input type="text" name="documento" id="cpf" value="<?php echo $documento; ?>" class="form-control">
      </div>

      <div class="form-group col-md-2" id="campoCNPJ" style="display:none;">
        <label for="cnpj">CNPJ:</label>
        <input type="text" name="documento" id="cnpj" value="<?php echo $documento; ?>" class="form-control">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-2">
        <label for="name">Categoria</label>
        <select class="form-control" name="categoria">
          <option value="<?php echo $categoria; ?>"> > <?php echo $categoria; ?></option>
          <?php
          $sql_cat = "SELECT * FROM tbcategorias Order By categoria ASC";
          $resgata_cat = mysqli_query($conn, $sql_cat);
          while ($rows_cat = mysqli_fetch_array($resgata_cat)) {
            echo '<option  value="'.$rows_cat['categoria'].'">'.$rows_cat['categoria'].'</option>';
          }?>
        </select>
      </div>

      <div class="form-group col-md-2">
        <label for="datareg">Data Cadastro</label>
        <input type="text" maxlength="15" class="form-control" placeholder="00/00/0000" id="data" name="datareg" value="<?php echo $datareg; ?>" onkeyup="somenteNumeros(this);">
      </div>

      <div class="form-group col-md-2">
        <label for="cep">CEP</label>
        <input type="text" maxlength="9" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>" onblur="pesquisacep(this.value);" autocomplete="user-password" onkeyup="somenteNumeros(this);" placeholder="00000-000">
      </div>

      <div class="form-group col-md-5">
        <label for="rua">Endereço</label>
        <input type="text" maxlength="150" class="form-control" id="rua" name="rua" value="<?php echo $rua; ?>" autocomplete="user-password">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-3">
        <label for="bairro">Bairro</label>
        <input type="text" maxlength="50" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>" autocomplete="user-password">
      </div>

      <div class="form-group col-md-3">
        <label for="cidade">Cidade</label>
        <input type="text" maxlength="50" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>" autocomplete="user-password">
      </div>

      <div class="form-group col-md-1">
        <label for="uf">UF</label>
        <input type="text" maxlength="5" class="form-control" id="uf" name="uf" value="<?php echo $uf; ?>" autocomplete="user-password">
      </div>

      <div class="form-group col-md-2">
        <label for="telefone">Telefone</label>
        <input type="text" maxlength="15" class="form-control" id="telefone" name="telefone" value="<?php echo $telefone; ?>" autocomplete="user-password">
      </div>

      <div class="form-group col-md-2">
        <label for="celular">Celular</label>
        <input type="text" maxlength="15" class="form-control" id="celular" name="celular" value="<?php echo $celular; ?>" autocomplete="user-password">
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-4">
        <label for="email">E-mail</label>
        <input type="text" maxlength="60" class="form-control" name="email" value="<?php echo $email; ?>" autocomplete="user-password" oninput="minuscula(event)" />
      </div>

      <div class="form-group col-md-6">
        <label for="obs">Observações</label>
        <input type="text" class="form-control" name="obs" value="<?php echo $obs; ?>" oninput="maiuscula(event)" />
      </div>
    </div>

    <div class="form-group col-md-12">
      <input type="submit" hidden="true">
      <div align="right">
        <button type="submit" class="btn btn-success btn-lg" title="Salvar Cadastro"><i class="bi bi-save"></i></button>
        |
        <a href="imprimir_atendimentos.php?idc=<?php echo $idc;?>" target="_blank" class="btn btn-info" title="Imprimir Históricos"><i class="bi bi-printer"></i></a>
        |
        <a href="delete_cliente.php?idc=<?php echo $idc;?>" action="delete_cliente.php" onclick="return deleta_cliente();" class="btn btn-danger" title="Deletar"><i class="bi bi-trash"></i></a>
      </div>
    </div>
  </form>
</div>