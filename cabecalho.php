<?php include_once"session.php";?>
<?php include_once"config.php";?>
<!DOCTYPE html>
<html>
<head>
    <html lang="pt-br">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Sistema de Cadastro - <?php echo $_SESSION['versao'];?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery.min.js"></script>
<script src="js/jquery.mask.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/all.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<script>
    $('.valor').mask('000.000.000.000.000,00', {reverse: true});
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cpf').mask('000.000.000-00');
    $("#cep").mask("00000-000");
    $("#data").mask("00/00/0000");
    $("#dataordem").mask("00/00/0000");
    $("#dataordem2").mask("00/00/0000");
    $("#datan").mask("00/00/0000");
    $("#data1").mask("00/00/0000");
    $("#data2").mask("00/00/0000");
    $("#telefone").mask("(00) 0000-0000");
    $("#telcom").mask("(00) 0000-0000");
    $("#celular").mask("(00) 00000-0000");
</script>
<script>
$(document).ready(function(){
    // Função para mostrar/ocultar os campos ao carregar a página
    function mostrarCampoDocumento() {
        var tipo = $('#tipoDocumento').val();
        console.log('Tipo selecionado:', tipo); // Verifique o valor selecionado
        if(tipo === 'CPF'){
            $('#campoCPF').show();
            $('#campoCNPJ').hide();
            $('#cpf').prop('disabled', false); // Habilita o campo CPF
            $('#cnpj').prop('disabled', true);  // Desabilita o campo CNPJ
        } else if(tipo === 'CNPJ'){
            $('#campoCNPJ').show();
            $('#campoCPF').hide();
            $('#cnpj').prop('disabled', false); // Habilita o campo CNPJ
            $('#cpf').prop('disabled', true);   // Desabilita o campo CPF
        } else {
            $('#campoCPF').hide();
            $('#campoCNPJ').hide();
        }
    }

    // Chamar a função ao carregar a página
    mostrarCampoDocumento();

    // Chamar a função ao mudar o valor do select
    $('#tipoDocumento').change(function(){
        mostrarCampoDocumento();
    });
});
</script>
<!--calendario -->
<script type="text/javascript">
$(document).ready(function(e) {
    $("#data,#datan,#datahist,#data1,#data2,#dataordem,#dataordem2")({
    setDate: "today",
    language: "pt-BR",
    todayHighlight: "true",
    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    dateFormat: 'dd/mm/yy',
    nextText: 'Próximo',
    prevText: 'Anterior'
  });
});
</script>
 <!-- Inicio busca cep -->
    <script type="text/javascript">  
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('rua').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('uf').value=("");
           // document.getElementById('ibge').value=("");
    }
    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
           // document.getElementById('ibge').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
     function pesquisacep(valor) {
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";
               // document.getElementById('ibge').value="...";
                //Cria um elemento javascript.
                var script = document.createElement('script');
                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };
 </script>
 <script type="text/javascript">
    $(document).ready(function()
    {
      $('#busca').autocomplete(
      {
        source: "busca_nomes.php",
        minLength: 2
      });
    });
  </script>
<script>
function deleta_cliente() {
  if(confirm("!!! MUITA ATENÇÃO !!! Deseja deletar esse Cliente? NÃO TEM como recuperar os dados depois. Para não deletar é o Cancelar. Se for deletar é o OK"))
    document.forms[0].submit();
  else 
    return false;}
</script>
<script>
function deleta_historico() {
  if(confirm("Deletar esse Histórico? Não tem como recuperar os dados depois. Se não for deletar é o Cancelar. Se for deletar é o OK"))
    document.forms[0].submit();
  else 
    return false;}
</script>
<script>
function deleta_ordem() {
  if(confirm("Deletar esse Pedido? Não tem como recuperar os dados depois. Se não for deletar é o Cancelar. Se for deletar é o OK"))
    document.forms[0].submit();
  else 
    return false;}
</script>
<script>
function deleta_produto() {
  if(confirm("Deletar esse Produto? Não tem como recuperar os dados depois. Se não for deletar é o Cancelar. Se for deletar é o OK"))
    document.forms[0].submit();
  else 
    return false;}
</script>
 <script>
    function somenteNumeros(num) {
        var er = /[^0-9-./]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
          campo.value = "";
        }
    }
 </script>
<style type="text/css">
input[type=text]{
background-color: #fff  
}
textarea:focus,
input[type=text]:focus,
input[type=password]:focus {
    background:#f5fafa;
}</style>
<script type="text/javascript">
function maiuscula(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toUpperCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}
</script>
<script type="text/javascript">
function minuscula(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toLowerCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}
</script>
  </head>
<body>
<nav class="navbar-default navbar-light navbar-flex" style="background-color: <?php echo $barra;?>;">
<div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
      </button>
<a class="navbar-brand-logo" href="index.php"><img src="logo/<?php echo $imagem;?>" alt="Em Geral" width="50" height="50"></a>
</div>
  <div id="navbar" class="navbar-collapse collapse">
 <ul class="nav navbar-nav navbar-right">
    <li><a href="index.php" title="Clientes"><i class="bi bi-folder" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Clientes</font></a></li>
    <li><a href="form_cliente.php" title="Novo"><i class="bi bi-file-earmark-person" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>">  Novo</font></a></li>
    <li><a href="index_ordens.php" title="Pedidos"><i class="bi bi-card-checklist" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Pedidos</font></a></li>
    <li><a href="index_produtos.php" title="Produtos"><i class="bi bi-card-list" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Produtos</font></a></li>

    <li><a href="index_historico.php" title="Históricos"><i class="bi bi-chat-left" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Históricos</font></a></li>

  <li><a href=""><i class="bi bi-person-badge" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>">  <?php echo $log;?></font></a></li>
  <li class="dropdown" style="display:<?php echo $admin;?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-gear" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Configurações</font> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                    <li><a href="form_empresa_editar.php" title="Empresa" style="display:<?php echo $admin;?>"><i class="bi bi-bar-chart-steps"></i> Empresa</a></li>
                        <li><a href="index_categoria.php" title="Categorias"><i class="bi bi-card-list"></i> Categorias</a></li>
                        <li><a href="admin.php"><i class="bi bi-people-fill"></i> Usuários</a></li>
                        <li><a href="bac.php"><i class="bi bi-layer-backward"></i> Backup</a></li>
                    </ul>
                </li>
       <li><a href="sair.php"><i class="bi bi-x-lg" style="color:<?php echo $letra;?>;"></i><font color="<?php echo $letra;?>"> Sair</font></a></li>
</ul>
<div>
  <div style="text-align:center">
  <form class="navbar-form navbar-center" method="get" action="busca.php" autocomplete="off">
  <div class="input-group">
    <input type="text" class="form-control" name="busca" id="busca" placeholder="nome ou documento" required onchange="this.form.submit()" autocomplete="off">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="bi bi-search"></i>
      </button>
  </form>
</div>
</nav>
 <main class="container">
</body>
</html>