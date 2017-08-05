
$(document).ready(function() {

    
    $.ajax({
        type: "POST",
        url: "../includes/bienvenida/bienvenida.php",
        
        success: function(response){ 
            var Valor = [];
            Valor = JSON.parse(response); 
            console.log(Valor);
            $('#demo-external-events .fc-event').each(function() {
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true, // maintain when user navigates (see docs on the renderEvent method)
                className : $(this).data('class')
            });


            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 99999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });


        // Initialize the calendar
        // -----------------------------------------------------------------
        $('#demo-calendar').fullCalendar({
            
            
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            lang: 'es',
            firstDay : 1,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },

            
            defaultDate: '2017-07-01',
            eventLimit: true, // allow "more" link when too many events
            events: Valor,
            eventClick: function(calEvent, jsEvent, view) {

                alert('' + calEvent.Rut);

                // change the border color just for fun
                $(this).css('border-color', 'red');

            }
        });   
        }
    });   

});
