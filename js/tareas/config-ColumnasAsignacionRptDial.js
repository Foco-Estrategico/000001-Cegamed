$(document).ready(function(){
    var ColumnasTable;
    var dataSet = [];
    getColumnas();
    UpdateTable();
    $("#AddColumna").click(function(){
        var Template = $("#AddColumnaTemplate").html();
        bootbox.dialog({
            title: "AGERGAR NUEVA COLUMNA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Registrar",
                    callback: function() {
                        var Nombre = $("#Nombre").val();
                        var TipoCampo = $("select[name='TipoCampo']").val();
                        var Tabla = $("select[name='Tabla']").val();
                        var Campo = $("select[name='Campo']").val();
                        var Operacion = $("select[name='Operacion']").val();                        
                        var CanInsert = true;
                        if(Nombre != ""){
                            if(TipoCampo != ""){
                                switch(TipoCampo){
                                    case '1':
                                        if(Tabla != ""){
                                            if(Campo != ""){
                                                CanInsert = true;
                                            }else{
                                                bootbox.alert("Debe seleccionar un Campo");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe seleccionar una Tabla");
                                            return false;
                                        }
                                    break;
                                    case '2':
                                        if(Campo != ""){
                                            CanInsert = true;
                                        }else{
                                            bootbox.alert("Debe seleccionar un Campo");
                                            return false;
                                        }
                                    break;
                                }
                            }else{
                                bootbox.alert("Debe seleccionar un Tipo de Campo");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe indicar un Título para el nombre de la Columna");
                            return false;
                        }
                        if(CanInsert){
                            var Result = addColumna();
                        }                 
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
    });
    $("body").on("click",".Update",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var Data = getColumnaData(ID);
        var Template = $("#UpdateColumnaTemplate").html();
        bootbox.dialog({
            title: "ACTUALIZAR COLUMNA",
            message: Template,
            buttons: {
                confirm: {
                    label: "Actualizar",
                    callback: function() {
                        var Nombre = $("#Nombre").val();
                        var TipoCampo = $("select[name='TipoCampo']").val();
                        var Tabla = $("select[name='Tabla']").val();
                        var Campo = $("select[name='Campo']").val();
                        var Operacion = $("select[name='Operacion']").val();                        
                        var CanUpdate = true;
                        if(Nombre != ""){
                            if(TipoCampo != ""){
                                switch(TipoCampo){
                                    case '1':
                                        if(Tabla != ""){
                                            if(Campo != ""){
                                                CanUpdate = true;
                                            }else{
                                                bootbox.alert("Debe seleccionar un Campo");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe seleccionar una Tabla");
                                            return false;
                                        }
                                    break;
                                    case '2':
                                        if(Campo != ""){
                                            CanUpdate = true;
                                        }else{
                                            bootbox.alert("Debe seleccionar un Campo");
                                            return false;
                                        }
                                    break;
                                }
                            }else{
                                bootbox.alert("Debe seleccionar un Tipo de Campo");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe indicar un Título para el nombre de la Columna");
                            return false;
                        }
                        if(CanUpdate){
                            var Result = updateColumna(ID);
                        }                  
                    }
                }
            }
        }).off("shown.bs.modal");
        $(".selectpicker").selectpicker("refresh");
        var Nombre = Data.data.Nombre;
        var TipoCampo = Data.data.TipoCampo;
        var Tabla = Data.data.Tabla;
        var Campo = Data.data.Campo;
        var Operacion = Data.data.Operacion;
        var id = Data.data.id;
        $("#Nombre").val(Nombre);
        $("select[name='TipoCampo']").val(TipoCampo);
        $(".selectpicker").selectpicker("refresh");
        switch(TipoCampo){
            case '1':
                $("#TablaContainer").removeClass("Hidden");
                $("#OperacionContainer").removeClass("Hidden");
                getTablas();
                $("select[name='Tabla']").val(Tabla);
                $(".selectpicker").selectpicker("refresh");
                getCampos();
                $("select[name='Campo']").val(Campo);
                $("select[name='Operacion']").val(Operacion);
                $(".selectpicker").selectpicker("refresh");
            break;
            case '2':
                $("#TablaContainer").addClass("Hidden");
                $("#OperacionContainer").addClass("Hidden");
                getCampos();
                $("select[name='Campo']").val(Campo);
                $(".selectpicker").selectpicker("refresh");
            break;
        }
    });
    $("body").on("change","select[name='TipoCampo']",function(){
        var TipoCampo = $(this).val();
        $("#FieldsContainer").addClass("Hidden");
        $("select[name='Tabla']").val("");
        $("select[name='Campo']").val("");
        $("select[name='Operacion']").val("");
        $(".selectpicker").selectpicker("refresh");
        switch(TipoCampo){
            case '1':
                $("#TablaContainer").removeClass("Hidden");
                $("#OperacionContainer").removeClass("Hidden");
                getTablas();
            break;
            case '2':
                $("#TablaContainer").addClass("Hidden");
                $("#OperacionContainer").addClass("Hidden");
                getCampos();
            break;
        }
        $("#FieldsContainer").removeClass("Hidden");
    });
    $("body").on("change","select[name='Tabla']",function(){
        var Tabla = $(this).val();
        getCampos();
    });
    $("body").on("dblclick",".PrioridadColumn",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.html();
        var html = "<input type='text' value='"+Value+"' class='Prioridad' style='background-color: #579ddb;color:  #FFFFFF;position: absolute;left: 0;width: 100%;text-align: center;' />";
        ObjectMe.append(html);
        $(".Prioridad").focus();
        $(".Prioridad").select();
        $(".Prioridad").focus();
    });
    $("body").on("dblclick",".Prioridad",function(){
        return false;
    });
    $("body").on("keyup",".Prioridad",function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });
    $("body").on("blur",".Prioridad",function(){
        $(this).remove();
    });
    $("body").on("change",".Prioridad",function(){
        var ObjectMe = $(this);
        var Value = ObjectMe.val();
        var ObjectTD = ObjectMe.closest("td");
        var ObjectTR = ObjectMe.closest("tr");
        var Row = ColumnasTable.row(ObjectTR).data();
        var cell = ColumnasTable.cell( ObjectTD );
        var ID = Row.Accion;
        if(Value != ""){
            cell.data(Value);
            SavePrioridad(Value,ID);
        }
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var TableRow = ObjectMe.closest("tr");
        var Delete = DeleteColumna(ID);
        if(Delete){
            ColumnasTable.row(TableRow).remove().draw();
        }
    });
    function UpdateTable(){
        ColumnasTable = $('#Columnas').DataTable({
            data: dataSet,
            columns: [
                { data: 'Prioridad',"width": "10%" },
                { data: 'Titulo',"width": "30%" },
                { data: 'TipoCampo',"width": "10%" },
                { data: 'Tabla',"width": "15%" },
                { data: 'Campo',"width": "15%" },
                { data: 'Operacion',"width": "10%" },
                { data: 'Accion',"width": "10%" }
            ],
            "columnDefs": [ 
                {
                    "targets": 0,
                    className: "PrioridadColumn",
                    "data": 'Prioridad',
                },
                {
                    "targets": 6,
                    "data": 'Actions',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-times-circle icon-lg Delete'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil Update'></div>";
                    }
                },
            ]
        });
        ColumnasTable.order([0, 'asc']).draw();
    }
    function getColumnas(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColumnasAsignacion.php",
            data: {},
            async: false,
            success: function(data){
                dataSet = JSON.parse(data);
            },
            error: function(){

            }
        });
    }
    function getTablas(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getTablas.php",
            data: {},
            async: false,
            success: function(data){
                $("select[name='Tabla']").html(data);
                $("select[name='Tabla']").selectpicker("refresh");
                $("select[name='Campo']").html("");
                $("select[name='Campo']").selectpicker("refresh");
            },
            error: function(){

            }
        });
    }
    function getCampos(){
        var TipoCampo = $("select[name='TipoCampo']").val();
        var Tabla = $("select[name='Tabla']").val();
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getCampos.php",
            data: {
                TipoCampo: TipoCampo,
                Tabla: Tabla
            },
            async: false,
            success: function(data){
                console.log(data);
                $("select[name='Campo']").html(data);
                $("select[name='Campo']").selectpicker("refresh");
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    function addColumna(){
        var Nombre = $("#Nombre").val();
        var TipoCampo = $("select[name='TipoCampo']").val();
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("select[name='Campo']").val();
        var Operacion = $("select[name='Operacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/tareas/addColumnasignacion.php",
            data: {
                Nombre: Nombre,
                TipoCampo: TipoCampo,
                Tabla: Tabla,
                Campo: Campo,
                Operacion: Operacion
            },
            async: false,
            success: function(data){
                var json = JSON.parse(data);
                if(json.result){
                    location.reload();
                }
            },
            error: function(){

            }
        });
    }
    function SavePrioridad(Value,ID){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/updatePrioridad.php",
            data: {
                Value: Value,
                ID: ID,
            },
            async: false,
            success: function(data){
                //alert(data);
            },
            error: function(){

            }
        });
    }
    function DeleteColumna(ID){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "../includes/tareas/deleteColumna.php",
            data: {
                ID: ID,
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
    function getColumnaData(ID){
        var json;
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColumnaData.php",
            data: {
                ID: ID,
            },
            async: false,
            success: function(data){
                json = JSON.parse(data);
            },
            error: function(){

            }
        });
        return json;
    }
    function updateColumna(ID){
        var Nombre = $("#Nombre").val();
        var TipoCampo = $("select[name='TipoCampo']").val();
        var Tabla = $("select[name='Tabla']").val();
        var Campo = $("select[name='Campo']").val();
        var Operacion = $("select[name='Operacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/tareas/updateColumnasignacion.php",
            data: {
                ID: ID,
                Nombre: Nombre,
                TipoCampo: TipoCampo,
                Tabla: Tabla,
                Campo: Campo,
                Operacion: Operacion
            },
            async: false,
            success: function(data){
                var json = JSON.parse(data);
                if(json.result){
                    location.reload();
                }
            },
            error: function(){

            }
        });
    }
});