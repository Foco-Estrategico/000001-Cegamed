$(document).ready(function(){

    var PersonaTable;
    var DeudaTable;
    var PagosTable;
    $("body").on("change","select[name='TipoCarga']",function(){
        var TipoCarga = $(this).val();
        showElements(TipoCarga);
        switch(TipoCarga){
            case "carga":
                $("#CargaContainer").show();
                $("#PagosContainer").hide();
            break;
            case "pagos":
                $("#PagosContainer").show();
                $("#CargaContainer").hide();
            break;
        }
    });
    $("body").on("click",".delete",function(){
        var ObjectMe = $(this);
        var ObjectTR = ObjectMe.closest("tr");
        var Tabla = ObjectMe.attr("tabla");
        var TablaRows;
        switch(Tabla){
            case "Persona":
                TablaRows = PersonaTable;
            break;
            case "Deuda":
                TablaRows = DeudaTable;
            break;
            case "pagos_deudas":
                TablaRows = PagosTable;
            break;
        }
        var ID = ObjectMe.attr("id");
        var Delete = deleteRow(Tabla,ID);
        if(Delete){
            TablaRows.row(ObjectTR).remove().draw();
        }
    });
    function showElements(TipoCarga){
        $.ajax({
			type: "POST",
			url: "../carga/ajax/getCargas.php",
			data: {
				TipoCarga: TipoCarga
			},
			beforeSend: function(){
				$('#Cargando').modal({
					backdrop: 'static',
					keyboard: false
				})
			},
			success: function(data){
                $('#Cargando').modal('hide');
                if(isJson(data)){
                    var json = JSON.parse(data);
                    switch(TipoCarga){
                        case "carga":
                            $.each(json, function(i, val) {
                                var Tabla = i;
                                switch(Tabla){
                                    case "Persona":
                                        var PersonaColumns = val.Campos;
                                        $("#TablaPersonas thead tr th").remove();
                                        PersonaColumns.forEach(function(element, index, array){
                                            $("#TablaPersonas thead tr").append("<th>"+element.data+"</th>");
                                        });
                                        if(typeof PersonaTable != "undefined"){
                                            PersonaTable.destroy();
                                        }
                                        PersonaTable = $('#TablaPersonas').DataTable({
                                            data: val.Data,
                                            columns: PersonaColumns,
                                            "columnDefs": [
                                                {
                                                    "targets": PersonaColumns.length - 1,
                                                    "data": "Accion",
                                                    "render": function(data,type,row){
                                                        return "<button class='btn btn-danger delete' tabla='Persona' id='"+data+"'><i class='fa fa-close'></i></button>"
                                                    }
                                                }
                                            ]
                                        });
                                    break;
                                    case "Deuda":
                                        var DeudaColumns = val.Campos;
                                        $("#TablaDeudas thead tr th").remove();
                                        DeudaColumns.forEach(function(element, index, array){
                                            $("#TablaDeudas thead tr").append("<th>"+element.data+"</th>");
                                        });
                                        if(typeof DeudaTable != "undefined"){
                                            DeudaTable.destroy();
                                        }
                                        DeudaTable = $('#TablaDeudas').DataTable({
                                            data: val.Data,
                                            columns: DeudaColumns,
                                            "columnDefs": [
                                                {
                                                    "targets": DeudaColumns.length - 1,
                                                    "data": "Accion",
                                                    "render": function(data,type,row){
                                                        return "<button class='btn btn-danger delete' tabla='Deuda' id='"+data+"'><i class='fa fa-close'></i></button>"
                                                    }
                                                }
                                            ]
                                        });
                                    break;
                                }
                            });
                        break;
                        case "pagos":
                            $.each(json, function(i, val) {
                                var Tabla = i;
                                switch(Tabla){
                                    case "pagos_deudas":
                                        var PagoColumns = val.Campos;
                                        $("#TablaPagos thead tr th").remove();
                                        PagoColumns.forEach(function(element, index, array){
                                            $("#TablaPagos thead tr").append("<th>"+element.data+"</th>");
                                        });
                                        if(typeof PagosTable != "undefined"){
                                            PagosTable.destroy();
                                        }
                                        PagosTable = $('#TablaPagos').DataTable({
                                            data: val.Data,
                                            columns: PagoColumns,
                                            "columnDefs": [
                                                {
                                                    "targets": PagoColumns.length - 1,
                                                    "data": "Accion",
                                                    "render": function(data,type,row){
                                                        return "<button class='btn btn-danger delete' tabla='pagos_deudas' id='"+data+"'><i class='fa fa-close'></i></button>"
                                                    }
                                                }
                                            ]
                                        });
                                    break;
                                }
                            });
                        break;
                    }
                }
			},
			error: function(){
			}
		});
    }
    function deleteRow(Tabla,ID){
        var ToReturn = false;
        $.ajax({
            type: "POST",
            url: "../carga/ajax/deleteRowCarga.php",
            dataType: "html",
            async: false,
            data: {
                Tabla: Tabla,
                ID: ID
            },
            success: function(data){
                if(isJson(data)){
                    ToReturn = true;
                }
            },
            error: function(){
            }
        });
        return ToReturn;
    }
});