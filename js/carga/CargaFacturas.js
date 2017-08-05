$(document).ready(function(){
	Dropzone.autoDiscover = false;

	var Filename = "";
	var myDropzone;

	var TableFacturas = "";
	var TableFacturasDataSet = [];

	var SelectedAll = false;

	$('select').selectpicker();

	getFacturasInubicables();
	updateTableFacturas();

	$("#file-up").dropzone({
		url: "../carga/ajax/uploadFacturas.php",
		acceptedFiles: ".pdf",
		maxFiles:100,
		uploadMultiple: true, // Adding This 
		parallelUploads: 100,
		init: function() {
			myDropzone = this;
		},

		sending: function(file, xhr, formData) {
			/* formData.append("MarcaData", MarcaData);
			formData.append("TipoCarga", $("select[name='TipoCarga']").val()); */
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
			console.log(response);
			$('#load').modal('hide');
			TableFacturas.destroy();
			getFacturasInubicables();
			updateTableFacturas();
			/* if(isJson(response)){
				var json = JSON.parse(response);
				console.log(json);
				Push.create('Carga Automatica',{
					body: "El archivo: "+json.filename+" esta siendo procesado\nEstado: "+json.comment+"\nCarga realizada por: "+json.usuario
				});
			} */
			myDropzone.removeAllFiles();
		},
		processing: function () {
			$('#load').modal({
				backdrop: 'static',
				keyboard: false
			})
		}
	});
	$("body").on("change",".inputCheckSelect",function(){
		var ObjectMe = $(this);
		var ObjectTR = ObjectMe.closest("tr");
		var data = TableFacturas.row(ObjectTR).data();
		var Seleccion = data.Seleccion;
		var SeleccionArray = Seleccion.split("_");
		var NewSeleccion = SeleccionArray[0] == "0" ? "1_"+SeleccionArray[1] : "0_"+SeleccionArray[1];
		data.Seleccion = NewSeleccion;
		TableFacturas.row(ObjectTR).data(data).draw();
	});
	$("body").on("click",".deleteFactura",function(){
		var ObjectMe = $(this);
		var ObjectTR = ObjectMe.closest("tr");
		var idFactura = ObjectMe.attr("id");
		var Delete = deleteFactura(idFactura);
		if(Delete){
			TableFacturas.row(ObjectTR).remove().draw();
		}
	});
	$("body").on("click","#SeleccionarTodo",function(){
		TableFacturas.rows().every(function(rowIdx,tableLoop,rowLoop){
			var data = this.data();
			var Seleccion = data.Seleccion;
			var SeleccionArray = Seleccion.split("_");
			var Char = SelectedAll ? "0" : "1";
			data.Seleccion = Char+"_"+SeleccionArray[1];
		});
		SelectedAll = SelectedAll ? false : true;
		TableFacturas.destroy();
		updateTableFacturas();
	});
	$("body").on("click","#EliminarSeleccionados",function(){
		bootbox.confirm({
			message: "¿Desea eliminar las facturas seleccionadas?",
			buttons: {
				confirm: {
					label: 'Si',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result){
					var idFacturas = [];
					TableFacturas.rows().every(function(rowIdx,tableLoop,rowLoop){
						var data = this.data();
						var Seleccion = data.Seleccion;
						var SeleccionArray = Seleccion.split("_");
						if(SeleccionArray[0] == "1"){
							idFacturas.push(SeleccionArray[1]);
						}
					});
					idFacturas = idFacturas.join();
					if(idFacturas != ""){
						var Delete = deleteFactura(idFacturas);
						if(Delete){
							TableFacturas.destroy();
							getFacturasInubicables();
							updateTableFacturas();
						}
					}else{
						bootbox.alert("Debe seleccionar almenos una factura");
					}
				}
			}
		});
	});
	function getFacturasInubicables(){
		$.ajax({
            type: "POST",
            url: "../carga/ajax/getFacturasInubicables.php",
            dataType: "html",
            async: false,
            data: {
            },
            success: function(data){
				if(isJson(data)){
					TableFacturasDataSet = JSON.parse(data);
				}
            },
            error: function(){
            }
        });
	}
	function updateTableFacturas(){
		TableFacturas = $('#TableFacturas').DataTable({
            data: TableFacturasDataSet,
            paging: false,
            iDisplayLength: 100,
            columns: [
                { data: 'Factura', width: "35%" },
                { data: 'Fecha', width: "25%" },
                { data: 'Usuario', width: "25%" },
				{ data: 'Seleccion', width: "7.5%" },
                { data: 'Accion', width: "7.5%" }
            ],
            "columnDefs": [
                {
					className: "dt-center",
                    "targets": 3,
                    "data": 'Seleccion',
                    "render": function( data, type, row ) {
						var DataArray = data.split("_");
						var CheckedClass = DataArray[0] == "1" ? "active" : "";
						var CheckedInput = DataArray[0] == "1" ? "checked=''" : "";
                        return "<div id='"+DataArray[1]+"'><label class='form-checkbox form-normal form-primary "+CheckedClass+"'><input type='checkbox' class='inputCheckSelect' "+CheckedInput+"></label></div>";
                    }
				},
				{
					className: "dt-center",
                    "targets": 4,
                    "data": 'Accion',
                    "render": function( data, type, row ) {
                        return "<button class='btn btn-danger deleteFactura' id='"+data+"'>Eliminar</button>";
                    }
                }
            ]
        });
	}
	function deleteFactura(idFacturas){
		var ToReturn = false;
		$.ajax({
            type: "POST",
            url: "../carga/ajax/deleteFacturasInubicables.php",
            dataType: "html",
            async: false,
            data: {
				idFacturas: idFacturas
            },
            success: function(data){
				console.log(data);
				if(isJson(data)){
					var json = JSON.parse(data);
					if(json.result){
						ToReturn = true;
					}
				}
            },
            error: function(){
            }
		});
		return ToReturn;
	}
	function AddClassModalOpen(){
        setTimeout(function(){
            if(!$("body").hasClass("modal-open")){
                $("body").addClass("modal-open");
            }
        }, 500);
    }
});