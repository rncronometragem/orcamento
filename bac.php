<?php include_once "session.php"; ?>
<?php
// Conexão com o banco de dados
include_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

// Verifica se o diretório de backup existe e cria-o se necessário
$diretorio_backup = "./docs/backup/";
if (!is_dir($diretorio_backup)) {
    if (!mkdir($diretorio_backup, 0777, true)) {
        die("Erro ao criar o diretório de backup");
    }
}

// Verifica se a conexão foi estabelecida corretamente
if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Verifica se já passaram 7 dias desde o último backup
$ultimo_backup = glob($diretorio_backup . "*.sql");
if (!empty($ultimo_backup)) {
    $data_ultimo_backup = filemtime($ultimo_backup[0]);
    $diferenca_dias = (time() - $data_ultimo_backup) / (60 * 60 * 24);
    if ($diferenca_dias < 7) {
        echo "Ainda não é hora de fazer backup.";
        exit;
    }
}

// Nome do arquivo de backup com a data atual
$nome_arquivo_backup = "backup_" . date("Y-m-d") . ".sql";

// Caminho completo do arquivo de backup
$caminho_arquivo_backup = $diretorio_backup . $nome_arquivo_backup;

// Abre o arquivo de backup para escrita
$arquivo_backup = fopen($caminho_arquivo_backup, 'w');

// Obtém todas as tabelas do banco de dados
$tabelas = mysqli_query($conn, "SHOW TABLES");

// Itera sobre as tabelas
while ($tabela = mysqli_fetch_array($tabelas)) {
    $nome_tabela = $tabela[0];
    
    // Obtém o CREATE TABLE da tabela
    $resultado = mysqli_query($conn, "SHOW CREATE TABLE $nome_tabela");
    $create_table = mysqli_fetch_row($resultado)[1];
    
    // Escreve o CREATE TABLE no arquivo de backup
    fwrite($arquivo_backup, "-- Criação da tabela $nome_tabela\n");
    fwrite($arquivo_backup, "$create_table;\n\n");
    
    // Obtém os dados da tabela
    $resultado = mysqli_query($conn, "SELECT * FROM $nome_tabela");
    
    // Escreve os dados da tabela no arquivo de backup
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $colunas = implode(', ', array_keys($linha));
        $valores = implode(', ', array_map(function ($valor) use ($conn) {
            if ($valor === null) {
                return 'NULL';
            } else {
                return "'" . mysqli_real_escape_string($conn, $valor) . "'";
            }
        }, $linha));
        
        fwrite($arquivo_backup, "INSERT INTO $nome_tabela ($colunas) VALUES ($valores);\n");
    }
    
    fwrite($arquivo_backup, "\n");
}

// Fecha o arquivo de backup
fclose($arquivo_backup);

// Verifica se o backup foi gerado com sucesso
if (file_exists($caminho_arquivo_backup)) {
echo "<script>alert('Backup Realizado com sucesso!'); window.location = 'index.php';</script>";
} else {
    echo "Erro ao gerar o backup.";
}
?>