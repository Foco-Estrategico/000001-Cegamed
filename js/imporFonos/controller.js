$(document).ready(function() {
	$('#listCorrectos').load('ajax/listCorrectos.php',function(){
		$('.TableEmpresas').dataTable();
	});

	$('#listIncorrecto').load('ajax/listIncorrectos.php',function(){
		$('.TableEmpresas').dataTable();
	});

	$(document).on('click', '.unlink', function(){
		$('#aviso').modal('show')
		$('.bsi').attr('attr',$(this).attr('attr'));
	});

	$(document).on('click', '.edit', function(){
		$('#editFono').modal('show')
		$.post('ajax/dataFono.php', {id: $(this).attr('attr')}, function(data) {
			value = $.parseJSON(data);
			$('input[name="Rut"]').val(value[0].Rut)
			$('input[name="Fono"]').val(value[0].Fono)
		});
		$('.updateFono').attr('attr',$(this).attr('attr'));
	});

	$(document).on('click', '.updateFono', function() {
		$('#editFono').modal('hide');
		$.post('ajax/updateFono.php', {
			id: $(this).attr('attr'),
			Rut: $('input[name="Rut"]').val(),
			Fono: $('input[name="Fono"]').val()
		}, function(data) {
			$('#load').modal('hide');
			$('#listCorrectos').load('ajax/listCorrectos.php',function(){
				$('.TableEmpresas').dataTable();
			});
			$('#listIncorrecto').load('ajax/listIncorrectos.php',function(){
				$('.TableEmpresas').dataTable();
			});
		});
	});

	$("#dropzoneExcel").dropzone({
		url: "ajax/vinCampos.php",
		acceptedFiles: ".xlsx,.csv",
		maxFiles:1,
		init: function() {
			this.on("addedfile", function() {
				if (this.files[1]!=null){
					this.removeFile(this.files[0]);
				}
			});
		},
		error: function(file,response){
			if (response == "You can't upload files of this type.") {
					$('#alertFile').modal({
						backdrop: 'static',
						keyboard: false
					})
			}
		},
		success: function (file, response) {
			console.log(response);
			value = $.parseJSON(response);
			$('#load').modal('hide');
			$('#vincularCampos').html(value[1]);
		},
		processing: function () {
			$('#load').modal({
				backdrop: 'static',
				keyboard: false
			})
		}
	});

	$(document).on('change', '.campos', function(){
		if ($(this).val() != "") {
			$(this).attr('attr2', '1');
		}else{
			$(this).attr('attr2', '0');
		}
		if ($('.campos[attr2="1"]').length === 3) {
			$('.campos[attr2="0"]').attr('disabled', true);
		}else{
			$('.campos').attr('disabled', false);
		}
	});

	$(document).on('click', '#procesar', function(){
		$('#load').modal({
			backdrop: 'static',
			keyboard: false
		})
		var values = new FormData();
		$('.campos[attr2="1"]').each(function(index, el) {
			console.log($(el).val());
			values.append($(el).val(),$(el).attr('attr'));
		});
		values.append('doc',$(this).attr('url'));
		 $.ajax({
		            url: 'ajax/extracFonos.php',
		            type: 'POST',
		            data: values,
		            processData: false,
		            contentType: false,
		            success: function(e) {
						console.log(e);
		              	$('#listCorrectos').load('ajax/listCorrectos.php',function(){
							$('.TableEmpresas').dataTable();
						});
						$('#listIncorrecto').load('ajax/listIncorrectos.php',function(){
							$('.TableEmpresas').dataTable();
						});
						$('#load').modal('hide');
		            }
		        });

	});

	$(document).on('change', '.sheet', function(){
		$('#load').modal({
				backdrop: 'static',
				keyboard: false
		})
		$.post('ajax/sheetTag.php', {sheet: $(this).val(), doc: $('#procesar').attr('url')}, function(data){
			arr = $.parseJSON(data);
			if (arr[0] == 1) {
				$('#load').modal('hide');
				$('#vincularCampos').html(arr[1]);
			}else{
				alert(arr[1]);
			}
		});
	});

	$(document).on('click', '.bsi', function() {
		$.post('ajax/borrarFono.php', {id: $(this).attr('attr')}, function(data) {
			$('#listIncorrecto').load('ajax/listIncorrectos.php',function(){
				$('.TableEmpresas').dataTable();
			});
		});
	});

});