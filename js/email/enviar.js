$(document).ready(function(){

    verificaCronActivo();
    listaColasPendientes();

	function verificaCronActivo(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/verificaCron.php",
            //data: data,
            //dataType: "json",
            success: function(data){
            	if (data == 1)
			  	{
					$("#enviar-mail").removeAttr("disabled");
                    $('#continuarEnvio').prop('disabled', true); // true inactivo
			  	}else{
					$('#enviar-mail').prop('disabled', true);
                    verificaAlertaEnvio();
                    
			  	}
			  
            },
            error: function(){
                alert('erroryujuuu');
            }
        });		
	}

    function verificaAlertaEnvio(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/verificaAlertaEnvio.php",
            //data: data,
            //dataType: "json",
            success: function(data){
                data = JSON.parse(data);
               
                if (data.length > 0)//si entra aca es porq al menos tiene una cola de envio
                {
                   var usuarios = " ";
                   $.each(data, function (indice, elemento){
                    usuarios+=elemento+", ";
                   });
                    $(".alert").addClass("alert-warning");
                    $('#continuarEnvio').prop('disabled', false); // true inactivo
				    $(".alert").html("Alerta, Ya existen envíos programados, comuníquese con el usuario(s) "+usuarios+" para eliminar o continuar con los envios");
                }else{
                    $(".alert").addClass("alert-warning");
				    $(".alert").html("Alerta, El envio de correo solo sera permitido en el horario de 09:00 am - 20:00 pm");
                }
          
			  
            },
            error: function(){
                alert('erroryujuuu');
            }
        });		
	}

    function listaColasPendientes(){
		 $.ajax({
            type: "POST",
            url: "../includes/email/GetListarColas.php",
            //dataType: "html",
            async: false,
            data: {},
            success: function(data){
                
                $("select[name='colaPendiente']").html(data);
                $("select[name='colaPendiente']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarcargos');          
            }
        });
	}


    $('#colaPendiente').on('change', function(){
        var table = $("#colaPendiente option:selected").html(); 
        var tableEmail = $("#tablaEnvio option:selected").html(); 
		$.ajax({
			type: "POST",
			url: "../includes/email/info-estrategia.php",
			data: { table:table, tableEmail: tableEmail},
			dataType: "html",
			beforeSend: function(){
			},
			success: function(result){
				var data = JSON.parse(result);
				$('#enviadosPendientes').html(data[2]);
				$('#esperaPendientes').html(data[3]);
				$('#horaPendientes').html(data[4]);
			},
			error: function(){
			}
		});
    });


    $("body").on("click","#cancelarEnvio", function(){
        var idCola = $('#colaPendiente').val();
        if (idCola == 0){
             CustomAlert("Debe seleccionar una cola");
        }else
        {
        bootbox.confirm("¿Esta seguro que desea eliminar el envio?", function(result) {
            if (result) {
               cancelarCola(idCola);                
            }
        });
    }
    });


    $("body").on("click","#continuarEnvio", function(){
        var idCola = $('#colaPendiente').val();
        if (idCola == 0){
             CustomAlert("Debe seleccionar una cola");
        }else
        {
        bootbox.confirm("¿Esta seguro que desea continuar con el envio?", function(result) {
            if (result) {
               continuarCola(idCola);                
            }
        });
    }
    });

    function cancelarCola(ID){
    $.ajax({
        type: "POST",
        url: "../includes/email/cancelarCola.php",
        dataType: "html",
        data: { ID: ID },
        success: function(data){
            CustomAlert("El envío ha sido cancelado");
            location.reload();              
        },
        error: function(){

        }
    });
} 

function continuarCola(ID){
    $.ajax({
        type: "POST",
        url: "../includes/email/continuarCola.php",
        dataType: "html",
        data: { ID: ID },
        success: function(data){
            if(data == 1){
                CustomAlert("El envío se activo satisfactoriamente");
            }else{
                if(data == 2){
                   CustomAlert("El envío se activara cuando todas las colas sean activadas por los usuarios"); 
                }     
            }   
            location.reload();              
        },
        error: function(){

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