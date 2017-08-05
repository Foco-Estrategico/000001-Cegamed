<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Test de Reclutamiento</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="Test de Reclutamiento FOCO Estrategico" name="description" />
		<meta content="Foco Estrategico" name="author" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
		<link href="theme/css/plugins.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/layout.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/plugins/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
		<link href="theme/css/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
		<link href="theme/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	</head>
	<body class="page-container-bg-solid page-header-menu-fixed">
		<div class="page-wrapper">
			<div class="page-wrapper-row">
				<div class="page-wrapper-top">
					<div class="page-header">
						<div class="page-header-top">
							<div class="container">
								<div class="page-logo">
									<a href="index.html">
										<img src="theme/img/login-invert.png" alt="logo" class="logo-default">
									</a>
								</div>
								<a href="javascript:;" class="menu-toggler"></a>
								<div class="top-menu">
									<ul class="nav navbar-nav pull-right">
										<li class="dropdown dropdown-user dropdown-dark">
											<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
												<img alt="" class="img-circle" src="../img/av1.png">
												<span class="username username-hide-mobile"><span class="nameUser">Usuario</span>  <i class="fa fa-angle-down" aria-hidden="true"></i></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-default">
												<li>
													<a href="ajax/closeSession.php">
													<i class="icon-key"></i> Cerrar sesion</a>
												</li>
											</ul>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="page-header-menu">
							<div class="container">
								<div class="hor-menu">
									<ul class="nav navbar-nav">
										<li>
											<a href="actualizar_datos.php"> Actualizar Datos </a>
										</li>
										<li>
											<a href="prueba.php"> Prueba</a>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-wrapper-row full-height">
		<div class="page-wrapper-middle">
			<div class="page-container">
				<div class="page-content-wrapper">
					<div class="page-head">
						<div class="container">
							<div class="page-title">
								<h1>Actualizar Datos </h1>
							</div>
						</div>
					</div>
					<div class="page-content form-cont">
						<div class="container">
							<div class="page-content-inner">
								<div class="row">
									<div class="alert alert-success alert-dismissible display-hide" Id="Alert">
										<button type="button" class="close" id="closeAlert"><span aria-hidden="true">&times;</span></button>
										<strong>¡Bien hecho!</strong> <span id="mns"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="portlet light " id="form_wizard_1">
											<div class="portlet-title">
												<div class="caption">
													<i class=" icon-layers	"></i>
													<span class="caption-subject bold uppercase">Actualizacion de Datos
													</span>
												</div>
												<div class="actions">
													<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
												</div>
											</div>
											<div class="portlet-body form">
												<form class="form-horizontal" action="#" id="submit_form" method="POST">
													<div class="form-wizard">
														<div class="form-body">
															<ul class="nav nav-pills nav-justified steps">
																<li>
																	<a href="#tab1" data-toggle="tab" class="step">
																		<span class="number"> 1 </span>
																		<span class="desc">
																		<i class="fa fa-check"></i> Datos Generales </span>
																	</a>
																</li>
																<li>
																	<a href="#tab2" data-toggle="tab" class="step">
																		<span class="number"> 2 </span>
																		<span class="desc">
																		<i class="fa fa-check"></i> Datos Previcionales </span>
																	</a>
																</li>
																<li>
																	<a href="#tab3" data-toggle="tab" class="step active">
																		<span class="number"> 3 </span>
																		<span class="desc">
																		<i class="fa fa-check"></i> Contactos </span>
																	</a>
																</li>
																<li>
																	<a href="#tab4" data-toggle="tab" class="step">
																		<span class="number"> 4 </span>
																		<span class="desc">
																		<i class="fa fa-check"></i> Domicilio </span>
																	</a>
																</li>
															</ul>
															<div id="bar" class="progress progress-striped" role="progressbar">
																<div class="progress-bar progress-bar-success"> </div>
															</div>
															<div class="tab-content">
																<div class="alert alert-danger display-none">
																	<button class="close" data-dismiss="alert"></button> You have some form errors. Please check below.
																</div>
																<div class="alert alert-success display-none">
																	<button class="close" data-dismiss="alert"></button> Your form validation is successful!
																</div>
																<div class="tab-pane active" id="tab1">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="control-label col-md-3">
																					RUT
																				</label>
																				<div class="col-md-3">
																					<input type="text" class="form-control" name="rut">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Apellidos
																				</label>
																				<div class="col-md-6">
																					<input type="text" class="form-control" name="apellidos">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Nombres
																				</label>
																				<div class="col-md-6">
																					<input type="text" class="form-control" name="nombres">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Teléfono
																				</label>
																				<div class="col-md-4">
																					<input type="text" class="form-control" name="telefono">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Fecha de nacimiento
																				</label>
																				<div class="col-md-3">
																					<input type="text" class="form-control date-picker" name="fechaNacimeinto">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Correo
																				</label>
																				<div class="col-md-6">
																					<input type="text" class="form-control" name="correo">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="tab-pane" id="tab2">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="control-label col-md-3">
																					AFP
																				</label>
																				<div class="col-md-3">
																					<input type="text" class="form-control" name="afp">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Sistema de Salud
																				</label>
																				<div class="col-md-6">
																					<input type="text" class="form-control" name="sistemaSalud">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					UF
																				</label>
																				<div class="col-md-6">
																					<input type="text" class="form-control" name="uf">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Ges
																				</label>
																				<div class="col-md-4">
																					<input type="text" class="form-control" name="ges">
																				</div>
																			</div>

																			<div class="form-group">
																				<label class="control-label col-md-3">
																					Pensionado
																				</label>
																				<div class="col-md-3">
																					<input type="text" class="form-control" name="pensionado">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<style>
																	.PaddingLeft{
																		padding-left: 2%;
																		padding-right: 2%;
																	}
																</style>
																<div class="tab-pane" id="tab3">
																	<div class="row">
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Nombre:</label>
																				<input type="text" class="form-control" name="contactoNombre">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Parentesco:</label>
																				<input type="text" class="form-control" name="contactoParentesco">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">N° Celular 1:</label>
																				<input type="text" class="form-control" name="contactoCelular1">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">N° Celular 2:</label>
																				<input type="text" class="form-control" name="contactoCelular2">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Nombre:</label>
																				<input type="text" class="form-control" name="contacto2Nombre">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Parentesco:</label>
																				<input type="text" class="form-control" name="contacto2Parentesco">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">N° Celular 1:</label>
																				<input type="text" class="form-control" name="contacto2Celular1">
																			</div>
																		</div>
																		<div class="col-sm-3 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">N° Celular 2:</label>
																				<input type="text" class="form-control" name="contacto2Celular2">
																			</div>
																		</div>
																	</div>
																</div>
																<div class="tab-pane" id="tab4">
																	<div class="row">
																		<div class="col-sm-6 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Region:</label>
																				<input type="text" class="form-control" name="region">
																			</div>
																		</div>
																		<div class="col-sm-6 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Ciudad:</label>
																				<input type="text" class="form-control" name="ciudad">
																			</div>
																		</div>
																		<div class="col-sm-6 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Comuna:</label>
																				<input type="text" class="form-control" name="comuna">
																			</div>
																		</div>
																		<div class="col-sm-6 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Telefono Fijo:</label>
																				<input type="text" class="form-control" name="telefonoFijo">
																			</div>
																		</div>
																		<div class="col-sm-12 PaddingLeft">
																			<div class="form-group">
																				<label class="control-label">Direccion:</label>
																				<textarea class="form-control" name="direccion"></textarea>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-actions">
																	<div class="row">
																		<div class="col-md-offset-3 col-md-9">
																			<a href="javascript:;" class="btn default button-previous">
																			<i class="fa fa-angle-left"></i> Atras </a>
																			<a href="javascript:;" class="btn btn-outline green button-next"> <span>Siguiente</span>
																				<i class="fa fa-angle-right"></i>
																			</a>
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
							</div>
						</div>
					</div>
					<div class="page-wrapper-row">
						<div class="page-wrapper-bottom">
							<div class="page-footer">
								<div class="container">
									2017 &copy; FOCO Estrategico
								</div>
							</div>
							<div class="scroll-to-top">
								<i class="icon-arrow-up"></i>
							</div>
						</div>
					</div>
				</div>
				<script src="theme/plugins/jquery.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
				<script src="theme/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
				<script src="theme/plugins/jquery.blockui.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
				<script src="theme/js/app.min.js" type="text/javascript"></script>
				<script src="theme/js/layout.min.js" type="text/javascript"></script>
				<script src="theme/js/demo.min.js" type="text/javascript"></script>
				<script src="theme/js/quick-nav.min.js" type="text/javascript"></script>
				<script src="theme/plugins/ladda/spin.min.js" type="text/javascript"></script>
				<script src="theme/plugins/ladda/ladda.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
				<script src="theme/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
				<script src="../js/reclutamiento/controller.js"></script>
			</body>
		</html>