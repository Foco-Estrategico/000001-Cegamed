
audiojs.events.ready(function() {
    audiojs.createAll();
});
$(document).ready(function() {
    var RecordTable;
    var RecordId;
    var EvaluationTable;
    var Ejecutivo = [];
    var CantEvaluations = 0;
    var EvaluationsArray = [];
    var StatusObject;
    var RecordGroups = [];
    var GroupRecordsFlag = false;
    var PrintObject;
    var CarteraObject;
    var ArraySelectedOptions = [];

    FillPersonalList(GlobalData.nombre_cedente);

    $("body").on("change","select[name='Ejecutivo']",function(){
        var Val = $(this).val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        if(Val != ""){
            Ejecutivo[0] = $(this).find("option:selected").text().toUpperCase();
            Ejecutivo[1] = $(this).val();
            FillPeriodo();
            var Periodo = $("select[name='Periodos']").val();
            getTipificacion(Periodo);
            if(HizoCierre(idMandante,idCartera)){
                $("#Records .AddEvaluation").remove();
                $("#FinDeProceso").addClass("ElementInvisible");
                RecordTable.clear().draw();
                CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
            }else{
                RecordTable.destroy();
                RecordTable.draw();
                UpdateRecords(idMandante,idCartera,Periodo);
                ShowBotonCierre();
            }
        }
    });
    function ShowBotonCierre(){
        var Periodo = $("select[name='Periodos']").val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        if(HaveEvaluatedRecords()){
            if(PuedeHacerCierreDeProceso(idMandante,idCartera)){
                $("#FinDeProceso").removeClass("ElementInvisible");
            }else{
                $("#FinDeProceso").addClass("ElementInvisible");
            }
        }
    }
    $("body").on("change","select[name='Periodos']",function(){
        var Periodo = $(this).val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        getTipificacion(Periodo);
        if(HizoCierre(idMandante,idCartera)){
            $("#Records .AddEvaluation").remove();
            $("#FinDeProceso").addClass("ElementInvisible");
            $("select[name='Tipificacion']").prop("disabled",true);
            $("select[name='Tipificacion']").selectpicker("refresh");
            RecordTable.clear().draw();
            CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
        }else{
            RecordTable.destroy();
            RecordTable.draw();
            UpdateRecords(idMandante,idCartera,Periodo);
            ShowBotonCierre();
        }
    });
    $("body").on("change","select[name='Tipificacion']",function(){
        var Periodo = $("select[name='Periodos']").val();
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        RecordTable.destroy();
        UpdateRecords(idMandante,idCartera,Periodo);
    });
    $('body').on('click','.AddEvaluation', function(){
        var Template = $("#Calificacion").html();

        RecordId = $(this).attr("id");
        RecordId = RecordId.substr(RecordId.indexOf("_") + 1, RecordId.length);
        
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
            var Cartera = "";
            var Filename = "";
            var Audio = "";
            var Date = "";
            var Status = "";
            var NewEvaluation = false;
        ObjectTR.find("td").each(function(index){
            switch(index){
                case 0:
                    Cartera = $(this).html();
                    CarteraObject = $(this);
                break;
                case 1:
                    Filename = $(this).html();
                break;
                case 3:
                    Audio = $(this).html();
                break;
                case 4:
                    Date = $(this).html();
                break;
                case 5:
                    Status = $(this).html();
                    if(Status == ""){
                        NewEvaluation = true;
                    }
                    StatusObject = $(this);
                break;
                case 6:
                break;
                case 7:
                    PrintObject = $(this);
                break;
            }
        });

            Template = Template.replace("{RECORD_AUDIO}",Audio);

        bootbox.dialog({
            title: "CALIFICACIÓN GENERAL DE LA EVALUACIÓN DE " + Ejecutivo[0],
            message: Template,
            closeButton: false,
            buttons: {
                confirm: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        if(HaveEvaluations()){
                            var TableTmp = $("#Evaluations");
                            SaveEvaluation(NewEvaluation, TableTmp);
                        }else{
                            CustomAlert("Debe ingresar al menos una evaluación");
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
            },
            size: 'large'
        }).off("shown.bs.modal");
        if(Status != ""){
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluation.php",
                data: { Id_Grabacion: RecordId },
                dataType: "html",
                success: function(data){
                    var Evaluation = JSON.parse(data);
                    Evaluation = Evaluation[0];
                    var Id_Evaluacion = Evaluation.id;
                    $.ajax({
                        type: "POST",
                        url: "../includes/calidad/GetEvaluationDetails.php",
                        data: { Id_Evaluacion: Id_Evaluacion },
                        dataType: "html",
                        success: function(data1){
                            var result = JSON.parse(data1);
                            EvaluationsArray = result.Competencias;
                            ArraySelectedOptions = result.SelectedOptions;
                            //EvaluationsArray = JSON.parse(data1);
                            UpdateEvaluations();
                        },
                        error: function(){
                        }
                    });
                },
                error: function(){
                }
            });
        }else{
            $.ajax({
                type: "POST",
                url: "../includes/calidad/GetEvaluationTemplate.php",
                dataType: "html",
                data: {Ejecutivo: Ejecutivo[1]},
                success: function(data){
                    EvaluationsArray = JSON.parse(data);
                    UpdateEvaluations();
                },
                error: function(){
                }
            });
            //UpdateEvaluations();
        }
    });
    $("body").on("keypress",".justNumber",function(e){
        if(e.keyCode == 190){
            return false;
        }
    });
    $('body').on( 'click', '.AddAfirmaciones', function () {
        console.log(ArraySelectedOptions);
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var ObjectNameText = ObjectTR.find(".NameObject");
        var ObjectDescriptionText = ObjectTR.find(".DescriptionObject");
        var ObjectEsperadoText = ObjectTR.find(".showEsperadoModal");
        var ObjectObservationText = ObjectTR.find(".ObservationObject");
        var ObjectNote = ObjectTR.find(".NoteObject");
        var ObjectCalfPonderada = ObjectTR.find(".CalfPonderadaObject");
        var ObjectPonderacion = ObjectTR.find(".PonderacionObject");
        var Template = $("#EvaluationFormObservation").html();

        var Row = EvaluationTable.row( ObjectTR ).data();
        var ID = Row.ID;
        var Ponderacion = Number(Row.Ponderacion);

        bootbox.dialog({
            title: "Formulario de Observación de la competencia: " + ObjectNameText.html(),
            message: Template,
            closeButton: false,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-purple",
                    callback: function() {
                        getEvaluationData();
                        AddClassModalOpen();
                    }
                },
                cancel: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function() {
                        AddClassModalOpen();
                    }
                }
            },
            size: "large"
        });
        var ObjectPreguntas = $(".PregunatasEvaluaciones .Preguntas");
        var ArrayDimensiones = selectAfirmaciones(ID);
        var Dimensiones = ArrayDimensiones.Dimensiones;
        var NotaMaxima = ArrayDimensiones.NotaMaxima;
        ObjectPreguntas.attr("notamaxima",NotaMaxima);
        ObjectPreguntas.attr("ponderacion",Ponderacion);
        ObjectPreguntas.attr("id",ID);
        if(ArraySelectedOptions[ID] == undefined){
            ArraySelectedOptions[ID] = [];
        }
        var Cont = 0;
        $.each(Dimensiones,function(key,Dimension){
            var Div = "<div id='D_"+Dimension.idDimension+"' class='Dimensiones' ponderacion='"+Dimension.Ponderacion+"'></div>";
            ObjectPreguntas.append(Div);
            var Preguntas = Dimension.Preguntas[0];
            var NotaCompetencia = NotaMaxima * (Ponderacion / 100);
            $.each(Preguntas,function(key,Pregunta){
                var Color = "#FFFFFF";
                if(Cont % 2 == 0){
                    Color = "#EEEEEE";
                }
                var idAfirmacion = Pregunta.idAfirmacion;
                var Afirmacion = Pregunta.Afirmacion;
                var Ponderacion = Pregunta.Ponderacion;
                var ValueSelected = ArraySelectedOptions[ID][Cont];
                var Div = "<div class='Pregunta Afirmaciones' id='A_"+idAfirmacion+"' ponderacion='"+Ponderacion+"' style='overflow: hidden; overflow: hidden;padding: 10px 5px;background-color: "+Color+";'>"+
                                "<div class='Texto' style='width: 40%;float: left;'>"+Afirmacion+"</div>"+
                                "<div class='Opciones' style='float: left;width: 60%;display: inline-flex;'></div>"+
                            "</div>";
                ObjectPreguntas.find("#D_"+Dimension.idDimension).append(Div);
                $.each(Pregunta.Opciones,function(key,Opcion){
                    var idOpcion = Opcion.idOpcion;
                    var OpcionTxt = Opcion.Opcion;
                    var Valor = Opcion.Valor;
                    var Active = "";
                    var Checked = "";
                    if(ValueSelected != undefined){
                        var ArraySelected = ValueSelected.split("|");
                        if(ArraySelected[2] != -1){
                            if(Number(Valor) == Number(ArraySelected[2])){
                                Active = " active ";
                                Checked = " checked='' ";
                            }
                        }
                    }
                    Div = "<div class='Opcion' style='width: calc(100% / 5);float: left;'>"+
                                    "<label class='form-radio form-icon form-text "+Active+"' style='height: 100%;'><input "+Checked+" name='"+idAfirmacion+"' id='O_"+idOpcion+"' value='"+Valor+"' type='radio'> "+OpcionTxt+"</label>"+
                                "</div>";
                    ObjectPreguntas.find("#D_"+Dimension.idDimension).find("#A_"+idAfirmacion+" .Opciones").append(Div);
                });
                Cont++;
            });
        });
    });
    $("body").on("update","#Evaluations",function(){
        UpdateEvaluationSummaryFoot();
    });
    $("body").on("click",".close",function(){
        AddClassModalOpen();
    });
    $("#FinDeProceso").click(function(){
        var idCartera = GlobalData.id_cedente;
        var idMandante = GlobalData.id_mandante;
        var Template = $("#Observations").html();
        if(HaveEvaluatedRecords()){
            bootbox.dialog({
                title: "CIERRE DE PROCESO DE '" + GlobalData.nombre_mandante.toUpperCase() + "'",
                message: Template,
                buttons: {
                    success: {
                        label: "Guardar",
                        className: "btn-purple",
                        callback: function() {
                            /*var Observation_aspectosF = $("#Observation_aspectosF").val();
                            var Observation_aspectosC = $("#Observation_aspectosC").val();
                            var Observation_comprimisoE = $("#Observation_comprimisoE").val();*/
                            var Observation_aspectosF = "";
                            var Observation_aspectosC = "";
                            var Observation_comprimisoE = "";
                            var TipoCierre = $("select[name='Tipo_Cierre']").val();
                            if(TipoCierre != ""){
                                CierreDeProceso(idMandante,idCartera,Observation_aspectosF,Observation_aspectosC,Observation_comprimisoE,TipoCierre);
                            }else{
                                bootbox.alert("Debe seleccionar un tipo de cierre");
                            }
                            /*if(Observation_aspectosF != ""){
                                if(Observation_aspectosC != ""){
                                    if(Observation_comprimisoE != ""){
                                        CierreDeProceso(idMandante,idCartera,Observation_aspectosF,Observation_aspectosC,Observation_comprimisoE,TipoCierre);
                                    }else{
                                        CustomAlert("Debe introducir Compromoso del Ejecutivo");
                                        return false;
                                    }
                                }else{
                                    CustomAlert("Debe introducir un Aspecto a Corregir");
                                    return false;
                                }
                            }else{
                                CustomAlert("Debe introducir un Aspecto a Fortalecer");
                                return false;
                            }*/
                        }
                    }
                }
            }).off("shown.bs.modal");
            $("select").selectpicker("refresh");
        }else{
            bootbox.alert("No hay evaluaciones suficientes para realizar el cierre");
        }
    });
    function FillPersonalList(Cartera){
        $.ajax({
            type: "POST",
            url: "../includes/personal/fillSelectEvaluadas.php",
            dataType: "html",
            data: {
                Cartera: Cartera,
            },
            success: function(data){
                $("select[name='Ejecutivo']").html(data);
                $("select[name='Ejecutivo']").selectpicker('refresh');
                if(GlobalData.isEjecutivo){
                    $("select[name='Ejecutivo']").prop("disabled",true);
                    $("select[name='Ejecutivo']").selectpicker('refresh');
                    Ejecutivo[0] = GlobalData.personalName.toUpperCase();
                    Ejecutivo[1] = GlobalData.username;
                    FillPeriodo();
                    var Periodo = $("select[name='Periodos']").val();
                    getTipificacion(Periodo);
                    UpdateRecords(GlobalData.id_mandante,GlobalData.id_cedente,Periodo);
                    if(HaveEvaluatedRecords()){
                        var idCartera = GlobalData.id_cedente;
                        var idMandante = GlobalData.id_mandante;
                        if(HizoCierre(idMandante,idCartera)){
                            $("#Records .AddEvaluation").remove();
                            CustomAlert("Ya realizo el cierre del mes, consulte sus cierres realizados haciendo <a href='mis_cierres.php' style='color: red; font-weight: bold;'>click aqui</a>");
                        }else{
                            $("#FinDeProceso").removeClass("ElementInvisible");
                        }
                    }
                }else{
                    PreloadRecordTable();
                }
            },
            error: function(){
            }
        });
    }
    function HaveEvaluatedRecords(){
        var ToReturn = false;
        var Cont = 0;
        RecordTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.Status != ""){
                Cont++;
            }
        });
        if(Cont > 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    function FillCarteraList(startDate, endDate){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/fillCartera.php",
            dataType: "html",
            data: {
                startDate: startDate,
                endDate: endDate
            },
            success: function(data){
                $("select[name='Cartera']").html(data);
                $("select[name='Cartera']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function UpdateRecords(idMandante,idCartera,Periodo){
        var Tipificacion = $("select[name='Tipificacion']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/GetRecordsEvaluadas.php",
            data: { 
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo,
                Tipificacion: Tipificacion
            },
            dataType: "html",
            async: false,
            success: function(data){
                console.log(data);
                var dataSet = JSON.parse(data);
                var CantRecords = dataSet.length;
                RecordGroups = [];
                for(var i in dataSet){
                    var ID = dataSet[i].Imprimir;
                    RecordGroups[ID] = false;
                }
                RecordTable = $('#Records').DataTable({
                    data: dataSet,
                    columns: [
                        { data: 'Cartera' },
                        { data: 'Filename' },
                        { data: 'Tipificacion' },
                        { data: 'Listen' },
                        { data: 'Date' },
                        { data: 'Status' }, 
                        { data: 'Evaluar' },
                        { data: 'Imprimir' }
                    ],
                    "columnDefs": [ 
                        /*{
                            "targets": 0,
                            "data": 'Cartera',
                            "render": function( data, type, row ) {
                                //return "<span class='checkbox'><input id='"+row.Evaluar+"' class='magic-checkbox groupSelecter' type='checkbox'><label for='"+row.Evaluar+"'>"+data+"</label></span>";
                                var ToReturn = "";
                                if(row.Status != ""){
                                    ToReturn = "<span class='checkbox'><input id='"+row.Evaluar+"' class='magic-checkbox groupSelecter' type='checkbox'><label for='"+row.Evaluar+"'>"+data+"</label></span>";
                                }else{
                                    ToReturn = data;
                                }
                                return ToReturn;
                            }
                        },*/
                        {
                            "targets": 3,
                            "data": 'Listen',
                            "render": function( data, type, row ) {
                                return "<audio src='"+data+"' preload='auto' controls></audio>";
                            }
                        },
                        {
                            "targets": 6,
                            "data": 'Evaluar',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-pencil AddEvaluation'></i></div>";
                            }
                        },
                        {
                            "targets": 7,
                            "data": 'Imprimir',
                            "render": function( data, type, row ) {
                                var ToReturn = "";
                                if(row.Status != ""){
                                    ToReturn = "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                                }
                                //return "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
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
        RecordTable = $('#Records').DataTable({
            data: dataSet,
            columns: [
                { data: 'Cartera' },
                { data: 'Filename' },
                { data: 'Listen' },
                { data: 'Date' },
                { data: 'Status' }, 
                { data: 'Evaluar' },
                { data: 'Imprimir' }
            ],
            "columnDefs": [ 
                {
                    "targets": 2,
                    "data": 'Listen',
                    "render": function( data, type, row ) {
                        return "<audio src='"+data+"' preload='auto' controls></audio>";
                    }
                },
                {
                    "targets": 5,
                    "data": 'Evaluar',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-pencil AddEvaluation'></i></div>";
                    }
                },
                {
                    "targets": 6,
                    "data": 'Imprimir',
                    "render": function( data, type, row ) {
                        var ToReturn = "";
                        if(row.Status != ""){
                            ToReturn = "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
                        }
                        //return "<div style='text-align: center;'><a href='EvaluationResume.php?id="+data+"' target='_blank'><i style='cursor: pointer;' id='Record_"+data+"' class='fa fa-print Print'></i></a></div>";
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
                { data: 'Nombre', width: "20%" },
                { data: 'Descripcion', width: "40%" },
                { data: 'Esperado', width: "10%" },
                { data: 'Nota', width: "10%" },
                { data: 'ID', width: "10%" }
            ],
            "columnDefs": [ 
                {
                    className: "NameObject",
                    "targets": 0,
                },
                {
                    className: "DescriptionObject",
                    "targets": 1,
                },
                {
                    className: "dt-center",
                    "targets": 2,
                    "data": 'Esperado',
                    "render": function( data, type, row ) {
                        return "<button class='btn btn-success showEsperadoModal' text='"+data+"'>?</button>";
                    }
                },
                {
                    className: "dt-right NoteObject",
                    "targets": 3,
                    "searchable": false
                },
                {
                    "targets": 4,
                    "data": 'ID',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer;' class='fa fa-pencil AddAfirmaciones'></i></div>";
                    }
                }
            ]
        });
        //EvaluationTable.order([4, 'asc']).draw();
        EvaluationTable.page('last').draw(false);
        $("#Evaluations").trigger('update');
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
    function UpdateEvaluationSummaryFoot(){
        var ContEvaluaciones = 0;
        var SumPonderacion = 0;
        var SumNotas = 0;
        $("#Evaluations tbody tr").each(function(indexTR){
            ContEvaluaciones++;
            $(this).find("td").each(function(indexTD){
                switch(indexTD){
                    case 3:
                        SumNotas += Number($(this).text());
                    break;
                }
            });
        });
        $("#PromNota").html((SumNotas).toFixed(2));
        AddClassModalOpen();
    }
    function SaveEvaluation(NewEvaluation, TableTmp){
        if(NewEvaluation){
            var IDtmp = PrintObject.closest("tr").find(".AddEvaluation").attr("id");
            var IDArray = IDtmp.split("_");
            AddEvaluation_DB(RecordId,TableTmp);
            ArraySelectedOptions = [];
            StatusObject.html("Evaluada");
            PrintObject.html("<div style='text-align: center;'><a href='EvaluationResume.php?id="+IDArray[1]+"' target='_blank'><i style='cursor: pointer;' id='Record_"+IDArray[1]+"' class='fa fa-print Print'></i></a></div>");
            $("#FinDeProceso").removeClass("ElementInvisible");
            /*Cartera = CarteraObject.html();
            CarteraObject.html("<span class='checkbox'><input id='"+IDArray[1]+"' class='magic-checkbox groupSelecter' type='checkbox'><label for='"+IDArray[1]+"'>"+Cartera+"</label></span>");*/
        }else{
            UpdateEvaluation_DB(RecordId);
        }
    }
    function AddEvaluation_DB(RecordId,TableTmp){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/AddEvaluation.php",
            dataType: "html",
            async: false,
            data: {
                PersonalUsername: Ejecutivo[1],
                RecordId: RecordId,
            },
            success: function(data){
                if(data != "0"){
                    Id_Evaluation = data;
                    //AddEvaluationDetails(Id_Evaluation,TableTmp);
                    AddEvaluationDetails(Id_Evaluation);
                }
            },
            error: function(){
            }
        });
    }
    function AddEvaluationDetails(Id_Evaluacion){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/AddEvaluationDetails.php",
            dataType: "html",
            async: false,
            data: {
                Id_Evaluacion: Id_Evaluacion, 
                Afirmaciones: ArraySelectedOptions
            },
            success: function(data){
                console.log(data);
            },
            error: function(){
            }
        });
    }
    function UpdateEvaluation_DB(RecordId,TableTmp){

        $.ajax({
            type: "POST",
            url: "../includes/calidad/UpdateEvaluation.php",
            dataType: "html",
            data: {
                RecordId: RecordId,
            },
            success: function(data){
                if(data != "0"){
                    Id_Evaluation = data;
                    AddEvaluationDetails(Id_Evaluation);
                }
            },
            error: function(){
            }
        });
    }
    function HaveEvaluations(){
        var ToReturn = false;
        var ContEvaluations = 0;
        $("#Evaluations tbody tr").each(function(indexTR){
            if(!$(this).find("td").hasClass("dataTables_empty")){
                ContEvaluations++;
            }
        });
        if(ContEvaluations > 0){
            ToReturn = true;
        }
        return ToReturn;
    }
    
    function PuedeHacerCierreDeProceso(idMandante, idCartera){
        var ToReturn = false;
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/HaveEvaluations.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                var ObjectJson = JSON.parse(data);
                if(ObjectJson.Return){
                    ToReturn = true;
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function CierreDeProceso(idMandante,idCartera,Observation_aspectosF,Observation_aspectosC,Observation_comprimisoE,TipoCierre){
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/CierreDeProceso.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Observation_aspectosF: Observation_aspectosF,
                Observation_aspectosC: Observation_aspectosC,
                Observation_comprimisoE: Observation_comprimisoE,
                TipoCierre: TipoCierre,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                console.log(data);
                var ObjectJson = JSON.parse(data);
                if(ObjectJson.Return){
                    ToReturn = true;
                    location.reload();
                }else{
                    CustomAlert("Hubo un problema al crear el cierre");
                }
            },
            error: function(){
            }
        });
    }
    function HizoCierre(idMandante,idCartera){
        var ToReturn = false;
        var Periodo = $("select[name='Periodos']").val();
        $.ajax({
            type: "POST",
            url: "../includes/calidad/HizoCierre.php",
            dataType: "html",
            data: {
                Ejecutivo: Ejecutivo[1],
                Cartera: idCartera,
                Mandante: idMandante,
                Periodo: Periodo
            },
            async: false,
            success: function(data){
                var ObjectJson = JSON.parse(data);
                ToReturn = ObjectJson.Return;
            },
            error: function(){
            }
        });
        return ToReturn;
    }
    function FillPeriodo(){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getEvaluacionesByMonthsAndYears.php",
            dataType: "html",
            data: {
                idPersonal: Ejecutivo[1]
            },
            async: false,
            success: function(data){
                $("select[name='Periodos']").html(data);
                $("select[name='Periodos']").selectpicker('refresh');
            },
            error: function(){
            }
        });
    }
    function getTipificacion(Periodo){
        $.ajax({
            type: "POST",
            url: "../includes/calidad/getTipificacionGrabacionesEvaluadas.php",
            data: { 
                Ejecutivo: $("select[name='Ejecutivo']").val(),
                Periodo: Periodo
            },
            async: false,
            dataType: "html",
            success: function(data){
                $("select[name='Tipificacion']").html(data);
                $("select[name='Tipificacion']").prop("disabled",false);
                $("select[name='Tipificacion']").selectpicker("refresh");
            },
            error: function(){
            }
        });
    }
    function selectAfirmaciones(Competencia){
        var ToReturn = "";
        $.ajax({
            type: "POST",
            url: "../includes/calidad/selectAfirmacionesByCompetencia.php",
            dataType: "html",
            data: {
                Competencia: Competencia
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
    function getEvaluationData(){
        var ID = $(".PregunatasEvaluaciones .Preguntas").attr("id");
        var NotaMaxima = $(".PregunatasEvaluaciones .Preguntas").attr("notamaxima");
        var PonderacionCompetencia = $(".PregunatasEvaluaciones .Preguntas").attr("ponderacion");
        var NotaCompetencia = Number(NotaMaxima * (PonderacionCompetencia / 100));
        ArraySelectedOptions[ID] = [];
        var Nota = 0;
        var Cont = 0;
        $(".PregunatasEvaluaciones .Preguntas .Dimensiones").each(function(){
            var ObjectDimension = $(this);
            var PonderacionDimension = ObjectDimension.attr("ponderacion");
            var NotaDimension = Number(NotaCompetencia * (PonderacionDimension / 100));
            ObjectDimension.find(".Afirmaciones").each(function(){
                var ObjectAfirmacion = $(this);
                var idAfirmacion = ObjectAfirmacion.attr("id");
                idAfirmacion = idAfirmacion.split("_");
                idAfirmacion = idAfirmacion[1];
                var PonderacionAfirmacion = ObjectAfirmacion.attr("ponderacion");
                var NotaAfirmacion = Number(NotaDimension * (PonderacionAfirmacion / 100));
                var NotaPorOpcion = Number(NotaAfirmacion / NotaMaxima);
                var Value = -1;
                ObjectAfirmacion.find(".Opciones .Opcion").each(function(){
                    var ObjectOpcion = $(this);
                    var ObjectInput = ObjectOpcion.find("input");
                    if(ObjectInput.is(':checked')){
                        Value = ObjectInput.val();
                    }
                });
                var NotaSeleccionada = Value >= 0 ? Number(Value * NotaPorOpcion) : 0;
                if(Value >= 0){
                    Nota += NotaSeleccionada;
                }
                //console.log(NotaSeleccionada);
                ArraySelectedOptions[ID].push(idAfirmacion+"|"+NotaSeleccionada+"|"+Value);
            });
        });
        EvaluationTable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.ID == ID){
                data.Nota = Number(Nota).toFixed(2);
                this.data(data);
            }
        });
        EvaluationTable.draw();
        $("#Evaluations").trigger('update');
    }
    $("body").on("click",".showEsperadoModal",function(){
        var Text = $(this).attr("text");
        CustomAlert(Text);
    });
});