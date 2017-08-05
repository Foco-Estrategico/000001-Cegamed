$(document).ready(function() 
{
	$('#FechaInicio').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
    $('#FechaTermino').datepicker({autoclose:true,format: "yyyy-mm-dd", weekStart: 1, language: 'es'});
    $('#TipoBusqueda').change(function(){
        var Tipo = $('#TipoBusqueda').val();
        var data = "Tipo="+Tipo;  
      
        $.ajax({
            type: "POST",
            url: "../includes/reporte/PorCartera.php",
            data: data,
            success: function(response)
            {
                $('#Div1').html(response);
                $('#Mandante').selectpicker('refresh');
                
            }	
        }); 	
    });

    $(document).on("change","#Mandante" , function(){
        var Mandante = $('#Mandante').val();
        data = "Mandante="+Mandante;
        $.ajax({
            type: "POST",
            url: "../includes/reporte/Cartera.php",
            data: data,
            success: function(response)
            {
                $('#Div2').html(response);
                $('#Cartera').selectpicker('refresh');
            }
        });   
               
    });

    $(document).on("change","#Cartera" , function(){
        var Cartera = $('#Cartera').val();
        var Mandante = $('#Mandante').val();
        data = "Cartera="+Cartera+"&Mandante="+Mandante;
        $.ajax({
            type: "POST",
            url: "../includes/reporte/Periodo.php",
            data: data,
            success: function(response)
            {
                $('#Div3').html(response);
                $('#Periodo').selectpicker('refresh');
            }
        });       
    });


    $(document).on("click","#Buscar" ,function(){

        
        $('#demo-morris-line').empty();
        $('#demo-morris-diario').empty();
        $('#demo-morris-contactabilidad').empty();
        $('#demo-morris-acumulado').empty();
        $('#demo-morris-contactabilidad-acumulada').empty();
        var Tipo = $('#TipoBusqueda').val();
        var Periodo = $('#Periodo').val();
        var Mandante = $('#Mandante').val();
        var Cartera = $('#Cartera').val();
        var data = "Tipo="+Tipo+"&Periodo="+Periodo+"&Mandante="+Mandante+"&Cartera="+Cartera;
   
        if(Tipo==1){
            
            $('body').addClass("loading");    

            $.ajax({
                type: "POST",
                url: "../includes/reporte/VerCartera.php",
                data: data,
                success: function(response)
                {
                   
                    $('#Mostrar').html(response);

                    var json1 = $('#json1').val();
                    json1 = jQuery.parseJSON(json1);
                    console.log(json1);
                    
                    new Morris.Line({
                        element: 'demo-morris-line',
                        data: json1,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Contactabilidad %', ''],
                        gridEnabled: false,
                        parseTime: false,
                        barColors: ['#ff5733', '#ff5733'],
                        resize:true,
                        hideHover: 'auto'
                    });

                    $('#TablaScroll').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );
                    $('#TablaScroll2').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );
                    $('#TablaScroll3').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );
                    $('#TablaScroll4').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );
                    $('#TablaScroll5').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );
                    var json2 = $('#json2').val();
                    json2 = jQuery.parseJSON(json2);
                    console.log(json2);
                    
                    new Morris.Bar({
                        element: 'demo-morris-diario',
                        data: json2,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Contactabilidad %', ''],
                        gridEnabled: false,
                        barColors: ['#177bbb', '#afd2f0'],
                        resize:true,
                        hideHover: 'auto'
                    });

                    var json3 = $('#json3').val();
                    json3 = jQuery.parseJSON(json3);
                    console.log(json3);
                    
                    new Morris.Bar({
                        element: 'demo-morris-contactabilidad',
                        data: json3,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Contactabilidad %', ''],
                        gridEnabled: false,
                        barColors: ['#FF5733', '#FF5733'],
                        resize:true,
                        hideHover: 'auto'
                    });

                    var json4 = $('#json4').val();
                    json4 = jQuery.parseJSON(json4);
                    console.log(json4);
                    
                    new Morris.Bar({
                        element: 'demo-morris-acumulado',
                        data: json4,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Contactabilidad %', ''],
                        gridEnabled: false,
                        barColors: ['#f9ee14', '#f9ee14'],
                        resize:true,
                        hideHover: 'auto'
                    });

                    var json5 = $('#json5').val();
                    json5 = jQuery.parseJSON(json5);
                    console.log(json5);
                    
                    new Morris.Bar({
                        element: 'demo-morris-contactabilidad-acumulada',
                        data: json5,
                        xkey: 'y',
                        ykeys: ['a', 'b'],
                        labels: ['Contactabilidad %', ''],
                        gridEnabled: false,
                        barColors: ['#37f914 ', '#37f914'],
                        resize:true,
                        hideHover: 'auto'
                    });
                    /*$('#TablaScroll2').DataTable( {
                        "scrollX": true,
                        "bSort": false
                    } );*/

                    
                    $('body').removeClass("loading");
                    $('#TipoBusqueda').val('default');
                    $('#TipoBusqueda').selectpicker("refresh");
                    $('#Mandante').val('default');
                    $('#Mandante').selectpicker("refresh");
                    $('#Cartera').val('default');
                    $('#Cartera').selectpicker("refresh");
                    $('#Periodo').val('default');
                    $('#Periodo').selectpicker("refresh");
                }	
            });	
            
        }
        else if(Tipo==2){
        }
        
    });
});
