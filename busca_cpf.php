<?php 
include_once "config.php";
$cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
$query = "SELECT idc FROM tbclientes WHERE cpf = '$cpf' OR cpf = '".substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9, 2)."'";
$resultado = mysqli_query($conn, $query) or die("Erro ao executar consulta");
if (mysqli_num_rows($resultado) > 0) {
    // CPF encontrado
    $idc = mysqli_fetch_assoc($resultado)['idc'];
    echo json_encode(array('existe' => true, 'idc' => $idc));
}else{
    // CPF não encontrado
    echo json_encode(array('existe' => false));
}
mysqli_close($conn);
?>