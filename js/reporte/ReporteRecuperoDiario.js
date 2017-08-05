$(document).ready(function(){
    
    getMesesRecupero();
    
    $("body").on("change","select[name='Mes']",function(){
        var Mes = $(this).val();
        $("#RecuperoDiario").html("");
        $("#RecuperoAcumulado").html("");
        getData(Mes);
    });
    function getMesesRecupero(){
         $.ajax({
            type: "POST",
            url: "../includes/reporte/recuperoDiario/getMesesRecuperos.php",
            data: {},
            async: false,
            dataType: "html",
            success: function(data){
                $("select[name='Mes']").html(data);
                $("select[name='Mes']").selectpicker("refresh");
                var Mes = $("select[name='Mes']").val();
                getData(Mes);
            },
            error: function(){
            }
        });
    }
    function getData(Mes){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recuperoDiario/getRecuperosData.php",
            data: {
                Mes: Mes
            },
            async: false,
            dataType: "html",
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    console.log(json);
                    Morris.Bar({
                        element: 'RecuperoDiario',
                        data:json.Diario,
                        xkey: 'Dia',
                        ykeys: ['Monto'],
                        labels: ['Recupero'],
                        gridEnabled: true,
                        barColors: ['#177bbb'],
                        resize:true,
                        hideHover: 'auto'
                    });
                    Morris.Bar({
                        element: 'RecuperoAcumulado',
                        data:json.Acumulado,
                        xkey: 'Dia',
                        ykeys: ['Monto'],
                        labels: ['Recupero'],
                        gridEnabled: true,
                        barColors: ['#177bbb'],
                        resize:true,
                        hideHover: 'auto'
                    });
                }
            },
            error: function(){
            }
        });
    }
});