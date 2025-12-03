<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<script>
$(document).ready(function() {
  $('#cpf').on('blur', function() {
    var cpf = $(this).val().replace(/\D/g, ''); // Remove pontos e traço do CPF
    if (cpf.length != 11 || !/^\d+$/.test(cpf)) {
      alert('O CPF digitado é inválido. Por favor, digite um CPF válido.');
      return false;
    }
    $.post('busca_cpf.php', {cpf: cpf}, function(data) {
      console.log(data); // Exibe a resposta do servidor no console
      if (data.existe) {
        var idc = data.idc; // Recupera o valor do idc do cliente encontrado
        alert('O CPF já existe no sistema. Você será redirecionado para a página de edição de cliente.');
        window.location.href = 'form_cliente_editar.php?idc=' + idc; // Redireciona para a página form_cliente_editar.php com o valor do idc como parâmetro
      } else {
        //alert('CPF não cadastrado ainda...');
      }
    }, 'json');
  });
});
  </script>
<h3>Adicionar</h3>(pesquise antes de cadastrar um novo)
<form  method="post" name="adiciona" action="insert_cliente.php" autocomplete='off'>
  <hr />
<div class="form-group col-md-2">
 <label for="tipoDocumento">Documento:</label>
    <select name="tipodoc" id="tipoDocumento" class="form-control">
        <option value="CNPJ">CNPJ</option>
        <option value="CPF">CPF</option>
    </select>
    </div>
    <div class="form-group col-md-2" id="campoCPF" style="display:none;">
        <label for="cpf">CPF:</label>
        <input type="text" name="documento"  id="cpf" value="" class="form-control">
    </div>

    <div class="form-group col-md-2" id="campoCNPJ" style="display:none;">
        <label for="cnpj">CNPJ:</label>
        <input  type="text" name="documento" id="cnpj" value="" class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label for="inputlg" for="name">Cliente</label>
      <input type="text"  class="form-control"  name="nome" maxlength="150" value="" required="required"  oninput="maiuscula(event)"/>
    </div>
    <div class="form-group col-md-2">
  <label for="inputlg" for="name">Categoria</label>
  <select class="form-control" name="categoria">
       <?php
     $sql_cat = "SELECT * FROM tbcategorias Order By categoria ASC";
        $resgata_cat = mysqli_query($conn, $sql_cat);
        while($rows_cat = mysqli_fetch_array($resgata_cat)){
      echo '<option  value="'.$rows_cat['categoria'].'">'.$rows_cat['categoria'].'</option>';
        }?>
     </select>
    </div>   
    
      <div class="form-group col-md-2">
      <label for="campo3">CEP</label>
      <input type="text" maxlength="9" class="form-control" id="cep" name="cep" value="" onblur="pesquisacep(this.value);" autocomplete="user-password" onkeyup="somenteNumeros(this);" placeholder="00000-000">
    </div>
    <div class="form-group col-md-6">
      <label for="campo1">Endereço</label>
      <input type="text" maxlength="60" class="form-control" id="rua" name="rua" value="" autocomplete="user-password">
    </div>
    <div class="form-group col-md-4">
      <label for="campo2">Bairro</label>
      <input type="text" maxlength="50" class="form-control" id="bairro" name="bairro" value="" autocomplete="user-password">
    </div>
      <div class="form-group col-md-4">
      <label for="campo1">Município</label>
      <input type="text" maxlength="50" class="form-control" id="cidade" name="cidade" value="" autocomplete="user-password">
    </div>
    <div class="form-group col-md-1">
      <label for="campo3">UF</label>
      <input type="text" maxlength="5" class="form-control" id="uf" name="uf" value="" autocomplete="user-password">
    </div>
    <div class="form-group col-md-2">
      <label for="campo2">Telefone</label>
      <input type="text" maxlength="15" class="form-control" placeholder="" id="telefone" name="telefone" value="" autocomplete="user-password">
    </div>
        <div class="form-group col-md-2">
      <label for="campo3">Celular</label>
      <input type="text" maxlength="15" class="form-control" id="celular" name="celular" value="" autocomplete="user-password">
    </div>
    <div class="form-group col-md-4">
      <label for="campo3">E-mail</label>
      <input type="text" maxlength="60" class="form-control" name="email" value="" autocomplete="user-password" oninput="minuscula(event)"/>
    </div>
      <div class="form-group col-md-4">
      <label for="campo3">Observações</label>
      <input type="text" maxlength="100" class="form-control" name="obs" value="" autocomplete="user-password">
    </div>
 <div class="col-md-12">
      <button type="submit" class="btn btn-success btn-lg" title="Salvar"><i class="bi bi-save"></i></button>
    </div>
  </div>
</form>
<?php include_once"rodape.php";?>
