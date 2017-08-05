$(document).ready(function(){
    $("#Download").click(function(){
        descargarAsignacion();
    });
    function descargarAsignacion(){
        $('#Cargando').modal({
            backdrop: 'static',
            keyboard: false
        })
        $.ajax({
            type: "POST",
            url: "../includes/tareas/descargarAsignacion.php",
            data:{},
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
                var json = JSON.parse(response);
                console.log(json);
                $("body").append("<a id='Tmp'>aaa</a>");
                var a = $("a#Tmp");
                a.attr("href",json.file);
                a.attr("download",json.fileName+".xlsx");
                $("a#Tmp")[0].click();
                $("a#Tmp").remove();
            }	
        });
    }
});