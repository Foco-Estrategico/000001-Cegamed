$(document).ready(function(){
    
    var myChart;
    var myChartContacto;
    var myChartNivel1;
    var myChartNivel2;
    var myChartNivel3;

    //graficoBarra();

    function graficoBarra(){
        var ctx = document.getElementById("myChartBarra");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }   
        });
    }

    function grafico(ArrayNombres,ArrayCantidades,ArrayId,idChart,tipoGrafico){
        //var ctx = document.getElementById("myChart");
        var ctx = document.getElementById(idChart);
        var Width = $("#"+idChart).closest("div").width();
        //alert(Width);
        myChart = new Chart(ctx, {
              type: tipoGrafico,           
              data: {
                //labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                labels: ArrayNombres,
                id : ArrayId,
                datasets: [{
                    label: '',
                    //data: [12, 19, 3, 5, 2, 3],
                    data: ArrayCantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 90, 10, 0.2)',
                        'rgba(13, 255, 192, 0.2)',
                        'rgba(97, 102, 54, 0.2)',
                        'rgba(178, 34, 34, 0.2)', 
                        'rgba(255, 255, 0, 0.2)', 
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(240, 230, 140, 0.2)',
                        'rgba(205, 92, 92, 0.2)',
                        'rgba(107, 142, 35, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(152, 251, 152, 0.2)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 90, 10, 1)',
                        'rgba(13, 255, 192, 1)',
                        'rgba(97, 102, 54, 1)',
                        'rgba(178, 34, 34, 1)', 
                        'rgba(255, 255, 0, 1)', 
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(240, 230, 140, 1)',
                        'rgba(205, 92, 92, 1)',
                        'rgba(107, 142, 35, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(152, 251, 152, 1)'
                    ],
                    borderWidth: 2
                    
                }]
        },
          /*  options:{
                responsive: true,
                width: 800,
                legend: {
                    onClick: function(event, legendItem) {
                        alert('entro');
                    }
                    

                },
                

                  
            
            }*/
           
        });
        return myChart;
    }
 

    
    

    mostrarDatosUltimaCarga(); 

    function mostrarDatosUltimaCarga(){       
        $.ajax({
            type:"POST",
            //data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetDatosUltimaCarga.php",
            success:function(data){    
                  
               data = JSON.parse(data);
               data = data[0];
               //alert(data.fecha);  
               $('#fechaCarga').val(data.fecha);   
               $('#cantidadRut').val(data.Cant_Ruts); 
               //alert(formatDollar(data.Deuda_Total));
               $('#totalDeuda').val(data.Deuda_Total);

            },
            error:function(){
                alert('error');
            }
        });       
    }

    $('body').on( 'click', '#verDeudas', function () {       
            listarDeudasCarga();
    });

    listarDeudasCarga();    


    function listarDeudasCarga(){ 
        $.ajax({
            type: "POST",
            url: "../includes/reporteria/GetListarDeudasCarga.php",
            //data: data,
            //dataType: "json",
            success: function(data){
                data = JSON.parse(data);
                var TablaDeudas = $('#listaDeudas').DataTable({
                    data: data, // este es mi json
                    paging: true,
                    //scrollX: false,
                    columns: [
                        { data : 'Rut' }, // campos que trae el json
                        { data : 'Nombre' },
                        { data : 'Monto' },                        
                        { data : 'FechaVencimiento' },
                        { data : 'numeroFactura' }

                        //{ data: 'Actions' }
                    ]
                    
                   /*  "columnDefs": [
                      
                        {
                            "targets": 3,
                            "data": 'Actions', 
                            "render": function( data, type, row ) {
                                return "<div style='text-align: center;' id='"+data+"'><i style='cursor: pointer; margin: 0 10px;' class='btn eliminar fa fa-trash btn-danger btn-icon icon-lg'></i><i style='cursor: pointer; margin: 0 10px;' class='fa fa-pencil-square-o btn btn-primary btn-icon icon-lg modificar'></div>";
                            }
                        }
                    ] */
                }); 
            },
            error: function(){
                alert('erroryujuuu');
            }
        });
    }

    function formatDollar(num){
    var p = num.toFixed(2).split(".");
    return "$" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? "." : "") + acc;
    }, "");
}

mostrarDeudaPorMeses();

function mostrarDeudaPorMeses(){  
  
        $.ajax({
            type:"POST",
            //data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetDeudasMes.php",
            success:function(data){

               data = JSON.parse(data);

               //console.log(data);
               //alert(data);
               var ArrayFecha = [];
               var ArrayMonto = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayFecha.push(Value.fecha);
                ArrayMonto.push(Value.monto);
               }
               //console.log(ArrayNombres);
               //console.log(ArrayId);
               var myChartContacto = grafico(ArrayFecha,ArrayMonto,'',"myChart","pie");
            },
            error:function(){
                alert('error');
            }
        });       
    }


});