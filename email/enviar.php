<?php include('../db/connect.php');
include('../class/email/opciones.php');
include("../class/global/global.php");
require_once('../class/session/session.php');
$objetoSession = new Session('2',false); // 1,4
//include("../email/cron-email-masivo.php");
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "email,email_send"));
// ** Logout the current user. **
$objetoSession->creaLogoutAction(); // VERIFICAR FUNCIONAMIENTO DE ESTE METODO
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true"))
{ //
  //to fully log out a visitor we need to clear the session varialbles
    $objetoSession->borrarVariablesSession();
    $objetoSession->logoutGoTo("../index.php");
}
$validar = $_SESSION['MM_UserGroup'];
$objetoSession->creaMM_restrictGoTo();
$usuario = $_SESSION['MM_Username'];
$cedente = $_SESSION['cedente'];
$nombreUsuario = $_SESSION['nombreUsuario'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar email</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="../css/nifty.min.css" rel="stylesheet">
    <link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
    <link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">
    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
    <!--Summernote [ OPTIONAL ]-->
    <link href="../plugins/summernote/summernote.min.css" rel="stylesheet">



    <!--Custom CSS-->
    <style>
    #message{
        position: fixed;
        top:5px;
        left:50%;
        width:90%;
        z-index:99;
        max-width: 600px;
        transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -webkit-transform: translateX(-50%);
    }
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
    <!--=================================================-->


</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-sm">

        <!--NAVBAR-->
        <!--===================================================-->
        <?php include("../layout/header.php"); ?>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">

                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Enviar Email</h1>

                    <!--Searchbox-->
                    <!-- <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="demo-pli-magnifi-glass"></i></button>
                            </span>
                        </div>
                    </div> -->
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->

                <!--Breadcrumb-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <ol class="breadcrumb">
					<li><a href="#">Home</a></li>
					<li><a href="#">Email</a></li>
					<li class="active">Enviar</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div id="message">
                        <div class="alert alert-info"></div>
                    </div>
					<div class="row">
					    <div class="col-lg-12">
					        <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Seleccione Template</h3>
                                </div>
                                <div class="panel-body">
                                    <form>
                                        <div class="row">
                                          <div class="col-sm-9">
                                            <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="estrategia">Seleccione Estrategia</label>
                                                    <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="estrategia" name="estrategia">
                                                        <option value="">Seleccione</option>
                                                        <?php $estrategias = new opciones;
                                                            echo $estrategias->estrategias($con);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group" id="templatepicker">
                                                    <label for="template">Seleccione tabla de envió</label>
                                                    <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="tablaEnvio" name="tablaEnvio">
                                                        <option value="1">Cedente</option>
                                                        <option value="0">histórico</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group" id="templatepicker">
                                                    <label for="template">Seleccione Template</label>
                                                    <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="template" name="template">
                                                        <option value="5">Seleccione</option>
                                                        <?php $templates = new opciones;
                                                            echo $templates->general($con);
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            </div>
                                            </div>
                                            <div class="col-sm-3">
                                              <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="cantidad-rut">Cantidad de Rut Estrategia</label>
                                                        <input type="text" id="cantidad-rut" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="cantidad-emails">Cantidad de Email</label>
                                                        <input type="text" id="cantidad-emails" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Diseñar Mensaje</h3>
                                </div>
                                <div class="panel-body">
                                    <form role="form" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-lg-1 control-label text-left" for="asunto">Asunto</label>
                                            <div class="col-lg-11">
                                                <input type="text" id="asunto" class="form-control">
                                            </div>
                                        </div>
                                    </form>
                                    <div id="summernote"></div>
                                    <div class="checkbox">
                                        <label class="form-checkbox form-normal form-primary"><input type="checkbox" id="facturas"> Adjuntar Facturas</label>
                                    </div>
                                    <button id="enviar-mail" class="btn btn-primary" type="button">Enviar</button>
                                    <button id="clean-temp" class="btn btn-primary" type="button">Limpiar</button>
                                    <br><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Estado de envío de Email</h3>
                                </div>
                                <div class="panel-body">
                                    <p>Enviados: <span id="enviados"> - </span></p>
                                    <p>En espera: <span id="espera"> - </span></p>
                                    <p>Hora de envío: <span id="hora"> - </span></p>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Estado de envío de Email cola</h3>
                                </div>
                                <div class="panel-body">
                                    <form>
                                        <div class="form-group">
                                          <select class="selectpicker" title="Seleccione" data-live-search="true" data-width="100%" id="colaPendiente" name="colaPendiente">
                                          </select>
                                          <div class="panel-body">
                                              <p>Enviados: <span id="enviadosPendientes"> - </span></p>
                                              <p>En espera: <span id="esperaPendientes"> - </span></p>
                                              <p>Hora de envío: <span id="horaPendientes"> - </span></p>

                                          <button id="cancelarEnvio" class="btn btn-danger" type="button">Eliminar envío</button>
                                          <button id="continuarEnvio" class="btn btn-success" type="button">Continuar envío</button>
                                          </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Enviar Prueba</h3>
                                </div>
                                <div class="panel-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="email-prueba">Enviar Correo de Prueba</label>
                                            <input type="text" id="email-prueba" class="form-control">
                                        </div>
                                        <button id="enviar-prueba" class="btn btn-primary" type="button">Enviar</button>
                                    </form>
                                </div>
                            </div>
                            <!--
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Cron</h3>
                                </div>
                                <div class="panel-footer">
                                    <button id="ejecutar-cron" class="btn btn-primary" type="button">Ejecutar</button>
                                </div>
                            </div>
                            -->
                        </div>
					</div>

                </div>
                <!--End page content-->
            </div>
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
                You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
            </div>



            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">
                <!-- 14GB of <strong>512GB</strong> Free. -->
            </div>



            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <!-- <p class="pad-lft">&#0169; 2016 Your Company</p> -->



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
        <!--===================================================-->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->



    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->


      <!--JAVASCRIPT-->
    <!--=================================================-->
    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>
    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>
    <!--Fast Click [ OPTIONAL ]-->
    <script src="../plugins/fast-click/fastclick.min.js"></script>
    <!--Nifty Admin [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>
<!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>
<!--Bootstrap Select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!--Summernote [ OPTIONAL ]-->
    <script src="../plugins/summernote/summernote.min.js"></script>
<!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>
<!--SUMMERNOTE INITIATION-->
    <script src="../js/email/summernote-ini.js"></script><script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    <script src="../plugins/bootbox/bootbox.min.js"></script>
    <script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../js/global/funciones-global.js"></script>
<!-- Consultas -->
    <script src="../js/email/email.js"></script>
    <script src="../js/email/enviar.js"></script>


</body>
</html>
