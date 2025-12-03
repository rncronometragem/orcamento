<?php
$hoje = date('d/m/Y'); // pega dia de hoje
$idc = $_GET['idc'];
$result_nomes = "SELECT * FROM tbclientes WHERE idc='$idc' LIMIT 1";
$resultado_nomes = mysqli_query($conn, $result_nomes);
$conta = mysqli_num_rows($resultado_nomes); // conta registro
while($rows_nomes = mysqli_fetch_array($resultado_nomes)){
    $nome = $rows_nomes['nome'];
}
?>
<style>
    .modal-body {
        max-height: 400px; /* Altura máxima da modal */
        overflow-y: auto; /* Habilita rolagem vertical */
    }
/* Inicialmente oculta o campo Select2 */
.hide-select2 {
    display: none;
}
</style>
<form method="post" action="insert_ordens.php" enctype="multipart/form-data">
    <input type="hidden" name="nome" value="<?php echo $nome; ?>">
    <input type="hidden" name="log" value="<?php echo $log; ?>">
    <input type="hidden" class="form-control" name="idc" value="<?php echo $idc; ?>">
    <div class="form-group col-md-2">
        <label for="inputlg">Data</label>
        <input type="text" class="form-control" name="data" maxlength="15" value="<?php echo $hoje; ?>" id="dataordem" required="required" autocomplete="user-password">
        <input type="hidden" class="form-control" name="status_ordem" maxlength="15" value="pendente" required="required" autocomplete="user-password">
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-lg" title="Salvar Ordem"><i class="bi bi-save"> Criar Pedido</i></button>
    </div>
</form>
<table class="table table-hover">
<thead>
    <tr>
        <th width="10%" align="left">Data</th>
        <th width="10%" align="left">Número Pedido</th>
        <th width="10%" align="left">Status</th>
        <th width="10%" align="left">Itens do Pedido</th>
        <th width="10%" align="left">Login</th>
        <th width="10%" align="center">Opções</th>
    </tr>
</thead>
<?php
$idc = $_GET['idc'];
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

$result_nomes = "SELECT * FROM tbordens WHERE idc='$idc' ORDER BY data DESC LIMIT $offset, $numerodepagina";
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
            <div class="modal fade" id="modalVerItens' . $rows_nomes["ido"] . '"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         
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
    <select name="descricao" value="" class="tbproduto form-control" required></select>
    
</div>
                                <div class="form-group">
                                    <label for="quantidade">Quantidade:</label>
                                    <input type="number" class="form-control" name="quantidade" value="1" required>
                                </div>
                                <div class="form-group">
    <label for="preco">Preço:</label>
    <input type="text" class="form-control valor" id="valor" name="preco" required>
</div>
                                <button type="button" class="btn btn-primary" onclick="submitForm(' . $ido . ')">Adicionar Item</button>
                            </form>
                        </div>
                     <div class="modal-footer">
                    <div style="text-align: center; display: inline-block;">
 <form id="formStatus' . $ido . '" method="post" action="update_status.php">
                                <input type="hidden" name="ido" value="' . $ido . '">
                                   <select style="background-color:'.$status_class.';inline-block; width: 150px;" class="form-control" name="status" id="status' . $rows_nomes['ido'] . '" onchange="updateStatus(' . $rows_nomes['ido'] . ')">
                                        <option value="pendente" ' . ($rows_nomes['status_ordem'] == 'pendente' ? 'selected' : '') . '>Pendente</option>
                                        <option value="concluido" ' . ($rows_nomes['status_ordem'] == 'concluido' ? 'selected' : '') . '>Concluído</option>
                                    </select>
                        
                            </form>
                            </div>

                            <div style="text-align: center; display: inline-block;"><button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button></div>

                            
                    </div>
                    </div>
                </div>
            </div>
            Nº <a title="Ver Itens" data-toggle="modal" data-target="#modalVerItens' . $rows_nomes["ido"] . '">' . $rows_nomes["ido"] . '</a>
        </td>
        <td width="10%"><p align="left"><span class="btn '.$status_class.'">' . $rows_nomes["status_ordem"] . '</span>
                            </td>
        <td width="10%"><p align="left"><a title="Ver Itens" data-toggle="modal" data-target="#modalVerItens' . $rows_nomes["ido"] . '">Ver Itens</a></p></td>
        <td width="10%"><p align="left">' . $rows_nomes["log"] . '</p></td>
        <td width="10%">
            <div class="btn-group">
                <form method="post" name="dataForm" action="imprimir_pedido.php" target="_blank">
                    <input type="hidden" name="ido" value="' . $rows_nomes['ido'] . '">
                    <input type="hidden" name="nome" value="' . $nome . '">
                    <input type="hidden" name="idc" value="' . $rows_nomes['idc'] . '">
                    <button type="submit" title="Imprimir Histórico"><i class="bi bi-printer" style="color:blue"></i></button>
                </form>
            </div>
            <div class="btn-group">
                <form method="post" id="form" name="dataForm" action="delete_ordens.php">
                    <input type="hidden" name="nome" value="' . $rows_nomes['nome'] . '">
                    <input type="hidden" name="idc" value="' . $rows_nomes['idc'] . '">
                    <input type="hidden" name="ido" value="' . $rows_nomes['ido'] . '">
                    <button type="submit" title="Deletar" onclick="return deleta_ordem();"><i class="bi bi-trash" style="color:red"></i></button>
                </form>
            </div>
        </td>
    </tr>
    </tbody>';
}
echo '</table>';
?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
function submitForm(ido) {
  var descricao = $('.tbproduto').val();
    if (!descricao) {
        alert('Por favor, selecione um produto válido.');
        return; // Cancela o envio do formulário se a descrição estiver vazia
    }

    setTimeout(function() {
        var formData = $("#formItens" + ido).serialize();
        console.log(formData); // Verifica os dados antes de enviar

        $.ajax({
            type: 'POST',
            url: 'insert_itens_ajax.php',
            data: formData,
            success: function(response) {
                if(response === "success") {
                    alert('Item adicionado com sucesso!');

                    recarregarTabelaItens(ido);
                    $("#formItens" + ido)[0].reset(); // Limpa o formulário

                    // Limpa o Select2 para evitar o envio de valores antigos
                    $('.tbproduto').val(null).trigger('change.select2');
                } else {
                    alert('Ocorreu um erro ao adicionar o item.');
                }
            },
            error: function() {
                alert('Ocorreu um erro ao adicionar o item.');
            }
        });
    }, 200); // Ajuste o tempo de atraso se necessário
}

function recarregarTabelaItens(ido) {
    $.ajax({
        url: 'recarregar_tabela_itens.php', // Arquivo PHP que retorna o HTML da tabela
        type: 'GET',
        data: { ido: ido },
        success: function(response) {
            // Substituir o conteúdo da tabela pelo novo conteúdo
            $("#tabelaItens" + ido).html(response);
        }
    });
}

// Função para deletar item
$(document).on('click', '.btn-deletar', function() {
    var ido = $(this).data('ido');
    var idi = $(this).data('idi');

    $.ajax({
        url: 'delete_item.php',
        type: 'POST',
        data: { idi: idi },
        success: function(response) {
            if(response === "success") {
                alert('Item deletado com sucesso!');
                recarregarTabelaItens(ido);
            } else {
                alert('Ocorreu um erro ao deletar o item.');
            }
        }
    });
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializa o Select2 logo no carregamento da página
    $('.tbproduto').select2();

    // Aplica estilos diretamente via jQuery
    $('.tbproduto').next('.select2-container').css({
        'width': '100%',        // Define a largura do Select2
        'height': '40px',       // Define a altura do Select2
    });

    // Estilos adicionais para ajustar a altura e o padding interno
    $('.tbproduto').next('.select2-container').find('.select2-selection').css({
        'height': '40px',       // Define a altura do campo de seleção
        'padding-top': '4px',   // Ajusta o espaçamento interno superior
        'padding-bottom': '4px' // Ajusta o espaçamento interno inferior
    });

    $('.tbproduto').next('.select2-container').find('.select2-selection__rendered').css({
        'line-height': '1.5',   // Ajusta a altura da linha para centralizar o texto
        'padding-top': '4px',   // Ajusta o espaçamento interno superior do texto
        'padding-bottom': '8px' // Ajusta o espaçamento interno inferior do texto
    });

    // Carrega os produtos assim que a página é carregada
    $.ajax({
        url: 'buscar_produtos.php',
        dataType: 'json',
        data: { term: '' },
        success: function(data) {
            $('.tbproduto').empty().append('<option value="">Selecione um produto</option>');
            $.each(data, function(index, item) {
                $('.tbproduto').append(
                    $('<option>', { 
                        value: item.produto, // ID do produto
                        text: item.text, // Nome do produto
                        'data-preco': item.valor // Preço do produto
                    })
                );
            });

            // Atualiza o Select2 com as novas opções
            $('.tbproduto').trigger('change');

            // Abre o dropdown do Select2 automaticamente
            //$('#tbproduto').select2('open');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Erro ao buscar os produtos: ', textStatus, errorThrown);
            alert('Erro ao buscar os produtos.');
        }
    });

    $('.tbproduto').change(function() {
        var preco = $(this).find(':selected').data('preco');
        var produto = $(this).find(':selected').text();

        $('#valor').val(preco);
        $('.tbproduto').val(produto);
    });
});
</script>
