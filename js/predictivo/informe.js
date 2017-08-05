$(document).ready(function(){
    setInterval(function(){
        funcionMostrarInforme();
    },4000)
    function funcionMostrarInforme()
    {
        $.ajax(
        {            
            type: "POST",
            url: "../includes/crm/mostrarInforme.php",
            //data:datos,
            success: function(response)
            {
                //console.log(response);
                $('#mostrarInforme').html(response);
            },
            error: function(response){     
                //alert(response);
            }
        });
    }
});