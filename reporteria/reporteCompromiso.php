<?php
require_once('../db/db.php');
include("../class/global/global.php");
require_once('../class/session/session.php');
$objetoSession = new Session('2,3',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "gra,compro"));
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
$cedente = $_SESSION['cedente'];
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
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
    <style type="text/css">
        .ProgresRotate{
            transform: rotate(180deg);
        }
            .ProgresRotate > div{
                transform: rotate(180deg);
            }
        h4{
            margin: 25px 0 10px 0;
            border-top: 1px solid #cccccc;
            padding-top: 25px;
        }
        #comRangoFecha{
            display: none;
        }
        #comMes{
            display: none;
        }
        #comMesSemana{
            display: none;
        }
        .HumanComparison{
            display: none;
        }
        .FullWidth{
            width: 100% !important;
        }
        .Right{
            display: none;
        }
        .Chart {
            padding: 0 15px !important;
        }
        .legendColorBox > div {
            border: 0 !important;
        }
        .legendColorBox div div {
            width: 20px !important;
        }
        .legendLabel {
            font-size: 13px;
            text-align: left;
            padding: 0 5px;
        }
        .flot-tick-label.tickLabel{
            font-weight: bold;
            font-size: 13px;
        }
        text {
            font-weight: bold !important;
            fill: #595e62;
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
            <li><a href="#">Reporteria</a></li>
            <li class="active">Estadistica por Compromiso</li>
          </ol>
          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
          <!--End breadcrumb-->
          <!--Page content-->
          <!--===================================================-->
          <div id="page-content">
            <div class="row">
                <div class="eq-height">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h2 class="panel-title bg-mint">Filtros</h2>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-6 .Left">
                                        <div class="col-sm-6 col-sm-offset-3">
                                          <div class="form-group">
                                              <label class="control-label">Seleccione rango de fecha</label>
                                              <div id="date-range">
                                                  <div class="input-daterange input-group" id="datepicker">
                                                      <input type="text" class="form-control" name="start" />
                                                      <span class="input-group-addon">a</span>
                                                      <input type="text" class="form-control" name="end" />
                                                  </div>
                                              </div>
                                              <!--<button id="FiltrarPorFecha" class="btn btn-primary" style="margin-top: 10px;" type="submit">Filtrar Fecha</button>-->
                                          </div>
                                        </div>
                                        <!--<div class="col-sm-6 col-sm-offset-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Grupo:</label>
                                                <select class="selectpicker form-control" name="GrupoLeft" title="Seleccione" data-live-search="true" data-width="100%">
                                                    <option value="0">Antiguos</option>
                                                    <option value="0">Nuevos</option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <!--<div class="col-sm-6 col-sm-offset-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Ejecutivo:</label>
                                                <select class="selectpicker form-control inputEjecutivo" name="EjecutivoLeft" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                            </div>
                                        </div>-->
                                    </div>
                                    <div class="col-sm-6 .Right">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <div class="form-group">
                                                <label class="control-label">Por semana:</label>
                                                <select class="selectpicker form-control" id="semana"  name="semana"  data-width="100%">
                                                  <option value="0">No</option>
                                                  <option value="1">Sí</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-6 col-sm-offset-3">
                                            <div class="form-group"> disabled="disabled"
                                                <label class="control-label">Seleccione Grupo:</label>
                                                <select class="selectpicker form-control InputForComparison" disabled="disabled" name="GrupoRight" title="Seleccione" data-live-search="true" data-width="100%">
                                                <option value="0">Por defecto</option>
                                                </select>
                                            </div>
                                        </div>-->
                                      <!--  <div class="col-sm-6 col-sm-offset-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Ejecutivo:</label>
                                                <select class="selectpicker form-control inputEjecutivo InputForComparison" disabled="disabled" name="EjecutivoRight" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-offset-5">
                                    <div class="form-group">
                                        <label class="control-label">Seleccione el Mes:</label>
                                        <select class="selectpicker form-control" id="mes" name="mes" data-width="100%">
                                            <option value="0">Seleccione</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                </div>
                                <button id="Mostrar" class="btn btn-primary col-sm-2 col-sm-offset-5">MOSTRAR</button>
                            </div>
                        </div>
                        <div> <!--id="result"-->
                            <div class="panel panel-primary" id="comRangoFecha">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-mint">Compromisos por rango de fechas</h2>
                                </div>
                                <!-- Panel model -->
                                <!--===================================================-->
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-3" align="center">
                                          <div class="col-sm-12" align="center" id="leyendaRangoFecha">
                                              <div class="list-group">
                                              </div>
                                          </div>
                                        </div>
                                        <div class="col-sm-9" align="center"><canvas id="chart1" width="400" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary" id="comMes">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-mint">Compromisos por mes</h2>
                                </div>
                                <!-- Panel model -->
                                <!--===================================================-->
                                <div class="panel-body" id="Perfil">
                                  <div class="row">
                                      <div class="col-sm-3" align="center">
                                        <div class="col-sm-12" align="center" id="leyendaPorMes">
                                            <div class="list-group">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-sm-9" align="center"><canvas id="chart2" width="400" height="100"></canvas>
                                      </div>
                                  </div>
                                </div>
                            </div>
                            <div class="panel panel-primary" id="comMesSemana">
                                <div class="panel-heading">
                                    <h2 class="panel-title bg-mint">Compromisos por semanas del mes</h2>
                                </div>
                                <!-- Panel model -->
                                <!--===================================================-->
                                <div class="panel-body" id="Perfil">
                                  <div class="row">
                                      <div class="col-sm-3" align="center">
                                        <div class="col-sm-12" align="center" id="leyendaMesSemana">
                                            <div class="list-group">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-sm-9" align="center"><canvas id="chart3" width="400" height="100"></canvas>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <!--===================================================-->
                        <!--End Panel model-->
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
    <script src="../js/usuarios/usuarios.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <script src="../js/nifty.min.js"></script>
    <script src="../plugins/morris-js/morris.min.js"></script>
    <script src="../plugins/morris-js/morris_horizontal.min.js"></script>
    <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../plugins/skycons/skycons.min.js"></script>
    <script src="../plugins/switchery/switchery.min.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../js/demo/nifty-demo.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../js/demo/ui-alerts.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/reporte/reporteCompromiso.js"></script>
    <!--Flot Chart [ OPTIONAL ]-->
    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.stack.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.min.js"></script>
</body>
</html>
