<?php
$datahoje = date('d-m-Y-s');
$arquivo = 'clientes'.$datahoje.'.xls';
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/msexcel");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );
?>
<?php include_once"session.php";?>
<?php include_once('config.php');?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Cadastro de Clientes</title>
<head>
<body>
    <?php
    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td colspan="13"><center><b>Cadastro de Clientes</b></center></td>';
    $html .= '</tr>';        
    $html .= '<tr>';
    $html .= '<td><b>IDC</b></td>';
    $html .= '<td><b>NOME</b></td>';
    $html .= '<td><b>CATEGORIA</b></td>';
    $html .= '<td><b>DATA CADASTRO</b></td>';
    $html .= '<td><b>DOCUMENTO</b></td>';
    $html .= '<td><b>CEP</b></td>';
    $html .= '<td><b>ENDEREÇO</b></td>';
    $html .= '<td><b>NUMERO</b></td>';
    $html .= '<td><b>COMPLEMENTO</b></td>';
    $html .= '<td><b>BAIRRO</b></td>';
    $html .= '<td><b>MUNICÍPIO</b></td>';
    $html .= '<td><b>UF</b></td>';
    $html .= '<td><b>TELEFONE</b></td>';
    $html .= '<td><b>CELULAR</b></td>';
    $html .= '<td><b>EMAIL</b></td>';
    $html .= '<td><b>OBS</b></td>';
    $html .= '</tr>';

    $conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);    
    mysqli_set_charset($conn,"utf8");

    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    // Construção da query
    $result_sql = "SELECT * FROM tbclientes WHERE 1=1";
    if ($categoria != '') {
        $result_sql .= " AND categoria = '$categoria'";
    }
    $result_sql .= " ORDER BY nome ASC";

    $result_query = mysqli_query($conn, $result_sql);
    while($linha = mysqli_fetch_assoc($result_query)){
        $html .= '<tr>';
        $html .= '<td>'.$linha["idc"].'</td>';
        $html .= '<td>'.$linha["nome"].'</td>';
        $html .= '<td>'.$linha["categoria"].'</td>';
        $datareg = date('d/m/Y',strtotime($linha["datareg"]));
        $html .= '<td>'.$datareg.'</td>';
        $html .= '<td>'.$linha["documento"].'</td>';
        $html .= '<td>'.$linha["cep"].'</td>';
        $html .= '<td>'.$linha["rua"].'</td>';
        $html .= '<td>'.$linha["num"].'</td>';
        $html .= '<td>'.$linha["numcomp"].'</td>';
        $html .= '<td>'.$linha["bairro"].'</td>';
        $html .= '<td>'.$linha["cidade"].'</td>';
        $html .= '<td>'.$linha["uf"].'</td>';
        $html .= '<td>'.$linha["telefone"].'</td>';
        $html .= '<td>'.$linha["celular"].'</td>';
        $html .= '<td>'.$linha["email"].'</td>';
        $html .= '<td>'.$linha["obs"].'</td>';
        $html .= '</tr>';
    }
    echo $html;
    exit; 
    ?>
</body>
</html>