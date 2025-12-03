<?php
include('config.php');

$ido = $_GET['ido'];

$result_itens = "SELECT * FROM tbitens WHERE ido='$ido'";
$resultado_itens = mysqli_query($conn, $result_itens);
$totalGeral = 0;

echo '<thead>
        <tr>
            <th>Descrição</th>
            <th>Quant</th>
            <th>Preço Unit</th>
            <th>Subtotal</th>
            <th>Ações</th>
        </tr>
      </thead>
      <tbody>';
while ($rows_itens = mysqli_fetch_array($resultado_itens)) {
    $subtotal = $rows_itens['quantidade'] * $rows_itens['preco'];
    $totalGeral += $subtotal;
    echo '<tr id="item' . $rows_itens['idi'] . '">
            <td>' . $rows_itens['descricao'] . '</td>
            <td>' . $rows_itens['quantidade'] . '</td>
            <td>R$ ' . number_format($rows_itens['preco'], 2, ',', '.') . '</td>
            <td class="subtotal">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>
            <td><button class="btn btn-danger btn-sm btn-deletar" data-ido="' . $ido . '" data-idi="' . $rows_itens['idi'] . '">Deletar</button></td>
        </tr>';
}
echo '</tbody>
      <tfoot>
        <tr>
            <th colspan="3" style="text-align:right">Total:</th>
            <td id="total' . $ido . '">R$ ' . number_format($totalGeral, 2, ',', '.') . '</td>
            <td></td>
        </tr>
      </tfoot>';
?>