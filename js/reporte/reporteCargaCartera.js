$(document).ready(function(){
    var myChartSegmento;
    $("#casosSegmento").hide();
    
    
    function grafico(ArrayTotales,ArraySegmento,ArrayId,idChart,tipoGrafico){
        //var ctx = document.getElementById("myChart");
        var ctx = document.getElementById(idChart);
        var Width = $("#"+idChart).closest("div").width();
        //alert(Width);
        myChart = new Chart(ctx, {
              type: 'bar',
               data: {
        labels: ArraySegmento,
        datasets: [{
            label: 'Total',
            data: ArrayTotales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                //'rgba(255, 206, 86, 0.2)',
                //'rgba(75, 192, 192, 0.2)',
                //'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                //'rgba(255, 206, 86, 1)',
                //'rgba(75, 192, 192, 1)',
                //'rgba(153, 102, 255, 1)',
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
        return myChart;
    }

     function mostrarDatosPorSegmento(){
        //data = {idCedente:'47'}; 
        $.ajax({
            type:"POST",
            //data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetDatosPorSegmento.php",
            success:function(data){
               data = JSON.parse(data);
               //console.log(data);
               //alert(data);
               var ArrayTotales = [];
               var ArraySegmento = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayTotales.push(Value.total);
                ArraySegmento.push(Value.segmento);
               }
               //console.log(ArrayNombres);
               myChartSegmento = grafico(ArrayTotales,ArraySegmento,'',"myChart","bar");
            },
            error:function(){
                alert('error');
            }
        });       
    }


  var TablaCasos;
    function mostrarCasosPorSegmento(segmento){
        data = {segmento:segmento}; 
        $.ajax({
            type:"POST",
            data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetCasosPorSegmento.php",
            success:function(data){
               data = JSON.parse(data);

               $("#casosSegmento").show();

                    
                    TablaCasos = $('#listaCasos').DataTable({
                    data: data, // este es mi json
                    paging: true,
                    //scrollX: false,
                    columns: [
                        { data : 'nombre' },
                        { data : 'total' },                        
                        { data: 'marca' },
                        { data: 'cantidadFactura' }
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
                TablaCasos.order([1,"desc"]).draw();
            },
            error:function(){
                alert('error');
            }
        }); 
        TablaCasos.destroy();

    }

    mostrarDatosPorSegmento();


     $("#myChart").click(function(evt){
        //var activePoints = myChartContacto.getElementsAtEvent(evt);
        var activePoints = myChartSegmento.getElementsAtEvent(evt);
        var chartData = activePoints[0]['_chart'].config.data;
        console.log(chartData);
        var idx = activePoints[0]['_index'];
        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var url = "tipo_gestion" + label + "&value=" + value;
        mostrarCasosPorSegmento(label)
        //alert(url);    
    });


     mostrarDatosUltimaCarga(); 

    function mostrarDatosUltimaCarga(){       
        $.ajax({
            type:"POST",
            //data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetDatosUltimaCarga.php",
            success:function(data){ 
                 //alert(data);                
               data = JSON.parse(data);
               data = data[0];
               //alert(data.fecha);  
               $('#fechaCarga').val(data.fecha);   
               $('#cantidadRut').val(data.Cant_Ruts); 
               //alert(data.Deuda_Total);
               //$('#totalDeuda').val(formatDollar(Number(data.Deuda_Total)));
               $('#totalDeuda').val(data.Deuda_Total);
            },
            error:function(){
                alert('error');
            }
        });       
    }


});    