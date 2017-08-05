<?php
require_once('../db/db.php');
include("../class/global/global.php");
require_once('../class/session/session.php');
$objetoSession = new Session('1,2,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "cal,cal_grank"));
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
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
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
            <li><a href="#">Calidad</a></li>
            <li class="active">Resumen Comparativo</li>
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
                                <h2 class="panel-title">Filtros</h2>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Seleccione Empresa:</label>
                                            <select class="selectpicker form-control" name="Empresa" title="Seleccione" data-live-search="true" data-width="100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Seleccione Cedente:</label>
                                            <select class="selectpicker form-control" name="Cedente" title="Seleccione" data-live-search="true" data-width="100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="control-label">Seleccione Periodo:</label>
                                            <select class="selectpicker form-control" name="Periodo" title="Todos" data-live-search="true" data-width="100%">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button id="Mostrar" class="btn btn-info col-sm-2 col-sm-offset-5">MOSTRAR</button>
                            </div>
                        </div>
                        <div id="result">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h2 class="panel-title">Resumen de notas</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="Charts Comparison" style="text-align: center;">
                                        <div class="Chart" style="height: calc(100vh - 40px)" id="Chart"></div>
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
    <script src="../js/global/funciones-global.js"></script>
    <script src="../js/calidad/resumen-ranking.js"></script>
    <!--Flot Chart [ OPTIONAL ]-->
    <script src="../plugins/flot-charts/jquery.flot.js"></script>
    <script src="../plugins/flot-charts/jquery.flot.resize.min.js"></script>    
</body>
</html>
