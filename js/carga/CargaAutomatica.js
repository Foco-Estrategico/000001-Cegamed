$(document).ready(function(){
	Dropzone.autoDiscover = false;
	estados = [];
	var Filename = "";
	var myDropzone;
	var CamposSelect;
	var MarcaData = "";

	$('select').selectpicker();

	$("#file-up").dropzone({
		url: "../carga/ajax/uploadAsignacionAutomatica.php",
		//acceptedFiles: ".xlsx,.csv",
		//acceptedFiles: ".xlsx,.xls",
		acceptedFiles: ".xlsx,.xls,.txt,.csv",
		maxFiles:1,
		autoProcessQueue: false,
		init: function() {
			myDropzone = this;
			this.on("addedfile", function() {
				if (this.files[1]!=null){
					this.removeFile(this.files[0]);
					return false;
				}else{
					showCargaModal();
				}
			});
		},

		sending: function(file, xhr, formData) {
			formData.append("MarcaData", MarcaData);
			formData.append("TipoCarga", $("select[name='TipoCarga']").val());
		},

		error: function(file,response){
			console.log(response);
			if(response == "You can't upload files of this type."){
				$('#alertFile').modal({
					backdrop: 'static',
					keyboard: false
				});
			}
			myDropzone.removeAllFiles();
		},

		success: function (file, response) {
			$('#load').modal('hide');
			if(isJson(response)){
				var json = JSON.parse(response);
				if(json.result){
					console.log(json);
					Push.create('Carga Automatica',{
						body: "El archivo: "+json.filename+" esta siendo procesado\nEstado: "+json.comment+"\nCarga realizada por: "+json.usuario
					});
				}else{
					bootbox.alert(json.Message);
				}
			}
			myDropzone.removeAllFiles();
		},
		processing: function () {
			$('#load').modal({
				backdrop: 'static',
				keyboard: false
			})
		}
	});
	$("body").on("change","select[name='TipoCarga']",function(){
		var Value = $(this).val();
		switch(Value){
			case "carga":
				$("#ContainerMarca").hide();
			break;
			case "marca":
				$("#ContainerMarca").show();
			break;
		}
	});
	$("body").on("click",".addCampoMarca",function(){
		var Tabla = $("select[name='Tabla']").val();
		if(Tabla != ""){
			var Template = $("#TemplateCampoMarca").html();
			Template = Template.replace("{CAMPOS}",CamposSelect);
			$("#ContainerMarca").append(Template);
			$(".selectpicker").selectpicker("refresh");
		}else{
			bootbox.alert("Debe seleccionar una tabla primero.",function(){AddClassModalOpen();});
		}
	});
	$("body").on("click",".deleteCampoMarca",function(){
		var ObjectMe = $(this);
		var ObjectContainer = ObjectMe.closest(".CampoMarca");
		ObjectContainer.remove();
	});
	$("body").on("change","select[name='Tabla']",function(){
		var Value = $(this).val();
		$.ajax({
            type: "POST",
            url: "ajax/selectCamposTablas.php",
            dataType: "html",
            async: false,
            data: {
                tabla: Value
            },
            success: function(data){
				CamposSelect = data;
				$("select[name='CampoRelacion']").html(CamposSelect);
				$(".CampoMarca select[name='CampoMarca']").each(function(){
					var ObjectMe = $(this);
					ObjectMe.html(CamposSelect);
				});
				$(".selectpicker").selectpicker("refresh");
            },
            error: function(response){
            }
        });
	});
	function showCargaModal(){
		var Template = $("#TemplateCarga").html();
		bootbox.dialog({
			title: "CARGA AUTOMATICA",
			message: Template,
			closeButton: false,
			buttons: {
				confirm: {
					label: "Guardar",
					className: "btn-purple",
					callback: function() {
						var TipoCarga = $("select[name='TipoCarga']").val();
						switch(TipoCarga){
							case "carga":
							case "pagos":
								MarcaData = "-";
								myDropzone.processQueue();
							break;
							case "marca":
								var Tabla = $("select[name='Tabla']").val();
								var CampoRelacion = $("select[name='CampoRelacion']").val();
								var CanProcess = false;
								if(Tabla != ""){
									if(CampoRelacion != ""){
										var Cont = 0;
										$(".CampoMarca select[name='CampoMarca']").each(function(){
											var Value = $(this).val();
											if(Value == ""){
												Cont++;
											}
										});
										if(Cont == 0){
											CanProcess = true;
										}else{
											bootbox.alert("Debe Seleccionar almenos un campo de base de datos por cada Campo de marca",function(){AddClassModalOpen();});
										}
									}else{
										bootbox.alert("Debe Seleccionar un campo relación",function(){AddClassModalOpen();});
									}
								}else{
									bootbox.alert("Debe Seleccionar una tabla",function(){AddClassModalOpen();});
								}
								if(CanProcess){
									MarcaData += Tabla+"|";
									MarcaData += CampoRelacion+"|";
									$(".CampoMarca select[name='CampoMarca']").each(function(){
										var Value = $(this).val();
										MarcaData += Value+",";
									});
									MarcaData = MarcaData.substring(0,MarcaData.length - 1);
									myDropzone.processQueue();
								}else{
									return false;
								}
							break;
						}
					}
				},
				cancel: {
					label: "Cancelar",
					className: "btn-danger",
					callback: function() {
						myDropzone.removeAllFiles();
					}
				}
			}
		}).off("shown.bs.modal");
		$(".selectpicker").selectpicker("refresh");
	}
	function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
    }
});