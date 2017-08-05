<?PHP
	require_once('../db/db.php');
	include("../class/global/global.php");
	require_once('../class/session/session.php');
	$objetoSession = new Session('1,2,3,4,6',false); // 1,4
	//Para Id de Menu Actual (Menu Padre, Menu hijo)
	$objetoSession->crearVariableSession($array = array("idMenu" => "car,carAu"));
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
					</div>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End page title-->
					<!--Breadcrumb-->
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<ol class="breadcrumb">
						<li><a href="#">Carga</a></li>
						<li class="active">Modulo Carga Cartera</li>
					</ol>
					<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!--End breadcrumb-->
					<!--Page content-->
					<!--===================================================-->
					<div id="page-content">
						<div class="row">
							<div class="panel minPanel">
								<div class="panel-heading">
									<h3 class="panel-title bg-primary">
										<i class="ti-upload"></i> Subir documento
									</h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<p class="text-main text-bold mar-no">Importante!</p>
											<p>El documento a subir solo puede ser formato Excel.</p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<form id="file-up" class="dropzone">
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
					<!--===================================================-->
					<!--End page content-->
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

						<div class="modal fade" tabindex="-1" role="dialog" id="alertProcesar">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							<div class="modal-body">
								<div class="Content" style="max-height: 400px;overflow: auto;"></div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
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
	<script id="TemplateCarga" type="text/template">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<div class="form-group">
					<label class="control-label">Tipo de Carga:</label>
					<select class="selectpicker form-control" name="TipoCarga" title="Seleccione" data-live-search="true" data-width="100%">
						<option value="carga">Carga</option>
						<option value="pagos">Pagos</option>
						<option value="marca">Marca</option>
					</select>
				</div>
			</div>
		</div>
		<div id="ContainerMarca" style="border: 2px dashed #cccccc;padding: 20px; display: none;">
			<h4>Tabla Afectada</h4>
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<div class="form-group">
						<label class="control-label">Tabla:</label>
						<select class="selectpicker form-control" name="Tabla" title="Seleccione" data-live-search="true" data-width="100%">
							<!-- <option value="Persona">Persona</option> -->
							<option value="Deuda">Deuda</option>
							<!-- <option value="Mail">Correos</option>
							<option value="fono_cob">Telefonos</option>
							<option value="Direcciones">Direcciones</option> -->
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<h4>Campo Relación</h4>
			</div>
			<div class="row" style="border-bottom: 1px solid #cccccc;">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Campo:</label>
						<select class="selectpicker form-control" name="CampoRelacion" title="Seleccione" data-live-search="true" data-width="100%"></select>
					</div>
				</div>
			</div>
			<div class="row">
				<h4>Campo Marca <i class="fa fa-plus addCampoMarca" style="color: green; cursor: pointer;"></i></h4>
			</div>
			<div class="row CampoMarca" style="border-bottom: 1px solid #cccccc;">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Campo:</label>
						<select class="selectpicker form-control" name="CampoMarca" title="Seleccione" data-live-search="true" data-width="100%"></select>
					</div>
				</div>
			</div>
		</div>
	</script>
	<script id="TemplateCampoMarca" type="text/template">
		<div class="row CampoMarca" style="border-bottom: 1px solid #cccccc;">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Campo:</label>
					<select class="selectpicker form-control" name="CampoMarca" title="Seleccione" data-live-search="true" data-width="100%">
						{CAMPOS}
					</select>
				</div>
			</div>
			<div class="col-sm-1">
				<i class="fa fa-close deleteCampoMarca" style="font-size: 25px; color: red; cursor: pointer; padding: 15px 0;"></i>
			</div>
		</div>
	</script>


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
		<script src="../js/carga/CargaAutomatica.js"></script>
		<script src="../js/global/funciones-global.js"></script>
	</body>
</html>
