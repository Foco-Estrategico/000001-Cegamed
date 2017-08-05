$(document).ready(function(){

    var TableCompetencias;
    var CompetenciasDataSet;

    var TableDimensiones;
    var DimensionesDataSet;

    var TableAfirmaciones;
    var AfirmacionesDataSet;

    var TableOpcionesAfirmaciones;
    var OpcionesAfirmacionesDataSet;
    
    getCompetencias();
    UpdateTableCompetencias();
    
    $("body").on("click",".addDimensiones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        var Template = $("#TemplateDimensiones").html();
        Template = Template.replace("{COMPETENCIA}",idCompetencia);
        bootbox.dialog({
            title: "TABLA DE DIMENSIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        getDimensiones(idCompetencia);
        UpdateTableDimensiones();
    });
    $("body").on("click",".addAfirmaciones",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var IDArray = ID.split("_");
        var idCompetencia = IDArray[0];
        var idDimension = IDArray[1];
        var Template = $("#TemplateAfirmaciones").html();
        Template = Template.replace("{DIMENSION}",idDimension);
        bootbox.dialog({
            title: "TABLA DE AFIRMACIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: "large"
        });
        getAfirmaciones(idDimension);
        UpdateTableAfirmaciones();
    });
    $("body").on("click",".addOpcion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var IDArray = ID.split("_");
        var idDimension = IDArray[0];
        var idAfirmacion = IDArray[1];
        var Template = $("#TemplateOpcionesAfirmaciones").html();
        Template = Template.replace("{AFIRMACION}",idAfirmacion);
        bootbox.dialog({
            title: "TABLA DE OPCIONES",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            },
            size: "large"
        });
        getOpcionesAfirmaciones(idAfirmacion);
        UpdateTableOpcionesAfirmaciones();
    });
    $("body").on("click","#addCompetencia",function(){
        var CanSave = CanSavePonderacion(TableCompetencias);
        if(CanSave){
            var Template = $("#TemplateAddCompetencia").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA COMPETENCIA",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='NombreCompetencia']").val();
                            var Descripcion = $("input[name='DescripcionCompetencia']").val();
                            var Ponderacion = $("input[name='PonderacionCompetencia']").val();
                            var Tag = $("input[name='TagCompetencia']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableCompetencias,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        if(Descripcion != ""){
                                            if(Tag != ""){
                                                SaveCompetencia();
                                            }else{
                                                bootbox.alert("Debe ingresar el Tag");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar una descripcción");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });
        }else{
            bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeCompetencia",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        bootbox.confirm({
            message: "¿Esta seguro de borrar la competencia seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteCompetencia(idCompetencia);
                }
            }
        });
    });
    $("body").on("click",".updateCompetencia",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idCompetencia = ObjectDiv.attr("id");
        var Template = $("#TemplateAddCompetencia").html();
        var PonderacionAnterior = 0;
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DE COMPETENCIA",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='NombreCompetencia']").val();
                        var Descripcion = $("input[name='DescripcionCompetencia']").val();
                        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
                        var Tag = $("input[name='TagCompetencia']").val();
                        if(Ponderacion != ""){
                            CanUpdate = CanUpdatePonderacion(TableCompetencias,Ponderacion,PonderacionAnterior);
                            if(CanUpdate){
                                if(Nombre != ""){
                                    if(Descripcion != ""){
                                        if(Tag != ""){
                                            UpdateCompetencia(idCompetencia);
                                        }else{
                                            bootbox.alert("Debe ingresar el Tag");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripcción");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible actualizar la competencia debido a que la sumatoria de las competencias suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Competencia = GetCompetencia(idCompetencia);
        $("input[name='NombreCompetencia']").val(Competencia.Nombre);
        $("input[name='DescripcionCompetencia']").val(Competencia.Descripcion);
        $("input[name='PonderacionCompetencia']").val(Competencia.Ponderacion);
        $("input[name='TagCompetencia']").val(Competencia.Tag);
        PonderacionAnterior = Competencia.Ponderacion;
    });


    $("body").on("click","#addDimension",function(){
        var idCompetencia = $(this).attr("competencia");
        var CanSave = CanSavePonderacion(TableDimensiones);
        if(CanSave){
            var Template = $("#TemplateAddDimension").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA DIMENSION",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='Nombre']").val();
                            var Ponderacion = $("input[name='Ponderacion']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableDimensiones,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        SaveDimension(idCompetencia);
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva dimension debido a que ya la sumatoria de las dimensiones suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });

        }else{
            bootbox.alert("No es posible agregar nueva competencia debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeDimension",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idDimension = ID[1];
        var idCompetencia = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Dimension seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteDimension(idDimension,idCompetencia);
                }
            }
        });
    });
    $("body").on("click",".updateDimension",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idDimension = ID[1];
        var idCompetencia = ID[0];
        var Template = $("#TemplateAddDimension").html();
        var PonderacionAnterior = 0;
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACION DE DIMENSION",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Ponderacion = $("input[name='Ponderacion']").val();
                        if(Ponderacion != ""){
                            CanSave = CanUpdatePonderacion(TableDimensiones,Ponderacion,PonderacionAnterior);
                            if(CanSave){
                                if(Nombre != ""){
                                    //SaveDimension(idCompetencia);
                                    UpdateDimension(idDimension,idCompetencia);
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible agregar nueva dimension debido a que ya la sumatoria de las dimensiones suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Dimension = GetDimension(idDimension);
        $("input[name='Nombre']").val(Dimension.Nombre);
        $("input[name='Ponderacion']").val(Dimension.Ponderacion);
        PonderacionAnterior = Dimension.Ponderacion;
    });
    $("body").on("click","#addAfirmacion",function(){
        var idDimension = $(this).attr("dimension");
        var CanSave = CanSavePonderacion(TableAfirmaciones);
        if(CanSave){
            var Template = $("#TemplateAddAfirmacion").html();
            bootbox.dialog({
                title: "FORMULARIO DE NUEVA AFIRMACIÓN",
                message: Template,
                closeButton: false,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            var Nombre = $("input[name='Nombre']").val();
                            var Ponderacion = $("input[name='Ponderacion']").val();
                            var DescripcionSimple = $("input[name='DescripcionSimple']").val();
                            var Corte = $("input[name='Corte']").val();
                            if(Ponderacion != ""){
                                CanSave = CanSavePonderacion(TableAfirmaciones,Ponderacion);
                                if(CanSave){
                                    if(Nombre != ""){
                                        if(DescripcionSimple != ""){
                                            if(Corte != ""){
                                                SaveAfirmacion(idDimension);
                                            }else{
                                                bootbox.alert("Debe ingresar un valor de corte");
                                                return false;
                                            }
                                        }else{
                                            bootbox.alert("Debe ingresar una descripción simple");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar un nombre");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las afirmaciones suma mas de 100%");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar una ponderación");
                                return false;
                            }
                        }
                    },
                    cancel: {
                        label: "Cancelar",
                        className: "btn-danger",
                        callback: function() {
                        }
                    }
                }
            });
        }else{
            bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las competencias suma 100%");
        }
    });
    $("body").on("click",".removeAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idAfirmacion = ID[1];
        var idDimension = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Afirmacion seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteAfirmacion(idAfirmacion,idDimension);
                }
            }
        });
    });
    $("body").on("click",".updateAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idAfirmacion = ID[1];
        var idDimension = ID[0];
        var PonderacionAnterior = 0;
        var Template = $("#TemplateAddAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE ACTUALIZACIÓN DE AFIRMACIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Ponderacion = $("input[name='Ponderacion']").val();
                        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
                        var Corte = $("input[name='Corte']").val();
                        if(Ponderacion != ""){
                            CanUpdate = CanUpdatePonderacion(TableAfirmaciones,Ponderacion,PonderacionAnterior);
                            if(CanUpdate){
                                if(Nombre != ""){
                                    if(DescripcionSimple != ""){
                                        if(Corte != ""){
                                            UpdateAfirmacion(idAfirmacion,idDimension);
                                        }else{
                                            bootbox.alert("Debe ingresar un valor de corte");
                                            return false;
                                        }
                                    }else{
                                        bootbox.alert("Debe ingresar una descripción simple");
                                        return false;
                                    }
                                }else{
                                    bootbox.alert("Debe ingresar un nombre");
                                    return false;
                                }
                            }else{
                                bootbox.alert("No es posible agregar nueva afirmacion debido a que ya la sumatoria de las afirmaciones suma mas de 100%");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar una ponderación");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var Afirmacion = GetAfirmacion(idAfirmacion);
        $("input[name='Nombre']").val(Afirmacion.Nombre);
        $("input[name='Ponderacion']").val(Afirmacion.Ponderacion);
        $("input[name='DescripcionSimple']").val(Afirmacion.DescripcionSimple);
        $("input[name='Corte']").val(Afirmacion.Corte);
        PonderacionAnterior = Afirmacion.Ponderacion;
    });
    $("body").on("click","#addOpcionAfirmacion",function(){
        var idAfirmacion = $(this).attr("afirmacion");
        var Template = $("#TemplateAddOpcionAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE NUEVA OPCIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Valor = $("input[name='Valor']").val();
                        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
                        if(Nombre != ""){
                            if((Valor != "") && (Valor > 0)){
                                if(DescripcionCaracteristica != ""){
                                    SaveOpcionAfirmacion(idAfirmacion);
                                }else{
                                    bootbox.alert("Debe ingresar una descripción característica");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar un valor valido");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Nombre");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
    });
    $("body").on("click",".removeOpcionAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idOpcionAfirmacion = ID[1];
        var idAfirmacion = ID[0];
        bootbox.confirm({
            message: "¿Esta seguro de borrar la Opcion seleccionada?",
            buttons:{
                confirm:{
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel:{
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function(result){
                if(result){
                    DeleteOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion);
                }
            }
        });
    });
    $("body").on("click",".updateOpcionAfirmacion",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        ID = ID.split("_");
        var idOpcionAfirmacion = ID[1];
        var idAfirmacion = ID[0];
        var Template = $("#TemplateAddOpcionAfirmacion").html();
        bootbox.dialog({
            title: "FORMULARIO DE NUEVA OPCIÓN",
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        var Nombre = $("input[name='Nombre']").val();
                        var Valor = $("input[name='Valor']").val();
                        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
                        if(Nombre != ""){
                            if((Valor != "") && (Valor > 0)){
                                if(DescripcionCaracteristica != ""){
                                    UpdateOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion);
                                }else{
                                    bootbox.alert("Debe ingresar una descripción característica");
                                    return false;
                                }
                            }else{
                                bootbox.alert("Debe ingresar un valor valido");
                                return false;
                            }
                        }else{
                            bootbox.alert("Debe ingresar un Nombre");
                            return false;
                        }
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                    }
                }
            }
        });
        var OpcionAfirmacion = GetOpcionAfirmacion(idOpcionAfirmacion);
        $("input[name='Nombre']").val(OpcionAfirmacion.Nombre);
        $("input[name='Valor']").val(OpcionAfirmacion.Valor);
        $("input[name='DescripcionCaracteristica']").val(OpcionAfirmacion.DescripcionCaracteristica);
        console.log(OpcionAfirmacion);
    });
    
    function UpdateTableCompetencias(){
        TableCompetencias = $('#Competencias').DataTable({
            data: CompetenciasDataSet,
            columns: [
                { data: 'Nombre' },
                { data: 'Ponderacion' },
                { data: 'ID' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil updateCompetencia'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-plus addDimensiones'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-remove removeCompetencia'></i></div>";
                    }
                }
            ]
        });
    }
    function getCompetencias(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCompetenciasMantenedor.php",
            dataType: "html",
            data: {},
            async: false,
            success: function(data){
                if(isJson(data)){
                    CompetenciasDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableDimensiones(){
        TableDimensiones = $('#Dimensiones').DataTable({
            data: DimensionesDataSet,
            columns: [
                { data: 'Nombre' },
                { data: 'Ponderacion' },
                { data: 'ID' }
            ],
            "columnDefs": [
                {
                    "targets": 2,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil updateDimension'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-plus addAfirmaciones'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-remove removeDimension'></i></div>";
                    }
                }
            ]
        });
    }
    function getDimensiones(idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetDimensiones.php",
            dataType: "html",
            data: {
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    DimensionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableAfirmaciones(){
        TableAfirmaciones = $('#Afirmaciones').DataTable({
            data: AfirmacionesDataSet,
            columns: [
                { data: 'Nombre',width: "40%" },
                { data: 'Ponderacion',width: "10%" },
                { data: 'DescripcionSimple',width: "20%" },
                { data: 'Corte',width: "10%" },
                { data: 'ID',width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 4,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil updateAfirmacion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-plus addOpcion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-remove removeAfirmacion'></i></div>";
                    }
                }
            ]
        });
    }
    function getAfirmaciones(idDimension){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetAfirmaciones.php",
            dataType: "html",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    AfirmacionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function UpdateTableOpcionesAfirmaciones(){
        TableOpcionesAfirmaciones = $('#Opciones').DataTable({
            data: OpcionesAfirmacionesDataSet,
            columns: [
                { data: 'Nombre', width: "40%" },
                { data: 'Valor', width: "10%" },
                { data: 'DescripcionCaracteristica', width: "40%" },
                { data: 'ID', width: "10%" }
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 5px;' class='fa fa-pencil updateOpcionAfirmacion'></i><i style='cursor: pointer; margin: 0 5px;' class='fa fa-remove removeOpcionAfirmacion'></i></div>";
                    }
                }
            ]
        });
    }
    function getOpcionesAfirmaciones(idAfirmacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetOpcionesAfirmaciones.php",
            dataType: "html",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    OpcionesAfirmacionesDataSet = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
    }
    function CanSavePonderacion(Table,ValPonderacion){
        var ToReturn = true;
        var SumPonderacion = 0;
        Table.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            SumPonderacion += Number(data.Ponderacion);
        });
        if(typeof ValPonderacion === "undefined"){ //Si no se le pasa el parametro de valPonderacion, es decir, si solo se esta verificando si se puede o no agregar un row nuevo
            if(SumPonderacion >= 100){
                ToReturn = false;
            }
        }else{ //Si se le pasa el parametro ValPonderacion, es decir, si se esta verificando si la ponderacion nueva mas las ponderaciones actuales son mayores a 100 
            SumPonderacion += Number(ValPonderacion);
            if(SumPonderacion > 100){
                ToReturn = false;
            }
        }
        return ToReturn;
    }
    function CanUpdatePonderacion(Table,ValPonderacion,ValorAnterior){
        if(typeof ValPonderacion === "undefined"){
            ValPonderacion = 0;
        }
        var ToReturn = true;
        var SumPonderacion = 0;
        Table.rows().every(function(rowIdx,tableLoop,rowLoop){
            var data = this.data();
            SumPonderacion += Number(data.Ponderacion);
        });
        SumPonderacion -= Number(ValorAnterior);
        SumPonderacion += Number(ValPonderacion);
        if(SumPonderacion > 100){
            ToReturn = false;
        }
        return ToReturn;
    }
    function SaveCompetencia(){
        var Nombre = $("input[name='NombreCompetencia']").val();
        var Descripcion = $("input[name='DescripcionCompetencia']").val();
        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
        var Tag = $("input[name='TagCompetencia']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveCompetencia.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                Descripcion: Descripcion,
                Ponderacion: Ponderacion,
                Tag: Tag
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteCompetencia(idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteCompetencia.php",
            dataType: "html",
            data: {
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetCompetencia(idCompetencia){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetCompetencia.php",
            dataType: "html",
            data: {
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateCompetencia(idCompetencia){
        var Nombre = $("input[name='NombreCompetencia']").val();
        var Descripcion = $("input[name='DescripcionCompetencia']").val();
        var Ponderacion = $("input[name='PonderacionCompetencia']").val();
        var Tag = $("input[name='TagCompetencia']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateCompetencia.php",
            dataType: "html",
            data: {
                idCompetencia: idCompetencia,
                Nombre: Nombre,
                Descripcion: Descripcion,
                Ponderacion: Ponderacion,
                Tag: Tag
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableCompetencias.destroy();
                        getCompetencias();
                        UpdateTableCompetencias();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveDimension(idCompetencia){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveDimension.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                idCompetencia: idCompetencia
            },
            async: false,
            success: function(data){
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteDimension(idDimension,idCompetencia){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteDimension.php",
            dataType: "html",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetDimension(idDimension){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetDimension.php",
            dataType: "html",
            data: {
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateDimension(idDimension,idCompetencia){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateDimension.php",
            dataType: "html",
            data: {
                idDimension: idDimension,
                Nombre: Nombre,
                Ponderacion: Ponderacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableDimensiones.destroy();
                        getDimensiones(idCompetencia);
                        UpdateTableDimensiones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveAfirmacion(idDimension){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
        var Corte = $("input[name='Corte']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveAfirmacion.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                DescripcionSimple: DescripcionSimple,
                Corte: Corte,
                idDimension: idDimension
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteAfirmacion(idAfirmacion,idDimension){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteAfirmacion.php",
            dataType: "html",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetAfirmacion(idAfirmacion){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetAfirmacion.php",
            dataType: "html",
            data: {
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateAfirmacion(idAfirmacion,idDimension){
        var Nombre = $("input[name='Nombre']").val();
        var Ponderacion = $("input[name='Ponderacion']").val();
        var DescripcionSimple = $("input[name='DescripcionSimple']").val();
        var Corte = $("input[name='Corte']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateAfirmacion.php",
            dataType: "html",
            data: {
                idAfirmacion: idAfirmacion,
                Nombre: Nombre,
                Ponderacion: Ponderacion,
                DescripcionSimple: DescripcionSimple,
                Corte: Corte
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableAfirmaciones.destroy();
                        getAfirmaciones(idDimension);
                        UpdateTableAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function SaveOpcionAfirmacion(idAfirmacion){
        var Nombre = $("input[name='Nombre']").val();
        var Valor = $("input[name='Valor']").val();
        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/SaveOpcionAfirmacion.php",
            dataType: "html",
            data: {
                Nombre: Nombre,
                Valor: Valor,
                DescripcionCaracteristica: DescripcionCaracteristica,
                idAfirmacion: idAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function DeleteOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/DeleteOpcionAfirmacion.php",
            dataType: "html",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
    function GetOpcionAfirmacion(idOpcionAfirmacion){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetOpcionAfirmacion.php",
            dataType: "html",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    ToReturn = JSON.parse(data);
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function UpdateOpcionAfirmacion(idOpcionAfirmacion,idAfirmacion){
        var Nombre = $("input[name='Nombre']").val();
        var Valor = $("input[name='Valor']").val();
        var DescripcionCaracteristica = $("input[name='DescripcionCaracteristica']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateOpcionAfirmacion.php",
            dataType: "html",
            data: {
                idOpcionAfirmacion: idOpcionAfirmacion,
                Nombre: Nombre,
                Valor: Valor,
                DescripcionCaracteristica: DescripcionCaracteristica
            },
            async: false,
            success: function(data){
                console.log(data);
                if(isJson(data)){
                    var json = JSON.parse(data);
                    if(json.result){
                        TableOpcionesAfirmaciones.destroy();
                        getOpcionesAfirmaciones(idAfirmacion);
                        UpdateTableOpcionesAfirmaciones();
                    }
                }
            },
            error: function(){
            }
        });
    }
});