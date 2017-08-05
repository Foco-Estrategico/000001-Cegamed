$(document).ready(function(){
    var RecuperoTable;
    getgetCedentes();
    getCarteraField();
    getTramoField();
    getPeriodosMandante();
    getReportData();

    $("select[name='PeriodoMandante']").change(function(){
        RecuperoTable.clear().draw();
        RecuperoTable.destroy();
        getReportData();
    });
    $("select[name='Cedente']").change(function(){
        RecuperoTable.clear().draw();
        RecuperoTable.destroy();
        getReportData();
    });
    $("select[name='Cartera']").change(function(){
        RecuperoTable.clear().draw();
        RecuperoTable.destroy();
        getReportData();
    });
    $("select[name='Tramo']").change(function(){
        RecuperoTable.clear().draw();
        RecuperoTable.destroy();
        getReportData();
    });
    function getgetCedentes(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getCedentes.php",
            dataType: "html",
            data: {
            },
            async: false,
            success: function(data){
                $("select[name='Cedente']").html(data);
                $("select[name='Cedente']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getCarteraField(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getCarteraField.php",
            dataType: "html",
            data: {
            },
            async: false,
            success: function(data){
                $("select[name='Cartera']").html(data);
                $("select[name='Cartera']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getTramoField(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getTramoField.php",
            dataType: "html",
            data: {
            },
            async: false,
            success: function(data){
                $("select[name='Tramo']").html(data);
                $("select[name='Tramo']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getPeriodosMandante(){
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getPeriodosMandante.php",
            dataType: "html",
            data: {
            },
            async: false,
            success: function(data){
                $("select[name='PeriodoMandante']").html(data);
                $("select[name='PeriodoMandante']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getReportData(){
        var idCedente = $("select[name='Cedente']").val();
        var idPeriodo = $("select[name='PeriodoMandante']").val();
        var Cartera = $("select[name='Cartera']").val();
        var Tramo = $("select[name='Tramo']").val();
        $.ajax({
            type: "POST",
            url: "../includes/reporte/recupero/getReportData.php",
            dataType: "html",
            data: {
                idCedente: idCedente,
                idPeriodo: idPeriodo,
                Cartera: Cartera,
                Tramo: Tramo
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(data){
                $('#Cargando').modal('hide');
                console.log(data);
                var Dataset = JSON.parse(data);
                RecuperoTable = $('#Recuperos').DataTable({
                    data: Dataset,
                    columns: [
                        { data: 'Cont', width: '1%' },
                        { data: 'Estatus', width: '20%' },
                        { data: 'CasosDeuda' },
                        { data: 'SumDeuda' },
                        { data: 'SumRecupero' },
                        { data: 'CasosRecupero' }, 
                        { data: 'MontoRecuperoAvg' },
                        { data: 'CasosRecuperoAvg' }
                    ],
                    "columnDefs": [ 
                        {
                            "targets": 0,
                            className: "text-center"
                        },
                        {
                            "targets": 1,
                        },
                        {
                            "targets": 2,
                            className: "text-center"
                        },
                        {
                            "targets": 3,
                            "data": 'SumDeuda',
                            className: "text-right",
                            "render": function( data, type, row ) {
                                return formatDollar(data);
                            }
                        },
                        {
                            "targets": 4,
                            "data": 'SumRecupero',
                            className: "text-right",
                            "render": function( data, type, row ) {
                                return formatDollar(data);
                            }
                        },
                        {
                            "targets": 5,
                            className: "text-center"
                        },
                        {
                            "targets": 6,
                            className: "text-center"
                        },
                        {
                            "targets": 7,
                            className: "text-center"
                        },
                    ]
                });
            },
            error: function(){
            }
        });
        UpdateEvaluationSummaryFoot();
    }
    function UpdateEvaluationSummaryFoot(){
        var CasosDeuda = 0;
        var SumDeuda = 0;
        var SumRecupero = 0;
        var CasosRecupero = 0;
        var MontoRecuperoAvg = 0;
        var CasosRecuperoAvg = 0;
        
        /*$("#SumPonderacion").html(SumPonderacion.toFixed(2));
        $("#PromNota").html((SumNotas / ContEvaluaciones).toFixed(2));
        $("#PromCalPonderada").html(SumCalPonderada.toFixed(2));*/
        RecuperoTable.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            CasosDeuda += data.CasosDeuda;
            SumDeuda += data.SumDeuda;
            SumRecupero += data.SumRecupero;
            CasosRecupero += data.CasosRecupero;
            MontoRecuperoAvg += data.MontoRecuperoAvg;
            CasosRecuperoAvg += data.CasosRecuperoAvg;
        });
        $("#CasosDeuda").html(CasosDeuda.toFixed(2));
        $("#SumDeuda").html(formatDollar(SumDeuda));
        $("#SumRecupero").html(formatDollar(SumRecupero));
        $("#CasosRecupero").html(CasosRecupero.toFixed(2));
        $("#MontoRecuperoAvg").html(MontoRecuperoAvg.toFixed(2));
        $("#CasosRecuperoAvg").html(CasosRecuperoAvg.toFixed(2));
    }
});