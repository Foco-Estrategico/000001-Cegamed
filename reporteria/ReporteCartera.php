<?php
require_once('../db/db.php');
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
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
  
    
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

          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="eq-height">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h2 class="panel-title bg-mint">Filtro</h2>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="sel1">Tipo de Búsqueda</label>

                                        <select class="selectpicker" id="TipoBusqueda"  data-live-search="true" data-width="100%">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Por Cartera</option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div1">
                                            <label for="sel1">Mandante</label>
                                            <select class="selectpicker" disabled data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione</option>
                                            </select>
                                        </div>
                                    </div>  
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div2">
                                            <label for="sel1">Cartera</label>
                                            <select class="selectpicker" disabled data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>    
                                
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div id="Div3">
                                            <label class="control-label">Periodo</label>
                                            <select class="selectpicker" disabled data-live-search="true" data-width="100%">
                                                <option value="0">Seleccione</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="control-label">Ver</label>
                                            <button class="btn btn-primary btn-block" id="Buscar">Ver</button>
                                        </div>
                                    </div>


                            </div>
                            
                        </div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h2 class="panel-title bg-mint">Gestiones Diarias</h2>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div id="Mostrar">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-mint">Cumplimiento Meta Contactabilidad</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div id="demo-morris-line" style="height:300px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-mint">Contactos Diarios</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div id="demo-morris-diario" style="height:300px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-mint">% Contactabilidad Diaria</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div id="demo-morris-contactabilidad" style="height:300px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-mint">Contactos Acumulados</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div id="demo-morris-acumulado" style="height:300px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h2 class="panel-title bg-mint">% Contactabilidad Acumulada</h2>
                                    </div>
                                    <div class="panel-body">
                                        <div id="demo-morris-contactabilidad-acumulada" style="height:300px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
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
    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->
    <!--JAVASCRIPT-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="../js/reporte/reporteGestion.js"></script>
   
</body>
</html>