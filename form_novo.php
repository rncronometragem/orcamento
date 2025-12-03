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
<h3>Faça seu Cadastro</h3>
<form  method="post" name="adiciona" action="insert_cliente.php" autocomplete='off'>
  <hr />
  <div class="form-group col-md-2">
      <label for="campo2">CPF</label>
      <input type="text" maxlength="15" class="form-control" name="cpf" placeholder="000.000.000-00" id="cpf" value="" autocomplete="off" onkeyup="somenteNumeros(this);">
    </div>
    <div class="form-group col-md-6">
      <label for="inputlg" for="name">Nome</label>
      <input type="text"  class="form-control"  name="nome" maxlength="150" value="" required="required"  oninput="maiuscula(event)"/>
    </div>
    <div class="form-group col-md-5">
  <label for="inputlg" for="name">Escolha seu Curso</label>
  <select class="form-control" name="categoria">
       <?php
     $sql_cat = "SELECT * FROM tbcategorias Order By categoria ASC";
        $resgata_cat = mysqli_query($conn, $sql_cat);
        while($rows_cat = mysqli_fetch_array($resgata_cat)){
      echo '<option  value="'.$rows_cat['categoria'].'">'.$rows_cat['categoria'].'</option>';
        }?>
     </select>
    </div>   
    <div class="form-group col-md-3">
      <label for="campo2">CNPJ</label>
      <input type="text" maxlength="15" class="form-control" id="cnpj" name="cnpj" value="" autocomplete="off" onkeyup="somenteNumeros(this);">
    </div>
    <div class="form-group col-md-2">
      <label for="campo2">Inscrição Estadual</label>
      <input type="text" maxlength="15" class="form-control" id="inscestadual" name="inscestadual" value="" autocomplete="off" onkeyup="somenteNumeros(this);">
    </div>
    <div class="form-group col-md-4">
      <label for="inputlg" for="name">Contato</label>
      <input type="text"  class="form-control"  name="responsavel" maxlength="150" value="" autocomplete="user-password" oninput="maiuscula(event)"/>
    </div>
    <div class="form-group col-md-2">
      <label for="campo3">Data de Nasc</label>
      <input type="text" maxlength="10" class="form-control" placeholder="00/00/0000" autocomplete='off' id="data" name="datanasc" value=""  required="required"  onkeyup="somenteNumeros(this);">
    </div>
        <div class="form-group col-md-2">
      <label for="campo2">RG</label>
      <input type="text" maxlength="15" class="form-control" name="rg" value="" autocomplete="off">
    </div>
    <div class="form-group col-md-2">
      <label for="campo2">Estado Civil</label>
      <input type="text" maxlength="50" class="form-control" id="civil" name="estcivil" value="" autocomplete="off" oninput="maiuscula(event)"/>
    </div>
     <div class="form-group col-md-4">
      <label for="campo2">Profissão</label>
      <input type="text" maxlength="100" class="form-control" name="profissao" value="" autocomplete="off" oninput="maiuscula(event)"/>
    </div>
      <div class="form-group col-md-2">
      <label for="campo3">CEP</label>
      <input type="text" maxlength="9" class="form-control" id="cep" name="cep" value="" onblur="pesquisacep(this.value);" autocomplete="user-password" onkeyup="somenteNumeros(this);" placeholder="00000-000">
    </div>
    <div class="form-group col-md-4">
      <label for="campo1">Endereço</label>
      <input type="text" maxlength="60" class="form-control" id="rua" name="rua" value="" autocomplete="user-password">
    </div>
     <div class="form-group col-md-1">
      <label for="campo1">Num</label>
      <input type="text" maxlength="10" class="form-control" id="num" name="num" value="" autocomplete="user-password">
    </div>
     <div class="form-group col-md-1">
      <label for="campo1">Comp</label>
      <input type="text" maxlength="10" class="form-control" id="numcomp" name="numcomp" value="" autocomplete="user-password">
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
      <div class="form-group col-md-2">
      <label for="campo3">Tel Com</label>
      <input type="text" maxlength="15" class="form-control" id="telcom" name="telcom" value="" autocomplete="user-password">
    </div>
    <div class="form-group col-md-4">
      <label for="campo3">E-mail</label>
      <input type="text" maxlength="60" class="form-control" name="email" value="" autocomplete="user-password" oninput="minuscula(event)"/>
    </div>
    <div hidden class="form-group col-md-3">
  <label for="inputlg" for="name">Status</label>
  <select class="form-control" name="cad_status">
       <?php
     $sql_status = "SELECT * FROM tbstatus Order By cad_status ASC";
        $resgata_status = mysqli_query($conn, $sql_status);
        while($rows_status = mysqli_fetch_array($resgata_status)){
      echo '<option  value="'.$rows_status['cad_status'].'">'.$rows_status['cad_status'].'</option>';
        }?>
     </select>
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
