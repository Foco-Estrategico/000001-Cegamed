<?PHP
	require_once('../db/db.php');
	//include("../class/admin/conf_gestion.php");
	include("../class/global/global.php");
	require_once('../class/session/session.php');
	$objetoSession = new Session('1,2,3,4,6',false); // 1,4
	//Para Id de Menu Actual (Menu Padre, Menu hijo)
	$objetoSession->crearVariableSession($array = array("idMenu" => "gra,rptRecDA"));
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
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Foco | Software de Estrategia</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/nifty.min.css" rel="stylesheet">
		<link href="../premium/icon-sets/solid-icons/premium-solid-icons.min.css" rel="stylesheet">
		<link href="../plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="../plugins/themify-icons/themify-icons.min.css" rel="stylesheet">
		<link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">
		<link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../plugins/animate-css/animate.min.css" rel="stylesheet">
		<link href="../plugins/dropzone/dropzone.css" rel="stylesheet">
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-dataTables/jquery.dataTables.css" rel="stylesheet"  media="screen">
        <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">
	</head>
	<body>
		<div id="container" class="effect mainnav-sm">
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
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Reporteria</a></li>
						<li class="active">Reporte de Recupero Diario</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title bg-primary">
                                        Filtros de bsuqueda
									</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Seleccione Mes:</label>
                                                <select class="selectpicker form-control" name="Mes" title="Seleccione" data-live-search="true" data-width="100%"></select>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
						</div>
                        <div class="row">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title bg-primary">
                                        Gráfico de Recupero Diario
									</h3>
								</div>
								<div class="panel-body">
									<div class="row">
                                        <div class="Chart" id="RecuperoDiario" style="width:100%;height:400px;"></div>
                                    </div>
								</div>
							</div>
                        </div>
                        <div class="row">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title bg-primary">
                                        Gráfico de Recupero Acumulado
									</h3>
								</div>
								<div class="panel-body">
                                    <div class="row">
                                        <div class="Chart" id="RecuperoAcumulado" style="width:100%;height:400px;"></div>
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
		<script src="../plugins/skycons/skycons.min.js"></script>
		<script src="../plugins/switchery/switchery.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/demo/nifty-demo.min.js"></script>
		<script src="../js/global.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../plugins/dropzone/dropzone.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../plugins/bootstrap-dataTables/jquery.dataTables.js"></script>
    	<script src="../plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="../js/global/funciones-global.js"></script>
        <!--Flot Chart [ OPTIONAL ]-->
        <script src="../plugins/flot-charts/jquery.flot.js"></script>
        <script src="../plugins/flot-charts/jquery.flot.stack.js"></script>
        <script src="../plugins/flot-charts/jquery.flot.resize.min.js"></script>
        <script src="../plugins/morris-js/morris.min.js"></script>
        <script src="../plugins/morris-js/morris_horizontal.min.js"></script>
        <script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>
        <script src="../js/reporte/ReporteRecuperoDiario.js"></script>
	</body>
</html>
