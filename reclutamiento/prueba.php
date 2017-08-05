<?php
include_once("../includes/functions/Functions.php");
Main_IncludeClasses("db");
Main_IncludeClasses("reclutamiento");
/*$db = new DB();
$db->query("update pruebas_reclutamiento set status='1'");*/
$ReclutamientoClass = new Reclutamiento();
$PruebasDisponibles = $ReclutamientoClass->usuarioTienePruebasDisponibles();
?>
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
	</head>
	<style>
		.TimeContainer{
			text-align: center;
		}
			.Time{
				overflow: hidden;
				display: inline-block;
				border: 2px solid #333333;
				padding: 0 10px;
			}
			.Time.Positive{
				color: green;
			}
			.Time.Negative{
				color: red;
			}
				.Time .Digits{
					font-size: 50px;
					display: inline-block;
				}
				.Time .Digits.Separator:after{
					content: ":";
				}
			.Instrucciones{
				border: 2px solid #b9b9b9;
				padding: 10px 15px;
			}
				.Instrucciones p{
					margin: 0;
				}
	</style>
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
								<div class="hor-menu  ">
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
								<h1>Prueba </h1>
							</div>
							<div class="page-toolbar">
								<!--<button type="button" class="btn btn-primary ladda-button" id="Calificar"  data-style="expand-right" style="margin-top: 15px;"><span class="ladda-label"><i class="fa fa-floppy-o" aria-hidden="true"></i>  Continuar</span></button>-->
							</div>
						</div>
					</div>
					<div class="page-content form-cont">
						<div class="container">
							<div class="page-content-inner">
								<div class="row">
									<div class="portlet light">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-pencil-square-o"></i>
													<span class="caption-subject bold uppercase"> <?php if(!$PruebasDisponibles){ echo 'Aviso'; }else{ echo 'Preguntas'; } ?></span>
												</div>
												<div class="actions">
													<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title=""> </a>
												</div>
											</div>
										<div class="portlet-body">
											<?php
											$Prueba = "";
											if($PruebasDisponibles){
												$ArrayABC = array(0=>"");
												for ($i=65;$i<=90;$i++) {
													array_push($ArrayABC,strtolower(chr($i)));
												}
												$Prueba = $ReclutamientoClass->getPruebaActiva();
												$ReclutamientoClass->Perfil = $Prueba["id_perfil"];
												
												$Time = $Prueba["time"];
												$Minutes = sprintf("%'.02d\n",number_format($Time/60,0));
												$Seconds =  sprintf("%'.02d\n",$Time % 60);
												?>
													<div class="TimeContainer">
														<div class="Time Positive">
															<div class="Digits" id="Minutes"><?php echo $Minutes; ?></div>
															<div class="Digits Separator"></div>
															<div class="Digits" id="Seconds"><?php echo $Seconds; ?></div>
														</div>
													</div>
												<?php
												switch($Prueba["id_tipotest"]){
													case '1': //Default
														$Preguntas = $ReclutamientoClass->getPreguntas();
														$Cont = 1;
														if(count($Preguntas) > 0){
															foreach($Preguntas as $Pregunta){
																$ReclutamientoClass->idPregunta = $Pregunta["id"];
																$Opciones = $ReclutamientoClass->getOpciones();
																?>
																<div class="Pregunta" style="margin-top: 20px;" id="Pr_<?php echo $Pregunta['id']; ?>">
																	<h4><?php echo $Cont.".- ".utf8_encode($Pregunta["pregunta"]); ?></h4>
																	<form class="form-block">
																		<?php
																		foreach($Opciones as $Opcion){
																		?>
																		<div class="row" style="padding: 5px 0;margin-left: 20px;">
																			<label class="form-radio form-normal"><input type="radio" id="Op_<?php echo $Opcion['id']; ?>" name="<?php echo $Pregunta['id']; ?>"> <?php echo $Opcion['opcion']; ?></label>
																		</div>
																		<?php
																		}
																		?>
																	</form>
																</div>
																<?php
																$Cont++;
															}
															?>
																<br>
																<div class="row">
																	<div class="col-md-12">
																		<button class="btn btn-primary btn-lg pull-right" id="Calificar">Continuar</button>
																	</div>
																</div>
																<br>
															<?php
														}else{
														echo "No hay preguntas disponibles";
														}
													break;
													case '2': //Test de Competencias
														$Preguntas = $ReclutamientoClass->getPreguntasCompetencias();
														$Cont = 1;
														if(count($Preguntas) > 0){
															?>
																<h3>Instrucciones:</h3>
																<div class="Instrucciones">
																	<p>Seleccione un valor a las siguientes preguntas según el orden de importancia que usted considere, teniendo en cuenta que los valores son los siguientes: </p>
																	<p>1. NO ME IDENTIFICO</p>
																	<p>2. ME IDENTIFICO</p>
																	<p>3. ME IDENTIFICO MUCHO</p>
																</div>
															<?php
															foreach($Preguntas as $Pregunta){
																$ReclutamientoClass->idPregunta = $Pregunta["id"];
																$Opciones = $Pregunta['opciones'];
																$ArrayOpciones = array();
																$ArrayOpcionesTmp = explode(";",$Opciones);
																foreach($ArrayOpcionesTmp as $Opcion){
																	$Array = explode("_",$Opcion);
																	$Array = array("Value"=>$Array[0],"Text"=>$Array[1]);
																	array_push($ArrayOpciones,$Array);
																}
																$ArrayOpciones = array_sort($ArrayOpciones,"Value");
																$Opciones = $ReclutamientoClass->getOpcionesCompetencias();
																?>
																<div class="Pregunta" style="margin-top: 20px;" id="Pr_<?php echo $Pregunta['id']; ?>">
																	<h4><?php echo $Cont.".- ".utf8_encode($Pregunta["pregunta"]); ?></h4>
																	<form class="form-block">
																		<?php
																		$ContOpcion = 1;
																		foreach($Opciones as $Opcion){
																			?>
																			<div class="row Options" style="padding: 5px 0;margin-left: 20px;">
																				<h5 style="margin: 0 !important;"><?php echo $ArrayABC[$ContOpcion].") ".utf8_encode($Opcion['opcion']); ?></h5 style="margin: 0 !important;">
																				<div class="btn-group mt-radio-inline" style="padding-top: 15px;">
																					<?php
																					foreach($ArrayOpciones as $OpcionPregunta){
																					?>
																					<label class="mt-radio"><input type="radio" name="option_<?php echo $Pregunta['id']."_".$OpcionPregunta['Value']; ?>" value="<?php echo $Opcion['ponderacion']."_".$OpcionPregunta['Value']; ?>"><?php echo $OpcionPregunta["Text"]; ?><span></span></label>
																					<?php
																					}
																					?>
																				</div>
																			</div>
																			<?php
																			$ContOpcion++;
																		}
																		?>
																	</form>
																</div>
																<?php
																$Cont++;
															}
															?>
																<br>
																<div class="row">
																	<div class="col-md-12">
																		<button class="btn btn-primary btn-lg pull-right" id="Calificar">Continuar</button>
																	</div>
																</div>
																<br>
															<?php
														}else{
															echo "No hay preguntas disponibles";
														}
													break;
													case 3:
														?>
															<style>
																.Preguntas{
																	overflow: hidden;
																}
																	.Pregunta{
																		width: calc((100% / 4) - 2%/*Margen*/);
																		margin: 10px 1%;
																		float: left;
																	}
																		.Pregunta table{
																			width: 100%;
																		}
																			.Pregunta table tr{

																			}
																				.Pregunta table tr th{
																					text-align: center;
																					border: 1px solid #cccccc;
																				}
																					.Pregunta table tr th:nth-child(1){
																						width: 50%;
																					}
																						.Pregunta.Error table tr th:nth-child(1){
																							background-color: red;
																							color: #FFFFFF;
																						}
																					.Pregunta table tr th:nth-child(2), .Pregunta table tr th:nth-child(3){
																						width: 25%;
																					}
																				.Pregunta table tr td{
																					text-align: center;
																					border: 1px solid #cccccc;
																				}
																					.Pregunta table tr td.BoxSelection{
																						cursor: pointer;
																						display: table-cell !important;
																					}
																						.Pregunta table tr td.BoxSelection.Selected.Left{
																							background-color: green;
																						}
																						.Pregunta table tr td.BoxSelection.Selected.Right{
																							background-color: #f54f00;
																						}
															</style>
															<h3>Instrucciones:</h3>
															<div class="Instrucciones">
																<p>En cada uno de los 28 grupos de palabras, escoja la palabra que más lo(a) represente y márquela en la columna MAS  y escoja una palabra que menos lo(a) represente y márquela en la columna MENOS.</p>
															</div>
															<div class="Preguntas">
																<?php
																	$CantidadPreguntas = $ReclutamientoClass->getCantPreguntasPersonalidad();
																	for($i=1;$i<=$CantidadPreguntas; $i++){
																		?>
																			<div class="Pregunta">
																				<table>
																					<thead>
																						<tr>
																							<th class="NumeroPregunta"><?php echo $i; ?></th>
																							<th>MÁS</th>
																							<th>MENOS</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																							$Opciones = $ReclutamientoClass->getPreguntasPersonalidad($i);
																							foreach($Opciones as $Opcion){
																								?>
																									<tr id="O_<?php echo $Opcion["id"]; ?>">
																										<td><?php echo utf8_encode($Opcion["Opcion"]); ?></td>
																										<td class="BoxSelection Left" side="Left"></td>
																										<td class="BoxSelection Right" side="Right"></td>
																									</tr>
																								<?php
																							}
																						?>
																					</tbody>
																				</table>
																			</div>
																		<?php
																	}
																?>
															</div>
															<br>
															<div class="row">
																<div class="col-md-12">
																	<button class="btn btn-primary btn-lg pull-right" id="Calificar">Continuar</button>
																</div>
															</div>
															<br>
														<?php
													break;
												}
											}else{
												echo "Usted ya participo en el proceso de reclutamiento, debe esperar el proceso de calificación.";
											}
											?>
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
    </body>


<script src="theme/plugins/jquery.min.js" type="text/javascript"></script>
<script src="theme/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="theme/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="theme/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="theme/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="theme/js/app.min.js" type="text/javascript"></script>
<script src="theme/js/layout.min.js" type="text/javascript"></script>
<script src="theme/js/demo.min.js" type="text/javascript"></script>
<script src="theme/js/quick-nav.min.js" type="text/javascript"></script>
<script src="theme/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="theme/plugins/ladda/spin.min.js" type="text/javascript"></script>
<script src="theme/plugins/ladda/ladda.min.js" type="text/javascript"></script>
<script src="../plugins/bootbox/bootbox.min.js"></script>
<script>$('.nameUser').load('ajax/nameUser.php');</script>
<script>
	$(document).ready(function(){
		if($(".TimeContainer").size() > 0){
			ShowMessageCronometro();
		}
		function ShowMessageCronometro(){
			var Minutes = $(".Time #Minutes").html();
			var Seconds = $(".Time #Seconds").html();
			bootbox.alert("Para continuar con el proceso de seleccion deberá completar la siguiente prueba en un tiempo de "+Minutes+" minutos con "+Seconds+" segundos de lo contrario sera colocado como incompleto lo que influira en su proceso de selección",function(){
				InitCronometro();
			});
		}
		function InitCronometro(){
			var Cronometro = setInterval(function(){
				var Minutes = $(".Time #Minutes").html();
				var Seconds = $(".Time #Seconds").html();
				Seconds = Number(Seconds) - 1;
				Seconds = (Seconds >= 0) && (Seconds < 10) ? "0"+Seconds : Seconds;
				if((Number(Minutes) >= 1) && (Number(Seconds) >= 1)){
					var ObjectTime = $(".Time");
					if(!ObjectTime.hasClass("Positive")){
						ObjectTime.removeClass("Negative");
						ObjectTime.addClass("Positive");
					}
				}else{
					var ObjectTime = $(".Time");
					if(!ObjectTime.hasClass("Negative")){
						ObjectTime.removeClass("Positive");
						ObjectTime.addClass("Negative");
					}
				}
				if((Number(Minutes) == 0) && Number(Seconds) == 0){
					clearInterval(Cronometro);
					alert("Su tiempo ha culminado");
					MarcarNoTerminado();
				}
				if(Number(Seconds) < 0){
					Minutes = Number(Minutes) - 1;
					Minutes = Minutes < 10 ? "0"+Minutes : Minutes;
					$(".Time #Minutes").html(Minutes);
					$(".Time #Seconds").html("59");
				}else{
					$(".Time #Seconds").html(Seconds);
				}
			}, 1000);
		}
		function MarcarNoTerminado(){
			$.ajax({
				type: "POST",
				url: "ajax/marcarNoTerminada.php",
				dataType: "html",
				data: {},
				async: false,
				success: function(data){
					location.reload();
				},
				error: function(data){
				}
			});
		}
	});
</script>
    <?php
        if($PruebasDisponibles){
            ?>
                <script src="../js/reclutamiento/prueba_<?php echo $Prueba['id_tipotest']; ?>.js"></script>
            <?php
        }
    ?>
</body>
</html>