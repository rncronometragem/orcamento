<?php include_once"session.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"config.php";?>
<?php
//
if(!empty($_SESSION['usuario'] === 'admin')){
}else{
$_SESSION['msg'] = "Área restrita";
echo "<script>location.href='index.php';</script>";
}
?>
<header><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery.form.js"></script>
<?php
//
$id = $_GET['id'];
//  
 $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
  $result_nomes = "SELECT * FROM tbusuarios WHERE id LIKE '$id'";
    $resultado_nomes = mysqli_query($conn, $result_nomes);
    while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
  //
  $id = $rows_nomes['id'];
  $nome = $rows_nomes['nome'];
  $email = $rows_nomes['email'];
  $usuario = $rows_nomes['usuario'];
  $senha = $rows_nomes['senha'];
  $status = $rows_nomes['status'];
}
?>
<br>
<!-- modal perfil-->
<div class="modal" id="AbrirModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar Senha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form  method="post" name="editaruser" action="update_senha.php" autocomplete="off">
<input type="hidden" name="id" value="<?php echo $id; ?>">
     <div class="form-group col-md-4">
     <label for="inputlg" for="name">Usuário</label>
      <input type="text"  class="form-control"  name="usuario" maxlength="150" value="<?php echo $usuario; ?>" required="required" autocomplete="user-password" readonly>
    </div> 
     <div class="form-group col-md-4">
     <label for="inputlg" for="name">Nova Senha</label>
      <input type="text"  class="form-control"  name="senha" maxlength="150" value="" autocomplete="off" required="true">
    </div>
     <div class="col-md-1">
      <label for="inputlg" for="name"></label>
      <button type="submit" class="btn btn-success btn-lg" title="Salvar"><i class="bi bi-save"></i></button></form>
    </div>

      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
       </div>
  </div>
</div>
</div>
<!-- modal perfil-->
<br>
<h4></h4>
<table style="width:100%">
  <tr>
    <th>
<form  method="post" enctype="multipart/form-data" name="adiciona" action="update_usuario.php" autocomplete='off'>
  <input type="hidden"  class="form-control" hidden="true" name="id" maxlength="50" value="<?php echo $id; ?>">
  <input type="hidden"  class="form-control" hidden="true" name="status" maxlength="50" value="<?php echo $status; ?>">
            <div class="form-group col-md-3">
      <label for="inputlg" for="name">Nome</label>
      <input type="text"  class="form-control"  name="nome" maxlength="50" value="<?php echo $nome; ?>" required="true">
        </div>
        
            <div class="form-group col-md-3">
      <label for="inputlg" for="name">E-mail</label>
      <input type="text"  class="form-control"  name="email" maxlength="50" value="<?php echo $email; ?>" required="true">
        </div>
                <div class="form-group col-md-2">
      <label for="inputlg" for="name">Usuário <a href="#" data-toggle="modal" data-target="#AbrirModal">Alterar Senha</a></label>
      <input type="text"  class="form-control"  name="usuario" maxlength="50" value="<?php echo $usuario; ?>" required="true" readonly>
        </div>

                <div class="form-group col-md-2">
      <label for="inputlg" for="name">Status</label>
      <input type="text"  class="form-control"  name="nivelread" maxlength="50" value="<?php echo $status; ?>" required="true" readonly>
        </div>
              </th>
</tr>
</table>
<button type="submit" class="btn btn-success btn-lg" title="Salvar"><i class="bi bi-save"></i></button>
</div>
  </div>
</form>