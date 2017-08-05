$(document).ready(function() {

    var TablaTrabajadores;
    listarTrabajadores();

/*
** Lista las empresas registradas en BD
*/
function listarTrabajadores(){      
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarTrabajadores.php",
            //data: data,
            dataType: "json",
            success: function(data){
                TablaTrabajadores = $('#listaTrabajadores').DataTable({
                    data: data, // este es mi json
                    paging: true,
                    //scrollX: false,
                    columns: [
                        { data : 'nombre' }, // campos que trae el json
                        { data : 'usuario' },
                        { data : 'email' },
                        { data: 'Actions' }
                    ],
                    
                     "columnDefs": [
                      
                        {
                            "targets": 3,
                            "data": 'Actions', 
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminar fa fa-trash btn-danger btn-icon icon-lg'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificar'></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(){
                alert('erroryujuuu');
            }
        });
    }

/*
** Formulario Registro de trabajadores
*/
$('body').on( 'click', '#AddTrabajador', function () {
        bootbox.dialog({
            title: "Registro de Trabajador",
            message: $("#RegistrarTrabajador").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        
                        var nombre = $('#nombreTrabajador').val();
                        var rut = $('#rutTrabajador').val();
                        var fechaNacimiento = $('#fechaNaciTrabajador').val();
                        var nacionalidad = $('#nacionalidadTrabajador').val();

                        var region = $('#regionTrabajador').val();
                        var ciudad = $('#ciudadTrabajador').val();
                        var comuna = $('#comunaTrabajador').val();

                        var direccion = $('#direccionTrabajador').val();
                        var telefonoParticular = $('#particularTrabajador').val();
                        var telefono = $('#telefonoTrabajador').val();
                        var email = $('#correoTrabajador').val();

                        var contacto = $('#contactoTrabajador').val();
                        var parentesco = $('#parentescoTrabajador').val();
                        var celular1 = $('#celular1Trabajador').val();
                        var celular2 = $('#celular2Trabajador').val();

                        var contacto1 = $('#contacto2Trabajador').val();
                        var parentesco1 = $('#parentesco2Trabajador').val();
                        var celular11 = $('#celular12Trabajador').val();
                        var celular21 = $('#celular22Trabajador').val();

                        var afp = $('#afpTrabajador').val();
                        var salud = $('#saludTrabajador').val();
                        var uf = $('#ufTrabajador').val();
                        var ges = $('#gesTrabajador').val();
                        var pensionado = $('#pensionadoTrabajador').val();
                        var remuneracion = $('#remuneracionTrabajador').val();                        
                        var cargo = $('#cargoBD').val();

                        var sexo = $('#sexoTrabajador').val();
                        var estadoCivil = $('#estadoCivilTrabajador').val();
                        var hijos = $('#hijosTrabajador').val();
                        var tipoEjecutivo = $('#tipoEjecutivoTrabajador').val();
                        var tipoContrato = $('#tipoContratoTrabajador').val();
                        //var antiguedad = $('#antiguedadTrabajador').val();
                        var fechaIngreso = $('#fechaIngresoTrabajador').val();
                        

                        var datos = {'nombre':nombre, 'rut':rut, 'fechaNacimiento':fechaNacimiento, 'nacionalidad':nacionalidad, 'comuna':comuna, 'direccion':direccion, 'telefonoParticular':telefonoParticular, 'telefono':telefono, 'email':email, 'contacto':contacto, 'parentesco':parentesco, 'celular1':celular1, 'celular2':celular2, 'contacto1':contacto1, 'parentesco1':parentesco1, 'celular11':celular11, 'celular21':celular21, 'afp':afp, 'salud':salud, 'uf':uf, 'ges':ges, 'pensionado':pensionado, 'remuneracion':remuneracion, 'cargo':cargo, 'sexo':sexo, 'estadoCivil':estadoCivil, 'hijos':hijos, 'tipoEjecutivo':tipoEjecutivo, 'tipoContrato':tipoContrato, 'fechaIngreso':fechaIngreso};

                         //console.log(datos);

                        
                        if ((nombre == "") || (cargo == "") || (rut == "") || (comuna == ""))
                        {
                          CustomAlert("Debe ingresar todos los datos obligatorios (Nombre-Cargo-Rut-Comuna)");
                          return false;
                        }          

                         addTrabajador(datos);

                        /*addTrabajador(nombre,rut,fechaNacimiento,nacionalidad,region,ciudad,comuna,direccion,telefonoParticular,telefono,email,contacto,parentesco,celular1,celular2,contacto1,parentesco1,celular11,celular21,afp,salud,uf,ges,pensionado,remuneracion,cargo);                       */
                    }
                }                
            },
            size: 'large'
       }).off("shown.bs.modal");
       $(".selectpicker").selectpicker("refresh");
       $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
        mostrarCargos();
        mostrarRegiones();
        mostrarNacionalidades();
        mostrarEstadoCivil();
        mostrarTipoContrato();
        //mostrarAntiguedad();
        mostrarTipoEjecutivo();
        mostrarSexo();
});  


/*
** Guarda en BD una empresa nueva
*/ // function addTrabajador(nombre, telefono, email, direccion){  
function addTrabajador(datos){      
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/crear_trabajador.php",
            dataType: "html",
            data: datos,
            success: function(data){
                if(data == 1){
                    CustomAlert("Trabajador registrado exitosamente!");
                    location.reload();
                }else{
                    if(data == 2){
                        CustomAlert("Trabajador no registrado, el Rut ya existe!");    
                    }
                }          
            },
            error: function(){
                alert('errorTrabajador');
            }
        });
}


/*
** Formulario Modificacion de trabajadores
*/
$('body').on( 'click', '.modificar', function () {   
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var idTrabajador = ObjectDiv.attr("id");     
        bootbox.dialog({
            title: "Modificar Trabajador",
            message: $("#ModificarTrabajador").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {

                        var nombre = $('#nombreTrabajador').val();
                        var rut = $('#rutTrabajador').val();
                        var fechaNacimiento = $('#fechaNaciTrabajador').val();
                        var nacionalidad = $('#nacionalidadTrabajador').val();

                        var region = $('#regionTrabajador').val();
                        var ciudad = $('#ciudadTrabajador').val();
                        var comuna = $('#comunaTrabajador').val();

                        var direccion = $('#direccionTrabajador').val();
                        var telefonoParticular = $('#particularTrabajador').val();
                        var telefono = $('#telefonoTrabajador').val();
                        var email = $('#correoTrabajador').val();

                        var contacto = $('#contactoTrabajador').val();
                        var parentesco = $('#parentescoTrabajador').val();
                        var celular1 = $('#celular1Trabajador').val();
                        var celular2 = $('#celular2Trabajador').val();

                        var contacto1 = $('#contacto2Trabajador').val();
                        var parentesco1 = $('#parentesco2Trabajador').val();
                        var celular11 = $('#celular12Trabajador').val();
                        var celular21 = $('#celular22Trabajador').val();

                        var afp = $('#afpTrabajador').val();
                        var salud = $('#saludTrabajador').val();
                        var uf = $('#ufTrabajador').val();
                        var ges = $('#gesTrabajador').val();
                        var pensionado = $('#pensionadoTrabajador').val();
                        var remuneracion = $('#remuneracionTrabajador').val();                        
                        var cargo = $('#cargoBD').val();

                        var sexo = $('#sexoTrabajador').val();
                        var estadoCivil = $('#estadoCivilTrabajador').val();
                        var hijos = $('#hijosTrabajador').val();
                        var tipoEjecutivo = $('#tipoEjecutivoTrabajador').val();
                        var tipoContrato = $('#tipoContratoTrabajador').val();
                        //var antiguedad = $('#antiguedadTrabajador').val();
                        var fechaIngreso = $('#fechaIngresoTrabajador').val();
                        var fechaTermino = $('#fechaTerminoTrabajador').val();
                        var idEstatusEgreso = $('#estatusEgresoTrabajador').val();

                        if ((fechaTermino != "") && (idEstatusEgreso == 0))
                        {
                            CustomAlert("Debe seleccionar el motivo del egreso");
                            return false;
                        }else{
                            if ((fechaTermino == "") && (idEstatusEgreso > 0)){
                                CustomAlert("Debe seleccionar la fecha de egreso");
                                return false;   
                            }
                        }
                        
                         var datos = {'nombre':nombre, 'rut':rut, 'fechaNacimiento':fechaNacimiento, 'nacionalidad':nacionalidad, 'comuna':comuna, 'direccion':direccion, 'telefonoParticular':telefonoParticular, 'telefono':telefono, 'email':email, 'contacto':contacto, 'parentesco':parentesco, 'celular1':celular1, 'celular2':celular2, 'contacto1':contacto1, 'parentesco1':parentesco1, 'celular11':celular11, 'celular21':celular21, 'afp':afp, 'salud':salud, 'uf':uf, 'ges':ges, 'pensionado':pensionado, 'remuneracion':remuneracion, 'cargo':cargo, 'sexo':sexo, 'estadoCivil':estadoCivil, 'hijos':hijos, 'tipoEjecutivo':tipoEjecutivo, 'tipoContrato':tipoContrato, 'fechaIngreso':fechaIngreso, 'fechaTermino':fechaTermino, 'idEstatusEgreso':idEstatusEgreso, 'idTrabajador':idTrabajador};

                         //console.log(datos);

                        
                        if ((nombre == "") || (cargo == "") || (rut == "") || (comuna == "") || (comuna == 0))
                        {
                          CustomAlert("Debe ingresar todos los datos obligatorios (Nombre-Cargo-Rut-Comuna)");
                          return false;
                        }          

                                                               
                        modificaTrabajador(datos);                      
                    }
                }                
            },
             size: 'large'
       }).off("shown.bs.modal");
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen(); 
       $(".selectpicker").selectpicker("refresh");
       getDatosTrabajador(idTrabajador);
       $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
}); 



/*
** Trae los datos de un trabajador en especifico ......
*/
function getDatosTrabajador(idTrabajador)
  {
    $.ajax({
        type:"POST",
        data: {idTrabajador: idTrabajador},
        //dataType: "json",
        url:"../includes/trabajador/GetDatosTrabajador.php",
        success:function(data){  
        data = JSON.parse(data);
         console.log(data);
        

        if (data[0] != undefined){
            var contactoN1 = data[0];  
            $('#contactoTrabajador').val(contactoN1.nombreContacto);
            $('#parentescoTrabajador').val(contactoN1.parentesco);
            $('#celular1Trabajador').val(contactoN1.celular1);
            $('#celular2Trabajador').val(contactoN1.celular2); 
        }
        if (data[1] != undefined){
            var contactoN2 = data[1]; 
            $('#contacto2Trabajador').val(contactoN2.nombreContacto);
            $('#parentesco2Trabajador').val(contactoN2.parentesco);
            $('#celular12Trabajador').val(contactoN2.celular1);
            $('#celular22Trabajador').val(contactoN2.celular2);
        }
        
            
        mostrarMotivoEgreso();
        mostrarNacionalidades();
        mostrarCargos();
        mostrarRegiones();
        FiltrarCiudades(data.idRegion);
        FiltrarComunas(data.idProvincia);
        mostrarEstadoCivil();
        mostrarTipoContrato();
        //mostrarAntiguedad();
        mostrarTipoEjecutivo();
        mostrarSexo();
    
        $('#nombreTrabajador').val(data.nombre);
        $('#rutTrabajador').val(data.rut);
        $('#fechaNaciTrabajador').val(data.fechaNacimiento);
        $('#nacionalidadTrabajador').val(data.idNacionalidad);
        $('#nacionalidadTrabajador').selectpicker("refresh");        
        $('#ciudadTrabajador').val(data.idProvincia);
        $('#ciudadTrabajador').selectpicker("refresh");       
        $('#direccionTrabajador').val(data.direccion);
        $('#particularTrabajador').val(data.fonoParticular);
        $('#telefonoTrabajador').val(data.fonoMovil);
        $('#correoTrabajador').val(data.email);
        $('#afpTrabajador').val(data.afp);
        $('#saludTrabajador').val(data.salud);
        $('#ufTrabajador').val(data.uf);
        $('#gesTrabajador').val(data.ges);
        $('#pensionadoTrabajador').val(data.pensionado);
        $('#remuneracionTrabajador').val(data.remuneracion);                        
        $('#cargoBD').val(data.idCargo);
        $('#cargoBD').selectpicker("refresh");        
        $('#regionTrabajador').val(data.idRegion);
        $('#regionTrabajador').selectpicker("refresh");
        $('#comunaTrabajador').val(data.idComuna);
        $('#comunaTrabajador').selectpicker("refresh");
        $('#sexoTrabajador').val(data.sexo);
        $('#sexoTrabajador').selectpicker("refresh");
        $('#estadoCivilTrabajador').val(data.estadoCivil);
        $('#estadoCivilTrabajador').selectpicker("refresh");
        $('#hijosTrabajador').val(data.hijos);
        $('#tipoEjecutivoTrabajador').val(data.tipoEjecutivo);
        $('#tipoEjecutivoTrabajador').selectpicker("refresh");
        $('#tipoContratoTrabajador').val(data.tipoContrato);
        $('#tipoContratoTrabajador').selectpicker("refresh");
        $('#fechaIngresoTrabajador').val(data.fechaIngreso);   
        $('#fechaTerminoTrabajador').val(data.fechaTermino);
        $('#estatusEgresoTrabajador').val(data.idEstatusEgreso);  
        $('#estatusEgresoTrabajador').selectpicker("refresh");          
            
          },
          error: function(){             
            alert('errorrrrrrDatostrabajador');
          }          
    });
  }

/*
** Modifica trabajador
*/
function modificaTrabajador(datos){      
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/modifica_trabajador.php",
            dataType: "html",
            data: datos,
            success: function(data){
                if(data == 1){
                    CustomAlert("Trabajador modificado exitosamente!");
                    location.reload();
                }else{
                    if(data == 2){
                        CustomAlert("Trabajador no modificado, el Rut ya existe!");    
                    }
                }     
         
            },
            error: function(){
                alert('errormodificaTrabajador');
            }
        });
}

$("body").on("click",".eliminar", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar el Trabajador?", function(result) {
            if (result) {
                eliminarTrabajador(ObjectTR, ID);                
            }
        });
});


function eliminarTrabajador(TableRow, ID){
    $.ajax({
        type: "POST",
        url: "../includes/trabajador/eliminarTrabajador.php",
        dataType: "html",
        data: { idTrabajador: ID },
        success: function(data){
            CustomAlert("El trabajador ha sido eliminado");
            TablaTrabajadores.row(TableRow).remove().draw();
            $("#listaTrabajadores").trigger('update');                
        },
        error: function(){

        }
    });
} 


 /*
    *  Validando campo email
  */ 
 $('body').on( 'blur', '#correoTrabajador', function ( event ) { 
     event.preventDefault();
    // Expresion regular para validar el correo
    if ($('#correoTrabajador').val().trim() != "")
    {
      var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
      // Se utiliza la funcion test() nativa de JavaScript
      if (!(regex.test($('#correoTrabajador').val().trim())))
      {
        $('#correoTrabajador').val("");
        CustomAlert("La dirección de correo no es valida!");    
      }
    }
  });


  /*
   *  Muestra los cargos de los trabajadores
  */
    function mostrarCargos(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarCargos.php",
            //dataType: "html",
            async: false,
            data: {},
            success: function(data){
                
                $("select[name='cargoBD']").html(data);
                $("select[name='cargoBD']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarcargos');          
            }
        });
    }

  /*
   *  Muestra las nacionalidades
  */
    function mostrarNacionalidades(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarNacionalidad.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='nacionalidadTrabajador']").html(data);
                $("select[name='nacionalidadTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarcargos');          
            }
        });
    }

    

  /*
   *  Muestra sexo
  */
    function mostrarSexo(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarSexo.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='sexoTrabajador']").html(data);
                $("select[name='sexoTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarsexo');          
            }
        });
    }

    

    /*
   *  Muestra Tipo ejecutivo
  */
    function mostrarTipoEjecutivo(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarTipoEjecutivo.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='tipoEjecutivoTrabajador']").html(data);
                $("select[name='tipoEjecutivoTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrartipoEjecutivo');          
            }
        });
    }

    /*
   *  Muestra Antiguedad
  */


    function mostrarAntiguedad(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarAntiguedad.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='antiguedadTrabajador']").html(data);
                $("select[name='antiguedadTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarAntiguedad');          
            }
        });
    }

     /*
   *  Muestra Tipo Contrato
  */
    function mostrarTipoContrato(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarTipoContrato.php",
            async: false,
            data: {},
            success: function(data){          
                $("select[name='tipoContratoTrabajador']").html(data);
                $("select[name='tipoContratoTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarTipoContrato');          
            }
        });
    }

     /*
   *  Muestra estado civil
  */
    function mostrarEstadoCivil(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarEstadoCivil.php",
            async: false,
            data: {},
            success: function(data){                
                $("select[name='estadoCivilTrabajador']").html(data);
                $("select[name='estadoCivilTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarEstadoCivil');          
            }
        });
    }


     /*
   *  Muestra estado civil
  */
    function mostrarMotivoEgreso(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarMotivoEgreso.php",
            async: false,
            data: {},
            success: function(data){   
         
                $("select[name='estatusEgresoTrabajador']").html(data);
                $("select[name='estatusEgresoTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarMotivoEgreso');          
            }
        });
    }


  /*
  * Lista las ciudades dependiendo de la region seleccionada
  */ 
  $('body').on( 'change', '#regionTrabajador', function ( event ) { 
      var idRegion = $('#regionTrabajador').val();
      if ((idRegion != 0) || (idRegion != ""))
      {
        FiltrarCiudades(idRegion);
      }else {
        //resetearCombos();
      }
  });

  /*
    * busca ciudades dependiendo de la region seleccionada
  */
  function FiltrarCiudades(idRegion){
      $.ajax({
          type: "POST",
          url: "../includes/trabajador/GetListarCiudades.php",
          async: false,
          dataType: "html",
          data: {idRegion: idRegion},
          success: function(data){
              $("select[name='ciudadTrabajador']").html(data);
              $("select[name='ciudadTrabajador']").selectpicker('refresh');
          },
          error: function(){
              alert('errorfiltrarciudades');
          }
      });
  }

   /*
  * Lista las comunas dependiendo de la provincia seleccionada
  */ 
  $('body').on( 'change', '#ciudadTrabajador', function ( event ) { 
      var idProvincia = $('#ciudadTrabajador').val();
      if ((idProvincia != 0) || (idProvincia != ""))
      {
        FiltrarComunas(idProvincia);
      }else {
        //resetearCombos();
      }
  });

  /*
    * busca comunas dependiendo de la provincia seleccionada
  */
  function FiltrarComunas(idProvincia){
      $.ajax({
          type: "POST",
          url: "../includes/trabajador/GetListarComunas.php",
          async: false,
          dataType: "html",
          data: {idProvincia: idProvincia},
          success: function(data){
              $("select[name='comunaTrabajador']").html(data);
              $("select[name='comunaTrabajador']").selectpicker('refresh');
          },
          error: function(){
              alert('errorfiltrarcomunas');
          }
      });
  }



  /*
   *  Muestra las regiones
  */
    function mostrarRegiones(){
        $.ajax({
            type: "POST",
            url: "../includes/trabajador/GetListarRegiones.php",
            async: false,
            //dataType: "html",
            data: {},
            success: function(data){
                $("select[name='regionTrabajador']").html(data);
                $("select[name='regionTrabajador']").selectpicker('refresh');
            },
            error: function(){   
                alert('errormostrarregiones');          
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
