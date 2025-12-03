<?php
include('config.php'); // Incluir o arquivo de configuração do banco de dados

// Verifica se o parâmetro 'term' foi passado pela requisição AJAX
if(isset($_GET['term'])) {
    $term = $_GET['term'];

    // Verifica se a conexão foi bem-sucedida
    if (!$conn) {
        die('Erro ao conectar ao banco de dados: ' . mysqli_connect_error());
    }

    // Consulta na tabela 'tbprodutos' buscando produtos que contenham o termo digitado
    $query = "SELECT idp, produto, valor FROM tbprodutos WHERE produto LIKE '%" . mysqli_real_escape_string($conn, $term) . "%'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Erro na consulta: ' . mysqli_error($conn));
    }

    $descricao = array();

    // Percorre os resultados e adiciona ao array $produtos
    while($row = mysqli_fetch_assoc($result)) {
        $descricao[] = array(
            'idp' => $row['idp'], // Adiciona o ID para ser utilizado no Select2
            'text' => $row['produto'], // 'text' é o rótulo utilizado pelo Select2
            'valor' => number_format($row['valor'], 2, ',', '.') // Formata o valor para exibir em reais
        );
    }

    // Retorna o resultado em formato JSON
    echo json_encode($descricao);
} else {
    echo json_encode(array('error' => 'Parâmetro "term" não fornecido'));
}
?>
