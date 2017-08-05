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
								<form class="search-form" action="page_general_search.html" method="GET">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Buscar..." name="query">
										<span class="input-group-btn">
											<a href="javascript:;" class="btn submit">
												<i class="icon-magnifier"></i>
											</a>
										</span>
									</div>
								</form>
								<div class="hor-menu  ">
									<ul class="nav navbar-nav">
										<li>
											<a href="javascript:;"> Inicio </a>
										</li>
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
								<h1>Prueba de formulario wizard</h1>
							</div>
							<div class="page-toolbar">
							</div>
						</div>
					</div>
					<div class="page-content form-cont">
						<div class="container">
							<ul class="page-breadcrumb breadcrumb">
								<li>
									<a href="index.html">Inicio</a>
									<i class="fa fa-circle"></i>
								</li>
								<li>
									<span>Prueba de formulario wizard</span>
								</li>
							</ul>
							<div class="page-content-inner">
								<div class="row">

									 <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light " id="form_wizard_1">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class=" icon-layers	"></i>
                                                            <span class="caption-subject bold uppercase">Ejemplo Form Wizard -
                                                                <span class="step-title"> Para Jontahan </span>
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
                                                                                    <i class="fa fa-check"></i> Ejempo </span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#tab2" data-toggle="tab" class="step">
                                                                                <span class="number"> 2 </span>
                                                                                <span class="desc">
                                                                                    <i class="fa fa-check"></i> Ejemplo </span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#tab3" data-toggle="tab" class="step active">
                                                                                <span class="number"> 3 </span>
                                                                                <span class="desc">
                                                                                    <i class="fa fa-check"></i> Ejemplo </span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#tab4" data-toggle="tab" class="step">
                                                                                <span class="number"> 4 </span>
                                                                                <span class="desc">
                                                                                    <i class="fa fa-check"></i> Ejemplo </span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                                                        <div class="progress-bar progress-bar-success"> </div>
                                                                    </div>
                                                                    <div class="tab-content">
                                                                        <div class="alert alert-danger display-none">
                                                                            <button class="close" data-dismiss="alert"></button> You have some form errors. Please check below. </div>
                                                                        <div class="alert alert-success display-none">
                                                                            <button class="close" data-dismiss="alert"></button> Your form validation is successful! </div>
                                                                        <div class="tab-pane active" id="tab1">
                                                                            <h3 class="block">Provide your account details</h3>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Username
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="username" />
                                                                                    <span class="help-block"> Provide your username </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Password
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="password" class="form-control" name="password" id="submit_form_password" />
                                                                                    <span class="help-block"> Provide your password. </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Confirm Password
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="password" class="form-control" name="rpassword" />
                                                                                    <span class="help-block"> Confirm your password </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Email
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="email" />
                                                                                    <span class="help-block"> Provide your email address </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab2">
                                                                            <h3 class="block">Provide your profile details</h3>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Fullname
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="fullname" />
                                                                                    <span class="help-block"> Provide your fullname </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Phone Number
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="phone" />
                                                                                    <span class="help-block"> Provide your phone number </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Gender
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <div class="radio-list">
                                                                                        <label>
                                                                                            <input type="radio" name="gender" value="M" data-title="Male" /> Male </label>
                                                                                        <label>
                                                                                            <input type="radio" name="gender" value="F" data-title="Female" /> Female </label>
                                                                                    </div>
                                                                                    <div id="form_gender_error"> </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Address
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="address" />
                                                                                    <span class="help-block"> Provide your street address </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">City/Town
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="city" />
                                                                                    <span class="help-block"> Provide your city or town </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Country</label>
                                                                                <div class="col-md-4">
                                                                                    <select name="country" id="country_list" class="form-control">
                                                                                        <option value=""></option>
                                                                                        <option value="AF">Afghanistan</option>
                                                                                        <option value="AL">Albania</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Remarks</label>
                                                                                <div class="col-md-4">
                                                                                    <textarea class="form-control" rows="3" name="remarks"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab3">
                                                                            <h3 class="block">Provide your billing and credit card details</h3>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Card Holder Name
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="card_name" />
                                                                                    <span class="help-block"> </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Card Number
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" class="form-control" name="card_number" />
                                                                                    <span class="help-block"> </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">CVC
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" placeholder="" class="form-control" name="card_cvc" />
                                                                                    <span class="help-block"> </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Expiration(MM/YYYY)
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" placeholder="MM/YYYY" maxlength="7" class="form-control" name="card_expiry_date" />
                                                                                    <span class="help-block"> e.g 11/2020 </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-md-3">Payment Options
                                                                                    <span class="required"> * </span>
                                                                                </label>
                                                                                <div class="col-md-4">
                                                                                    <div class="checkbox-list">
                                                                                        <label>
                                                                                            <input type="checkbox" name="payment[]" value="1" data-title="Auto-Pay with this Credit Card." /> Auto-Pay with this Credit Card </label>
                                                                                        <label>
                                                                                            <input type="checkbox" name="payment[]" value="2" data-title="Email me monthly billing." /> Email me monthly billing </label>
                                                                                    </div>
                                                                                    <div id="form_payment_error"> </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab4">
                                                                            <h3 class="block">Heeee Terminaste al fin</h3>
                                                                            <h4>Te felicito</h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-actions">
                                                                    <div class="row">
                                                                        <div class="col-md-offset-3 col-md-9">
                                                                            <a href="javascript:;" class="btn default button-previous">
                                                                                <i class="fa fa-angle-left"></i> Back </a>
                                                                            <a href="javascript:;" class="btn btn-outline green button-next"> Continue
                                                                                <i class="fa fa-angle-right"></i>
                                                                            </a>
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