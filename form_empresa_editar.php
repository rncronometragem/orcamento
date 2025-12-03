<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<?php
//
if(!empty($_SESSION['usuario'] == 'admin')){
}else{
$_SESSION['msg'] = "Área restrita";
echo "<script>location.href='index.php';</script>";
}
?>
<?php
//
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
 mysqli_set_charset($conn,"utf8");
 $result_nomes = "SELECT * FROM tbempresa";
 $resultado_nomes = mysqli_query($conn, $result_nomes);
     while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
//
  $ide = $rows_nomes['ide'];
  $nome = $rows_nomes['nome'];
  $cnpj = $rows_nomes['cnpj'];
  $inscestadual = $rows_nomes['inscestadual'];
  $cep = $rows_nomes['cep'];
  $rua =  $rows_nomes['rua'];
  $num = $rows_nomes['num'];
  $numcomp = $rows_nomes['numcomp'];
  $cidade = $rows_nomes['cidade'];
  $bairro = $rows_nomes['bairro'];
  $uf = $rows_nomes['uf'];
  $telefone = $rows_nomes['telefone'];
  $telcom = $rows_nomes['telcom'];
  $celular = $rows_nomes['celular']; 
  $email = $rows_nomes['email'];
  $site = $rows_nomes['site'];
  $imagem = $rows_nomes['imagem'];
  $barra = $rows_nomes['barra'];
  $letra = $rows_nomes['letra'];
 }
 ?>
<br>
<!-- modal perfil-->
<div class="modal" id="AbrirModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar Logo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <center><img src="./logo/<?php echo $imagem;?>" width="250" height="250"></center>
       <form  method="post" enctype="multipart/form-data" name="adiciona" action="update_logo.php">
    <input type="hidden"  class="form-control" hidden="true" name="ide" maxlength="50" value="<?php echo $ide; ?>">
    <div> 
      <p></p>
      <label for="inputlg" for="name">Imagem</label>
      <input type="file"  name="arquivo" value="" required accept="image/*">

        <input type="submit" value="Enviar" onsubmit="Checkfiles(this)" class="btn btn-info btn-sm"></div>
</form> 
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
       </div>
    </div>
  </div>
</div>
<!-- modal perfil-->
<h4>Sua Empresa</h4>
<hr>
<form  method="post" name="editar" action="update_empresa.php" autocomplete="off">
<input type="hidden" name="ide" value="<?php echo $ide; ?>">
<div class="form-group col-md-3" style="width:160px">
      <a href="#" data-toggle="modal" data-target="#AbrirModal"><img src="logo/<?php echo $imagem; ?>" class="media-object" height="150" width="150"></a>
</div>
<div class="form-group col-md-5">
      <label for="inputlg" for="name">Empresa</label>
      <input type="text"  class="form-control"  name="nome" maxlength="150" value="<?php echo $nome; ?>" autocomplete="user-password">
    </div> </div> 
    <div class="form-group col-md-3">
      <label for="campo2">CNPJ</label>
      <input type="text" maxlength="15" class="form-control" id="cnpj" name="cnpj" value="<?php echo $cnpj; ?>" autocomplete="off" onkeyup="somenteNumeros(this);">
    </div>
    <div class="form-group col-md-2">
      <label for="campo2">Inscrição Estadual</label>
      <input type="text" maxlength="15" class="form-control" id="inscestadual" name="inscestadual" value="<?php echo $inscestadual; ?>" autocomplete="off" onkeyup="somenteNumeros(this);">
    </div>

      <div class="form-group col-md-2">
      <label for="campo3">CEP</label>
      <input type="text" maxlength="9" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>" onblur="pesquisacep(this.value);" autocomplete="user-password" onkeyup="somenteNumeros(this);" placeholder="00000-000">
    </div>
    <div class="form-group col-md-5">
      <label for="campo1">Endereço</label>
      <input type="text" maxlength="60" class="form-control" id="rua" name="rua" value="<?php echo $rua; ?>" autocomplete="user-password">
    </div>
     <div class="form-group col-md-1">
      <label for="campo1">Num</label>
      <input type="text" maxlength="10" class="form-control" id="num" name="num" value="<?php echo $num; ?>" autocomplete="user-password">
    </div>
     <div class="form-group col-md-2">
      <label for="campo1">Comp</label>
      <input type="text" maxlength="10" class="form-control" id="numcomp" name="numcomp" value="<?php echo $numcomp; ?>" autocomplete="user-password">
    </div>
      <div class="form-group col-md-3">
      <label for="campo2">Bairro</label>
      <input type="text" maxlength="20" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>" autocomplete="user-password">
    </div>
      <div class="form-group col-md-3">
      <label for="campo1">Município</label>
      <input type="text" maxlength="20" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>" autocomplete="user-password">
    </div>
    <div class="form-group col-md-1">
      <label for="campo3">UF</label>
      <input type="text" maxlength="5" class="form-control" id="uf" name="uf" value="<?php echo $uf; ?>" autocomplete="user-password">
    </div>
    <div class="form-group col-md-2">
      <label for="campo2">Telefone</label>
      <input type="text" maxlength="15" class="form-control" placeholder=""  name="telefone" value="<?php echo $telefone; ?>" autocomplete="user-password">
    </div>
        <div class="form-group col-md-3">
      <label for="campo3">Celular</label>
      <input type="text" maxlength="20" class="form-control"  name="celular" value="<?php echo $celular; ?>" autocomplete="user-password">
    </div>
      <div class="form-group col-md-2">
      <label for="campo3">Tel Com</label>
      <input type="text" maxlength="15" class="form-control"  name="telcom" value="<?php echo $telcom; ?>" autocomplete="user-password">
    </div>  
    <div class="form-group col-md-3">
      <label for="campo3">Site</label>
      <input type="text" maxlength="60" class="form-control" name="site" value="<?php echo $site; ?>" autocomplete="user-password">
    </div>
    <div class="form-group col-md-3">
      <label for="campo3">E-mail</label>
      <input type="text" maxlength="60" class="form-control" name="email" value="<?php echo $email; ?>" autocomplete="user-password">
    </div>
    <br>
    <div class="col-md-12">
      <br>
     Barra <input type="color" id="cores" name="barra" list="arcoIris" value="<?php echo $barra; ?>">
<datalist id="arcoIris">
<option value="#0275d8">Vermelho</option>
<option value="#5cb85c">Laranja</option>
<option value="#5bc0de">Amarelo</option>
<option value="#f0ad4e">Verde</option>
<option value="#d9534f">Azul</option>
<option value="#292b2c">Indigo</option>
<option value="#f7f7f7">Violeta</option>
</datalist>
Letra <input type="color" id="cores" name="letra" list="arcoIris" value="<?php echo $letra; ?>">
<datalist id="arcoIris">

<option value="#000000">Branco</option>
<option value="#FFA500">Laranja</option>
<option value="#FFFF00">Amarelo</option>
<option value="#008000">Verde</option>
<option value="#0000FF">Azul</option>
<option value="#4B0082">Indigo</option>
<option value="#EE82EE">Violeta</option>
</datalist>
</div>

            <div class="col-md-12">
              <br>
    <a href="JavaScript: window.history.back();" class="btn btn-default" title="Voltar"><i class="bi bi-arrow-left-circle"></i></a>
      <button type="submit" class="btn btn-success btn-lg" title="Salvar"><i class="bi bi-save"></i></button></form>
    </div>
  </div>
<br/>
<?php include_once"rodape.php";?>