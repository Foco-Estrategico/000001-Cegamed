$(document).ready(function(){

    $("body").on("click","#GuardarCampo",function(){
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("input[name='Campo']").val();
        var Tipo = $("select[name='TipoCampo']").val();
        saveCampo(Tabla,Campo,Tipo);
    });
    function saveCampo(Tabla,Campo,Tipo){
        $.ajax({
            type: "POST",
            url: "../carga/ajax/agregarCampo.php",
            dataType: "html",
            async: false,
            data: {
                tabla: Tabla,
                nombre: Campo,
                tipo: Tipo
            },
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        bootbox.alert("Campo creado satisfactoriamente");
                        $("select[name='Tabla']").val("");
                        $("input[name='Campo']").val("");
                        $("select[name='TipoCampo']").val("");
                        $(".selectpicker").selectpicker("refresh");
                    }
                }
            },
            error: function(){
            }
        });
    }
});