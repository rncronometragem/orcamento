<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php
if(!empty($_SESSION['usuario'] === 'admin')){
}else{
$_SESSION['msg'] = "Área restrita";
echo "<script>location.href='index.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html lang="pt-br">
<br>
<br>
<table class="table table-hovers">
<script>
function show_cancelar() {
  if(confirm("Tem certeza que quer [[DELETAR]] esse Usuário ?"))
    document.forms[0].submit();
  else 
    return false;}
</script>
<script>
function show_cadastrar() {
  if(confirm("Cadastrar um novo Usuário ?"))
    document.forms[0].submit();
  else 
    return false;}
</script>
<?php
$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_SPECIAL_CHARS);
if($btnCadUsuario){
  include_once 'config.php';
  $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
  $dados_rc = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  $erro = false;
  $dados_st = array_map('strip_tags', $dados_rc);
  $dados = array_map('trim', $dados_st);
  if(in_array('',$dados)){
    $erro = true;
    $_SESSION['msg'] = "Necessário preencher todos os campos";
  }elseif((strlen($dados['senha'])) < 6){
    $erro = true;
    $_SESSION['msg'] = "A senha deve ter no minímo 6 caracteres";
  }elseif(stristr($dados['senha'], "'")) {
    $erro = true;
    $_SESSION['msg'] = "Caracter ( ' ) utilizado na senha é inválido";
  }else{
    $result_usuario = "SELECT id FROM tbusuarios WHERE usuario='". $dados['usuario'] ."'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
      $erro = true;
      $_SESSION['msg'] = "Este usuário já está sendo utilizado";
    }
    $result_usuario = "SELECT id FROM tbusuarios WHERE email='". $dados['email'] ."'";
    $resultado_usuario = mysqli_query($conn, $result_usuario);
    if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
      $erro = true;
      $_SESSION['msg'] = "Este e-mail já está cadastrado";
    }
  }
  //var_dump($dados);
  if(!$erro){
    //var_dump($dados);
    $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
    
    $result_usuario = strip_tags("INSERT INTO tbusuarios (nome, email, usuario, senha, controle, status) VALUES (
            '" .$dados['nome']. "',
            '" .$dados['email']. "',
            '" .$dados['usuario']. "',
            '" .$dados['senha']. "',
            '" .$dados['controle']. "',
            '" .$dados['status']. "'
            )");
    $resultado_usario = mysqli_query($conn, $result_usuario);
    if(mysqli_insert_id($conn)){
      $_SESSION['msg'] = "Usuário cadastrado com sucesso";
      
    }else{
      $_SESSION['msg'] = "Erro ao cadastrar o usuário";
    }
  }
}
?>
<div><?php
          if(isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
          }
        ?>
 </div>
<form method="POST" action="">
<table style="width:100%">
  <tr>
    <td>
      <p>
  <a class="btn btn-success" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    +
  </a>
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body">
  <table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="20%" style="text-align:left;">Nome</th>
       <th width="10%" style="text-align:center">Usuário</th>
       <th width="10%" style="text-align:center">E-mail</th>
       <th width="10%" style="text-align:center">Senha(mín 6 digitos)</th>
       <th width="10%" style="text-align:center">Status</th>
       <th width="10%" style="text-align:center"></th>
     </tr>
   </thead>
  <tbody>
    <tr>
      <th scope="row"><input type="text" name="nome" placeholder="Digite o nome" class="form-control" required></th>
      <td><input type="text" name="usuario" placeholder="Digite o usuário" class="form-control" required></td>
      <td><input type="text" name="email" placeholder="Digite o e-mail" class="form-control" required></td>
      <td><input type="text" name="senha" placeholder="Digite a senha" class="form-control" required></td>
      <td><select name="status" class="form-control">
  <option value="atendente">Atendente</option>
  <option value="administrador">Administrador</option>
        </select></td>
        <input type="text" name="controle" value="index.php" hidden="true">
        <th><input type="submit" name="btnCadUsuario" value="Salvar" class="btn btn-success btn-block"></th>
    </tr>
  </tbody>
</table>
</div>
</form>
</div>
<?php
$data = date("Y-m-d");
// data funcionado bem
   echo '<table class="table table-hover" style="width:auto;">
  <thead>
    <tr>
       <th width="20%" style="text-align:left;">Nome</th>
       <th width="10%" style="text-align:center">Usuário</th>
       <th width="10%" style="text-align:center">E-mail</th>
       <th width="10%" style="text-align:center">Status</th>
       <th width="10%" style="text-align:center">Deletar</th>
     </tr>
   </thead>';

    $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
    $result_nomes = "SELECT * FROM tbusuarios ORDER BY status Desc";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    $conta = mysqli_num_rows($resultado_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
$del = $rows_nomes['usuario'];
if($del == "admin"){
    $del="disabled";
}else{
    $del="enable";
}
echo '<tbody>
      <tr>
         <th scope="row" width="20%" style="text-align:left"><a href="form_usuario_editar.php?id='.$rows_nomes["id"].'" title="Editar">'.$rows_nomes['nome'].'</a></th>
         <td width="10%" style="text-align:center"><a href="form_usuario_editar.php?id='.$rows_nomes["id"].'" title="Editar">'.$rows_nomes['usuario'].'</td>
         <td width="10%" style="text-align:center"><a href="form_usuario_editar.php?id='.$rows_nomes["id"].'" title="Editar">'.$rows_nomes['email'].'</td>
         <td width="10%" style="text-align:center"><a href="form_usuario_editar.php?id='.$rows_nomes["id"].'" title="Editar">'.$rows_nomes['status'].'</td>
         <td width="10%" style="text-align:center"><form  method="post" id"form" name="dataForm" action="delete_usuario.php" onsubmit="return show_cancelar();">
  <input type="text" hidden name="nome" value="'.$rows_nomes['nome'].'">       
  <input type="text" hidden name="id" value="'.$rows_nomes['id'].'">
  <button type="submit" class="btn btn-danger" title="Deletar" '.$del.'><i class="bi bi-trash"></i>
</form></td>
      </tr>
     </tbody>';
 }
echo '</table>';
?>
<h3 style="text-align:left">Total: <?php echo $conta;?></h3>
<div style="text-align:right"></div>