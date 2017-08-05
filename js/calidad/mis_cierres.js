
audiojs.events.ready(function() {
    audiojs.createAll();
});
$(document).ready(function() {
    var RecordTable;
    var CierreId;
    var EvaluationTable;
    var Ejecutivo = [];
    var CantEvaluations = 0;
    var EvaluationsArray = [];
    var StatusObject;
    var RecordGroups = [];
    var GroupRecordsFlag = false;
    var PrintObject;
    var CarteraObject;

    getCierresByMonthsAndYears();
    PreloadRecordTable();

    
    $("#FiltrarPorFecha").click(function(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        if((startDate != "") && (endDate != "")){
            //FillCarteraList(startDate,endDate);
            FillPersonalList(startDate,endDate,GlobalData.nombre_cedente);
        }
    });

    $("body").on("change","select[name='Cierres']",function(){
        var Month = $(this).val();
        FillPersonalList(Month);
    });

    $("body").on("change","select[name='Ejecutivo']",function(){
        var Val = $(this).val();
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        var Month = $("select[name='Cierres']").val();
        var Cartera = GlobalData.nombre_cedente;
        var IdCedente = GlobalData.id_cedente;
        if(Val != ""){
            Ejecutivo[0] = $(this).find("option:selected").text().toUpperCase();
            Ejecutivo[1] = $(this).val();
            RecordTable.destroy();
            UpdateRecords(Month,IdCedente);
        }
    });
    $('body').on('click','.Visualizar', function(){
        var Template = $("#Calificacion").html();

        CierreId = $(this).attr("id");
        CierreId = CierreId.substr(CierreId.indexOf("_") + 1, CierreId.length);
        bootbox.dialog({
            title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Ejecutivo[0],
            message: Template,
            buttons: {
                confirm: {
                    label: "Salir",
                    callback: function() {
                        
                    }
                }
            },
            size: 'large'
        }).off("shown.bs.modal");
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCierre.php",
            data: { CierreId: CierreId },
            dataType: "html",
            success: function(data){
                var Evaluation = JSON.parse(data);
                Evaluation = Evaluation[0];
                var Id_Evaluaciones = Evaluation.Id_Evaluaciones;
                $.ajax({
                    type: "POST",
                    url: "../includes/calidad/GetEvaluationDetailsCierre.php",
                    data: { Id_Evaluaciones: Id_Evaluaciones },
                    dataType: "html",
                    success: function(data1){
                        EvaluationsArray = JSON.parse(data1);
                        UpdateEvaluations();
                        $("#Evaluations").trigger('update');
                    },
                    error: function(){
                    }
                });
            },
            error: function(){
            }
        });
    });
    $("body").on("click",".close",function(){
        AddClassModalOpen();
    });
    $("body").on("update","#Evaluations",function(){
        UpdateEvaluationSummaryFoot();
    });
    function FillPersonalList(Month){
        $.ajax({
            type: "POST",
            url: "../includes/personal/fillSelectCierres.php",
            dataType: "html",
            data: {
                Month: Month
            },
            success: function(data){
                $("select[name='Ejecutivo'").removeAttr("disabled");
                $("select[name='Ejecutivo']").html(data);
                $("select[name='Ejecutivo']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function UpdateRecords(Month, IdCedente){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCierres.php",
            data: { 
                Ejecutivo: $("select[name='Ejecutivo']").val(),
                IdCedente: IdCedente,
                Month: Month
            },
            dataType: "html",
            success: function(data){
                console.log(data);
                var dataSet = JSON.parse(data);
                var CantRecords = dataSet.length;
                RecordGroups = [];
                for(var i in dataSet){
                    var ID = dataSet[i].Imprimir;
                    RecordGroups[ID] = false;
                }
                RecordTable = $('#Cierres').DataTable({
                    data: dataSet,
                    columns: [
                        { data: 'NotaPeriodo' },
                        { data: 'PerfilEjecutivo' },
                        { data: 'TipoCierre' },
                        { data: 'Date' },
                        { data: 'Visualizar' },
                        { data: 'Imprimir' }
                    ],
                    "columnDefs": [ 
                        {
                            "targets": 2,
                            "data": 'TipoCierre',
                            "render": function( data, type, row ) {
                                var ToReturn = "";
                                switch(data){
                                    case '0':
                                        ToReturn = "Semanal";
                                    break;
                                    case '1':
                                        ToReturn = "Mensual";
                                    break;
                                }
                                return ToReturn;
                            }
                        },
                        {
                            "targets": 4,
                            "data": 'Evaluar',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;'><i style='cursor: pointer;' id='Cierre_"+data+"' class='fa fa-pencil Visualizar'></i></div>";
                            }
                        },
                        {
                            "targets": 5,
                            "data": 'Imprimir',
                            "render": function( data, type, row ) {
                                var ToReturn = "";
                                if(row.Status != ""){
                                    ToReturn = "<div style='text-align: center;'><a href='CierreResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Cierre_"+data+"' class='fa fa-print Print'></i></a></div>";
                                }
                                return ToReturn;
                            }
                        }
                    ]
                });
            },
            error: function(){
            }
        });
    }
    function PreloadRecordTable(){
        var dataSet = [];
        RecordTable = $('#Cierres').DataTable({
            data: dataSet,
            columns: [
                { data: 'NotaPeriodo' },
                { data: 'PerfilEjecutivo' },
                { data: 'TipoCierre' },
                { data: 'Date' },
                { data: 'Visualizar' },
                { data: 'Imprimir' }
            ],
            "columnDefs": [ 
                {
                    "targets": 4,
                    "data": 'Evaluar',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'><i style='cursor: pointer;' id='Cierre_"+data+"' class='fa fa-pencil Visualizar'></i></div>";
                    }
                },
                {
                    "targets": 5,
                    "data": 'Imprimir',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.Status != ""){
                            ToReturn = "<div style='text-align: center;'><a href='CierreResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Cierre_"+data+"' class='fa fa-print Print'></i></a></div>";
                        }
                        return ToReturn;
                    }
                }
            ]
        });
    }
    function UpdateEvaluations(){
        CantEvaluations = 0;
        EvaluationTable = $('#Evaluations').DataTable({
            data: EvaluationsArray,
            paging: false,
            iDisplayLength: 100,
            columns: [
                { data: 'Nombre_Grabacion' },
                { data: 'Grabacion' },
                { data: 'Nota' }, 
            ],
            "columnDefs": [ 
                {
                    "targets": 1,
                    "data": 'Grabacion',
                    "render": function( data, type, row ) {
                        return "<audio src='"+data+"' preload='auto' controls></audio>";
                    }
                },
            ]
        });
    }
    function CustomAlert(Message){
        bootbox.alert(Message,function(){
            AddClassModalOpen();
        });
    }
    function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
    }
    function getCierresByMonthsAndYears(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getCierresByMonthsAndYears.php",
            dataType: "html",
            data: {},
            success: function(data){
                $("select[name='Cierres']").html(data);
                $("select[name='Cierres']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function UpdateEvaluationSummaryFoot(){
        var ContEvaluaciones = 0;
        var SumNotas = 0;
        $("#Evaluations tbody tr").each(function(indexTR){
            ContEvaluaciones++;
            $(this).find("td").each(function(indexTD){
                switch(indexTD){
                    case 2:
                        SumNotas += Number($(this).text());
                    break;
                }
            });
        });
        $("#PromNota").html((SumNotas / (ContEvaluaciones - 1)).toFixed(2));
        AddClassModalOpen();
    }
});