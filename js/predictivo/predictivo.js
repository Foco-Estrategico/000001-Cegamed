$(document).ready(function()
{
    $('#contenido').hide();
	var id_dial = $('#id_dial').val();
	var rut_dial = $('#rut_ultimo').val();
	var cedente_dial = $('#IdCedente').val();
	var cedente= $('#IdCedente').val();
	var fono_dial = $('#fono_dial').val();
	var usuario_dial = $('#usuario_dial').val();
	var dateInicioLlamada;
	var idFilaFono;
	var rutEstrategia;
	var intervalollamada;
	var intervaloEsperandoLlamada;
	var intervaloHangup;
	var pausaEjec; // 1 = noPausa --- 2 = pausa
	var color;
	var texto;
	var icono;
	var nombrePausa;
	var sonido;

	var tiempo = {
        hora: 0,
        minuto: 0,
        segundo: 0
    };

    var tiempo_corriendo = null;



function capturaHangup() // colgar
{
	$.ajax(
	{            
		type: "POST",
		url: "../includes/crm/capturaHangup.php",
		//data:datos,
		success: function(response)
		{
			console.log(response);
			if (response == 1){
				ejecutarSonido('../sonidos/hangup.wav');
				nuevoEstatus('DEAD','');
			}
				
		},
		error: function(response){     
			alert(response);
		}
	});
}

function intervalo_hangup(){
	intervaloHangup = setInterval(function(){
						capturaHangup();
					},500);	
}				


 function ejecutarSonido(sonido){
	var rutaSonido = sonido; 
	var sonido = new Audio();
	sonido.addEventListener('play', function () {
		//
	}, false);
	
	//Cuarto evento: el audio acaba de terminar 
	sonido.addEventListener('ended', function () {
		/*consola.innerHTML += '* <strong>ended</strong>: ' +
			'¡El audio acaba de terminar!<br/>';*/
	}, false);

	sonido.src = rutaSonido;
	sonido.play();
 }

$(document).on('click', '.Break', function() {
	var desactivado = $(this).closest('i').attr('disabled');
	if (desactivado != 'disabled'){
		// necesito saber si esta en pausa o no
		if (pausaEjec == 1){
			// noPausa
			var id = $(this).closest('i').attr('id');
			pausaEjecutivo(id);
			
		}else{
			if (pausaEjec == 2){
				// pausa
				unPauseEjecutivo();
			}	
		}
		
	}	
});

function desactivarBotonera(id){
	var idBoton;
	$("#Botonera i").each(function(){
		idBoton = $(this).attr('id');
		if ((idBoton) != (id)){
			$(this).attr('disabled', 'disabled');
		}        	   
   	});
}

function activarBotonera(){
	$("#Botonera i").each(function(){
		$(this).removeAttr('disabled');
	});
}

function accionesBotonera(id){
	switch (id){
		case 'bano':
			color = 'danger';
			texto = 'Estoy en el Baño';
			icono = 'ion-waterdrop';
			nombrePausa = 'Bano';
		break;
		case 'descanso':
			color = 'info';
			texto = 'Tomando café';
			icono = 'ion-coffee';
			nombrePausa = 'Cafe';
		break;
		case 'soporte':
			color = 'warning';
			texto = 'Soporte';
			icono = 'ion-settings';
			nombrePausa = 'Soporte';
		break;
		case 'office':
			color = 'mint';
			texto = 'Oficce';
			icono = 'ion-edit';
			nombrePausa = 'Office';
		break;
		case 'capacitacion':
			color = 'purple';
			texto = 'Estoy en Capacitación';
			icono = 'ion-help-buoy';
			nombrePausa = 'Capacitacion';
		break;
		case 'reunion':
			color = 'success';
			texto = 'Estoy en reunión';
			icono = 'ion-person-stalker';
			nombrePausa = 'Reunion';
		break;
	}
	
}




function pausaEjecutivo(id){
	// simulacro de salir clearInterval(intervalollamada);
	desactivarBotonera(id);
	pausaEjec = 2;
	clearInterval(intervalollamada);
	$("#CallNotification").closest(".alert").remove();
	ShowNotification = false;
	accionesBotonera(id);
	nuevoEstatus('PAUSED',nombrePausa);
	intervalo_EsperandoLlamada(texto,color,icono);
	pausePredictivo();
	
}

function unPauseEjecutivo(){
	// simulacro de entrar
	var cedente = $('#IdCedente').val();
	rutEstrategia = 1; // para indicar que se encuentra en buscar
	//funcionLimpiar();
	var rut_buscado;
	var fono;
	var idCola = $('#seleccione_tipo_busqueda').val();	
	$("#CallNotification").closest(".alert").remove();
	activarBotonera();
	pausaEjec = 1;
	nuevoEstatus('DISPONIBLE','');
	intervaloLlamada();		        
	unPausePredictivo();
}	

function tiempollamadaInicio(){
	dateInicioLlamada = new Date();
}

function transcurrido(){
	var dateFinLlamada = new Date(); 
    //La diferencia se da en milisegundos así que se debe dividir entre 1000
    var diferencia = (dateFinLlamada-dateInicioLlamada);
	segLlamadaTranscurrido = Math.floor(diferencia / 1000);
	return segLlamadaTranscurrido;
}

	function limpiarSesion()
	{
		$.ajax(
	    {
			type: "POST",
			url: "../includes/crm/limpiar_sesion.php",
			data: 'a=1',
			success: function()
			{
				console.log('impliar sesion');
			}
		});
	}
	if(id_dial==1)
	{
		var data_fono = 'rut='+rut_dial;
		var data_deudas = 'rut='+rut_dial+"&cedente="+cedente_dial;

	}
	else
	{

		var nombre_usuario_foco = $('#nombre_usuario_foco').val();

		// Para que el campo acepte solo numeros
		$(document).on('keyup','.solo-numero',function (){
			console.log("Si paso ");
	            this.value = (this.value + '').replace(/[^0-9]/g, '');
	          });

		function validarNuevoCorreo()
		{
			var sw1 = 0;
			var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#nombre").val() == "" ){
				$("#nombre").focus().after("<span class='errorL'>Ingrese un nombre</span>");
			    sw1 = 1;
		    }
		    if( $("#correo_nuevo").val() == "" || !emailreg.test($("#correo_nuevo").val()) ){
	        	$("#correo_nuevo").focus().after("<span class='errorL'>Ingrese un email correcto</span>");
				sw1 = 1;
	     	}
	     	if( $("#cargo").val() == "0"  ){
	        	$("#cargo").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}console.log("paso aqui: " + $("#uso").val());
	     	if(  $("#uso").val() == null || $("#uso").val() == "0" ){
	        	$("#uso").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};
		function validarNuevoCorreocc()
		{
			var sw1 = 0;
			var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#nombre_cc").val() == "" ){
				$("#nombre_cc").focus().after("<span class='errorL'>Ingrese un nombre</span>");
			    sw1 = 1;
		    }
		    if( $("#correo_nuevo_cc").val() == "" || !emailreg.test($("#correo_nuevo_cc").val()) ){
	        	$("#correo_nuevo_cc").focus().after("<span class='errorL'>Ingrese un email correcto</span>");
				sw1 = 1;
	     	}
	     	if( $("#cargo_cc").val() == "0"  ){
	        	$("#cargo_cc").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}console.log("paso aqui: " + $("#uso_cc").val());
	     	if(  $("#uso_cc").val() == null || $("#uso_cc").val() == "0" ){
	        	$("#uso_cc").focus().after("<span class='error'>Seleccione una opción</span>");
				sw1 = 1;
	     	}
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};

		function validarNuevaDireccion()
		{
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#direccion_nuevo").val().trim() == "" ){
				$("#direccion_nuevo").focus().after("<span class='errorL'>Ingrese una dirección</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};

		function validarNuevoTelefono()
		{
			var sw1 = 0;
			$(".error").remove();
			$(".errorL").remove();
			if( $("#fono_discado_nuevo").val().trim() == "" ){
				$("#fono_discado_nuevo").focus().after("<span class='errorL'>Ingrese un telefono</span>");
			    sw1 = 1;
		    }
		    if (sw1 == 0)
		    {
		    	return false;
		    }else{
		    	return true;
		    }
		};


		var user_dial = $('#usuario_usuario_foco').val();
		$(document).on('click', '#AddCorreoN', function() {
			console.log("paso aqui");
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreo();
			if (resValidacion == true)
			{
				return false;
			}
			var correo_nuevo = $('#correo_nuevo').val();
			var rut_correo = $('#rut_ultimo').val();
			var cargo = $('#cargo').val();
			var uso = $('#uso').val();
			var nombre = $('#nombre').val();
			var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso+"&nombre="+nombre;
			console.log(data_correo_nuevo);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertar_correo.php",
				data:data_correo_nuevo,
				success: function(response)
				{
					limpiarSesion();
					$('#mostrar_correo').html(response);
					console.log(response);
					$('#AggCorreoModal').modal('hide');
					$('#correo_nuevo').val("") ;
					$('#cargo').prop('selectedIndex',0);
					$('#uso').val("").selectpicker('refresh');
				}
			});


			$.niftyNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : "Registro Guardado",
				container : 'floating',
				timer : 4000
			});

		});

		$(document).on('click', '#AddCorreoNcc', function() {
			console.log("paso aqui");
			var resp = '';
			var data = 'id=1';
			var resValidacion = validarNuevoCorreocc();
			if (resValidacion == true)
			{
				return false;
			}
			var correo_nuevo = $('#correo_nuevo_cc').val();
			var rut_correo = $('#rut_ultimo_cc').val();
			var cargo = $('#cargo_cc').val();
			var uso = $('#uso_cc').val();
			var nombre = $('#nombre_cc').val();
			var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso+"&nombre="+nombre;
			console.log(data_correo_nuevo);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/insertar_correo_cc.php",
				data:data_correo_nuevo,
				success: function(response)
				{
					limpiarSesion();
					$('#mostrar_correo_cc').html(response);
					console.log(response);
					$('#AggCorreoModalcc').modal('hide');
					$('#correo_nuevo_cc').val("") ;
					$('#cargo_cc').prop('selectedIndex',0);
					$('#uso_cc').val("").selectpicker('refresh');
				}
			});


			$.niftyNoty({
				type: 'success',
				icon : 'fa fa-check',
				message : "Registro Guardado",
				container : 'floating',
				timer : 4000
			});

		});
		function fonosLlamando($tipoGestion){
			// verifico si la gestion es 1 y 5 y paso al siguiente Rut
			if (($tipoGestion == 1) || ($tipoGestion == 5)){
				nextRut();
			}else{
				// si la gestion es diferente a 1 y 5 marco el que acabo de llamar

				//alert(idFilaFono);
				$("#llamado"+idFilaFono).prop('checked',true);
				//$('#idFilaFono').attr('style', 'background-color:#CCFFFF');
				//background-color: #F3F781
				// verfico si tiene mas telefonos para Llamar
				var CantFilas = 0;
				var CantMarcados = 1; // style="background-color:#CCFFFF"
				$("#mostrar_fonos table tr").each(function(indexTR){
					var ObjectTR = $(this);
					if(indexTR > 0){
						ObjectTR.find("td").each(function(indexTD){
							var ObjectTD = $(this);
							switch(indexTD){
								case 4:
								var Checkbox = ObjectTD.find("input[type='checkbox']");
								if(Checkbox.is(":checked")){
									CantMarcados++;
								}
								break;
							}
						});
						CantFilas++;
					}
				});
				//alert(CantFilas);
				//alert(CantMarcados);
				if(CantMarcados >= CantFilas){	
					//alert('entro')			
					nextRut();
				}
			}
		}
		function funcionMostrarFonos(data_fono)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_fonos_rut.php",
				data:data_fono,
				success: function(response)
				{
					$('#mostrar_fonos').html(response);
					$('#mostrar_fonos_ocultar').hide();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
					$('#nuevo_correo_cc').prop("disabled",false);
					$('#script_cobranza_mostrar').show();
					$('#script_cobranza_ocultar').hide();
				}
			});
		}

        function funcionMostrarFono(rut, fon)
		{	
            $.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_fono.php",
				data: {rut: rut, fono: fon},
				success: function(response)
				{
					$('#mostrar_fonos').html(response);
					$('#mostrar_fonos_ocultar').hide();
					$('#nuevo_telefono').prop("disabled",false);
					$('#nuevo_direccion').prop("disabled",false);
					$('#nuevo_correo').prop("disabled",false);
					$('#nuevo_correo_cc').prop("disabled",false);
					$('#script_cobranza_mostrar').show();
					$('#script_cobranza_ocultar').hide();
				}
			});
		}
		function funcionMostrarDireccion(data_direccion)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_direccion_rut.php",
				data:data_direccion,
				success: function(response)
				{
					$('#mostrar_direccion').html(response);
					$('#mostrar_direccion_ocultar').hide();

				}
			});
		}
		function funcionMostrarDeudas(data_deudas)
		{
			$.ajax(
			{
				
				type: "POST",
				url: "../includes/crm/deudas.php",
				data:data_deudas,
				success: function(response)
				{
					console.log(response);
					$('#mostrar_deudas').html(response);

					funcionMostrar();
					funcionOcultar();
				},
            error: function(){     
     
            }
			});
		}
		function funcionMostrarRegistros(data_reg)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_reg.php",
				data:data_reg,
				success: function(response)
				{
					$('#cantidad').html(response);
				}
			});
		}
        function funcionMostrarNombreCliente(rut)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_nombreRut.php",
				data:{rut:rut},
				success: function(response)                
				{
			        $('#nombre_cliente').html(response);
				}
			});
		}
		function funcionMostrarCorreo(data_correo)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_correo_rut.php",
				data:data_correo,
				success: function(response)
				{
					$('#mostrar_correo').html(response);
					$('#mostrar_correo_ocultar').hide();

				}
			});
		}
		function funcionMostrarCorreocc(data_correo)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_correo_rut_cc.php",
				data:data_correo,
				success: function(response)
				{
					$('#mostrar_correo_cc').html(response);
					$('#mostrar_correo_ocultar_cc').hide();

				}
			});
		}
		function funcionMostrarGestion(data_gestion)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_rut.php",
				data:data_gestion,
				success: function(response)
				{
					$('#mostrar_gestion').html(response);
					$('#mostrar_gestion_ocultar').hide();
					$('#mostrar_gestion').show();
				}
			});
		}
		function funcionMostrarGestionTotal(data_gestion_total)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_total_rut.php",
				data:data_gestion_total,
				success: function(response)
				{
					
					$('#mostrar_gestion_total').html(response);
					$('#mostrar_gestion_total_ocultar').hide();
					$('#mostrar_gestion_total').show();
				}
			});
		}
		function funcionMostrarGestionDiaria(data_gestion_diaria)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_gestion_diaria_rut.php",
				data:data_gestion_diaria,
				success: function(response)
				{
					$('#mostrar_gestion_diaria').html(response);
					$('#mostrar_gestion_diaria_ocultar').hide();
					$('#mostrar_gestion_diaria').show();
				}
			});
		}
		function funcionMostrarPagos(data_pagos)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_pagos_rut.php",
				data:data_pagos,
				success: function(response)
				{
					$('#mostrar_pagos').html(response);
					$('#mostrar_pagos_ocultar').hide();
				}
			});
		}
		function funcionMostrarAgrupacion(cedente,prefijo,rut)
		{
			var rut = rut;
			//var data1 = "rut="+rut;
			var data1 = "rut="+rut+"&pantalla=predictivo";
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			//var data3 = "rut="+rut+"&cedente="+cedente;
			var data3 = "rut="+rut+"&cedente="+cedente+"&pantalla=predictivo";
			var data4 = "cedente="+cedente;
			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3)
			//funcionMostrarRegistros(data2);
			//funcionMostrarFonos(data2);
			funcionMostrarCorreo(data1);
			//funcionMostrarCorreocc(data1)
			funcionMostrarGestion(data1);
			funcionMostrarGestionTotal(data1);
			funcionMostrarGestionDiaria(data1);
			funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
            mostrarScriptCobranzaCedente();
			limpiarSesion();
		}
		function unPausePredictivo(){
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/unPausePredictivo.php",

				//data:ce,
				async: false,
				success: function(response)
				{

					//$('.nivel_1_ocultar').hide();
					//$('.nivel_1_mostrar').html(response);
					funcionLimpiar();
					ShowNotification = false;
					intervalo_EsperandoLlamada('Esperando LLamada','danger','pli-old-telephone');
					nuevoEstatus('DISPONIBLE','');
					
				}
			});		

		}
		function pausePredictivo(){
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/pausaPredictivo.php",
				//data:ce,
				async: false,
				success: function(response)
				{		
					consolel.log(response);
					consolel.log('20');
				}
			});		

		}
		function funcionMostrarAgrupacionNoFono(cedente,prefijo,rut)
		{
			var rut = rut;
			var data1 = "rut="+rut;
			var data2 = "rut="+rut+"&prefijo="+prefijo;
			var data3 = "rut="+rut+"&cedente="+cedente;
			var data4 = "cedente="+cedente;
			tiempollamadaInicio();
			funcionMostrarDireccion(data1);
			funcionMostrarDeudas(data3)
			funcionMostrarRegistros(data2);
			//funcionMostrarFonos(data2);
			funcionMostrarCorreo(data1);
			funcionMostrarCorreocc(data1)
			funcionMostrarGestion(data1);
			funcionMostrarGestionTotal(data1);
			funcionMostrarGestionDiaria(data1);
			funcionMostrarPagos(data1);
			funcionNivelRapido(data4);
			limpiarSesion();
		}
		function funcionNivelRapido(ce)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/nivel_rapido.php",
				data:ce,
				success: function(response)
				{
					$('#respuesta_rapida').html(response);
					$('#respuesta_rapida_ocultar').hide();
				}
			});
		}
		function funcionNivel1(ce)
		{
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/nivel_1.php",
				data:{ cedente:ce },
				async: false,
				success: function(response)
				{
					$('.nivel_1_ocultar').hide();
					$('.nivel_1_mostrar').html(response);
					//$(document).on('change', '#seleccione_nivel1', function()
					$('#seleccione_nivel1').change(function()
					{
						$('#tipo_gestion').val('');
						$('.nivel_2_ocultar').hide();
						$('.nivel_2_mostrar').show();
						var nivel2 = $('#seleccione_nivel1').val();
						var nivel2 = "nivel2="+nivel2;
						$.ajax(
						{
							type: "POST",
							url: "../includes/crm/nivel_2.php",
							data:nivel2,
							async: false,
							success: function(response)
							{
								$('.nivel_2_mostrar').html(response);
								//$(document).on('change', '#seleccione_nivel2', function()
								$('#seleccione_nivel2').change(function()
								{
									$('.nivel_3_ocultar').hide();
									$('.nivel_3_mostrar').show();
									var nivel3 = $('#seleccione_nivel2').val();
									var nivel3 = "nivel3="+nivel3;
									console.log(nivel3);
									$.ajax(
									{
										type: "POST",
										url: "../includes/crm/nivel_3.php",
										data:nivel3,
										async: false,
										success: function(response)
										{
											$('.nivel_3_mostrar').html(response);
											var tipo_gestion = $('#tipo_gestion').val();
											if (tipo_gestion==5)
											{
												$('#seleccione_nivel3').html("<select class='select1' id='seleccione_nivel3' name='seleccione_nivel3'><option value='0'>Seleccione</option><option value='0'>COMPROMISO</option></select>");
											}
											else
											{
												$('.nivel_3_mostrar').html(response);
											}

											//$(document).on('change', '#seleccione_nivel3', function()
											$('#seleccione_nivel3').change(function()
											{

												if($('#ultimo_fono').val()==0)
												{
													$.niftyNoty({
														type: 'danger',
														icon : 'fa fa-close',
														message : "Debe finalizar la Gestión para poder guardar una nueva gestión!",
														container : 'floating',
														timer : 4000
													});
													$('#seleccione_nivel3').prop('selectedIndex',0);
												}
												else
												{
													$('#grupo1').show();
													fono_discado = $('#ultimo_fono').val();
													console.log(tipo_gestion); // tipo_gestion
													var nivel4 = "id_tipo="+$('#tipo_gestion_final').val()+"&cortar_valor="+cortar_valor;
													$.ajax(
													{
														type: "POST",
														url: "../includes/crm/nivel_4.php",
														data:nivel4,
														async: false,
														success: function(response)
														{
															$('#grupo1_ocultar').hide();
															$('#grupo1').html(response);
															//$(document).on('click', '#guardar', function()
															$('#guardar').click(function()
															{																
																var tiempoLlamada = transcurrido();
																var i = 1;
																while(i<=10)
																{
																	$('#call'+i).prop("disabled",false);
																	i++;
																}
																$('#next_rut').prop("disabled",false);
																$('#prev_rut').prop("disabled",false);
																var fecha_compromiso = $('#fecha_compromiso').val();
																var monto_compromiso = $('#monto_compromiso').val();
																var fechaAgendamiento = $('#fecha_agendamiento').val();
																var horaAgendamiento = $('#hora_agendamiento').val();
																var comentario= $('#comentario').val();
																if(fecha_compromiso =='' || monto_compromiso =='' || comentario =='' || fechaAgendamiento =='' || horaAgendamiento == 0)
																{
																	alert('Debe Completar todos los campos!');
																	return 0;
																}
																var cedente = $('#IdCedente').val();

																var nivel1 = $('#seleccione_nivel1').val();
																var nivel2 = $('#seleccione_nivel2').val();
																var nivel3 = $('#seleccione_nivel3').val();
																var tipo_gestion2 = $('#tipo_gestion').val();
																var tipo_gestion_final = $('#tipo_gestion_final').val();
																var asignacion = $('#prefijo').val();
																console.log(tipo_gestion_final);

																var rut_ultimo = $('#rut_ultimo').val();
																if(tipo_gestion2==136 || tipo_gestion2==37)
																{

																	var fecha = new Date();
																	var fecha_gestion = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
																	var hora_gestion = fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
																	var numero_cola = $('#numero_cola').val();
																	var NombreGrabacion = $('#NombreGrabacion').val();
																	var origen = 1;
																	var insertar2 = "nivel1="+nivel1+"&nivel2="+nivel2+"&nivel3="+nivel3+"&comentario="+comentario+"&fecha_gestion="+fecha_gestion+"&hora_gestion="+hora_gestion+"&rut="+rut_ultimo+"&fono_discado="+fono_discado+"&tipo_gestion="+tipo_gestion_final+"&cedente="+cedente+"&fecha_compromiso="+fecha_compromiso+"&monto_compromiso="+monto_compromiso+"&usuario_foco="+nombre_usuario_foco+"&lista="+numero_cola+"&tiempoLlamada="+tiempoLlamada+"&NombreGrabacion="+NombreGrabacion+"&asignacion="+asignacion+"&origen="+origen+"&fechaAgendamiento="+fechaAgendamiento+"&horaAgendamiento="+horaAgendamiento;
																	$.ajax(
																	{
																		type: "POST",
																		url: "../includes/crm/insertar2.php",
																		data:insertar2,
																		async: false,
																		success: function(response)
																		{
																			progressBar(response);			
																			console.log(response);
																			$('#seleccione_nivel1').prop('selectedIndex',0);
																			$('#seleccione_nivel2').prop('selectedIndex',0);
																			$('#seleccione_nivel3').prop('selectedIndex',0);
																			$("textarea").val("");
																			$("#fecha_compromiso").val("");
																			$("#monto_compromiso").val("");
																			$('#respuesta').prop('selectedIndex',0);
																			//funcionMostrarAgrupacion(cedente,'1',rut_ultimo);
																			$('#ultimo_fono').val('0');

																			$.niftyNoty(
																			{
																				type: 'success',
																				icon : 'fa fa-check',
																				message : 'Respuesta Integral Guardada' ,
																				container : 'floating',
																				timer : 2000
																			});
																			$('#grupo1').hide();
																			$('.nivel_2_mostrar').hide();
																			$('.nivel_3_mostrar').hide();
																			$('.nivel_2_ocultar').show();
																			$('.nivel_3_ocultar').show();
																			eliminarAnexoBridge();
																			setTimeout(function(){
																				intervaloLlamada();
																				if (rutEstrategia == 2){
																					fonosLlamando(tipo_gestion_final);
																				}
																				unPausePredictivo();
																				activarBotonera();
																			},1000);
																		}
																	});
																}
																else
																{
																	var fecha = new Date();
																	var fecha_gestion = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
																	var hora_gestion = fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
																	var numero_cola = $('#numero_cola').val();
																	var NombreGrabacion = $('#NombreGrabacion').val();
																	var origen = 1;
																	var insertar1 = "nivel1="+nivel1+"&nivel2="+nivel2+"&nivel3="+nivel3+"&comentario="+comentario+"&fecha_gestion="+fecha_gestion+"&hora_gestion="+hora_gestion+"&rut="+rut_ultimo+"&fono_discado="+fono_discado+"&tipo_gestion="+tipo_gestion_final+"&cedente="+cedente+"&usuario_foco="+nombre_usuario_foco+"&lista="+numero_cola+"&fecha_compromiso="+fecha_compromiso+"&monto_compromiso="+monto_compromiso+"&tiempoLlamada="+tiempoLlamada+"&NombreGrabacion="+NombreGrabacion+"&asignacion="+asignacion+"&origen="+origen+"&fechaAgendamiento="+fechaAgendamiento+"&horaAgendamiento="+horaAgendamiento;
																	console.log(insertar1);
																	$.ajax(
																	{
																		type: "POST",
																		url: "../includes/crm/insertar1.php",
																		data:insertar1,
																		async: false,
																		success: function(response)
																		{												
                                                                            console.log(response);
																			progressBar(response);
																			$('#seleccione_nivel1').prop('selectedIndex',0);
																			$('#seleccione_nivel2').prop('selectedIndex',0);
																			$('#seleccione_nivel3').prop('selectedIndex',0);
																			$('#ultimo_fono').val('0');
																			$("textarea").val("");
																			$('#respuesta').prop('selectedIndex',0);
																			//funcionMostrarAgrupacionNoFono(cedente,'1',rut_ultimo);

																			$.niftyNoty(
																			{
																				type: 'success',
																				icon : 'fa fa-check',
																				message : 'Respuesta Integral Guardada' ,
																				container : 'floating',
																				timer : 2000
																			});
																			$('#grupo1').hide();
																			$('.nivel_2_mostrar').hide();
																			$('.nivel_3_mostrar').hide();
																			$('.nivel_2_ocultar').show();
																			$('.nivel_3_ocultar').show();
																			eliminarAnexoBridge();
																			setTimeout(function(){
																				intervaloLlamada();
																				if (rutEstrategia == 2){
																					fonosLlamando(tipo_gestion_final);
																				}
																				unPausePredictivo();
																				activarBotonera();
																			},1000);

																		}
																	});
																}
															});
														}
													});
													//
													//$(".selectpicker").selectpicker("refresh");
       												$('#date-range .input-daterange').datepicker({
            											format: "yyyy/mm/dd",
                										weekStart: 1,
            											todayBtn: "linked",
            											autoclose: true,
            											todayHighlight: true,
            											language: 'es'
        											});
												}

											});
										}
									});
								});
							}
						});
					});
				}
			});			
		}
		function funcionLimpiar()
		{
	  		$('#seleccione_nivel1').prop('selectedIndex',0);
			$('#seleccione_nivel2').prop('selectedIndex',0);
			$('#seleccione_nivel3').prop('selectedIndex',0);
			$("textarea").val("");
			$("#fecha_compromiso").val("");
			$("#monto_compromiso").val("");
			$('#respuesta').prop('selectedIndex',0);
			$('#seleccione_cedente2').prop('selectedIndex',0);
			//$('#seleccione_tipo_busqueda').prop('selectedIndex',0);
			//$("#respuesta").prop("disabled",true);
			$('#mostrar_deudas_ocultar').show();
			//$('#Botonera').show();
			$('#mostrar_deudas').hide();
			$('#mostrar_fonos_ocultar').show();
			$('#mostrar_fonos').hide();
			$('#mostrar_gestion_ocultar').show();
			$('#mostrar_gestion').hide();
			$('#mostrar_gestion_total_ocultar').show();
			$('#mostrar_gestion_total').hide();
			$('#mostrar_gestion_diaria_ocultar').show();
			$('#mostrar_gestion_diaria').hide();
			$('#mostrar_pagos_ocultar').show();
			$('#mostrar_pagos').hide();
			$('#mostrar_direccion_ocultar').show();
			$('#mostrar_direccion').hide();
			$('#mostrar_correo_ocultar').show();
			$('#mostrar_correo').hide();
			$('#mostrar_correo_ocultar_cc').show();
			$('#mostrar_correo_cc').hide();
			$('#rut_buscado').val('');
			$('#busqueda_estrategia').hide();
			$('#busqueda_rut').hide();
			$('#seleccione_tipo_busqueda').show();
			$("#nuevo_telefono").prop("disabled",true);
			$("#nuevo_direccion").prop("disabled",true);
			$("#nuevo_correo").prop("disabled",true);
			$('#script_cobranza_mostrar').hide();
			$('#script_cobranza_ocultar').show();
			$('#nombre_cliente').html('');
			$("#ContainerRutNumber input[name='RutNumber']").val("");
		}
		function funcionMostrar()
		{
			$('#mostrar_deudas').show();
			$('#mostrar_fonos').show();
			$('#mostrar_direccion').show();
			$('#mostrar_correo').show();
			$('#mostrar_correo_cc').show();
		
	  	}
	  	function funcionOcultar()
		{
			$('#mostrar_deudas_ocultar').hide();
			$('#mostrar_fonos_ocultar').hide();
			$('#mostrar_direccion_ocultar').hide();
			$('#mostrar_correo_ocultar').hide();
			$('#mostrar_correo_ocultar_cc').hide();
	  	}
		$(document).on('click', '.adjuntar', function()
		{
			var clase = '#l'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');

			if ($(clase).is(':checked'))
	        {
	        	$('#enviar_factura').prop("disabled",false);
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
	        else
	        {
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail.php",
					data:idmail,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
		});

		$(document).on('click', '.adjuntar_cc', function()
		{
			var clase = '#l_cc'+$(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');

			if ($(clase).is(':checked'))
	        {
	        	var idmail = "id_mail="+id_mail+"&id=1";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
	        else
	        {
				var idmail = "id_mail="+id_mail+"&id=0";
	        	$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_mail_cc.php",
					data:idmail,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 1000
						});
					}
				});
	        }
		});

		$(document).on('click', '#enviar_factura', function()
		{
			var cedente = $('#IdCedente').val();
			var rut = $('#rut_ultimo').val();
			var data = "cedente="+cedente+"&rut="+rut;
			console.log(data);
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/enviar_mail.php",
				data:data,
				success: function(response)
				{
					console.log(response);
					if(response==2)
					{
						var msg = "No has seleccionado un Email de Envio!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else if(response==3)
					{
						var msg = "No has adjuntado Factura!";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else if(response==1)
					{
						var msg = "No se puede enviar Mail , Cedente no tiene Template Cargado en la Base de Datos , Consulte con el Administrador.";
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : msg,
							container : 'floating',
							timer : 4000
						});
					}
					else
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				}
			});
		});
		$(document).on('click', '.fono_gestion', function()
		{
			var id = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var telefono = '#'+'telefono'+id;
			var valor_telefono = $(telefono).val();
			$('#ultimo_fono').val(valor_telefono);
		});
		$(document).on('click', '.ckhsel', function()
		{
			var id_deuda = $(this).closest('tr').attr('id');
			var clase = '#chk'+$(this).closest('tr').attr('class');
			var rut_factura = $('#rut_ultimo').val();
			var cedente = $('#IdCedente').val();
			console.log(clase);
			if ($(clase).is(':checked'))
	        {
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=1";

				console.log(var_factura);
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				});
			}
			else
			{
				var var_factura = "rut="+rut_factura+"&cedente="+cedente+"&id_deuda="+id_deuda+"&id=0";
				console.log(var_factura);
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/marcar_factura.php",
					data:var_factura,
					success: function(response)
					{
						$.niftyNoty({
							type: 'success',
							icon : 'fa fa-check',
							message : response,
							container : 'floating',
							timer : 4000
						});
					}
				});
			}

		});

		$(document).on('change', '.correo_cambiar', function()
		{
			var id = $(this).closest('tr').attr('id');
			var id_mail = $(this).closest('tr').attr('class');
			console.log(id_mail);
			var data1 = '#'+'correo'+id;
			var data2 = '#'+'nombre'+id;
			var data3 = '#'+'cargo'+id;
			var data4 = '#'+'obs'+id;
			var mail = $(data1).val();
			var nombre = $(data2).val();
			var cargo = $(data3).val();
			var obs = $(data4).val();
			console.log(cargo);
			var idmail = "id_mail="+id_mail+"&mail="+mail+"&nombre="+nombre+"&cargo="+cargo+"&obs="+obs;
	    	$.ajax(
			{
				type: "POST",
				url: "../includes/crm/actualizar_mail.php",
				data:idmail,
				success: function(response)
				{
					$.niftyNoty({
						type: 'success',
						icon : 'fa fa-check',
						message : 'Datos Actualizados!',
						container : 'floating',
						timer : 1000
					});
				}
			});
		});

		$(document).on('click', '#nuevo_telefono', function() {
			bootbox.dialog({
				title: "Ingrese Nuevo Telefono",
				message:'<div class="row"> ' +
						'<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-4 control-label" for="name">Nuevo Telefonos</label> ' +
						'<div class="col-md-4"> ' +
						'<input id="fono_discado_nuevo" name="name" type="number" placeholder="" class="form-control input-md solo-numero"> ' +
						' </div> ' +
						'</div> ' + '<div class="form-group"> ' +
						'' +
						'<div class="col-md-8"> <div class="form-block"> ' +
						'' +
						'</div>' +
						'</div> </div>' + '</form> </div> </div><script></script>',
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-primary",
						callback: function() {
							var resValidacion = validarNuevoTelefono();
							if (resValidacion == true)
							{
								return false;
							}
							var fono_discado_nuevo = $('#fono_discado_nuevo').val();
							if(fono_discado_nuevo.length < 9 || fono_discado_nuevo.length > 9)
							{
								$.niftyNoty({
									type: 'danger',
									icon : 'fa fa-close',
									message : "Registro no Cumple con el Formato Subtel (9 Numeros)",
									container : 'floating',
									timer : 4000
								});
							}
							else
							{
								var rut_fono = $('#rut_ultimo').val();
								var data_fono_nuevo = "rut="+rut_fono+"&fono_discado_nuevo="+fono_discado_nuevo;
								var idCola = $('#seleccione_tipo_busqueda').val();
								console.log(data_fono_nuevo);
								//Ok
								$.ajax(
								{
									type: "POST",
									url: "../includes/crm/insertar_fonos.php",
									data:data_fono_nuevo,
									success: function(response)
									{
										 
										/*$('#mostrar_fonos').html(response);
										console.log(response);
										$('#mostrar_fonos_ocultar').hide();*/
										var fecha = new Date();
										var fechaCarga = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
										var fila = $('#tablaTelefonos >tbody >tr').length + 1;										
										$('#tablaTelefonos tr:last').after('<tr id="'+fila+'"><td class="text-sm"><center><i class="fa fa-flag fa-lg icon-lg" style="color:#ff0080"></i> </center></td><td class="text-sm">Nuevo Fono</td><td class="text-sm"><input type="hidden" id="telefono'+fila+'" value="'+fono_discado_nuevo+'" name="telefono'+fila+'">'+fono_discado_nuevo+'</td><td class="text-sm">'+fechaCarga+'</td><td><center><input type="checkbox" disabled  class="fono_gestion" name="llamado'+fila+'" value="llamado'+fila+'" id="llamado'+fila+'" ></center></td><td><center></center></td></tr>');
									}
								});

								$.niftyNoty({
									type: 'success',
									icon : 'fa fa-check',
									message : "Registro Guardado",
									container : 'floating',
									timer : 4000
								});
							}

						}
					}
				}
			});
		});
		function retiroDocumentos()
		{
				bootbox.dialog({
				title: "Planilla Retiro Documentos",
				message:'<div class="row"> ' +
							'<div class="col-md-12"> ' +
								'<form class="form-horizontal"> ' +
								'<div class="form-group"> ' +
									'<label class="col-md-4 control-label" for="name">Responsable</label> ' +
									'<div class="col-md-6"> ' +
									'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
									'</div> ' +
								'</div> ' +
								'<div class="form-group"> ' +
									'<label class="col-md-4 control-label" for="name">Clienten</label> ' +
									'<div class="col-md-6"> ' +
									'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
									'</div> ' +
								'</div> ' +

								'</form>'+
							'</div>'+
						' </div>',
				buttons:
				{
					success:
					{
						label: "Guardar",
						className: "btn-primary",
						callback: function()
						{
							var direccion_nuevo = $('#direccion_nuevo').val();

							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax(
							{
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response)
								{
									$('#mostrar_direccion').html(response);
									console.log(response);
									$('#mostrar_direccion_ocultar').hide();
								}
							});

							$.niftyNoty({
								type: 'success',
								icon : 'fa fa-check',
								message : "Registro Guardado",
								container : 'floating',
								timer : 4000
							});
						}
					}
				}
			});
		}
		$(document).on('click', '#nuevo_direccion', function() {
			bootbox.dialog({
				title: "Ingrese Nueva Direccion",
				message:'<div class="row"> ' + '<div class="col-md-12"> ' +
						'<form class="form-horizontal"> ' + '<div class="form-group"> ' +
						'<label class="col-md-4 control-label" for="name">Nueva Direccion</label> ' +
						'<div class="col-md-4"> ' +
						'<input id="direccion_nuevo" name="name" type="text" placeholder="" class="form-control input-md"> ' +
						' </div> ' +
						'</div> ' + '<div class="form-group"> ' +
						'' +
						'<div class="col-md-8"> <div class="form-block"> ' +
						'' +
						'</div>' +
						'</div> </div>' + '</form> </div> </div><script></script>',
				buttons: {
					success: {
						label: "Guardar",
						className: "btn-primary",
						callback: function() {
							var resValidacion = validarNuevaDireccion();
							if (resValidacion == true)
							{
								return false;
							}
							var direccion_nuevo = $('#direccion_nuevo').val();

							var rut_direccion = $('#rut_ultimo').val();
							var data_direccion_nuevo = "rut="+rut_direccion+"&direccion_nuevo="+direccion_nuevo;
							$.ajax(
							{
								type: "POST",
								url: "../includes/crm/insertar_direccion.php",
								data:data_direccion_nuevo,
								success: function(response)
								{
									$('#mostrar_direccion').html(response);
									console.log(response);
									$('#mostrar_direccion_ocultar').hide();
								}
							});

							$.niftyNoty({
								type: 'success',
								icon : 'fa fa-check',
								message : "Registro Guardado",
								container : 'floating',
								timer : 4000
							});
						}
					}
				}
			});
		});

		$(document).on('click', '#nuevo_correo2 ', function() {
			var resp = '';
			var data = 'id=1';
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/ver_cargo.php",
				data:data,
				success: function(response)
				{
					resp = response;
					console.log(resp);
					var data_modal = resp;
					bootbox.dialog({
						title: "Ingrese Nuevo Correo",
						message:data_modal,
						buttons: {
							success: {
								label: "Guardar",
								className: "btn-primary",
								callback: function() {
									var correo_nuevo = $('#correo_nuevo').val();
									var rut_correo = $('#rut_ultimo').val();
									var cargo = $('#cargo').val();
									var uso = $('#uso').val();
									var data_correo_nuevo = "rut="+rut_correo+"&correo_nuevo="+correo_nuevo+"&cargo="+cargo+"&uso="+uso;
									console.log(data_correo_nuevo);
									$.ajax(
									{
										type: "POST",
										url: "../includes/crm/insertar_correo.php",
										data:data_correo_nuevo,
										success: function(response)
										{
											$('#mostrar_correo').html(response);
											console.log(response);
											$('#mostrar_correo_ocultar').hide();
										}
									});

									$.niftyNoty({
										type: 'success',
										icon : 'fa fa-check',
										message : "Registro Guardado",
										container : 'floating',
										timer : 4000
									});
								}
							}
						}
					});
				}
			});

		});
        $(document).on('change', '#seleccione_tipo_busqueda', function()
        {
            //alert('aquiiiii');
        });
		$(document).on('change', '#seleccione_tipo_busqueda', function()
		{
			if($('#seleccione_tipo_busqueda').val() == 1)
			{
				limpiarSesion();
				var id = $('#IdCedente').val();
				var data = "id="+id;
				$.ajax({
					type: "POST",
					url: "../includes/crm/seleccione_cedente.php",
					data:data,
					success: function(response){
						$('#busqueda_rut').hide();
						$('#busqueda_estrategia').show();
						$('#colas2').hide();
						$('#colas_mostrar2').show();
						$('#colas_mostrar2').html(response);
						$('.nivel_1_ocultar').hide();
						var ce1 = $('#IdCedente').val();
						var ce = ce1;
						funcionNivel1(ce);
						
					}
				});	
			}
			else
			{
				$('#busqueda_rut').show();
				$('#busqueda_estrategia').hide();
				//$('#cantidadRut').html('');	
				limpiarSesion();
				var ce1 = $('#IdCedente').val();
				var ce = ce1;
				funcionNivel1(ce);

			}
			mostrarScriptCobranzaCedente();
			$('#script_cobranza_mostrar').show();
		});

        mostrarColaDiscador();

        function mostrarColaDiscador(){
            $.ajax(
			{
				type: "POST",
				url: "../includes/crm/mostrar_colaDiscador.php",
				//data:data_validar,
				//dataType: 'json',
				success: function(response)
				{
                    //alert(response);
                    $('#colaDiscador').html(response);
                }
            });
        }

		function nuevoEstatus(estatus,pausa){
            $.ajax(
			{
				type: "POST",
				url: "../includes/crm/nuevoEstatus.php",
				data: { estatus: estatus, pausa: pausa },
				//dataType: 'json',
				success: function(response)
				{
                    //alert(response);                    
                }
            });
        }

        function datosAnexo(idCola){
            $.ajax(
			{
				type: "POST",
				url: "../includes/crm/datosCola.php",
				data:{idCola:idCola},
				//dataType: 'json',
				success: function(response)
				{
                    if (response == 1)
                    {
                        $.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : " No existe anexo para el usuario logueado!",
							container : 'floating',
							timer : 4000
						});
                    }
                }
            });
        }

		$(document).on('click', '.entrar', function(){
			var rut_buscado = $('#rut_buscado').val();
			var cedente = $('#IdCedente').val();
				
			//var data_validar = "rut="+rut_buscado+"&cedente="+cedente;
			//alert(data_validar);
			//var ce = "cedente="+cedente;
			//funcionNivel1(ce);
			rutEstrategia = 1; // para indicar que se encuentra en buscar
			/*$.ajax(
			{
				type: "POST",
				url: "../includes/crm/validar_rut.php",
				data:data_validar,
				dataType: 'json',
				success: function(response)
				{
					console.log(response.uno);
					if(response.uno==0)
					{
						$.niftyNoty({
							type: 'danger',
							icon : 'fa fa-close',
							message : " No existe Rut para el Cedente Seleccionado!",
							container : 'floating',
							timer : 4000
						});
						
					}
					else
					{
						console.log('por aca');
						var rut_ultimo = $('#rut_ultimo').val(rut_buscado);
						//alert(rut_ultimo);
						var nombre_cliente = $('#nombre_cliente').html(response.dos);
						//alert(response.dos);						
						funcionMostrarAgrupacion(cedente,'1',rut_buscado);
					}
				}
			}); 
            	$('#rut_ultimo').val(response.dos);
					$('#nombre_cliente').html(response.tres);
					$('#prefijo').val(response.cuatro);
					$('#cantidadRut').html(response.siete);
            */
			funcionLimpiar();
			var rut_buscado;
			var fono;
            var idCola = $('#seleccione_tipo_busqueda').val();
			intervalo_hangup();


			pausaEjec = 1;
			activarBotonera();
			if (idCola == 0){
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-close',
					message : "Debe Seleccionar la cola!",
					container : 'floating',
					timer : 4000
				});

			}else{

			
				
				datosAnexo(idCola);
				$('#Botonera').show();
			
				nuevoEstatus('DISPONIBLE','');
				
				$("#entrar").val('Salir');
				//rut_buscado = '15906521';
				//fono = '712223391';
			
				$("#ContainerRutNumber").show();
				$("#ContainerRutNumber input[name='anexoFoco']").val(GlobalData.anexo);

				$('#contenido').show(); 

				//$("#CallNotification").addClass("floating-container");		
			
				
				$('#seleccione_tipo_busqueda').prop("disabled",true);
				$("#entrar").removeClass("entrar btn-primary");
				$("#entrar").addClass("salir btn-danger");
				
				unPausePredictivo();
				intervaloLlamada();
				//intervalo_EsperandoLlamada();
				
			

				
			} 

		});

		var ShowNotification = false;
		function intervalo_EsperandoLlamada(texto,color,icono){
			
				intervaloEsperandoLlamada = setInterval(function(){
					if(!ShowNotification){
						$.niftyNoty({
								type: color,
								icon : icono,
								message : "<span id='CallNotification' style='font-weight: bold;font-size: 16px;'>"+texto+"<span>",
								container : 'floating',
								timer : 0,
								closeBtn: false
						});
						ShowNotification = true;
					}else{
						clearInterval(intervaloEsperandoLlamada);
					}
				},0);
		}



		function intervaloLlamada(){
			intervalollamada =setInterval(function(){
			llamadaPredictivo();
			console.log('eje');
			},500);            
		}

		
		

		function llamadaPredictivo(){
			$.ajax(
			{
				type: "POST",
				url: "../includes/crm/predictivoRut.php",
				//data:data_validar,
				dataType: 'json',
				success: function(response)
				{
					if (response.cantidad > 0){
						 clearInterval(intervalollamada);
						 nuevoEstatus('INCALL','');
						 $("#CallNotification").closest(".alert").remove();
						 ejecutarSonido('../sonidos/answer.wav');
						 var rut_buscado = response.uno;
						var fono = response.dos;
						desactivarBotonera('');
			
						$('#rut_buscado').val(rut_buscado);
            			$('#rut_ultimo').val(rut_buscado);
						$('#ultimo_fono').val(fono);
						$("#ContainerRutNumber input[name='RutNumber']").val(rut_buscado);

							funcionMostrarNombreCliente(rut_buscado);
							funcionMostrarAgrupacion(cedente,'1',rut_buscado);
							funcionMostrarFono(rut_buscado,fono);
					
					}

				}
				
			});	
		}

        $(document).on('click', '.salir', function()        
		{
           
			pausaEjec = 1;
			nuevoEstatus('','');				
			activarBotonera();
            clearInterval(intervalollamada);
			clearInterval(intervaloHangup);
			$("#CallNotification").closest(".alert").remove();
			ShowNotification = false;
			$('#Botonera').hide();
			$('#contenido').hide();
            $('#seleccione_tipo_busqueda').prop("disabled",false);
            $("#entrar").removeClass("salir btn-danger");
		    $("#entrar").addClass("entrar btn-primary");
            $("#entrar").val('Entrar');
			$("#ContainerRutNumber").hide();
			var idCola = $('#seleccione_tipo_busqueda').val();
            mostrarColaDiscador();
            $.ajax(
			{
				type: "POST",
				url: "../includes/crm/eliminarAnexo.php",
				data:{idCola: idCola},
				success: function(response)
				{
					//
                }
				
            });            

        });    

		function eliminarAnexoBridge(){
			var Anexo = $('#Anexo').val();
			var data = "Anexo="+Anexo;
			$.ajax({
				type: "POST",
				url: "../discador/cortar.php",
				data:data, 
				success: function(response){	
					
					$.niftyNoty({
						type: 'danger',
						icon : 'fa fa-phone',
						message : 'Llamada Cortada por Asterisk' ,
						container : 'floating',
						timer : 1000
					});
				}
			});	  
		}

		$(document).on('change', '#respuesta', function()
		{
			/*if($('#ultimo_fono').val()==0)
			{
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-check',
					message : "Debe seleccionar un telefono para guardar la Gestion!",
					container : 'floating',
					timer : 4000
				});
				$('#respuesta').prop('selectedIndex',0);
			}
			else
			{ */

				var tiempoLlamada = transcurrido();
				var fono_discado = $('#ultimo_fono').val();
				//$('#next_rut').prop("disabled",false);
				//$('#prev_rut').prop("disabled",false);
				var i = 1;
				while(i<=7)
				{
					$('#call'+i).prop("disabled",false);
					i++;
				}
				var cedente = $('#IdCedente').val();
				var resp = $('#respuesta').val();
				var tipo_gestion2 = 3;

				var rut_ultimo = $('#rut_ultimo').val();
				var fecha = new Date();
				var fecha_gestion = fecha.getFullYear()+"-"+(fecha.getMonth()+1)+"-"+fecha.getDate();
				var hora_gestion = fecha.getHours()+":"+fecha.getMinutes()+":"+fecha.getSeconds();
				var duracion_llamada2 = $('#duracion_llamada').val();
				var numero_cola = $('#numero_cola').val();
				var NombreGrabacion = $('#NombreGrabacion').val();
				var asignacion = $('#prefijo').val();
				var origen = 1;
				console.log(duracion_llamada2);
				var insertar3 = "nivel1="+resp+"&fecha_gestion="+fecha_gestion+"&hora_gestion="+hora_gestion+"&rut="+rut_ultimo+"&fono_discado="+fono_discado+"&tipo_gestion="+tipo_gestion2+"&cedente="+cedente+"&duracion_llamada="+duracion_llamada2+"&usuario_foco="+nombre_usuario_foco+"&lista="+numero_cola+"&tiempoLlamada="+tiempoLlamada+"&NombreGrabacion="+NombreGrabacion+"&asignacion="+asignacion+"&origen="+origen;
				$.ajax(
				{
					type: "POST",
					url: "../includes/crm/insertar3.php",
					data:insertar3,
					success: function(response)
					{
						console.log(response);
						progressBar(response);
						$('#seleccione_nivel1').prop('selectedIndex',0);
						$('#seleccione_nivel2').prop('selectedIndex',0);
						$('#seleccione_nivel3').prop('selectedIndex',0);
						$("textarea").val("");
						$("#fecha_compromiso").val("");
						$("#monto_compromiso").val("");
						$('#respuesta').prop('selectedIndex',0);
						funcionNivelRapido(cedente);
						$('#respuesta').prop('selectedIndex',0);
						if (rutEstrategia == 2){
							fonosLlamando(tipo_gestion2);
						}
						
						$.niftyNoty(
						{
							type: 'success',
							icon : 'fa fa-check',
							message : 'Respuesta Rapida Guardada' ,
							container : 'floating',
							timer : 2000
						});
						$('#ultimo_fono').val('0');
						//funcionMostrarAgrupacionNoFono(cedente,'1',rut_ultimo)
						//funcionMostrarAgrupacion(cedente,'1',rut_ultimo);
						$('#grupo1').hide();
						$('.nivel_2_mostrar').hide();
						$('.nivel_3_mostrar').hide();
						$('.nivel_2_ocultar').show();
						$('.nivel_3_ocultar').show();	
						//llamadaPredictivo();
						eliminarAnexoBridge();
						setTimeout(function(){
							intervaloLlamada();
							unPausePredictivo();
							activarBotonera();
						},1000);
					}
				});
			//}

		});
		$(document).on('change', '#seleccione_cedente2', function()
		{
	    	var id = $('#seleccione_cedente2').val();
	    	console.log(id);
	    	$('#IdCedente').val(id);
	    	limpiarSesion();
	    });


		$(document).on('change', '#seleccione_estrategia', function(){
			limpiarSesion();			
			var idq = $('#seleccione_estrategia').val();
			var data = "id="+idq;
			rutEstrategia = 2; // para indicar que se encuentra en estrategia
			$.ajax({
				type: "POST",
				url: "../includes/crm/seleccione_cola.php",
				data:data,
				success: function(response)
				{
					$('#grupo').html(response);
				}
			});
		});	

		function mostrarScriptCobranzaCedente(){
			$.ajax({
				type: "POST",
				url: "../includes/crm/mostrar_script_cobranza.php",
				data:{idCedente: GlobalData.id_cedente},
				dataType: "json",
				success: function(response)
				{
					if (response == ""){
						$('#script_cobranza_ocultar').hide();
						$('#script_cobranza_mostrar').html('Buenos días/Tardes, necesito comunicarme con el encargado de pagos a proveedores de la empresa ');
					}else
					{
						//alert(response);
						$('#script_cobranza_ocultar').hide();
						$('#script_cobranza_mostrar').html(response);
					}
					
				}
			});
		}


/* $(document).on('change', '#seleccione_cola', function(){
	var idCola = $('#seleccione_cola').val();
	if(idCola != ""){
		var Cola = "QR_"+$("#IdCedente").val()+"_"+idCola;
		actualizarCola(idCola,Cola);
		$.ajax({
			type: "POST",
			url: "../includes/crm/seleccione_asignacion.php",
			data:"Cola="+Cola,
			success: function(response)
			{
				
				$('#asignacion').html(response);
			}
		});	
	}
}); */

/* $(document).on('change', '#seleccione_asignacion', function(){
	var Cola_Final = $('#seleccione_asignacion').val();
	$('#nuevo_telefono').prop("disabled",false);
	$('#nuevo_direccion').prop("disabled",false);
	$('#nuevo_correo').prop("disabled",false);
	$.ajax({
		type: "POST",
		url: "../includes/crm/seleccione_query.php",
		data:"Prefijo="+Cola_Final, 
		dataType: 'json',
		success: function(response){
			console.log(response);	
			//alert(response.dos);
			$('#ocultar_rut').hide();
			$('#mostrar_rut').show();
			$('#mostrar_rut').html(response.uno);
			$('#mostrar_rut2').html(response.cinco);
			$('#next_rut').val(response.dos);
			$('#prev_rut').val(response.dos);		
			$('#nombre_cliente').html(response.tres);
			$('#mostrar_nombre_ocultar').hide();
			$('#rut_ultimo').val(response.dos);
			$('#prefijo').val(response.cuatro);
			progressBar(response.seis);			
			$('#cantidadRut').html(response.siete);		
			funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
		}
	});		
}); */


/* function progressBar(porcentaje){
	var Porcentaje = Number(porcentaje);
			if(Porcentaje <= 10){
				Color = "Red"; //Danger
			}
			if((Porcentaje > 10) && (Porcentaje <= 50)){
				Color = "Naranja"; //Warning
			}
			if((Porcentaje > 50) && (Porcentaje < 100)){
				Color = "Azul"; //Primary
			}
			if(Porcentaje == 100){
				Color = "Verde"; //Success
			}
			switch(Color){
				case 'Red':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-danger");
				break;
				case 'Naranja':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-warning");
				break;
				case 'Azul':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-success");
					$("#ProgressBar .progress-bar").addClass("progress-bar-primary");
				break;
				case 'Verde':
					$("#ProgressBar .progress-bar").removeClass("progress-bar-danger");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-warning");
					$("#ProgressBar .progress-bar").removeClass("progress-bar-primary");
					$("#ProgressBar .progress-bar").addClass("progress-bar-success");
				break;
			}
			$("#ProgressBar .progress-bar").css('width',porcentaje+"%");
			$("#ProgressBar .progress-bar").html(porcentaje+"%");
			$("#ProgressBar").show();

} */

		

	/*	$(document).on('click', '#next_rut', function(){			
			limpiarSesion();
			var rut = $('#next_rut').val();
			var prefijo = $('#prefijo').val();
			var data = "rut="+rut+"&prefijo="+prefijo;
			console.log(data);
			$.ajax({
				type: "POST",
				url: "../includes/crm/next_rut.php",
				data:data, 
				dataType: 'json',
				success: function(response){	
					//alert(response);		
					$('#mostrar_rut').html(response.uno);
					$('#mostrar_rut2').html(response.cinco);
					$('#next_rut').val(response.dos);
					$('#prev_rut').val(response.dos);
					$('#rut_ultimo').val(response.dos);
					$('#nombre_cliente').html(response.tres);
					$('#prefijo').val(response.cuatro);
					var cedente = $('#IdCedente').val();
					$('#cantidadRut').html(response.siete);
					
					progressBar(response.seis);	
					funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
				},
            error: function(){
                alert('error');
            }
			});	
		});	*/

		function nextRut(){
			limpiarSesion();
			var rut = $('#next_rut').val();
			var prefijo = $('#prefijo').val();
			var data = "rut="+rut+"&prefijo="+prefijo;
			console.log(data);
			$.ajax({
				type: "POST",
				url: "../includes/crm/next_rut.php",
				data:data, 
				dataType: 'json',
				success: function(response){	
					//alert(response);		
					$('#mostrar_rut').html(response.uno);
					$('#mostrar_rut2').html(response.cinco);
					$('#next_rut').val(response.dos);
					$('#prev_rut').val(response.dos);
					$('#rut_ultimo').val(response.dos);
					$('#nombre_cliente').html(response.tres);
					$('#prefijo').val(response.cuatro);
					var cedente = $('#IdCedente').val();
					$('#cantidadRut').html(response.siete);
					
					progressBar(response.seis);	
					funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);
				},
            error: function(){
                alert('error');
            }
			});	
		}

		$(document).on('click', '#prev_rut', function(){
			limpiarSesion();
			var rut = $('#prev_rut').val();
			var prefijo = $('#prefijo').val();
			var data = "rut="+rut+"&prefijo="+prefijo;
			console.log(data);
			$.ajax({
				type: "POST",
				url: "../includes/crm/prev_rut.php",
				data:data, 
				dataType: 'json',
				success: function(response){	
					$('#mostrar_rut').html(response.uno);
					$('#mostrar_rut2').html(response.cinco);
					$('#next_rut').val(response.dos);
					$('#prev_rut').val(response.dos);
					$('#rut_ultimo').val(response.dos);
					$('#nombre_cliente').html(response.tres);
					$('#prefijo').val(response.cuatro);
					$('#cantidadRut').html(response.siete);
					var cedente = $('#IdCedente').val();
					progressBar(response.seis);	
					funcionMostrarAgrupacion(cedente,response.cuatro,response.dos);

				}
			});		
		});		
	}	
/* function actualizarCola(idCola,Cola){
	$.ajax({
		type: "POST",
		url: "../task/actualizarColas.php",
		data:{
			Cola: idCola
		},
		sync:false,
		beforeSend: function(){
			$('#Cargando').modal({
				backdrop: 'static',
				keyboard: false
			})
		},
		success: function(data){
			actualizarAsignacion(Cola);
		},
		error: function(){
		}
	});
}
function actualizarAsignacion(Cola){
	$.ajax({
		type: "POST",
		url: "../task/actualizarAsignaciones.php",
		data:{
			Cola: Cola
		},
		sync:false,
		success: function(data){
			$('#Cargando').modal('hide');
		},
		error: function(){
		}
	});
} */

	$(document).on('click', '.Llamar', function(){
		$("#hour").text('00');
		$("#minute").text('00');
		$("#second").text('00');
		tiempo.segundo = 0;
		tiempo.minuto = 0;
		tiempo.hora = 0;
		tiempo_corriendo = setInterval(function(){
			tiempo.segundo++;
			if(tiempo.segundo >= 60)
			{
				tiempo.segundo = 0;
				tiempo.minuto++;
			}      

			// Minutos
			if(tiempo.minuto >= 60)
			{
				tiempo.minuto = 0;
				tiempo.hora++;
			}

			$("#hour").text(tiempo.hora < 10 ? '0' + tiempo.hora : tiempo.hora);
			$("#minute").text(tiempo.minuto < 10 ? '0' + tiempo.minuto : tiempo.minuto);
			$("#second").text(tiempo.segundo < 10 ? '0' + tiempo.segundo : tiempo.segundo);
		}, 1000);
		
		
		var id = $(this).closest('tr').attr('id');
		var Tel = $("#telefono"+id).val();
		var IdCedente = $("#IdCedente").val();
		var Anexo = $('#Anexo').val();
		var UsuarioAsterisk = $('#usuario').val();
		var data = "Tel="+Tel+"&Cedente="+IdCedente+"&Anexo="+Anexo+"&User="+UsuarioAsterisk;
		console.log(data);
		$("#fono"+id).removeClass("Llamar btn-success ");
		$("#fono"+id).addClass("Cortar btn-danger");

		var id = $(this).closest('tr').attr('id');
		var clase = '#chk'+$(this).closest('tr').attr('class');
		var telefono = '#'+'telefono'+id;
		var valor_telefono = $(telefono).val();
		$('#ultimo_fono').val(valor_telefono);
		$("#fg"+id).prop('checked',true);
		idFilaFono = id;
		$('#next_rut').prop("disabled",true);
		$('#prev_rut').prop("disabled",true);

		var i = 1;
		while(i<=20)
		{
			if(i==id){
				$('#fono'+i).prop("disabled",false);
				i++;
			}
			else{
				$('#fono'+i).prop("disabled",true);
				i++;
			}
		}
		
		$.ajax({
			type: "POST",
			url: "../discador/discador.php",
			data:data,
			dataType: 'json', 
			success: function(response){	
				var NombreGrabacion = response.dos;
				$('#NombreGrabacion').val(NombreGrabacion);
			}
		});	
		

	});
	$(document).on('click', '.CortarPredictivo', function(){
		clearInterval(tiempo_corriendo);
		var id = $(this).closest('tr').attr('id');
		var Anexo = $('#Anexo').val();
		var data = "Anexo="+Anexo;
		var i = 1;
		while(i<=10)
		{
			$('#fono'+i).prop("disabled",true);
			i++;
		}
		$.ajax({
			type: "POST",
			url: "../discador/cortar.php",
			data:data, 
			success: function(response){	
				
				$.niftyNoty({
					type: 'danger',
					icon : 'fa fa-phone',
					message : 'Llamada Cortada' ,
					container : 'floating',
					timer : 2000
				});
				eliminarAnexoBridge();
			}
		});	
	});


});
