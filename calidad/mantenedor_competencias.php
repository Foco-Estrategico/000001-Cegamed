<?PHP
require_once('../db/db.php');
include("../class/global/global.php");

require_once('../class/session/session.php');
$objetoSession = new Session('1,2,6',false); // 1,4
//Para Id de Menu Actual (Menu Padre, Menu hijo)
$objetoSession->crearVariableSession($array = array("idMenu" => "cal,mant_comp"));
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
                                
                                    <div class="row">
                                        <div class="panel primary">
                                            <div class="panel-heading bg-primary">
                                                <h3 class="panel-title">Configuración de Competencias</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-sm-12" style='margin-bottom: 20px;'>
                                                    <button id="addCompetencia" class="btn btn-primary" type="submit">Agregar</button>
                                                </div>
                                                <table id="Competencias" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Competencia</th>
                                                            <th>Ponderación</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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

    <script id="TemplateAddCompetencia" type="text/template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Nombre</label>
                    <div>
                        <input name="NombreCompetencia" type="text" placeholder="Nombre" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Descripcion</label>
                    <div>
                        <input name="DescripcionCompetencia" type="text" placeholder="Descripción" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Ponderación</label>
                    <div>
                        <input name="PonderacionCompetencia" type="number" min="1" max="100" class="form-control" placeholder="Ponderación">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Tag</label>
                    <div>
                        <input name="TagCompetencia" type="text" placeholder="Tag" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="TemplateAddDimension" type="text/template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Nombre</label>
                    <div>
                        <input name="Nombre" type="text" placeholder="Nombre" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Ponderación</label>
                    <div>
                        <input name="Ponderacion" type="number" min="1" max="100" class="form-control" placeholder="Ponderación">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="TemplateDimensiones" type="text/template">
        <div class="col-sm-12" style='margin-bottom: 20px;'>
            <button id="addDimension" competencia="{COMPETENCIA}" class="btn btn-primary" type="submit">Agregar</button>
        </div>
        <div class="row">
            <div class="col md-12">
                <table id="Dimensiones" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Dimension</th>
                            <th>Ponderación</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </script>
    <script id="TemplateAddAfirmacion" type="text/template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Nombre</label>
                    <div>
                        <input name="Nombre" type="text" placeholder="Nombre" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Ponderación</label>
                    <div>
                        <input name="Ponderacion" type="number" min="1" max="100" class="form-control" placeholder="Ponderación">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Descripción Simple</label>
                    <div>
                        <input name="DescripcionSimple" type="text" placeholder="Descripcion Simple" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Corte</label>
                    <div>
                        <input name="Corte" type="number" min="1" max="100" class="form-control" placeholder="Corte">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="TemplateAfirmaciones" type="text/template">
        <div class="col-sm-12" style='margin-bottom: 20px;'>
            <button id="addAfirmacion" dimension="{DIMENSION}" class="btn btn-primary" type="submit">Agregar</button>
        </div>
        <div class="row">
            <div class="col md-12">
                <table id="Afirmaciones" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Afirmacion</th>
                            <th style="width: 10%;">Ponderación</th>
                            <th style="width: 20%;">Descripción Simple</th>
                            <th style="width: 10%;">Corte</th>
                            <th style="width: 10%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </script>
    <script id="TemplateAddOpcionAfirmacion" type="text/template">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Nombre</label>
                    <div>
                        <input name="Nombre" type="text" placeholder="Nombre" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Valor</label>
                    <div>
                        <input name="Valor" type="number" min="1" max="100" class="form-control" placeholder="Valor">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="name">Descripción Caracteristica</label>
                    <div>
                        <input name="DescripcionCaracteristica" type="text" placeholder="Descripcion Caracteristica" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script id="TemplateOpcionesAfirmaciones" type="text/template">
        <div class="col-sm-12" style='margin-bottom: 20px;'>
            <button id="addOpcionAfirmacion" afirmacion="{AFIRMACION}" class="btn btn-primary" type="submit">Agregar</button>
        </div>
        <div class="row">
            <div class="col md-12">
                <table id="Opciones" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Opción</th>
                            <th style="width: 10%;">Valor</th>
                            <th style="width: 40%;">Descripción Característica</th>
                            <th style="width: 10%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </script>

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
    <script src="../js/calidad/mantenedor_competencias.js"></script>
</body>
</html>