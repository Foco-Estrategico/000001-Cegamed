$(document).ready(function(){
    
    var myChart;
    var myChartContacto;
    var myChartNivel1;
    var myChartNivel2;
    var myChartNivel3;


    function torta(ArrayNombres,ArrayCantidades,ArrayId,idChart){
        //var ctx = document.getElementById("myChart");
        var ctx = document.getElementById(idChart);
        var Width = $("#"+idChart).closest("div").width();
        //alert(Width);
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                //labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                labels: ArrayNombres,
                id : ArrayId,
                datasets: [{
                    //label: '# of Votes',
                    //data: [12, 19, 3, 5, 2, 3],
                    data: ArrayCantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    hoverBackgroundColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2
                    
                }]
            },
            options:{
                responsive: true,
                width: 800,
                legend: {
                    onClick: function(event, legendItem) {
                        alert('entro');
                    }
                    

                },
                

               /* series: {
                    events: {
                        legendItemClick: function(event) {
                            alert('entrooo');
                            var visibility = this.visible ? 'visible' : 'hidden';
                            if (!confirm('The series is currently '+ 
                                 visibility +'. Do you want to change that?')) {
                                return false;
                            }
                        }
                    }
                }  */      
            
            }
            /*options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }*/
        });
        return myChart;
    }

    $("#myChart").click(function(evt){
        //var activePoints = myChartContacto.getElementsAtEvent(evt);
        var activePoints = myChartContacto.getElementsAtEvent(evt);
        var chartData = activePoints[0]['_chart'].config.data;
        console.log(chartData);
        var idx = activePoints[0]['_index'];
        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var url = "tipo_gestion" + label + "&value=" + value;
        mostrarTipoContactoNivel1(chartData.id[idx]);
        //alert(url);    
    });

    $("#myChart1").click(function(evt){
        var activePoints = myChartNivel1.getElementsAtEvent(evt);
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];
        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var url = "tipo_gestion" + label + "&value=" + value;
        //lert(chartData.id[idx]);
        mostrarTipoContactoNivel2(chartData.id[idx]);
        //alert(url);    
    });

    $("#myChart2").click(function(evt){
        var activePoints = myChartNivel2.getElementsAtEvent(evt);
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];
        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var url = "tipo_gestion" + label + "&value=" + value;
        mostrarTipoContactoNivel3(chartData.id[idx]);
        //alert(url);    
    });

    $("#myChart3").click(function(evt){
        var activePoints = myChartNivel3.getElementsAtEvent(evt);
        var chartData = activePoints[0]['_chart'].config.data;
        var idx = activePoints[0]['_index'];
        var label = chartData.labels[idx];
        var value = chartData.datasets[0].data[idx];
        var url = "tipo_gestion" + label + "&value=" + value;
        //mostrarTipoContactoNivel3(chartData.id[idx]);
        //alert(url);    
    });

    mostrarTipoContacto(); 

    function mostrarTipoContacto(){
        alert('entro');
        data = {idCedente:'45',fechaInicio:'2017-01-25',fechaFin:'2017-05-01',segmento:'A'}; 
        $.ajax({
            type:"POST",
            data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetCantidadTipoContacto.php",
            success:function(data){
               alert(data); 
               data = JSON.parse(data);
               //console.log(data);
               //alert(data);
               var ArrayNombres = [];
               var ArrayCantidades = [];
               var ArrayId = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayNombres.push(Value.nombre);
                ArrayCantidades.push(Value.cantidad);
                ArrayId.push(Value.idTipoContacto);
               }
               //console.log(ArrayNombres);
               console.log(ArrayId);
               myChartContacto = torta(ArrayNombres,ArrayCantidades,ArrayId,"myChart");
            },
            error:function(){
                alert('error');
            }
        });       
    }
    
    function mostrarTipoContactoNivel1(idTipoGestion){
        data = {idCedente:'45',fechaInicio:'2017-01-25',fechaFin:'2017-05-01',idTipoGestion:idTipoGestion}; 
        $.ajax({
            type:"POST",
            data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetCantidadGestionNivel1.php",
            success:function(data){
               data = JSON.parse(data);
               //console.log(data);
               //alert(data);
               var ArrayNombres = [];
               var ArrayCantidades = [];
               var ArrayId = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayNombres.push(Value.nombre);
                ArrayCantidades.push(Value.cantidad); 
                ArrayId.push(Value.idNivel1);
               }
               //console.log(ArrayNombres);
               //console.log(ArrayCantidades);
               myChartNivel1 = torta(ArrayNombres,ArrayCantidades,ArrayId,"myChart1");
            },
            error:function(){
                alert('error');
            }
        });       
    }

    function mostrarTipoContactoNivel2(idNivel1){
        data = {idCedente:'45',fechaInicio:'2017-01-25',fechaFin:'2017-05-01',idTipoGestion:'1',idNivel1:idNivel1}; 
        $.ajax({
            type:"POST",
            data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetCantidadGestionNivel2.php",
            success:function(data){
               data = JSON.parse(data);
               //console.log(data);
               //alert(data);
               var ArrayNombres = [];
               var ArrayCantidades = [];
               var ArrayId = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayNombres.push(Value.nombre);
                ArrayCantidades.push(Value.cantidad);
                ArrayId.push(Value.idNivel2);
               }
               //console.log(ArrayNombres);
               //console.log(ArrayCantidades);
               myChartNivel2 = torta(ArrayNombres,ArrayCantidades,ArrayId,"myChart2");
            },
            error:function(){
                alert('error');
            }
        });       
    }

    function mostrarTipoContactoNivel3(idNivel2){
        data = {idCedente:'45',fechaInicio:'2017-01-25',fechaFin:'2017-05-01',idTipoGestion:'1',idNivel2:idNivel2}; 
        $.ajax({
            type:"POST",
            data: data,
            //dataType:"html",
            url: "../includes/reporteria/GetCantidadGestionNivel3.php",
            success:function(data){
               data = JSON.parse(data);
               //console.log(data);
               //alert(data);
               var ArrayNombres = [];
               var ArrayCantidades = [];
               var ArrayId = [];
               for (var indice in data){
                var Value = data[indice];
                ArrayNombres.push(Value.nombre);
                ArrayCantidades.push(Value.cantidad);
                ArrayId.push(Value.idNivel3);
               }
               //console.log(ArrayNombres);
               //console.log(ArrayCantidades);
               console.log(myChartNivel3);
               myChartNivel3 = torta(ArrayNombres,ArrayCantidades,ArrayId,"myChart3");
               console.log(myChartNivel3);
            },
            error:function(){
                alert('error');
            }
        });       
    }

});