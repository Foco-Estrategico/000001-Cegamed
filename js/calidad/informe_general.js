$(document).ready(function(){
    getCierresByMonthsAndYears();
    $("#Download").click(function(){
        var Periodo = $("select[name='Periodos']").val();
        if(Periodo != ""){
            DownloadInforme();
        }else{
            bootbox.alert("Debe seleccionar un periodo");
        }
    });
    function getCierresByMonthsAndYears(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getEvaluacionesByMonthsAndYearAndMandante.php",
            dataType: "html",
            data: {},
            success: function(data){
                $("select[name='Periodos']").html(data);
                $("select[name='Periodos']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function DownloadInforme(){
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DownloadInformeGeneral.php",
            dataType: "html",
            data: {
                Month: Periodo
            },
            success: function(data){
                var json = JSON.parse(data);
                var $a = $("<a id='AnclaTemp'>");
                $a.attr("href",json.file);
                $a.attr("download",json.filename+".xlsx");
                $("body").append($a);
                $("#AnclaTemp")[0].click();
                $("#AnclaTemp").remove();
            },
            error: function(){
            }
        });
    }
});