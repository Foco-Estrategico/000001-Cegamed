var GlobalData;
$(document).ready(function($){
  //Llamada a Variables Globales
  getGlobalData();

  $(document).on('click', '#idSeleccionarCedente', function() {
      //var cedente = $('#cedente').val();
      //var data = "cedente="+cedente;

      $.ajax({
        type: "POST",
        url: "../includes/global/seleccionar_cedente.php",
        data:{
          cedente: GlobalData.id_cedente,
          mandante: GlobalData.id_mandante
        },
        success: function(response)
        {
          resp = response;
          var data_modal = resp;
          bootbox.dialog({
            title: "Cedente",
            message:data_modal,
            buttons: {
              success: {
                label: "Enviar",
                className: "btn-primary",
                callback: function() {
                  if( $('#cedenteSeleccionado').val() == "0"  ){
                      $("#cedenteSeleccionado").focus().after("<span class='error'>Seleccione una opción</span>");
                      return false;
                  }
                  var cedente = $('#cedenteSeleccionado').val();
                  var data_cedente = "cedente="+cedente;

                  $.ajax(
                  {
                    type: "POST",
                    url: "../sesion_cedente_cambiar.php",
                    data:data_cedente,
                    success: function(response)
                    {
                      location.reload();
                    }
                  });


                }
              }
            }
          });
          setTimeout(function(){
            $(".selectpicker").selectpicker("refresh");
        },100);
          
        }
      });

    });
    PredictivoNotification();
    function PredictivoNotification(){
      setInterval(function(){
        $.ajax({
          type: "POST",
          url: "../includes/tareas/FindFinishedQueues.php",
          data:{},
          success: function(response)
          {
            if(response != ""){
              var json = JSON.parse(response);
              $.each(json,function(index, value){
                Push.create('Discador Predictivo',{
                  body: "La cola "+value.Queue+" asignada al grupo: "+value.Cola+" ha culminado. Click aqui para ir a configuracion de colas.",
                  onClick: function () {
                    window.open('../tareas/configuracionPredictivo.php','_blank');
                    this.close();
                  }
                });
              });
            }
          }
        });
      },3000);
    }
    JavaProcessNotification();
    function JavaProcessNotification(){
      setInterval(function(){
        $.ajax({
          type: "POST",
          url: "../carga/ajax/FindFinishedJavaProcess.php",
          data:{},
          success: function(response)
          {
            if(response != ""){
              var json = JSON.parse(response);
              $.each(json,function(index, value){
                Push.create('Carga Automatica',{
                  body: "El archivo: "+value.filename+" fue procesado satisfactoriamente.\nCarga realizada por: "+value.usuario
                });
              });
            }
          }
        });
      },3000);
    }

    // BOOTSTRAP DATEPICKER
    // =================================================================
    // Require Bootstrap Datepicker
    // http://eternicode.github.io/bootstrap-datepicker/
    // =================================================================
    //$('#demo-dp-txtinput input').datepicker();-----------------------------------------------------------------------

    //$('#demo-dp-txtinput input').datepicker({format: "dd-mm-yyyy"});



    // BOOTSTRAP DATEPICKER WITH AUTO CLOSE
    // =================================================================
    // Require Bootstrap Datepicker
    // http://eternicode.github.io/bootstrap-datepicker/
    // =================================================================

    //$('#demo-dp-component .input-group.date').datepicker({autoclose:true});-------------------------------------------

  if(($('#demo-dp-component .input-group.date').size() > 0) || ($('.input-daterange').size() > 0)){
    $.fn.datepicker.dates['es'] = {
          days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"],
          daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
          daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
          months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
          monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
          today: "Hoy"
    };
    $('#demo-dp-component .input-group.date').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});


    $('.input-daterange').datepicker({
        format: "yyyy/mm/dd",
        weekStart: 1,
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        language: 'es'
    });
  }




    //Formaro

    // PARA FORMATO DE NUMEROS CON SEPARADORES DECIMALES
    // =================================================================
	//$('#number_container').slideDown('fast');

	// $('#price').on('change',function(){
	// 	console.log('Change event.');
	// 	var val = $('#price').val();
	// 	$('#the_number').text( val !== '' ? val : '(empty)' );
	// });

	// $('#price').change(function(){
	// 	console.log('Second change event...');
	// });

	// //$('#price').number( true, 2 );
	// $('#price').number( true, 2,',','.' );


	// // Get the value of the number for the demo.
	// $('#get_number').on('click',function(){

	// 	var val = $('#price').val();

	// 	$('#the_number').text( val !== '' ? val : '(empty)' );



  //Funcion de Variables Globales
  function getGlobalData(){
    var ToReturn = false;
      $.ajax({
      type: "POST",
      url: "../includes/global/GetGlobalData.php",
      dataType: "html",
      async: false,
      success: function(data){
        GlobalData = JSON.parse(data);
        ToReturn = true;
        if (typeof GlobalData.id_cedente == "undefined"){
          $("#idSeleccionarCedente").remove();
        }
      },
      error: function(){

      }
      });
    return ToReturn;
  }

  //verificaSeleccionCedente();
  /*
  ** Funcion que verifica si en el modulo donde estoy parada necesita seleccionar cedente
  ** esto solo para el rol administrador
  */
  function verificaSeleccionCedente(){
    // necesito variable del menu para luego ver si el necesita cedente esto ultimo lo busco en bd
    var data = "idMenu="+GlobalData.idMenu;
    $.ajax({
      type: "POST",
      url: "../includes/global/GetAdminCedente.php",
      dataType: "html",
      //async: false,
      data:data,
      success: function(data){
        if (data == 1){
          seleccionMandanteCedente();
        }
      },
      error: function(){

      }
    });
    // busco
  }


  function seleccionMandanteCedente(){
     if (typeof nombreVariable == "undefined")
     bootbox.dialog({
            title: "Seleccionar Mandante y Cedente",
            message: templeteMandanteCedente,
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        /*var idTabla = $('#tablaBD').val();
                        var idCampos = $('#camposTabla').val();
                        var nombreTabla = $("#tablaBD option:selected").html();
                        if ((idTabla == 0) || (idTabla == ""))
                        {
                          CustomAlert("Debe seleccionar una tabla");
                          return false;
                        }
                        if ((idCampos == 0) || (idCampos == "") || (idCampos == null))
                        {
                          CustomAlert("Debe seleccionar minimo un campo");
                          return false;
                        }
                        addTabla(idTabla,idCampos,GlobalData.id_cedente,nombreTabla);*/
                        var idCedenteAdmin = $('#cedenteAdmin').val();
                        var idMandanteAdmin = $('#mandanteAdmin').val();
                        registraSessionCedente(idCedenteAdmin, idMandanteAdmin);
                        location.reload();

                    }
                }
            }
       }).off("shown.bs.modal");
       $(".selectpicker").selectpicker();
       mandantes();
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen();

  }


  function registraSessionCedente(idCedenteAdmin, idMandanteAdmin){
        $.ajax({
            type: "POST",
            url: "../includes/global/cedenteSessionAdmin.php",
            dataType: "html",
            data: { idCedenteMandante: idCedenteAdmin, idMandanteAdmin: idMandanteAdmin },
            success: function(data){

            },
            error: function(){

            }
        });
    }


  function mandantes(){
        $.ajax({
            type: "POST",
            url: "../includes/global/GetMandante.php",
            //dataType: "html",
            //data: {idCedente: idCedente},
            success: function(data){
                $("select[name='mandanteAdmin']").html(data);
                $("select[name='mandanteAdmin']").selectpicker('refresh');
            },
            error: function(){

            }
        });
    }

     function cedentesMandante(idMandante){
        $.ajax({
            type: "POST",
            url: "../includes/global/GetCedentesMandante.php",
            //dataType: "html",
            data: {mandante: idMandante},
            success: function(data){
                $("select[name='cedenteAdmin']").html(data);
                $("select[name='cedenteAdmin']").selectpicker('refresh');
            },
            error: function(){

            }
        });
    }


  $("body").on("change","#mandanteAdmin",function(){
    var idMandante = $('#mandanteAdmin').val();
    if ((idMandante != 0) || (idMandante != ""))
    {
      cedentesMandante(idMandante);
    }else {
      //resetearCombos();
    }
  });


  $('body').on( 'click', '#cambiaMandante', function () {
    seleccionMandanteCedente();
  });

  var templeteMandanteCedente = ''+
  '<div class="row">'+
    '<div class="col-md-12">'+
      '<form class="form-horizontal">'+
        '<div class="row">'+
          '<div class="col-md-12">'+
            '<div class="form-group">'+
              '<div class="col-md-3">'+
                '<label>Mandante</label>'+
              '</div> '+
              '<div class="col-md-8">'+
                '<select class="selectpicker" title="Seleccione" id="mandanteAdmin" name="mandanteAdmin" data-live-search="true" data-width="100%"></select>'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="row">'+
          '<div class="col-md-12">'+
            '<div class="form-group">'+
              '<div class="col-md-3">'+
                '<label>Cedente</label>'+
              '</div>'+
              '<div class="col-md-8">'+
                '<select class="selectpicker" title="Seleccione" id="cedenteAdmin" name="cedenteAdmin" data-live-search="true" data-width="100%"></select>'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>    '+
      '</form>'+
    '</div>'+
  '</div>';
});


var TimeOutCloseSession;
/*var ValidSession = function(){
  var moviendo= false;
  document.onmousemove = function(){
  moviendo= true;
  };
  setInterval (function() {
    if (!moviendo) {
      alertCloseSession();
      TimeCloseSession();
    } else {
      moviendo=false;
      closeModal();
    }
  }, 30000);
}*/
setInterval (function() {
  $.post('../class/verificarSession.php', function(data){
    if (data =='true'){
        var modal= '<div style="position: absolute !important; z-index:99999999" class="modal fade" tabindex="-1" role="dialog" id="alertSession2">';
        modal+= '<div class="modal-dialog" role="document">';
        modal+= '<div class="modal-content">';
        modal+= '<div class="modal-header">';
        modal+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        modal+= '<h4 class="modal-title"><b>Alerta de session </b></h4>';
        modal+= '</div>';
        modal+= '<div class="modal-body">';
        modal+= '<div class="row">';
        modal+= '<div class="col-md-offset-2 col-md-8">';
        modal+= '<h3 class="text-center">La sesion se cerrara en 10 segundos</h3>';
        modal+= '</div>';
        modal+= '</div>';
        modal+= '</div>';
        modal+= '</div><!-- /.modal-content -->';
        modal+= '</div><!-- /.modal-dialog -->';
        modal+= '</div>';
        $('body').append(modal)
        $('#alertSession2').modal({
          backdrop: 'static',
          keyboard: false
        });
        TimeCloseSession();
    }
  });
}, 30000);

var alertCloseSession = function(){
  var modal= '<div class="modal fade" tabindex="-1" role="dialog" id="alertSession">';
  modal+= '<div class="modal-dialog" role="document">';
  modal+= '<div class="modal-content">';
  modal+= '<div class="modal-header">';
  modal+= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
  modal+= '<h4 class="modal-title"><b>Alerta no se detecto movimiento</b></h4>';
  modal+= '</div>';
  modal+= '<div class="modal-body">';
  modal+= '<div class="row">';
  modal+= '<div class="col-md-offset-2 col-md-8">';
  modal+= '<h2 class="text-center">La sesion se cerrara en 10 segundos</h2>';
  modal+= '</div>';
  modal+= '</div>';
  modal+= '</div>';
  modal+= '<div class="modal-footer">';
  modal+= '<button type="button" class="btn btn-primary noCloseSession" data-dismiss="modal">No</button>';
  modal+= '</div>';
  modal+= '</div><!-- /.modal-content -->';
  modal+= '</div><!-- /.modal-dialog -->';
  modal+= '</div>';
  $('body').append(modal)
  $('#alertSession').modal({
    backdrop: 'static',
    keyboard: false
  });
}

var closeModal = function(){
  $('#alertSession').modal('hide');
  $('#alertSession').on('hidden.bs.modal', function (e) {
      $('#alertSession').remove();
  })
}

var TimeCloseSession = function(){
  TimeOutCloseSession = setTimeout(function() {
    $.post('../class/closeSession.php',function(){
      window.location = "../index.php";
    });
  }, 10000);
}

$(document).on('click', '.noCloseSession', function(){
  clearTimeout(TimeOutCloseSession);
});

function formatDollar(num) {
    var p = num.toFixed(2).split(".");
    return "$" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
    }, "") + "." + p[1];
}
//ValidSession();
function isJson(Value){
  var ToReturn = true;
  try{
    var json = $.parseJSON(Value);
  }
  catch(err){
    ToReturn = false;
  }
  return ToReturn;
}              