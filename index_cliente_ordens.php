<?php include_once"session.php";?>
<?php include_once"config.php";?>
<?php include_once"cabecalho.php";?>
<?php include_once"calendar.php";?>
<html>
<head>
    <title>Index Ordens</title>
</head>
<body>
<div class="container-fluid">
<br> 
<?php
echo '<table class="table table-hover">
<thead>
    <tr>
        <th width="10%" align="left">Data</th>
        <th width="10%" align="left">Número Pedido</th>
        <th width="10%" align="left">Status</th>
        <th width="10%" align="left">Itens do Pedido</th>
        <th width="10%" align="left">Login</th>
        <th width="10%" align="center">Opções</th>
    </tr>
</thead>';
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
} else {
    $pagina = 1;
}
$numerodepagina = 5;
$offset = ($pagina - 1) * $numerodepagina;

$total_paginas_sql = "SELECT COUNT(*) FROM tbordens";
$result = mysqli_query($conn, $total_paginas_sql);
$total_linhas = mysqli_fetch_array($result)[0];
$total_paginas = ceil($total_linhas / $numerodepagina);

$result_nomes = "SELECT * FROM tbordens ORDER BY data DESC LIMIT $offset, $numerodepagina";
$resultado_nomes = mysqli_query($conn, $result_nomes);
while ($rows_nomes = mysqli_fetch_array($resultado_nomes)) {
    $status_ordem = $rows_nomes['status_ordem'];
    $ido = $rows_nomes['ido'];
    $idc = $rows_nomes['idc'];
    $logb = $rows_nomes['log'];
    $datah = date('d/m/Y', strtotime($rows_nomes["data"]));
    $status_class = '';
    if ($status_ordem == 'pendente') {
        $status_class = 'bg-warning';
    } elseif ($status_ordem == 'concluido') {
        $status_class = 'bg-success';
    }


    echo '<tbody>
    <tr>
        <td width="10%" align="left" valign="middle">' . date('d/m/Y H:i', strtotime($rows_nomes["data"])) . '</td>
        <td width="10%" align="left">
            <div class="modal fade" id="modalVerItens' . $rows_nomes["ido"] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Itens do Pedido Nº ' . $rows_nomes["ido"] . '</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                                    <table class="table table-hover" id="tabelaItens' . $rows_nomes["ido"] . '">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Quant</th>
                                        <th>Preço Unit</th>
                                        <th>Subtotal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    $result_itens = "SELECT * FROM tbitens WHERE ido='$ido'";
                                    $resultado_itens = mysqli_query($conn, $result_itens);
                                    $totalGeral = 0;
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
                                        <td id="total' . $rows_nomes["ido"] . '">R$ ' . number_format($totalGeral, 2, ',', '.') . '</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <form id="formItens' . $ido . '" method="post">
                                <input type="hidden" name="ido" value="' . $ido . '">
                                <input type="hidden" name="idc" value="' . $idc . '">
                                <div class="form-group">
                                    <label for="descricao">Descrição:</label>
                                    <input type="text" class="form-control" name="descricao" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantidade">Quantidade:</label>
                                    <input type="number" class="form-control" name="quantidade" required>
                                </div>
                                <div class="form-group">
                                    <label for="preco">Preço:</label>
                                    <input type="text" class="form-control" name="preco" required>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="submitForm(' . $ido . ')">Adicionar Item</button>
                            </form>
                        </div>
                     <div class="modal-footer">
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <div class="form-group col-md-4">
  <label for="inputlg" for="name" style="display: inline-block; width: 80px;">Status</label>

                            <form id="formStatus' . $ido . '" method="post" action="update_status.php">
                                <input type="hidden" name="ido" value="' . $ido . '">
                                   <select style="background-color:'.$status_class.';inline-block; width: 80px;" class="form-control" name="status" id="status' . $rows_nomes['ido'] . '" onchange="updateStatus(' . $rows_nomes['ido'] . ')">
                                        <option value="pendente" ' . ($rows_nomes['status_ordem'] == 'pendente' ? 'selected' : '') . '>Pendente</option>
                                        <option value="concluido" ' . ($rows_nomes['status_ordem'] == 'concluido' ? 'selected' : '') . '>Concluído</option>
                                    </select>
                        
                            </form></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            Nº <a title="Ver Itens" data-toggle="modal" data-target="#modalVerItens' . $rows_nomes["ido"] . '">' . $rows_nomes["ido"] . '</a>
        </td>
        <td width="10%"><p align="left"><button type="button" class="btn '.$status_class.'">' . $rows_nomes["status_ordem"] . '</button>
                            </td>
        <td width="10%"><p align="left"><a title="Ver Itens" data-toggle="modal" data-target="#modalVerItens' . $rows_nomes["ido"] . '">Ver Itens</a></p></td>
        <td width="10%"><p align="left">' . $rows_nomes["log"] . '</p></td>
        <td width="10%">
            <div class="btn-group">
                <form method="post" name="dataForm" action="imprimir_pedido.php" target="_blank">
                    <input type="hidden" name="ido" value="' . $rows_nomes['ido'] . '">
                    <input type="hidden" name="nome" value="' . $rows_nomes['nome']. '">
                    <input type="hidden" name="idc" value="' . $rows_nomes['idc'] . '">
                    <button type="submit" title="Imprimir Histórico"><i class="bi bi-printer" style="color:blue"></i></button>
                </form>
            </div>
            <div class="btn-group">
                <form method="post" id="form" name="dataForm" action="delete_ordens.php">
                    <input type="hidden" name="nome" value="' . $rows_nomes['nome'] . '">
                    <input type="hidden" name="idc" value="' . $rows_nomes['idc'] . '">
                    <input type="hidden" name="ido" value="' . $rows_nomes['ido'] . '">
                    <button type="submit" title="Deletar" onclick="return deleta_historico();"><i class="bi bi-trash" style="color:red"></i></button>
                </form>
            </div>
        </td>
    </tr>
    </tbody>';
}
echo '</table>';
?>

<!-- Inclua jQuery e Bootstrap JS no final do arquivo, antes do fechamento do body -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
function submitForm(ido) {
    var formData = $("#formItens" + ido).serialize();

    $.ajax({
        type: 'POST',
        url: 'insert_itens_ajax.php',
        data: formData,
        success: function(response) {
            if(response === "success") {
                alert('Item adicionado com sucesso!');

                // Obter valores do formulário
                var descricao = $("#formItens" + ido + " input[name='descricao']").val();
                var quantidade = parseFloat($("#formItens" + ido + " input[name='quantidade']").val());
                var preco = parseFloat($("#formItens" + ido + " input[name='preco']").val().replace(',', '.'));

                var subtotal = quantidade * preco;

                // Adicionar o novo item na tabela sem recarregar a página
                $("#tabelaItens" + ido + " tbody").append(
                    '<tr id="item' + response.idi + '"><td>' + descricao + '</td><td>' + quantidade + '</td><td>R$ ' + preco.toFixed(2).replace('.', ',') + '</td><td class="subtotal">R$ ' + subtotal.toFixed(2).replace('.', ',') + '</td><td><button class="btn btn-danger btn-sm btn-deletar" data-ido="' + ido + '" data-idi="' + response.idi + '">Deletar</button></td></tr>'
                );

                // Atualizar o total geral
                atualizarTotais(ido);

                // Limpar o formulário
                $("#formItens" + ido)[0].reset();
            } else {
                alert('Ocorreu um erro ao adicionar o item.');
            }
        },
        error: function() {
            alert('Ocorreu um erro ao adicionar o item.');
        }
    });
}

// Função para deletar item
$(document).on('click', '.btn-deletar', function() {
    var ido = $(this).data('ido');
    var idi = $(this).data('idi');

    if (confirm("Tem certeza que deseja deletar este item?")) {
        $.ajax({
            type: 'POST',
            url: 'delete_item.php',
            data: { idi: idi },
            success: function(response) {
                if (response === "success") {
                    $("#item" + idi).remove();
                    atualizarTotais(ido);
                    alert("Item deletado com sucesso!");
                } else {
                    alert("Ocorreu um erro ao deletar o item.");
                }
            },
            error: function() {
                alert("Ocorreu um erro ao deletar o item.");
            }
        });
    }
});

function atualizarTotais(ido) {
    var totalGeral = 0;

    $("#tabelaItens" + ido + " tbody tr").each(function() {
        var subtotalStr = $(this).find(".subtotal").text().replace('R$', '').trim().replace('.', '').replace(',', '.');
        var subtotal = parseFloat(subtotalStr);

        // Verificar se subtotal é um número válido
        if (!isNaN(subtotal)) {
            totalGeral += subtotal;
        }
    });

    $("#total" + ido).text("Total: R$ " + totalGeral.toFixed(2).replace('.', ','));
}

// Função para enviar o formulário de atualização de status via AJAX e fechar o modal
function updateStatus(ido) {
    var form = document.getElementById('formStatus' + ido);
    var formData = new FormData(form);

    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            alert('Status atualizado com sucesso!');

            // Atualiza o status na tabela principal
            var novoStatus = $("#formStatus" + ido + " select[name='status']").val();
            $("#modalVerItens" + ido).modal('hide'); // Fecha o modal
            location.reload(); // Opcional: Recarrega a página para garantir atualização dos dados
        } else {
            alert('Erro ao atualizar o status.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}
</script>
</div>  
<?php include_once "paginar.php"; ?>
<?php include_once "rodape.php"; ?>
</body>
</html>