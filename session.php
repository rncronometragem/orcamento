<?php
session_start();
if (isset($_SESSION['status'])){
}else{
header("Location: login.php");
}
$user = $_SESSION['nome'];
$log = $_SESSION['usuario'];
$status = $_SESSION['status'];
if($status === "administrador"){
    $del="show";
}else{
    $del="none";
}
$acao = $_SESSION['usuario'];
if($acao === "admin"){
    $acao="enable";
}else{
    $acao="disabled";
}
$admin = $_SESSION['usuario'];
if($admin === "admin"){
    $admin="show";
}else{
    $admin="none";
}
$_SESSION['versao'] = 'Versão 2.0';
$_SESSION['vphp'] = 'Versão do PHP: ' . PHP_VERSION;
?>