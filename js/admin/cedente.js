$(document).ready(function() {
    var tablaCedente;
    var tablaMandante;
    //listarCedentes();
    listarMandantes();
    var id_mandante;

    function listarCedentes(ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetListarCedentesMandantes.php",
            data: {idMandante: ID},
            //dataType: "json",
            success: function(data){
                TablaCedente = $('#listaCedentes').DataTable({
                    
                    data: JSON.parse(data),
                    //data: data, // este es mi json
                    paging: true,
                    columns: [
                        { data : 'NombreCedente',"width": "80%", }, // campos que trae el json
                        { data: 'idCedente',"width": "20%", }
                    ],
                     "columnDefs": [
                        {
                            "targets": 0,
                            "data": 'NombreCedente',
                        },
                        {
                            "targets": 1,
                            "data": 'idCedente',
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminarCedente fa fa-trash btn-danger btn-icon icon-lg'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificaCedente'></i></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(){
                alert('erroryujuuu2');
            }
        });
    }

    function listarMandantes(){
        $.ajax({
            type: "POST",
            url: "../includes/admin/GetListar_mandantes.php",
            //data: data,
            //dataType: "json",
            success: function(data){
                console.log(data);
                TablaMandante = $('#listaMandantes').DataTable({
                   
                    data: JSON.parse(data), // este es mi json
                    paging: false,
                    //"scrollX": false,
                    columns: [
                        { data : 'nombre' }, // campos que trae el json
                        { data: 'id' }
                        
                    ],
                     "columnDefs": [
                      
                        {
                            "targets": 1,
                            "data": 'Actions', //<i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificar'>
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='fa fa-folder-open-o btn btn-success btn-icon icon-lg listarCedentes'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificaMandante'></i><i style='cursor: pointer; margin: 0 10px;' class='btn eliminarMandante fa fa-trash btn-danger btn-icon icon-lg'></i></div>";
                            }
                        }
                    ]
                }); 
            },
            error: function(response){
                alert(response);
                console.log(response);
            }
        });
    }    

    $('body').on( 'click', '#AddCedente', function () {
       bootbox.dialog({
            title: "Registro de Cedente",
            message: $("#RegistrarCedente").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombreCedente = $('#nombreCedente').val();
                        var fechaIngreso = $('#fechaIngreso').val();
                        var plan = $('#PlanDiscado').val();
                        var discado = $('#TipoOperacion').val();
                        var datos = { 'nombreCedente':nombreCedente, 'fechaIngreso':fechaIngreso, 'idMandante':id_mandante, 'plan':plan, 'discado':discado };

                        if ((nombreCedente == 0) || (nombreCedente == ""))
                        {
                          CustomAlert("Debe ingresar el nombre del cedente");
                          return false;
                        }
                        if ((fechaIngreso == 0) || (fechaIngreso == "") || (fechaIngreso == null))
                        {
                          CustomAlert("Debe seleccionar la fecha de ingreso del cedente");
                          return false;
                        }  
                        addCedente(datos);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen(); 
       $('.selectpicker').selectpicker("refresh");
       $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
    }); 


    $('body').on( 'click', '.modificaMandante', function () {
       var ObjectMe = $(this);
       var ObjectDiv = ObjectMe.closest("div");
       var ID = ObjectDiv.attr("id");
       var ObjectTR = ObjectMe.closest("tr"); 
       bootbox.dialog({
            title: "Modificar Mandante",
            message: $("#modificarMandante").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreMandante').val();
                        var evaluar = $('#evaluar').val();
                        if ((nombre == 0) || (nombre == ""))
                        {
                          CustomAlert("Debe ingresar el nombre del mandante");
                          return false;
                        }
                        var datos = {'nombre':nombre, 'evaluar':evaluar, 'id':ID};           
                        modificarMandante(datos);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
       $(".selectpicker").selectpicker("refresh");
       getDatosMandante(ID);
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen();
    }); 

     $('body').on( 'click', '.modificaCedente', function () {
       var ObjectMe = $(this);
       var ObjectDiv = ObjectMe.closest("div");
       var ID = ObjectDiv.attr("id");
       var ObjectTR = ObjectMe.closest("tr"); 
       bootbox.dialog({
            title: "Modificar Cedente",
            message: $("#modificaCedente").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreCedente').val();
                        var fechaIngreso = $('#fechaIngreso').val();
                        var plan = $('#PlanDiscado').val();
                        var discado = $('#TipoOperacion').val();
                        if ((fechaIngreso == 0) || (nombre == ""))
                        {
                          CustomAlert("Debe ingresar el nombre del cedente");
                          return false;
                        }
                        if ((fechaIngreso == 0) || (fechaIngreso == "") || (fechaIngreso == null))
                        {
                          CustomAlert("Debe seleccionar la fecha de ingreso del cedente");
                          return false;
                        }  
                        var datos = {nombre:nombre,fecha:fechaIngreso,id:ID, plan:plan, discado:discado};           
                        modificarCedente(datos,ObjectTR);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
        $('#date-range .input-daterange').datepicker({
            format: "yyyy/mm/dd",
                weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'es'
        });
       $(".selectpicker").selectpicker("refresh");
       getDatosCedente(ID);
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen();
    }); 

    function modificarCedente(datos,TableRow){  
        $.ajax({
            type: "POST",
            url: "../includes/admin/modificar_cedente.php",
            data: datos,
            async: false,
            success: function(data){
                TableRow.find("td").each(function(index){
                    switch(index){
                        case 0:
                            TablaCedente.cell( $(this)).data(datos.nombre).draw();
                        break;
                    }
                });
                
                CustomAlert("Cedente modificado exitosamente!");
            },
            error: function(){
                alert('error');
            }
        });
    }

    function getDatosMandante(idMandante)   
    {
    $.ajax({
        type:"POST",
        data: {idMandante: idMandante},
        //dataType: "json",
        url:"../includes/admin/GetMostrarMandante.php",
        success:function(data){  
        data = JSON.parse(data);
         console.log(data);

            $('#nombreMandante').val(data[0].nombre);
            $('#evaluar').val(data[0].Empieza); 
            $('#evaluar').selectpicker("refresh");          
            
          },
          error: function(){             
            alert('errorrrrrrDatostrabajador');
          }          
    });
  }

   function getDatosCedente(idCedente)   
    {
    $.ajax({
        type:"POST",
        data: {idCedente: idCedente},
        //dataType: "json",
        url:"../includes/admin/GetMostrarCedente.php",
        success:function(data){  
        data = JSON.parse(data);
         console.log(data);

            $('#nombreCedente').val(data[0].Nombre_Cedente);
            $('#fechaIngreso').val(data[0].Fecha_Ingreso); 
            $('#PlanDiscado').val(data[0].planDiscado);
            $('#PlanDiscado').selectpicker("refresh");     
            $('#TipoOperacion').val(data[0].tipo);
            $('#TipoOperacion').selectpicker("refresh");     
     
            
          },
          error: function(){             
            alert('errorrrrrrDatostrabajador');
          }          
    });
  }


    $('body').on( 'click', '#AddMandante', function () {
       bootbox.dialog({
            title: "Registro de Mandante",
            message: $("#RegistrarMandante").html(),
            buttons: {
                success: {
                    label: "Guardar",
                    className: "btn-primary",
                    callback: function() {
                        var nombre = $('#nombreMandante').val();
                        var evaluar = $('#evaluar').val();                         
                        if ((nombreMandante == 0) || (nombreMandante == ""))
                        {
                          CustomAlert("Debe ingresar el nombre del mandante");
                          return false;
                        } 
                        var datos = {'nombre':nombre, 'evaluar':evaluar};                
                        addMandante(datos);
                       
                    }
                }                
            }
       }).off("shown.bs.modal");
       $(".selectpicker").selectpicker("refresh");
       //FiltrarTablas(GlobalData.id_cedente);
       //resetearCombo();
       //AddClassModalOpen();
    }); 


    function addCedente(datos){    
        $.ajax({
            type: "POST",
            url: "../includes/admin/crear_cedente.php",
            dataType: "html",
            data: datos,
            success: function(data){        
                CustomAlert("Cedente ingresado exitosamente!");
                //location.reload();
                    TablaCedente.row.add(
                        {
                            "NombreCedente": datos['nombreCedente'],
                            "idCedente": data // OJOOOOOOOOOOO
                        }
                   ).draw(false);         
            },
            error: function(){
                alert('error');
            }
        });
    }

     function modificarMandante(datos){    
        $.ajax({
            type: "POST",
            url: "../includes/admin/modificar_mandante.php",
            dataType: "html",
            data: datos,
            success: function(data){       
                CustomAlert("Mandante modificado exitosamente!");
                location.reload();              
            },
            error: function(){
                alert('error');
            }
        });
    }

    



    function addMandante(datos){      
        $.ajax({
            type: "POST",
            url: "../includes/admin/crear_mandante.php",
            dataType: "html",
            data: datos,
            success: function(data){
                CustomAlert("Mandante ingresado exitosamente!");
                location.reload();
                    /*TablaCedente.row.add(
                        {
                            "fechaTermino": nombre,
                            "Actions": idCedente // OJOOOOOOOOOOO
                        }
                   ).draw(false);    */            
            },
            error: function(){
                alert('error');
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

    $("body").on("click",".eliminarCedente", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar el cedente?", function(result) {
            if (result) {                
                eliminarCedente(ObjectTR, ID);                
            }
            //AddClassModalOpen();
        });
    }); 


     $("body").on("click",".eliminarMandante", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        bootbox.confirm("¿Esta seguro que desea eliminar el mandante?.. También se eliminaran sus cedentes automaticamente", function(result) {
            if (result) {
                eliminaMandante(ObjectTR, ID);                
            }
            //AddClassModalOpen();
        });
    }); 


    $("body").on("click",".listarCedentes", function(){
        var ObjectMe = $(this);
        var ObjectDiv = ObjectMe.closest("div");
        var ID = ObjectDiv.attr("id");
        var ObjectTR = ObjectMe.closest("tr");
        id_mandante = ID;
        bootbox.dialog({
            title: "Lista de Cedentes",
            message: $("#listaCedente").html(),   

            size: 'large'
       }).off("shown.bs.modal");
       listarCedentes(ID);        
        
    }); 

    function eliminarCedente(TableRow, ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/eliminar_cedente.php",
            dataType: "html",
            data: {
                idCedente: ID
            },
            success: function(data){
                CustomAlert("El cedente ha sido eliminado");
                TablaCedente.row(TableRow).remove().draw();
                $("#listaCedente").trigger('update');                
            },
            error: function(){

            }
        });
    } 


    function eliminaMandante(TableRow, ID){
        $.ajax({
            type: "POST",
            url: "../includes/admin/eliminar_mandante.php",
            dataType: "html",
            data: {
                idMandante: ID
            },
            success: function(data){
                CustomAlert("El mandante ha sido eliminado");
                location.reload();
            },
            error: function(){

            }
        });
    }   

});    