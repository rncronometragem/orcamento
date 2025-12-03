<?php
include_once "config.php";

$tipo = $_POST['tipo'];       // CPF ou CNPJ
$documento = $_POST['documento']; // Só números

// Busca no banco (campo documento armazena com máscara)
$query = $conn->prepare("SELECT idc FROM tbclientes WHERE REPLACE(REPLACE(REPLACE(documento, '.', ''), '-', ''), '/', '') = ?");
$query->bind_param("s", $documento);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['existe' => true, 'idc' => $row['idc']]);
} else {
    echo json_encode(['existe' => false]);
}
$query->close();
$conn->close();
?>
