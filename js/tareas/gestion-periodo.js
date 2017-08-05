$(document).ready(function(){
    getCedentesMandante();

    $("#Download").click(function(){
        var startDate = $("input[name='start']").val();
        var endDate = $("input[name='end']").val();
        var Cedente = $("select[name='Cedente']").val();
        if(startDate != ""){
            if(endDate != ""){
                if(Cedente != ""){
                    descargarGestiones();
                }else{
                    bootbox.alert("Debe seleccionar un cedente");
                }
            }else{
                bootbox.alert("Debe seleccionar un rango de fecha valido");
            }
        }else{
            bootbox.alert("Debe seleccionar un rango de fecha valido");
        }
    });

    function getCedentesMandante(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getCedentesMandante.php",
            data:{
            },
            async: false,
            success: function(response){
                $("select[name='Cedente']").html(response);
                $("select[name='Cedente']").selectpicker("refresh");
            }	
        });
    }
    function descargarGestiones(){
        var startDateVal = $("input[name='start']").val();
        var endDateVal = $("input[name='end']").val();
        var CedenteVal = $("select[name='Cedente']").val();

        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        })
        $.ajax({
            type: "POST",
            url: "../includes/tareas/descargarGestiones.php",
            data:{
                startDate: startDateVal,
                endDate: endDateVal,
                Cedente: CedenteVal    
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                var json = JSON.parse(response);
                console.log(json);
                $("body").append("<a id='Tmp'>aaa</a>");
                var a = $("a#Tmp");
                a.attr("href",json.file);
                a.attr("download","Gestiones "+startDateVal+" - "+endDateVal+".xlsx");
                $("a#Tmp")[0].click();
                $("a#Tmp").remove();
            }	
        });
    }
});