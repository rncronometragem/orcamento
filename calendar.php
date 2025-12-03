<html>
<head>
<!-- data do calendario-->
<link rel="stylesheet" href="css/redmond/jquery-ui-1.10.1.custom.css" />
<script src="js/jquery-1.9.1.js" type="text/javascript"></script>
<script src="js/jquery-ui.js" type="text/javascript"></script>
<!-- calendario inicio-->
<style>
 body {
    font-family: Verdana, Arial, sans-serif;
    font-size: 14px;
}
.ui-datepicker-week-end a {
    color: red !important;
}
hr{
  border-color:#aaa;
  box-sizing:border-box;
  width:100%;  
}
</style>
  <script>
now = new Date();
    var feriados, i, dias;
    <!-- Array com os feriados -->
    var feriados = [
        "01/01/" + now.getFullYear() + " - Confraternização Universal",
        
    ];
    
    var dias = [
          "01/01/" + now.getFullYear(),
         
    ];

  $(function() {
    $(".dataagenda").datepicker({
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
    prevText: 'Anterior',
  inline: true,
  beforeShowDay:function(dateText, inst) {
           var datepickerDay = ('0' + dateText.getDate()).slice(-2) + '/'
      + ('0' + (dateText.getMonth()+1)).slice(-2) + '/'
      + dateText.getFullYear();
    console.log(datepickerDay);
        if(dias.indexOf(datepickerDay.trim()) > -1) {
        return [false, "", feriados[dias.indexOf(datepickerDay)].split('-')[1]];
    }
    return [true, "", ""];
  },
});
$(document).on('click', '#ui-datepicker-div .ui-state-disabled', function() {
    alert('Feriado');
});
});
 </script>
 <script>
now = new Date();
    var feriados, i, dias;
    <!-- Array com os feriados -->
    var feriados = [
        "01/01/" + now.getFullYear() + " - Confraternização Universal",
        
    ];
    
    var dias = [
          "01/01/" + now.getFullYear(),
         
    ];

  $(function() {
    $(".dataadiciona").datepicker({
  minDate: 0,
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
    prevText: 'Anterior',
  inline: true,
  beforeShowDay:function(dateText, inst) {
           var datepickerDay = ('0' + dateText.getDate()).slice(-2) + '/'
      + ('0' + (dateText.getMonth()+1)).slice(-2) + '/'
      + dateText.getFullYear();
    console.log(datepickerDay);
        if(dias.indexOf(datepickerDay.trim()) > -1) {
        return [false, "", feriados[dias.indexOf(datepickerDay)].split('-')[1]];
    }
    return [true, "", ""];
  },
});
$(document).on('click', '#ui-datepicker-div .ui-state-disabled', function() {
    alert('Feriado');
});
});
 </script>
  </body>
 </html>