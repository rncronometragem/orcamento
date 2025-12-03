<?php include_once"session.php";?>
<?php
include_once "config.php";
//
$ide = $_POST['ide'];
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$inscestadual = $_POST['inscestadual'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$num = $_POST['num'];
$numcomp = $_POST['numcomp'];
$cidade = $_POST['cidade'];
$bairro = $_POST['bairro'];
$uf = $_POST['uf'];
$telefone = $_POST['telefone'];
$telcom = $_POST['telcom'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$site = $_POST['site'];
$barra = $_POST['barra'];
$letra = $_POST['letra'];

// Prepare a statement
$stmt = mysqli_prepare($conn, "UPDATE tbempresa SET nome=?, cnpj=?, inscestadual=?, cep=?, rua=?, num=?, numcomp=?, cidade=?, bairro=?, uf=?, telefone=?, telcom=?, celular=?, email=?, site=?, barra=?, letra=? WHERE ide=?");

// checa a declaração
if ($stmt === false) {
    die('Erro na preparação da declaração: ' . mysqli_error($conn));
}

// Bind paramentros
mysqli_stmt_bind_param($stmt, 'sssssssssssssssssi', $nome, $cnpj, $inscestadual, $cep, $rua, $num, $numcomp, $cidade, $bairro, $uf, $telefone, $telcom, $celular, $email, $site, $barra, $letra, $ide);

// Executa o statement
if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('$nome Salvo com sucesso!'); window.location = 'form_empresa_editar.php?ide=$ide';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}

// Fecha a conexão
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>