$(document).ready(function(){
    var DiscadorTable;
    var ColasDiscadores = [];
    getColas();
    getTipoTelefono();
    getColasDiscadores();
    UpdateTable();
    IntervaloUpdateTabla();

    $("#Continuar").click(function(){
        var Cola = $("select[name='Asignacion']").val();
        var TipoTelefono = $("select[name='Tipo_Telefono']").val();
        var Canales = $("select[name='Canales']").val();
        var TlfxRut = $("input[name='TlfxRut']").val();
        var Salida = $("select[name='Salida']").val();
        if(Cola != ""){
            if(Canales != ""){
                if(TlfxRut != ""){
                    var CantTipoTelefono = 0;
                    jQuery.each(TipoTelefono,function(i,val){
                        CantTipoTelefono++;
                    });
                    if(CantTipoTelefono > 0){
                        if(Salida != ""){
                        crearQueryDiscador(Cola,TipoTelefono,Canales,TlfxRut,Salida);
                        }else{
                            bootbox.alert("Debe seleccionar una Salida");
                        }
                    }else{
                        bootbox.alert("Debe seleccionar un tipo de telefono");
                    }
                }else{
                    bootbox.alert("Debe ingresar una cantidad de telefonos por rut");
                }
            }else{
                bootbox.alert("Debe ingresar una cantidad de Canales");
            }
        }else{
                bootbox.alert("Debe seleccionar una Cola");
        }       
    });
    $("body").on("click",".btn-repro",function(){
        var ObjectMe = $(this);
        var SelectedValue = ObjectMe.attr("id");
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayidDiv = idDiv.split("_");
        switch(SelectedValue){
            case '0':
                //Reiniciar Cola.
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Reiniciar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue);
                            ReiniciarColaDiscado(ArrayidDiv[0]);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                        }
                    }
                });
            break;
            case '1':
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Iniciar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue);
                            IniciarColaDiscado(ArrayidDiv[0]);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                        }
                    }
                });
            break;
            case '2':
                bootbox.confirm({
                    message: "<div style='font-size: 20px;'>¿Desea Pausar la cola?</div>",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: 'SI',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'NO',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result){
                            CambiarEstadoColaDiscado(ArrayidDiv[0],SelectedValue);
                            ObjectDiv.find(".btn-repro").removeClass("Selected");
                            ObjectMe.addClass("Selected");
                        }
                    }
                });
            break;
        }
    });
    $("body").on("click",".Delete",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayID = idDiv.split("_");
        var idDiscador = ArrayID[1];
        EliminarColaDiscador(idDiscador);
    });
    $("body").on("click",".Status",function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idDiv = ObjectDiv.attr("id");
        var ArrayidDiv = idDiv.split("_");
        var SelectedValue = 0;
        if(ObjectMe.is(":checked")){
            SelectedValue = 1;
        }
        CambiarStatusColaDiscado(ArrayidDiv[0],SelectedValue);
    });
    function getTipoTelefono(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getTipoTelefono.php",
            data:{},
            async: false,
            success: function(response){
                $("select[name='Tipo_Telefono']").html(response);
                $("select[name='Tipo_Telefono']").selectpicker("refresh");
            }	
        });
    }
    function getColas(){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColas.php",
            data:{},
            async: false,
            success: function(response){
                $("select[name='Asignacion']").html(response);
                $("select[name='Asignacion']").selectpicker("refresh");
            }	
        });
    }
    function crearQueryDiscador(Cola,TipoTelefono,Canales,TlfxRut,Salida){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/crearQueryDiscador.php",
            data:{
                Cola: Cola,
                TipoTelefono: TipoTelefono,
                Canales: Canales,
                TlfxRut: TlfxRut,
                Salida:Salida
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                console.log(response);
                var json = JSON.parse(response);
                if(json.result){
                    var Queue = json.Queue;
                    CrearColaAsterisk(Queue);
                    ActualizarTabla();
                }
            }	
        });
    }
    function CrearColaAsterisk(Queue){
        $.ajax({
            type: "POST",
            url: "../discador/AGI/CrearCola.php",
            data:{
                Queue: Queue
            },
            async: false,
            success: function(response){
                //$('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function EliminarColaAsterisk(Queue){
        $.ajax({
            type: "POST",
            url: "../discador/AGI/EliminarCola.php",
            data:{
                Queue: Queue
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
            }	
        });
    }
    function UpdateTable(){
        DiscadorTable = $('#DiscadorTable').DataTable({
            data: ColasDiscadores,
            columns: [
                { data: 'Cola' },
                { data: 'Queue' },
                { data: 'Canales' },
                { data: 'TlfxRut' },
                { data: 'tipoTelefono' },
                { data: 'Reproduccion' },
                { data: 'Progreso' },
                { data: 'Status' },
                { data: 'Accion' }
            ],
            "columnDefs": [ 
                {
                    "targets": 5,
                    "data": 'Reproduccion',
                    "render": function( data, type, row ) {
                        var ArrayData = data.split("_");
                        var SelectedPlay = "";
                        var SelectedPause = "";
                        var SelectedStop = "";
                        switch(ArrayData[1]){
                            case '0':
                                SelectedStop = " Selected ";
                            break;
                            case '1':
                                SelectedPlay = " Selected ";
                            break;
                            case '2':
                                SelectedPause = " Selected ";
                            break;
                        }
                        return "<div style='text-align: center; font-size: 25px;' id='"+data+"'>"+
                                    "<i style='padding: 0 5px;' id='1' class='fa fa-play btn-repro "+SelectedPlay+"'></i>"+
                                    "<i style='padding: 0 5px;' id='2' class='fa fa-pause btn-repro "+SelectedPause+"'></i>"+
                                    "<i style='padding: 0 5px;' id='0' class='fa fa-stop btn-repro "+SelectedStop+"'></i>"+
                               "</div>";
                    }
                },
                {
                    "targets": 7,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        var ArrayData = data.split("_");
                        var idDiscador = ArrayData[0];
                        var Status = ArrayData[1];
                        var Checked = "";
                        if(Status > 0){
                            Checked = "checked"
                        }
                        return "<div style='text-align: center;' id='"+data+"'><input type='checkbox' "+Checked+" style='width: 25px;height: 25px;' class='Status' /></div>";
                    }
                },
                {
                    "targets": 8,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<div style='text-align: center; font-size: 25px;' id='Cola_"+data+"'><i style='cursor: pointer;' class='fa fa-trash Delete'></i></div>";
                    }
                }
            ]
        });
    }
    function getColasDiscadores(Modal = true){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/getColasDiscadores.php",
            data:{},
            async: false,
            beforeSend: function() {
                if(Modal){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
                ColasDiscadores = JSON.parse(response);
            }	
        });
    }
    function CambiarEstadoColaDiscado(Cola,Valor){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/CambiarEstadoColaDiscado.php",
            data:{
                Cola: Cola,
                Value: Valor
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function EliminarColaDiscador(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/EliminarColaDiscador.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                console.log(response);
                var json = JSON.parse(response);
                if(json.result){
                    EliminarColaAsterisk(json.Queue);
                    ActualizarTabla();
                }
            }	
        });
    }
    function ActualizarTabla(){
        DiscadorTable.destroy().draw();
        getColasDiscadores();
        UpdateTable();
        $('#Cargando').modal('hide');
    }
    function ReiniciarColaDiscado(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/ReiniciarColaDiscado.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function CambiarStatusColaDiscado(Cola,Valor){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/CambiarStatusColaDiscado.php",
            data:{
                Cola: Cola,
                Value: Valor
            },
            async: false,
            beforeSend: function() {
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    function IniciarColaDiscado(idDiscador){
        $.ajax({
            type: "POST",
            url: "../includes/tareas/IniciarColaDiscado.php",
            data:{
                Discador: idDiscador
            },
            async: false,
            success: function(response){
                $('#Cargando').modal('hide');
                console.log(response);
            }	
        });
    }
    
    function IntervaloUpdateTabla(){
        setInterval(function(){
            DiscadorTable.destroy().draw();
            getColasDiscadores(false);
            UpdateTable();
            
        },10000);
    }
});