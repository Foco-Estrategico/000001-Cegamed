<?PHP
	require_once('../db/db.php');
	include("../class/global/global.php");
	require_once('../class/session/session.php');
	$objetoSession = new Session('1,2,3,4,6',false); // 1,4
	//Para Id de Menu Actual (Menu Padre, Menu hijo)
	$objetoSession->crearVariableSession($array = array("idMenu" => "car,car"));
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
		<link href="../plugins/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="../plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
		<link href="../plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
		<link href="../css/carga.css" rel="stylesheet">
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
						<h1>Importar Telefonos</h1>
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Inicio</a></li>
						<li class="active">importar Telefonos</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="col-md-12">
								<div class="panel">
									<div class="panel-heading">
										<h3 class="panel-title bg-primary">
											<i class="ti-upload"></i> Subir documento
										</h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<p class="text-main text-bold mar-no">Importante!</p>
												<p>El documento a subir solo puede ser formato Excel o CSV.</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<form id="dropzoneExcel" class="dropzone">
													<div class="dz-default dz-message">
														<div class="dz-icon">
															<i class="demo-pli-upload-to-cloud icon-5x"></i>
														</div>
														<div>
															<span class="dz-text">Soltar archivos para cargar</span>
															<p class="text-sm text-muted">Haga clic para seleccionar manualmente</p>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="panel minPanel">
									<div class="panel-heading">
										<h3 class="panel-title bg-primary">
											<i class="ti-view-list"></i> Vincular campos
										</h3>
									</div>
									<div class="panel-body" id="vincularCampos">
										<h3>No se ah cargado un documento</h3>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="panel">
									<div class="panel-heading">
										<h3 class="panel-title bg-primary">
											<i class="ti-view-list"></i> Lista de telefonos correctos
										</h3>
									</div>
									<div class="panel-body" id="listCorrectos">

									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="panel">
									<div class="panel-heading">
										<h3 class="panel-title bg-primary">
											<i class="ti-view-list"></i> Lista de telefono que no cumplen la funcion
										</h3>
									</div>
									<div class="panel-body" id="listIncorrecto">

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

			<div class="modal fade" tabindex="-1" role="dialog" id="aviso">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><b>Alerta..!</b></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-offset-2 col-md-8">
								<h2 class="text-center">Está seguro de querer eliminar los datos?</h2>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary bno" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-default bsi" attr="" data-dismiss="modal">SI</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="modal fade" tabindex="-1" role="dialog" id="editFono">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><b>Editar Telefono</b></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<h4>Al editar se pasara automaticamente a la lista de correctos.</h4>
							</div>
							<div class="col-md-6 form-group">
								<label>Rut </label>
						  		<input type="text" name="Rut" class="form-control">
							</div>
							<div class="col-md-6 form-group">
								<label>Telefono </label>
						  		<input type="text" name="Fono" class="form-control">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default bno" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary updateFono" attr="">Actualizar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="modal fade" tabindex="-1" role="dialog" id="alertFile">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3>Disculpe!</h3>
						<h4>El archivo q esta intentando subir no corresponde al tipo de documento correcto</h4>
						<h4>Por favor verifique e intende de nuevo.</h4>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="modal fade" tabindex="-1" role="dialog" id="load">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
							<div class="spinner loading"></div>
							<h4 class="text-center">Procesando documento por favor espere...</h4>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

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
		<script src="../plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script src="../plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../plugins/dropzone/dropzone.min.js"></script>
		<script src="../plugins/bootbox/bootbox.min.js"></script>
		<script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>
		<script src="../js/imporFonos/controller.js"></script>
	</body>
</html>