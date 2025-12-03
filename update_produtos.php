<?php
// Inclui a conexão com o banco de dados
include_once "config.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário
    $idp = $_POST['idp']; // ID do produto
    $produto = $_POST['produto'];
    $valor = $_POST['valor'];

    // Formata o valor para o formato adequado (remover a vírgula)
    $valor = str_replace(',', '.', $valor);

    // Atualiza os dados do produto no banco de dados
    $sql = "UPDATE tbprodutos SET produto = ?, valor = ? WHERE idp = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincula as variáveis aos parâmetros da instrução preparada
        mysqli_stmt_bind_param($stmt, "sdi", $produto, $valor, $idp);

        // Executa a instrução
        if (mysqli_stmt_execute($stmt)) {
            // Redireciona de volta à página de listagem ou exibe uma mensagem de sucesso
            echo "<script>alert('$produto Salvo com sucesso!'); window.location = 'index_produtos.php';</script>";
            exit();
        } else {
            echo "Erro ao atualizar o produto: " . mysqli_error($conn);
        }

        // Fecha a instrução
        mysqli_stmt_close($stmt);
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
