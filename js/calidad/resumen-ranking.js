$(document).ready(function(){
    $("#Mostrar").click(function(){
        var Empresa = $("select[name='Empresa']").val();
        if(Empresa != ""){
            ShowGraphs();
            $("#result").show();
        }else{
            alert("Debe seleccionar una empresa");
        }
    });
    $("select[name='Empresa']").change(function(){
        var ObjectMe = $(this);
        var id = ObjectMe.val();
        fillCedentes(id);
    });
    $("select[name='Cedente']").change(function(){
        var ObjectMe = $(this);
        var idCedente = ObjectMe.val();
        var idMandante = $("select[name='Empresa']").val();
        fillPeriodos(idMandante,idCedente);
    });
    function ShowGraphs(){
        var Mandante = $("select[name='Empresa']").val();
        var Cedente = $("select[name='Cedente']").val();
        var Periodo = $("select[name='Periodo']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getRankingData.php",
            data: {
                Mandante: Mandante,
                Cedente: Cedente,
                Periodo: Periodo
                },
            dataType: "html",
            success: function(data){
                console.log(data);
                $("#Chart").html("");
                data = JSON.parse(data);
                if(data.length > 0){ 
                    Morris.Bar({
                        element: 'Chart',
                        data: data,
                        xkey: 'Ejecutivo',
                        ykeys: ['Nota'],
                        labels: ['Nota'],
                        horizontal: true,
                        ymin: 0,
                        ymax: 5,
                        numLines: 6,
                        gridTextSize: 12
                    });
                }
                
            },
            error: function(){
            }
        });
    }
    fillEmpresas();
    function fillEmpresas(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillEmpresas.php",
            data: { },
            dataType: "html",
            success: function(data){
                $("select[name='Empresa']").html(data);
                $("select[name='Empresa']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function fillCedentes(Mandante){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillCedentesEmpresas.php",
            data: { 
                Mandante: Mandante
            },
            dataType: "html",
            success: function(data){
                $("select[name='Cedente']").html(data);
                $("select[name='Cedente']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function fillPeriodos(Mandante,Cedente){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillPeriodosEvaluacionesMandanteCedentes.php",
            data: { 
                Mandante: Mandante,
                Cedente: Cedente
            },
            dataType: "html",
            success: function(data){
                $("select[name='Periodo']").html(data);
                $("select[name='Periodo']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
});