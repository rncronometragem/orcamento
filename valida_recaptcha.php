<?php
include_once("config.php");
//
session_start();
// recaptcha
if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
  {
 //google recaptcha - CHAVE SECRET Caixa de verificação v2
        $secret = 'COLOQUE SUA CHAVE secret Aqui';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success)
// recaptcha        
$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_STRING);

if ($btnLogin) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    if (!empty($usuario) && !empty($senha)) {
        $stmt = mysqli_prepare($conn, "SELECT id, nome, email, senha, controle, status, usuario FROM tbusuarios WHERE usuario=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $row_usuario = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        if ($row_usuario && password_verify($senha, $row_usuario['senha'])) {
            $_SESSION['id'] = $row_usuario['id'];
            $_SESSION['nome'] = $row_usuario['nome'];
            $_SESSION['email'] = $row_usuario['email'];
            $_SESSION['controle'] = $row_usuario['controle'];
            $_SESSION['status'] = $row_usuario['status'];
            $_SESSION['usuario'] = $row_usuario['usuario'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['msg'] = "Login e senha incorretos!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['msg'] = "Login e senha incorretos!";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['msg'] = "Página não encontrada";
    header("Location: login.php");
    exit();
}
}else{
$_SESSION['msg'] = "Procure o Administrador";
    header("Location: login.php");
}
?>