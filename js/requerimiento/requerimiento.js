$(document).ready(function()
{
	var TablaRequerimientos;

	listarRequerimientos();
    
    $(document).on('click', '#guardar', function() {
        var tipo = $('input:radio[name=tipoRequerimiento]:checked').val();
        var modulo = $('#modulo').val();
        var descripcion = $('#descripcion').val();
        var prioridad = $('#prioridad').val();

        if (descripcion == ''){
             $.niftyNoty(
				{
   				type: 'danger',
  				icon : 'fa fa-close',
  				message : "La descripción no debe estar vacía" ,
  				container : 'floating',
  				timer : 5000
  			    });

        }else{
        
			addRequerimiento(tipo,modulo,descripcion,prioridad);
		
        }
	});

$('body').on( 'click', '#AddRequerimiento', function () {
        bootbox.dialog({
            title: "Registro de Solicitud de Requerimiento",
            message: $("#requerimiento").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-success",
                    callback: function() {
                        var tipo = $('input:radio[name=tipoRequerimiento]:checked').val();
						var modulo = $('#modulo').val();
						var descripcion = $('#descripcion').val();
						var prioridad = $('#prioridad').val();
                        if (descripcion == ''){
                          CustomAlert("La descripción no debe estar vacía");
                          return false;
                        }                                                  
                        addRequerimiento(tipo, modulo, descripcion, prioridad);                      
                    }
                }                
            }
       }).off("shown.bs.modal");
	   $(".selectpicker").selectpicker("refresh");
	   $("#mejora").attr('checked', true); 
	   $("#mejora").closest("label.form-radio.form-normal").addClass("active");
});

$('body').on( 'click', '.modificar', function () {
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idRequerimiento = ObjectDiv.attr("id");
		if (idRequerimiento == 0){
			CustomAlert("Disculpe, no tiene permisos para modificar la solicitud");
            return false;
		}else{    
			bootbox.dialog({
				title: "Modificar Solicitud",
				message: $("#requerimiento").html(),
				buttons: {
					success: {
						label: "Modificar",
						className: "btn-success",
						callback: function() {
							var tipo = $('input:radio[name=tipoRequerimiento]:checked').val();
							var modulo = $('#modulo').val();
							var descripcion = $('#descripcion').val();
							var prioridad = $('#prioridad').val();
							if (descripcion == ''){
							CustomAlert("La descripción no debe estar vacía");
							return false;
							}                                                
							modificaRequerimiento(tipo, modulo, descripcion, prioridad, idRequerimiento);                  
						}
					}                
				}
		}).off("shown.bs.modal");
		getDatosRequerimiento(idRequerimiento);
	   }
});

$("body").on("click",".eliminar", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        if (ID == 0){
			CustomAlert("Disculpe, no tiene permisos para eliminar la solicitud");
            return false;
		}else{
			bootbox.confirm("¿Esta seguro que desea eliminar el requerimiento?", function(result) {
				if (result) {
					eliminarRequerimiento(ObjectTR, ID);                
				}
			});
		}
}); 

function eliminarRequerimiento(TableRow, ID){
    $.ajax({
        type: "POST",
        url: "../includes/requerimiento/eliminaRequerimiento.php",
        dataType: "html",
        data: { idRequerimiento: ID },
        success: function(data){
            CustomAlert("El requerimiento ha sido eliminado");
            TablaRequerimientos.row(TableRow).remove().draw();
            $("#requerimiento").trigger('update');                
        },
        error: function(){

        }
    });
} 

function getDatosRequerimiento(idRequerimiento){
	$.ajax({
        type:"POST",
        data: {idRequerimiento: idRequerimiento},
        //dataType: "json",
        url:"../includes/requerimiento/getDatosRequerimiento.php",
        success:function(data){ 
			data = JSON.parse(data);         
            data = data[0];
            $('#modulo').val(data.modulo);
			$('#modulo').selectpicker("refresh");
            $('#descripcion').val(data.descripcion);
            $('#prioridad').val(data.prioridad);
			$('#prioridad').selectpicker("refresh"); 
			$(".radio label.form-radio.form-normal").removeClass("active");
			if (data.tipo == 1){
				$("#mejora").attr('checked', true); 
				$("#mejora").closest("label.form-radio.form-normal").addClass("active");
				$("#errores").attr('checked', false); 
			}else{
				$("#mejora").attr('checked', false); 
				$("#errores").attr('checked', true); 
				$("#errores").closest("label.form-radio.form-normal").addClass("active");
			}
    
          },
          error: function(response){             
            alert(response);
          }          
    });
}


function addRequerimiento(tipo, modulo, descripcion, prioridad){   
		var datos = {'tipo':tipo, 'modulo':modulo, 'descripcion':descripcion, 'prioridad':prioridad};   
        $.ajax(
	    {
			type: "POST",
			url: "../includes/requerimiento/guardar.php",
			data: datos,
			success: function(response)
			{
			   $.niftyNoty(
				{
   				type: 'success',
  				icon : 'fa fa-close',
  				message : "Solicitud registrada exitosamente!!",
  				container : 'floating',
  				timer : 5000
  			    });
               location.reload();
			},
            error: function(response){     
					alert(response);
				}
		});
}


function modificaRequerimiento(tipo, modulo, descripcion, prioridad, idRequerimiento){   
		var datos = {'tipo':tipo, 'modulo':modulo, 'descripcion':descripcion, 'prioridad':prioridad, 'idRequerimiento':idRequerimiento};
        $.ajax(
	    {
			type: "POST",
			url: "../includes/requerimiento/modificaRequerimiento.php",
			data: datos,
			success: function(response)
			{
			   $.niftyNoty(
				{
   				type: 'success',
  				icon : 'fa fa-close',
  				message : "Solicitud modificada exitosamente!!",
  				container : 'floating',
  				timer : 5000
  			    });
               location.reload();
			},
            error: function(response){     
					alert(response);
				}
		});
}


function listarRequerimientos(){        
        $.ajax({
            type: "POST",
            url: "../includes/requerimiento/getListarRequerimientos.php",
            //data: data,
            dataType: "json",
            success: function(data){
                TablaRequerimientos = $('#listaRequerimientos').DataTable({
                    data: data, // este es mi json
                    paging: false,
                    columns: [
                        { data : 'tipo' }, // campos que trae el json
						{ data : 'modulo' },
						{ data : 'prioridad' },
						{ data : 'usuario' },
						{ data : 'fecha' },
                        { data: 'Actions' }
                    ],
                     "columnDefs": [
                      
                        {
                            "targets": 5,
                            "data": 'Actions', 
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminar fa fa-trash btn-danger btn-icon icon-lg'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificar'></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(){
                alert('errorListarRequerimiento');
            }
        });
    } 

function CustomAlert(Message){
      bootbox.alert(Message,function(){
          AddClassModalOpen();
      });
 } 

function AddClassModalOpen(){
    setTimeout(function(){
        if($("body").hasClass("modal-open")){
            $("body").removeClass("modal-open");
        }
    }, 500);
} 


});    
	