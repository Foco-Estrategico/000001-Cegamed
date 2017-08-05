$(document).ready(function(){
    var DatasetCierres = [];
    var DatasetPlan = [];
    var TableCierres;
    var TablePlan;
    var Ejecutivo;
    getPeriodoCierreEjecutivos();
    getCierreEjecutivos();
    UpdateTableCierres();
    $("body").on("click",".Plan",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        Ejecutivo = ObjectDiv.attr("id");
        var Template = $("#PlanAccionTemplate").html();
        bootbox.dialog({
            title: "PLAN DE ACCIÓN",
            message: Template,
            size: 'large'
        }).off("shown.bs.modal");
        getPlans();
        UpdateTablePlan();
    });
    $("body").on("click","#AddPlan",function(){
        var Template = $("#AddPlanAccionTemplate").html();
        bootbox.dialog({
            title: "PLAN DE ACCIÓN",
            message: Template,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Competencia = $("select[name='Competencia']").val();
                        var Modulo = $("select[name='Modulo']").val();
                        var Topico = $("select[name='Topico']").val();
                        if(Competencia != ""){
                            if(Modulo != ""){
                                if(Topico != ""){
                                    if(canAddPlan(Competencia,Modulo,Topico)){
                                        addPlan();
                                    }else{
                                        bootbox.alert("Plan ya fue agregado anteriormente");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("Debe seleccionar un Topico");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe seleccionar un Modulo");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe seleccionar una Competencia");
                            return false;
                        }
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        getCompetencias();
    });
    $("body").on("change","select[name='Competencia']",function(){
        getModulos();
        $("select[name='Topico']").html("");
        $("select[name='Topico']").selectpicker("refresh");
    });
    $("body").on("change","select[name='Modulo']",function(){
        getTopicos();
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        if(deletePlan(ID)){
            TablePlan.row(ObjectTR).remove().draw();
        }
    });
    $("select[name='Periodos']").change(function(){
        TableCierres.clear();
        TableCierres.destroy();
        getCierreEjecutivos();
        UpdateTableCierres();   
    });
    function UpdateTableCierres(){
        TableCierres = $('#Cierres').DataTable({
            data: DatasetCierres,
            columns: [
                { data: 'Number' },
                { data: 'Ejecutivo' },
                { data: 'AspectosFortalecer' },
                { data: 'AspectosCorregir' },
                { data: 'CompromisoEjecutivo' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 5,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-eye Plan'></i></div>";
                    }
                }
            ]
        });
    }
    function getCierreEjecutivos(){
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getCierreEjecutivos.php",
            dataType: "html",
            data: {
                Month: Periodo
            },
            async: false,
            success: function(data){
                DatasetCierres = JSON.parse(data);
            },
            error: function(data){
                console.log(data);    
            }
        });
    }
    function UpdateTablePlan(){
        TablePlan = $('#TablePlan').DataTable({
            data: DatasetPlan,
            columns: [
                { data: 'Competencia' },
                { data: 'Modulo' },
                { data: 'Topico' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 3,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-times-circle icon-lg Delete'></i></div>";
                    }
                }
            ]
        });
    }
    function getCompetencias(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getCompetencias.php",
            dataType: "html",
            data: {},
            async: false,
            success: function(data){
                $("select[name='Competencia']").html(data);
                $("select[name='Competencia']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getModulos(){
        var Competencia = $("select[name='Competencia']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getModulos.php",
            dataType: "html",
            data: {
                Competencia: Competencia
            },
            async: false,
            success: function(data){
                $("select[name='Modulo']").html(data);
                $("select[name='Modulo']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function getTopicos(){
        var Modulo = $("select[name='Modulo']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTopicos.php",
            dataType: "html",
            data: {
                Modulo: Modulo
            },
            async: false,
            success: function(data){
                $("select[name='Topico']").html(data);
                $("select[name='Topico']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function addPlan(){
        var Competencia = $("select[name='Competencia']").val();
        var Modulo = $("select[name='Modulo']").val();
        var Topico = $("select[name='Topico']").val();
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/addPlan.php",
            dataType: "html",
            data: {
                Competencia: Competencia,
                Modulo: Modulo,
                Topico: Topico,
                Ejecutivo: Ejecutivo,
                Month: Periodo
            },
            async: false,
            success: function(data){
                var json = JSON.parse(data);
                if(json.result){
                    TablePlan.row.add(
                        { 
                            "Competencia": $("select[name='Competencia'] option:selected").text(),
                            "Modulo": $("select[name='Modulo'] option:selected").text(),
                            "Topico": $("select[name='Topico'] option:selected").text(),
                            "Accion": json.id,
                        }
                    ).draw(false);
                    TablePlan.order([0, 'asc']).draw();
                }
            },
            error: function(){
            }
        });
    }
    function getPlans(){
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getPlans.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo,
                Month: Periodo
            },
            async: false,
            success: function(data){
                DatasetPlan = JSON.parse(data);
            },
            error: function(){
            }
        });
    }
    function deletePlan(ID){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "../includes/calidad/deletePlan.php",
            dataType: "html",
            data: {
                ID: ID
            },
            async: false,
            success: function(data){
                var json = JSON.parse(data);
                if(json.result){
                    ToReturn = true;
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function canAddPlan(Competencia,Modulo,Topico){
        var Periodo = $("select[name='Periodos']").val();
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "../includes/calidad/canAddPlan.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo,
                Competencia: Competencia,
                Modulo: Modulo,
                Topico: Topico,
                Month: Periodo
            },
            async: false,
            success: function(data){
                var json = JSON.parse(data);
                if(json.result){
                    ToReturn = true;
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function getPeriodoCierreEjecutivos(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getPeriodoCierreEjecutivos.php",
            dataType: "html",
            data: {},
            async: false,
            success: function(data){
                $("select[name='Periodos']").html(data);
                $("select[name='Periodos']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
});