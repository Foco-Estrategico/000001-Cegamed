$(document).ready(function(){

    var myChartRangoFecha;
    var myChartMesSemana;
    var myChartPorMes;

    $("#Mostrar").click(function(){           
        reporteRangoFecha();
        reportePorMes(); 
        reporteMesSemana();           
    });
/*'rgba(100, 100, 100, 0.25)',
            'rgba(100, 100, 100, 0.25)',
            'rgba(100, 100, 100, 0.25)',*/ 
    function graficoRangoFecha(arrayDias, arrayMontos, ArrayBackgroundColor, ArrayborderColor){
        var ctx = document.getElementById("chart1");
        var data = {
            labels: arrayDias,
            datasets: [{
            label: 'Monto compromiso en $',
            data: arrayMontos,
            backgroundColor: ArrayBackgroundColor,
            borderColor: ArrayborderColor,
            borderWidth: 2
            }]
        };
        var options = {
            scales: {
            yAxes: [{
            ticks: {
            beginAtZero:true
            }
            }]
            }
        };
        
        var chart1 = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

        return chart1;        

    }

    function getDatosRangoFecha(datos){
        $.ajax({
            type:"POST",
            data: datos,
            //dataType:"html",
            url: "../includes/reporteria/GetCompromisoRangoFecha.php",
            async: false,
            success:function(data){ 
               data = JSON.parse(data);    
               var ArrayDias = [];
               var ArrayMontos = [];
               var ArrayBackgroundColor = [];
               var ArrayborderColor = [];
               var totalMonto = 0;
               for (var indice in data){
                var Value = data[indice];
                var dias = Value.dias;
                ArrayDias.push(dias);
                //var valor = formatNumber.new(Value.monto); 
                ArrayMontos.push(Value.monto);      
                ArrayBackgroundColor.push('rgba(226, 232, 5, 0.5)');
                ArrayborderColor.push('rgba(200,200,200,1)');
                totalMonto = parseInt(totalMonto) + parseInt(Value.monto);                           
                // OJOOO aqui mismo sumar los montos para obtener el total para la leyenda
               } 
               if (typeof myChartRangoFecha != "undefined"){
                   myChartRangoFecha.destroy();
               }
               myChartRangoFecha = graficoRangoFecha(ArrayDias, ArrayMontos, ArrayBackgroundColor, ArrayborderColor);
               //grafico(ArrayNombreTipoGestion,ArrayMontoGestion,ArrayPorcentajes,"myChart5","pie");
               // mostrarLeyenda(myChartSegmento,"leyendaMontos",1);
               totalMonto = formatNumber.new(totalMonto);  
               var color = "#8ed9f3";
               var rango = "Desde: "+datos['inicio']+" Hasta: "+datos['fin'];
               $("#leyendaRangoFecha").find(".list-group").html('');
               $("#leyendaRangoFecha").find(".list-group").append("<a class='list-group-item' style='background-color: "+color+"' href='#'>"+rango+"<br><p style='background-color: #FFFF66; display: inline-block; padding: 2px 5px'> $ "+totalMonto+"</p></a>");
            },
            error:function(){
                alert('errormostrarDatosRangoFecha');
            }
            
        });   

          
    }

    function graficoMesSemana(ArraySemanas, ArrayMontos, ArrayBackgroundColor, ArrayborderColor){
        var ctx = document.getElementById("chart3");
        var data = {
            labels: ArraySemanas,
            datasets: [{
            label: 'Monto compromiso en $',
            data: ArrayMontos,
            backgroundColor: ArrayBackgroundColor,
            borderColor: ArrayborderColor,
            borderWidth: 2
            }]
        };
        var options = {
            scales: {
            yAxes: [{
            ticks: {
            beginAtZero:true
            }
            }]
            }
        };
        
        var chart3 = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        }); 

        return chart3;

    }

    function getDatosPorMesSemana(datos){
        $.ajax({
            type:"POST",
            data: datos,
            //dataType:"html",
            url: "../includes/reporteria/GetCompromisoSemanaMes.php",
            async: false,
            success:function(data){ 
               data = JSON.parse(data);    
               var ArraySemanas = [];
               var ArrayMontos = [];
               var ArrayBackgroundColor = [];
               var ArrayborderColor = [];
               var totalMonto = 0;
               for (var indice in data){
                    var Value = data[indice];
                    var semanas = Value.semanas;
                    ArraySemanas.push(semanas);
                    //var valor = formatNumber.new(Value.monto); 
                    ArrayMontos.push(Value.monto);      
                    ArrayBackgroundColor.push('rgba(66, 73, 73, 0.5)');
                    ArrayborderColor.push('rgba(200,200,200,1)');
                    totalMonto = parseInt(totalMonto) + parseInt(Value.monto);                  

               } 
               if (typeof myChartMesSemana != "undefined"){
                   myChartMesSemana.destroy();
               }
               myChartMesSemana = graficoMesSemana(ArraySemanas, ArrayMontos, ArrayBackgroundColor, ArrayborderColor);
               //grafico(ArrayNombreTipoGestion,ArrayMontoGestion,ArrayPorcentajes,"myChart5","pie");
               // mostrarLeyenda(myChartSegmento,"leyendaMontos",1);
               totalMonto = formatNumber.new(totalMonto);  
               var color = "#8ed9f3";
               var rango = "Mes: "+datos['nombreMes'];
               $("#leyendaMesSemana").find(".list-group").html('');
               $("#leyendaMesSemana").find(".list-group").append("<a class='list-group-item' style='background-color: "+color+"' href='#'>"+rango+"<br><p style='background-color: #FFFF66; display: inline-block; padding: 2px 5px'> $ "+totalMonto+"</p></a>");
            },
            error:function(response){
                alert('errormostrarPorMesSemana');
            }
            
        });   

          
    }


    function graficoPorMes(arrayDias, arrayMontos, ArrayBackgroundColor, ArrayborderColor){
        var ctx = document.getElementById("chart2");
        var data = {
            labels: arrayDias,
            datasets: [{
            label: 'Monto compromiso en $',
            data: arrayMontos,
            backgroundColor: ArrayBackgroundColor,
            borderColor: ArrayborderColor,
            borderWidth: 2
            }]
        };
        var options = {
            scales: {
            yAxes: [{
            ticks: {
            beginAtZero:true
            }
            }]
            }
        };
        
        var chart2 = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });   

        return chart2;     

    }

    function getDatosPorMes(datos){
        $.ajax({
            type:"POST",
            data: datos,
            //dataType:"html",
            url: "../includes/reporteria/GetCompromioPorMes.php",
            async: false,
            success:function(data){ 
               data = JSON.parse(data);    
               var ArrayDias = [];
               var ArrayMontos = [];
               var ArrayBackgroundColor = [];
               var ArrayborderColor = [];
               var totalMonto = 0;
               for (var indice in data){
                    var Value = data[indice];
                    var dias = Value.dias;
                    ArrayDias.push(dias);
                    //var valor = formatNumber.new(Value.monto); 
                    ArrayMontos.push(Value.monto);      
                    ArrayBackgroundColor.push('rgba(255, 159, 64, 0.5)');
                    ArrayborderColor.push('rgba(200,200,200,1)');
                    totalMonto = parseInt(totalMonto) + parseInt(Value.monto);                  

               } 
               if (typeof myChartPorMes != "undefined"){
                   myChartPorMes.destroy();
               }
               myChartPorMes = graficoPorMes(ArrayDias, ArrayMontos, ArrayBackgroundColor, ArrayborderColor);
               //grafico(ArrayNombreTipoGestion,ArrayMontoGestion,ArrayPorcentajes,"myChart5","pie");
               // mostrarLeyenda(myChartSegmento,"leyendaMontos",1);
               totalMonto = formatNumber.new(totalMonto);  
               var color = "#8ed9f3";
               var rango = "Mes: "+datos['nombreMes'];
               $("#leyendaPorMes").find(".list-group").html('');
               $("#leyendaPorMes").find(".list-group").append("<a class='list-group-item' style='background-color: "+color+"' href='#'>"+rango+"<br><p style='background-color: #FFFF66; display: inline-block; padding: 2px 5px'> $ "+totalMonto+"</p></a>");
            },
            error:function(response){
                alert('errormostrargetDatosPorMes');
            }
            
        });   

          
    }


    function reporteRangoFecha(){
        var startDate = $("#date-range .input-daterange input[name='start']").val();
        var endDate = $("#date-range .input-daterange input[name='end']").val();
        if((startDate != "") && (endDate != "")){
            $("#comRangoFecha").show();
            // mando a traer el reporte por fechas
            var datos = {inicio:startDate, fin:endDate};
            getDatosRangoFecha(datos);
        }else{
            $("#comRangoFecha").hide();
        }   
    }

    function reportePorMes(){
         var mes  = $("#mes").val();
         var nombreMes = $("#mes option:selected").html(); 
         if (mes != 0){
            //mando a traer el reporte
            $("#comMes").show();
            var datos = {mes:mes, nombreMes:nombreMes}; 
            getDatosPorMes(datos);                      
            
         }else{
            $("#comMes").hide();
         }
    }

    function reporteMesSemana(){
        var semana  = $("#semana").val();
        var nombreMes = $("#mes option:selected").html(); 
        if (semana == 0){
            $("#comMesSemana").hide();
        }else{
            var mes  = $("#mes").val();
            if (mes == 0){
                alert('Debe seleccionar el mes para poder generar el grafico por semana');
            }else{
                //mando a traer el reporte
                $("#comMesSemana").show();
                var datos = {mes:mes, nombreMes:nombreMes}; 
                getDatosPorMesSemana(datos);                         
                
            }
        }


    }

    var formatNumber = {
        separador: ".", // separador para los miles
        sepDecimal: ',', // separador para los decimales
        formatear:function (num){
        num +='';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
        }
        return this.simbol + splitLeft +splitRight;
        },
        new:function(num, simbol){
        this.simbol = simbol ||'';
        return this.formatear(num);
    }
}


});   