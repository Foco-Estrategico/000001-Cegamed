$(document).ready(function(){
    var PreguntasArray = [];
    $(".BoxSelection").click(function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectTable = ObjectTR.closest("table");
        var Side = ObjectMe.attr("side");
        ObjectTR.find(".BoxSelection").removeClass("Selected");
        ObjectTable.find(".BoxSelection[side='"+Side+"']").removeClass("Selected");
        $(this).addClass("Selected");
    });

    $("#Calificar").click(function(){
        fillArrayData();
        if(PreguntasArray.length < $(".Preguntas .Pregunta").size()){
            bootbox.alert("No ha llenado todas las preguntas verifique y complete las preguntas marcadas en color rojo");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "ajax/Calificar.php",
            dataType: "html",
            data: {
                Preguntas: PreguntasArray
            },
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data){
                $('#Cargando').modal('hide');
                bootbox.alert("Gracias por Participar en nuestro proceso de reclutamiento web, nos pondremos en contacto con usted", function(){
                    location.reload();
                });
            },
            error: function(){
            }
        });
    });
    function fillArrayData(){
        while (PreguntasArray.length > 0) {
            PreguntasArray.pop();
        }
        $(".Preguntas .Pregunta").each(function(indexPregunta){
            var ObjectMe = $(this);
            var NumeroPregunta = ObjectMe.find(".NumeroPregunta").html();
            var ArrayPregunta = [];
            if(ObjectMe.find(".BoxSelection.Selected").size() == 2){
                ObjectMe.removeClass("Error");
                ObjectMe.find(".BoxSelection.Selected").each(function(indexOption){
                    var ArrayTmp = [];
                    var ObjectOption = $(this);
                    var ObjectTR = ObjectOption.closest("tr");
                    var OptionID = ObjectTR.attr("id");
                    OptionID = OptionID.split("_");
                    OptionID = OptionID[1];
                    var Side = ObjectOption.attr("side");
                    var value = 0;
                    switch(Side){
                        case 'Left':
                            Value = 1;
                        break;
                        case 'Right':
                            Value = -1;
                        break;
                    }
                    ArrayTmp[0] = OptionID;
                    ArrayTmp[1] = Value;
                    ArrayPregunta.push(ArrayTmp);
                });
                PreguntasArray.push(ArrayPregunta);
            }else{
                ObjectMe.addClass("Error");
            }
        });
        console.log(PreguntasArray);
    }
    function isset(object){
        return (typeof object !=='undefined');
    }
});