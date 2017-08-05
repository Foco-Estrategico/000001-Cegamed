$(document).ready(function(){
    
    var myChartVencidas;
    var myChartNoVencidas;
    var totalPorCobrar = 0;
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

    getDatosFacturasVencidas();

    function graficoVencidas(arrayDias, arrayMontos, ArrayBackgroundColor, ArrayborderColor){
        var ctx = document.getElementById("chart1");
        var data = {
            labels: arrayDias,
            datasets: [{
            label: 'Monto Vencido en $',
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

    getDatosFacturasNoVencidas();

    function graficoNoVencidas(arrayDias, arrayMontos, ArrayBackgroundColor, ArrayborderColor){
        var ctx = document.getElementById("chart2");
        var data = {
            labels: arrayDias,
            datasets: [{
            label: 'Monto No Vencido en $',
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

    

    function getDatosFacturasVencidas(){
        $.ajax({
            type:"POST",
            //data: datos,
            //dataType:"html",
            url: "../includes/reporteria/GetMontoFacturasVencidas.php",
            async: false,
            success:function(data){ 
               data = JSON.parse(data);    
               var ArrayTramos = [];
               var ArrayMontos = [];
               var ArrayBackgroundColor = [];
               var ArrayborderColor = [];
               var totalMonto = 0;
               for (var indice in data){
                    var Value = data[indice];
                    var tramo = Value.tramo;
                    ArrayTramos.push(tramo);
                    //var valor = formatNumber.new(Value.monto); 
                    ArrayMontos.push(Value.monto);      
                    ArrayBackgroundColor.push('rgba(246, 30, 20, 0.5)');
                    ArrayborderColor.push('rgba(200,200,200,1)');
                    totalMonto = parseInt(totalMonto) + parseInt(Value.monto);                  

               } 
               if (typeof myChartVencidas != "undefined"){
                   myChartVencidas.destroy();
               }
               myChartVencidas = graficoVencidas(ArrayTramos, ArrayMontos, ArrayBackgroundColor, ArrayborderColor);
               totalPorCobrar = totalPorCobrar + totalMonto;
               totalMonto = formatNumber.new(totalMonto); 
               $("#monVencido").find(".vencido").html('');
               $("#monVencido").find(".vencido").append("<h4 align='left' class='text-danger'>$"+totalMonto+"</h4>");
            },
            error:function(response){
                alert('errorgetDatosFacturasVencidas');
            }
            
        });   

          
    }

    function getDatosFacturasNoVencidas(){
        $.ajax({
            type:"POST",
            //data: datos,
            //dataType:"html",
            url: "../includes/reporteria/GetMontoFacturasNoVencidas.php",
            async: false,
            success:function(data){ 
               data = JSON.parse(data);    
               var ArrayTramos = [];
               var ArrayMontos = [];
               var ArrayBackgroundColor = [];
               var ArrayborderColor = [];
               var totalMonto = 0;
               for (var indice in data){
                    var Value = data[indice];
                    var tramo = Value.tramo;
                    ArrayTramos.push(tramo);
                    //var valor = formatNumber.new(Value.monto); 
                    ArrayMontos.push(Value.monto);      
                    ArrayBackgroundColor.push('rgba(10, 84, 206, 0.5)');
                    ArrayborderColor.push('rgba(200,200,200,1)');
                    totalMonto = parseInt(totalMonto) + parseInt(Value.monto);                  

               } 
               if (typeof myChartNoVencidas != "undefined"){ 
                   myChartNoVencidas.destroy();
               }
               myChartNoVencidas =  graficoNoVencidas(ArrayTramos, ArrayMontos, ArrayBackgroundColor, ArrayborderColor);
               totalPorCobrar = totalPorCobrar + totalMonto; 
               totalPorCobrar = formatNumber.new(totalPorCobrar);
               totalMonto = formatNumber.new(totalMonto);  
               $("#muestraTotalMonto").find(".totalMonto").html(''); 
               $("#muestraTotalMonto").find(".totalMonto").append("<h3 align='left' class='text-dark'>$"+totalPorCobrar+"</h3>");
               $("#monNoVencido").find(".noVencido").html('');
               $("#monNoVencido").find(".noVencido").append("<h4 align='left' class='text-primary'>$"+totalMonto+"</h4>");
            },
            error:function(response){
                alert('errorgetDatosFacturasVencidas');
            }
            
        });   
          
    }

    
    
})