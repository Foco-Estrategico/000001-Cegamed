<?PHP
require_once('../db/db.php');
include("../class/global/global.php");

include("../class/calidad/calidad.php");

$CalidadClass = new Calidad();

//echo $CalidadClass->getRutaGrabaciones("20170725-185812_995883252_013_mrivero-all.mp3");

require_once('../class/session/session.php');
$objetoSession = new Session('1,2,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "cal,cal_eva"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction();
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
if(isset($_SESSION['cedente'])){
    if($_SESSION['cedente'] != ""){
        $cedente = $_SESSION['cedente'];
    }
}
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
</head>
<style>
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
</style>
<body>
    <input type="hidden" id="cedente" value="<?php echo $cedente; ?>">
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
                    <li><a href="#">Calidad</a></li>
                    <li class="active">Evaluar</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->




                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">

					<div class="row">
						<div class="eq-height">




								<!--Panel with Header-->
								<!--===================================================-->
							<div class="col-sm-12 eq-box-sm">
                                <div id="contenedor"></div>
                                <!--AUDIOJS [ REQUIRED ]-->


                                    <div class="row">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Buscar Grabaciones</h3>
                                            </div>
                                            <div class="panel-body" style="padding: 0 10px;">
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
                                                <!--<div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Seleccione Cartera:</label>
                                                        <select class="selectpicker form-control" name="Cartera" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                                    </div>
                                                </div>-->
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Seleccione Ejecutivo:</label>
                                                        <select disabled="disabled" class="selectpicker form-control" name="Ejecutivo" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Seleccione Tipificación:</label>
                                                        <select disabled="disabled" class="selectpicker form-control" name="Tipificacion" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button id="GroupRecords" class="btn btn-primary ElementInvisible" type="submit">Agrupar</button>
                                                </div>
                                                <table id="Records" class="display" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Cartera</th>
                                                            <th>Grabación</th>
                                                            <th>Tipificación</th>
                                                            <th>Escuchar</th>
                                                            <th>Fecha</th>
                                                            <th>Estado</th>
                                                            <th>Evaluar</th>
                                                            <th>Imprimir</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <script id="Calificacion" type="text/template">
                                        <div class="row">
                                            <div class="cols-sm-1">
                                                <div class="Record">
                                                    {RECORD_AUDIO}
                                                </div>
                                                <div class="Table">
                                                    <!--<button style="margin: 10px 0;" id="AddEvaluation" class="btn btn-success">Agregar</button>
                                                    <br>-->
                                                    <table id="Evaluations" class="display" cellspacing="0" width="100%">
                                                        <thead>
                                                            <th style='width: 20%;'>Nombre</th>
                                                            <th style='width: 40%;'>Palabras Claves</th>
                                                            <th style='width: 10%;'>Descripción</th>
                                                            <th style='width: 10%;'>Nota</th>
                                                            <th style='width: 10%;'>Evaluación</th>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <th style='width: 20%;'></th>
                                                            <th style='width: 40%;'></th>
                                                            <th style='width: 10%;'></th>
                                                            <th style='width: 10%;'><span id="PromNota">0</span></th>
                                                            <th style='width: 10%;'></th>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </script>
                                    <script id="EvaluationForm" type="text/template">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form class="form-horizontal">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="name">Descripción</label>
                                                        <div class="col-md-8">
                                                            <textarea id="Description" name="Description" rows="4" class="form-control" placeholder="Your content here.."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="name">Ponderación</label>
                                                        <div class="col-md-3">
                                                            <input id="Ponderacion" name="Ponderacion" type="number" min="1" max="100" placeholder="Ponderación" class="form-control input-md justNumber">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </script>
                                    <script id="EvaluationFormObservation" type="text/template">
                                        <div class="row">
                                            <div class="col-md-12 PregunatasEvaluaciones">
                                                <div class="Preguntas">
                                                </div>
                                            </div>
                                        </div>
                                    </script>
                                    <script id="EvaluationFormUpdate" type="text/template">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form class="form-horizontal">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="name">Descripción</label>
                                                        <div class="col-md-8">
                                                            <textarea id="Description" name="Description" rows="4" class="form-control" placeholder="Your content here..">{DESCRIPTION}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="name">Ponderación</label>
                                                        <div class="col-md-3">
                                                            <input id="Ponderacion" name="Ponderacion" type="number" min="1" max="100" value="{PONDERACION}" placeholder="Ponderación" class="form-control input-md justNumber">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </script>
                                    <style>
                                        .hide_column{
                                            display: none;
                                        }
                                        .ElementInvisible{
                                            display: none;
                                        }
                                    </style>
								<!--===================================================-->
								<!--End Panel with Header-->

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
        <footer id="footer">
            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                <ul class="footer-list list-inline">
                </li>
                </ul>
            </div>

        </footer>
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
    <script src="../js/funciones.js"></script>
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
    <script src="../plugins/audiojs/audio.min.js"></script>
    <script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/calidad/calidad.js"></script>
    <script src="../js/global.js"></script>

</body>
</html>
