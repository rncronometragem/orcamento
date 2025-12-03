<?php
  include_once"config.php";
  //
  $nome = $_POST['nome'];
  $categoria = $_POST['categoria'];
  $cad_status = $_POST['cad_status'];
  $datanasc = $_POST['datanasc']; // 16/02/2022
    $result=explode('/',$datanasc);
    $dia = $result[0];
    $mes = $result[1];
    $ano = $result[2];
  $datanasc = $ano.'-'.$mes.'-'.$dia;// 2022-02-16
  $datareg = date('Y-m-d'); // hoje
  $cnpj = $_POST['cnpj'];
  $inscestadual = $_POST['inscestadual'];
  $responsavel = $_POST['responsavel'];
  $cep = $_POST['cep'];
  $rua =  $_POST['rua'];
  $num = $_POST['num'];
  $numcomp = $_POST['numcomp'];
  $cidade = $_POST['cidade'];
  $bairro = $_POST['bairro'];
  $uf = $_POST['uf'];
  $cpf = $_POST['cpf'];
  $rg = $_POST['rg'];
  $telefone = $_POST['telefone'];
  $telcom = $_POST['telcom'];
  $celular = $_POST['celular']; 
  $email = $_POST['email'];
  $obs = $_POST['obs'];
  $estcivil = $_POST['estcivil'];
  $profissao = $_POST['profissao'];
  $datalog = date("Y-m-d H:i:s");
    //
 $sql = "INSERT INTO tbclientes (nome, datanasc, datareg, cnpj, inscestadual, responsavel, cep, rua, num, numcomp, cidade, bairro, uf, cpf, rg, telefone, telcom, celular, email, obs, estcivil, profissao, log, datalog, categoria, cad_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssss", $nome, $datanasc, $datareg, $cnpj, $inscestadual, $responsavel, $cep, $rua, $num, $numcomp, $cidade, $bairro, $uf, $cpf, $rg, $telefone, $telcom, $celular, $email, $obs, $estcivil, $profissao, $log, $datalog, $categoria, $cad_status);

if (mysqli_stmt_execute($stmt)) {
  // pós inserir dados enviar e-mail para o admin do site
  $to = $row['email']; // e-mail admin
        $subject = "Novo Cliente se Cadastro";
        $txt = "Olá Admin um novo Cliente como nome: $nome, se Cadastrou no seu Site\n Entre no sistema para saber mais informações...";
        $headers = "From: naoresponda@seusite.com.br" . "\r\n" . "X-Mailer: php";
        mail($to, $subject, $txt, $headers);

        $foi = $_SESSION['msg'] = "Seu Cadastro foi enviado com sucesso em breve entraremos em contato para lhe atender melhor obrigado.";
        echo "<script>alert('$foi'); window.location = 'index.php';</script>";
} else {
    echo "<script>window.location = 'javascript:history.back()';</script>";
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
//
$origem = 'img/padrao.png';
$destino = 'docs/padrao.png';  
copy($origem, $destino);
?>