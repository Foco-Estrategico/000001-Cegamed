<?php
    require_once('../db/db.php');
    include("../class/global/global.php");
    require_once('../class/session/session.php');
    $objetoSession = new Session('5,6',false); // 1,4
    //Para Id de Menu Actual (Menu Padre, Menu hijo)
    $objetoSession->crearVariableSession($array = array("idMenu" => "rec,rep"));
    // ** Logout the current user. **
    $objetoSession->creaLogoutAction();
    if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
    {
    //to fully log out a visitor we need to clear the session varialbles
        $objetoSession->borrarVariablesSession();
        $objetoSession->logoutGoTo("../index.php");
    }
    $validar = $_SESSION['MM_UserGroup'];
    $objetoSession->creaMM_restrictGoTo();
    $usuario = $_SESSION['MM_Username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foco | Software de Estrategia</title>
    <!--STYLESHEET-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <style type="text/css">
    .select1
             {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

             }
    .select2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    .text1
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CEECF5;

            }
    .text2
            {
        width: 100%;
        height: 30px;
        border: solid;
        border-color: #ccc;
        background-color: #CCC;

            }
    .mostrar_condiciones
           {
           }
    #midiv100
           {
            display: none;
           }

    #oculto
           {
            display: none;
           }
    #guardar
           {
            display: none;
           }
    #folder
           {
            display: none;
           }
    .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('../img/gears.gif')
            50% 50%
            no-repeat;
            }
body.loading
           {
            overflow: hidden;
           }
body.loading .modal
          {
           display: block;
          }

 #divtablapeq {
    width: 500px;
    }
 #divtablamed {
    width: 600px;
    }

    </style>
</head>
<body>
  <div id="container" class="effect mainnav-sm">
    <!--NAVBAR-->
    <!--===================================================-->
    <?php
    include("../layout/header.php");
    ?>
    <!--===================================================-->
    <!--END NAVBAR-->
      <div class="boxed">
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
          <!--Page Title-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <div id="page-title">
          <!--Searchbox-->
          </div>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End page title-->
          <!--Breadcrumb-->
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <ol class="breadcrumb">
            <li><a href="#">Administrador</a></li>
            <li class="active">Gestionar Usuarios</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="panel">
                    <div class="panel-heading bg-primary">
                        <h2 class="panel-title">Reporte de Reclutamiento</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Seleccione rango de fecha</label>
                                    <div id="date-range">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" class="form-control" name="start" />
                                            <span class="input-group-addon">a</span>
                                            <input type="text" class="form-control" name="end" />
                                        </div>
                                    </div>
                                    <button id="FiltrarPorFecha" class="btn btn-primary" style="margin-top: 10px;" type="submit">Filtrar Fecha</button>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Calificación:</label>
                                    <select class="selectpicker form-control" name="Calificacion" title="Todas" data-live-search="true" data-width="100%">
                                        <option value="1">Todos</option>
                                        <option value="2">Aprobados</option>
                                        <option value="3">Reprobados</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Perfil:</label>
                                    <select disabled="disabled" class="selectpicker form-control" name="Perfil" title="Seleccione" data-live-search="true" data-width="100%">
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label">Aspirantes:</label>
                                    <select disabled="disabled" class="selectpicker form-control" name="Aspirante" title="Seleccione" data-live-search="true" data-width="100%">
                                        <option value="1">Todos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="ResultTables" style="display: none;">
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="panel-heading bg-primary">
                            <button id="getReport" class="btn btn-success" style="height: 102%;position: absolute;right: 0;">Reporte</button>
                        </div>
                        <div class="panel-body">
                            <table id="Calificaciones" style="width:100%">
                                <thead>
                                    <th style="width: 60%">Nombre Completoo</th>
                                    <th style="width: 40%">Correo</th>
                                    <th style="width: 40%" >Pruebas</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel">
                        <div class="panel-heading bg-primary">
                            <h2 class="panel-title" id="Name"></h2>
                        </div>
                        <div class="panel-body">
                            <div id="ChartContainer">
                                <canvas id="radarChart" width="640" height="400"></canvas>
                                <div id="PersonalidadTemplate"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <script id="ListaTestAspirante" type="text/template">
            <table id="pruebasAspirantes" style="width:100%">
                <thead>
                    <th>Nombre Prueba</th>
                    <th>Fecha Prueba</th>
                    <th>Estatus</th>
                    <th>Promedio Calificación</th>
                    <th>Promedio Calificación Minima</th>
                    <th>Ver resultado</th>
                </thead>
                <tbody>
                </tbody>
            </table>
          </script>
          <!--===================================================-->
          <!--End page content-->
        </div>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->
        <!--MAIN NAVIGATION-->
        <!--===================================================-->
        <?php include("../layout/main-menu.php"); ?>
        <!--===================================================-->
        <!--END MAIN NAVIGATION-->
      </div>
        <!-- FOOTER -->
        <!--===================================================-->
        <?php include("../layout/footer.php"); ?>
        <!--===================================================-->
        <!-- END FOOTER -->
        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!--===================================================-->
        <div class="modal fade" tabindex="-1" role="dialog" id="Cargando">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="spinner loading"></div>
                            <h4 class="text-center">Procesando por favor espere...</h4>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/usuarios/usuarios.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
	  <script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../plugins/Chart.js/Chart.min.js"></script>
</body>
</html>
<script id="PersonalidadTemplate" type="text/template">
    <div class="row" id="PatronText">
        {PATRON}
    </div>
</script>
<script>
    $(document).ready(function(){
        
        $("#FiltrarPorFecha").click(function(){
            var startDate = $("input[name='start']").val();
            var endDate = $("input[name='end']").val();
            if((startDate == "") || (endDate == "")){
                bootbox.alert("Debe seleccionar un rango de fecha valido");
                return false;
            }
            $.ajax({
                type: "POST",
                url: "ajax/FiltrarFecha.php",
                dataType: "html",
                data: {
                    startDate: startDate,
                    endDate: endDate,
                },
                beforeSend: function(){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    if(data != ""){
                        $("select[name='Perfil']").html(data);
                        $("select[name='Perfil']").prop("disabled",false);
                        $("select[name='Perfil']").selectpicker('refresh');
                    }else{
                        $("select[name='Perfil']").html(data);
                        $("select[name='Perfil']").prop("disabled",true);
                        $("select[name='Perfil']").selectpicker('refresh');
                        bootbox.alert("No se consiguieron pruebas comprendidas entre las fechas "+startDate+" y "+endDate);
                    }
                },
                error: function(){
                }
            });
        });
        $("select[name='Perfil']").change(function(){
            var startDate = $("input[name='start']").val();
            var endDate = $("input[name='end']").val();
            var Perfil = $(this).val();
            $.ajax({
                type: "POST",
                url: "ajax/FiltrarPerfil.php",
                dataType: "html",
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    perfil: Perfil,
                },
                beforeSend: function(){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    if(data != ""){
                        $("select[name='Aspirante']").html(data);
                        $("select[name='Aspirante']").prop("disabled",false);
                        $("select[name='Aspirante']").selectpicker('refresh');
                    }
                    
                },
                error: function(){
                }
            });
        });
        var CalificationTable;
        $("select[name='Aspirante']").change(function(){
            var CalificationsArray = [];
            var startDate = $("input[name='start']").val();
            var endDate = $("input[name='end']").val();
            var Perfil = $("select[name='Perfil']").val();
            var Aspirante = $(this).val();
            $.ajax({
                type: "POST",
                url: "ajax/getCalifications.php",
                dataType: "html",
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    perfil: Perfil,
                    aspirante: Aspirante,
                },
                beforeSend: function(){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    CalificationsArray = JSON.parse(data);
                    CalificationTable = $('#Calificaciones').DataTable({
                        data: CalificationsArray,
                        "bDestroy": true,
                        columns: [
                            { data: 'Nombre' }, //yyyyyyyyyyyyyyyyyyyyyyy
                            { data: 'Correo' },
                            { data: 'Pruebas' },
                        ],
                        "columnDefs": [ 
                            {
                                className: "NameObject",
                                "targets": 0,
                            },
                            {
                                "targets": 2,
                                "searchable": false,
                                "data": "Aspirante",
                                "render": function( data, type, row ) {
                                    return "<div style='text-align: center;' id="+data+"><i style='cursor: pointer; margin: 0 10px;' class='ti-search icon-lg VerTest'></i></div>";
                                }
                            },
                        ]
                    });
                    $("#ResultTables").show();
                },
                error: function(){
                }
            });
        });


       $('body').on( 'click', '.VerTest', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        bootbox.dialog({
            title: "Pruebas",
            message: $("#ListaTestAspirante").html(),
            size: 'large'
       }).off("shown.bs.modal");
       listarPruebasAspirante(ID);   
    }); 

    $("#getReport").click(function(){
        var CalificationsArray = [];
        var startDate = $("input[name='start']").val();
        var endDate = $("input[name='end']").val();
        var Perfil = $("select[name='Perfil']").val();
        var Aspirante = $("select[name='Aspirante']").val();

        $.ajax({
            type: "POST",
            url: "ajax/getTestReport.php",
            dataType: "html",
            data: {
                startDate: startDate,
                endDate: endDate,
                perfil: Perfil,
                aspirante: Aspirante
            },
            async: false,
            beforeSend: function(){
                $('#Cargando').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
            success: function(data){
                var json = JSON.parse(data);
                var $a = $("<a id='AnclaTemp'>");
                $a.attr("href",json.file);
                $a.attr("download","Reporte Test-"+startDate+"-"+endDate+".xlsx");
                $("body").append($a);
                $("#AnclaTemp")[0].click();
                $("#AnclaTemp").remove();
                $('#Cargando').modal('hide');
            },
            error: function(){
            }
        });
    });

    var TablaTests;
    var Dataset = [];
    function listarPruebasAspirante(idAspirante){
        var startDate = $("input[name='start']").val();
        var endDate = $("input[name='end']").val();
        var Perfil = $("select[name='Perfil']").val();
        var Aspirante = $("select[name='Aspirante']").val();
        $.ajax({
            type: "POST",
            url: "ajax/getPruebasAspirante.php",
            //data: {idAspirante: idAspirante},
            data:{
                startDate: startDate,
                endDate: endDate,
                perfil: Perfil,
                aspirante: idAspirante,
            },
            async: false,
            //dataType: "json",
            success: function(data){
                Dataset = JSON.parse(data);
                TablaTests = $('#pruebasAspirantes').DataTable({
                    data: Dataset,
                    //data: data, // este es mi json
                    paging: true,
                    columns: [
                        //{ data : 'nombreAspirante' }, // campos que trae el json
                        { data : 'nombrePrueba' },
                        { data : 'fecha' },
                        { data : 'estatus' },
                        { data: 'PromedioCalificacion' },// PENDIENTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
                        { data: 'PromedioCalfMinima' },// PENDIENTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
                        { data: 'idPrueba' }
                    ],
                     "columnDefs": [
                      
                        {
                            "targets": 2,
                            "data": 'estatus',
                            "render": function( data, type, row ) {
                                var status = '';
                                switch(data)
                                {
                                    case '0':
                                        status = 'Por realizar';
                                    break;
                                    case '1':
                                        status = 'Realizado';
                                    break;
                                    case '2':
                                        status = 'Culminado';
                                    break;
                                }
                                return "<div style='text-align: center;'>"+status+"</div>";
                            }
                        },
                        {
                            "targets": 5,
                            "data": 'idPrueba',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='btn ShowGraph fa fa-eye icon-lg'></i></div>";
                            }
                        }
                    ] 
                }); 
            },
            error: function(){
                alert('erroryujuuu');
            }
        });
    }

    
       $('body').on( 'click', '.verResultado', function () {
        bootbox.hideAll();
       });




        
        $("body").on("click",".ShowGraph",function(){            
            var ObjectMe = $(this);
            var ObjectDiv = ObjectMe.closest("div");
            var Prueba = ObjectDiv.attr("id");
            var ObjectTableRow = ObjectMe.closest("tr");
            var ObjectName = ObjectTableRow.find(".NameObject");
            $.ajax({
                type: "POST",
                url: "ajax/getGraphData.php",
                data: {
                    prueba: Prueba
                },
                beforeSend: function(){
                    $('#Cargando').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#ChartContainer").find("iframe").each(function(){
                        $(this).remove();
                    });
                    $("#ChartContainer #PersonalidadTemplate").html("");
                    var canvas = document.getElementById("radarChart");
                    var ctx = canvas.getContext("2d");
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    $("#ChartContainer #radarChart").hide();
                    $("#ChartContainer #PersonalidadTemplate").hide();
                },
                success: function(data){
                    $('#Cargando').modal('hide');
                    $("#Name").html(ObjectName.html());
                    var json = JSON.parse(data);
                    switch(json.Test){
                        case '1':
                        case '2':
                            $("#ChartContainer #radarChart").show();
                             var data = {
                                labels: json.Preguntas,//["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
                                datasets: [
                                    {
                                        label: "Calificación Obtenida",
                                        backgroundColor: "rgba(0,0,255,0.2)",
                                        borderColor: "rgba(0,0,255,1)",
                                        pointBackgroundColor: "rgba(0,0,255,1)",
                                        pointBorderColor: "#fff",
                                        pointHoverBackgroundColor: "#fff",
                                        pointHoverBorderColor: "rgba(0,0,255,1)",
                                        data: json.Calificacion//[65, 59, 90, 81, 56, 55, 40]
                                    },
                                    {
                                        label: "Calificación Minima",
                                        backgroundColor: "rgba(255,99,132,0.2)",
                                        borderColor: "rgba(255,99,132,1)",
                                        pointBackgroundColor: "rgba(255,99,132,1)",
                                        pointBorderColor: "#fff",
                                        pointHoverBackgroundColor: "#fff",
                                        pointHoverBorderColor: "rgba(255,99,132,1)",
                                        data: json.CalificacionMinima//[28, 48, 40, 19, 56, 27, 73]
                                    }
                                ]
                            };
                            var ctx = document.getElementById("radarChart").getContext("2d");
                            new Chart(ctx, {
                                type: "radar",
                                data: data,
                                options: {
                                    pointLabel: {
                                        fontSize: 0
                                    },
                                    scale: {
                                        reverse: false,
                                        ticks: {
                                            beginAtZero: true,
                                            display: false
                                        }
                                    }
                                }
                            });
                        break;
                        case '3':
                            $("#ChartContainer #PersonalidadTemplate").show();
                            jQuery.each(json, function(i, val) {
                                //$("#" + i).append(document.createTextNode(" - " + val));
                                switch(i){
                                    case 'Test':
                                    default:
                                        var Titulo = "";
                                        var row = "";
                                        switch(i){
                                            case 'Patron':
                                                row = '<div class="row">'+
                                                        '<div class="col-md-12" style="text-align: center;font-weight: bold;font-size: 20px;margin-bottom: 20px;">'+val+'</div>'+
                                                    '</div>';
                                            break;
                                            case 'Emociones':
                                                Titulo = "Emociones:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Meta':
                                                Titulo = "Meta:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Juzga':
                                                Titulo = "Juzga a los demás por:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Influye':
                                                Titulo = "Influye en los demás mediante:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Valores':
                                                Titulo = "Su valor para la organización:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Abusa':
                                                Titulo = "Abusa de:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Presion':
                                                Titulo = "Bajo presión:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Teme':
                                                Titulo = "Teme:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Eficaz':
                                                Titulo = "Sería más eficaz si:";
                                                row = '<div class="row">'+
                                                    '<div class="col-md-3" style="font-weight: bold;">'+Titulo+'</div>'+
                                                    '<div class="col-md-9">'+val+'</div>'+
                                                '</div>';    
                                            break;
                                            case 'Observacion':
                                                console.log(val);
                                                jQuery.each(val, function() {
                                                    row += '<div class="row">'+
                                                        '<div class="col-md-12">'+this+'</div>'+
                                                    '</div>';
                                                });
                                            break;
                                        }
                                        $("#ChartContainer #PersonalidadTemplate").append(row);
                                    break;
                                }
                            });
                        break;
                    }
                },
                error: function(){
                }
            });
            bootbox.hideAll();
        });
    });
</script>