$(document).ready(function() 
{
	$('#enviar').click(function()
	{
		$('body').addClass("loading");
		var fecha = $('.fecha').val();
		var cedente = $('#cedente').val();
		var data = 'fecha='+fecha+"&cedente="+cedente;
		$.ajax(
	    {
			type: "POST",
			url: "../includes/reporte/mostrarReporteOnce.php",
    		data: data,
			success: function(response)
			{
				$('#reporte11').html(response);
				$('#demo-dt-basic').DataTable();
				$('body').removeClass("loading");
			}	
		});		
	});	

	$('#seleccionarFecha').click(function()
	{
		var fechaInicio = $('.fechaInicio').val();
		var fechaTermino = $('.fechaTermino').val();
		alert(fechaInicio + fechaTermino);
	});
	new Morris.Bar({
		element: 'demo-morris-bar',
		data: [
			{ y: '1', a: 100, b: 90 },
			{ y: '2', a: 75,  b: 65 },
			{ y: '3', a: 20,  b: 15 },
			{ y: '5', a: 50,  b: 40 },
			{ y: '6', a: 75,  b: 95 },
			{ y: '7', a: 15,  b: 65 },
			{ y: '8', a: 70,  b: 100 },
			{ y: '9', a: 100, b: 70 },
			{ y: '10', a: 50, b: 70 },
			{ y: '11', a: 20, b: 10 },
			{ y: '12', a: 40, b: 90 },
			{ y: '13', a: 70, b: 30 },
			{ y: '14', a: 50, b: 50 },
			{ y: '15', a: 100, b: 90 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Series A', 'Series B'],
		gridEnabled: false,
		gridLineColor: 'transparent',
		barColors: ['#177bbb', '#afd2f0'],
		resize:true,
		hideHover: 'auto'
	});

});
