$(document).ready(function() {
	var isUpdate = false;
	if ($('.date-picker').length) {
		$('.date-picker').datepicker({
			language: "es",
			format: 'yyyy-mm-dd',
			todayHighlight: true
		});
	}
	$('.nameUser').load('ajax/nameUser.php');
	//if ( $(".updateData").length ) {
		$.post('ajax/selectDatos.php', function(data) {
			value = $.parseJSON(data);
			if (value[0].length > 0 || value[1].length > 0 || value[2].length > 0 || value[3].length > 0) {
				if(value[0].length > 0){
					$('input[name="rut"]').val(value[0][0].Rut);
					$('input[name="apellidos"]').val(value[0][0].Apellidos);
					$('input[name="nombres"]').val(value[0][0].Nombres);
					$('input[name="telefono"]').val(value[0][0].Telefono);
					$('input[name="fechaNacimeinto"]').val(value[0][0].FechaNacimiento);
					$('input[name="correo"]').val(value[0][0].Correo);
				}

				if(value[3].length > 0){
					$('input[name="afp"]').val(value[3][0].Afp);
					$('input[name="sistemaSalud"]').val(value[3][0].SistemaSalud);
					$('input[name="uf"]').val(value[3][0].UF);
					$('input[name="ges"]').val(value[3][0].Ges);
					$('input[name="pensionado"]').val(value[3][0].Pensionado);
				}

				if(value[2].length > 0){
					$('input[name="contactoNombre"]').val(value[2][0].Nombre);
					$('input[name="contactoParentesco"]').val(value[2][0].Parentesco);
					$('input[name="contactoCelular1"]').val(value[2][0].Celular1);
					$('input[name="contactoCelular2"]').val(value[2][0].Celular2);
					$('input[name="contacto2Nombre"]').val(value[2][1].Nombre);
					$('input[name="contacto2Parentesco"]').val(value[2][1].Parentesco);
					$('input[name="contacto2Celular1"]').val(value[2][1].Celular1);
					$('input[name="contacto2Celular2"]').val(value[2][1].Celular2);
				}

				if(value[1].length > 0){
					$('textarea[name="direccion"]').val(value[1][0].Direccion);
					$('input[name="region"]').val(value[1][0].Region);
					$('input[name="ciudad"]').val(value[1][0].Ciudad);
					$('input[name="comuna"]').val(value[1][0].Comuna);
					$('input[name="telefonoFijo"]').val(value[1][0].Telefono);
				}
				//$('.procesar').attr('edit',value[4]);
				$('.form-wizard').attr('edit',value[4]);
				isUpdate = true;
			}
		});
	//}
	 $("#Login").click(function(e){
		e.preventDefault();
		var btn = Ladda.create(this);
	 	btn.start();
		var Username = $("input[name='Username']").val();
		var Password = $("input[name='Password']").val();
		if (Username =="" || Password == "") {
			setTimeout(function() {
				$('#mns').html('Los campos son requeridos.')
				$('.alert').show();
				btn.stop();
			}, 700);
		}else{
			$.ajax({
				type: "POST",
				url: "ajax/login.php",
				dataType: "html",
				data: {
					Username: Username,
					Password: Password,
				},
				success: function(data){
					value = $.parseJSON(data);
					if (value != false) {
						setTimeout(function() {
							//var url = (value[1] == true) ? 'prueba.php' : 'actualizar_datos.php';
							var url = 'actualizar_datos.php';
							window.location = url;
						}, 700);
					 }else{
					 	setTimeout(function() {
							$('#mns').html('Usuario o contraseña incorrectos.')
							$('.alert').show();
							btn.stop();
						}, 700);
					}
				},
				error: function(){
				}
			});
		}
	});

	 $('#closeAlert').on('click', function(event) {
	 	$('#Alert').hide();
	 });

	var error = $('.alert-danger');
	var success = $('.alert-success');

	$('#form_wizard_1').bootstrapWizard({
		'nextSelector': '.button-next',
		'previousSelector': '.button-previous',
		onTabClick: function (tab, navigation, index, clickedIndex) {
			success.hide();
			error.hide();
		},
		onNext: function (tab, navigation, index) {
			success.hide();
			error.hide();
			var numTabs = $('#form_wizard_1').find('.tab-pane').length
			if (index === numTabs) {
				saveData();
			}
		},
		onPrevious: function (tab, navigation, index) {
			success.hide();
			error.hide();
		},
		onTabShow: function (tab, navigation, index) {
			var total = navigation.find('li').length;
			var current = index + 1;
			var $percent = (current / total) * 100;
			$('#form_wizard_1').find('.progress-bar').css({
				width: $percent + '%'
			});
			var ButtonNext = $('#form_wizard_1').find(".button-next");
			ButtonNext.prop("disabled",false).removeClass("disabled");
			var numTabs = $('#form_wizard_1').find('.tab-pane').length;
			numTabs--;
			if (index === numTabs) {
				ButtonNext.find("span").html("Guardar");
			}else{
				ButtonNext.find("span").html("Siguiente");
			}
		}
	});
	function saveData(){
		var formValues = new FormData();
		$('.form-cont').find('input, textarea').each(function(index, el) {
			formValues.append($(el).attr('name'), $(el).val());
		});
		if (isUpdate) {
			formValues.append('id',$('.form-wizard').attr('edit'));
			$.ajax({
				url: 'ajax/updateDatos.php',
				type: 'POST',
				data: formValues,
				processData: false,
				contentType: false,
				async: false,
				success: function(e) {
					window.location = "prueba.php";
				}
			});
		}else{
			$.ajax({
				url: 'ajax/datos.php',
				type: 'POST',
				data: formValues,
				processData: false,
				contentType: false,
				success: function(e) {
					window.location = "prueba.php";
				}
			});
		}
	}

});


