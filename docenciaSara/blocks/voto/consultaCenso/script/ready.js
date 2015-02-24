// Asociar el widget de validación al formulario
$("#consultaCenso").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});

$(function() {
    $("#consultaCenso").submit(function() {
        $resultado=$("#consultaCenso").validationEngine("validate");
        if ($resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});


//Asociar el widget para selección de fecha a los campos
//$("#fechaSalida").datepicker({
//    showOn: 'both',
//    buttonImage: 'theme/basico/img/calendar.png',
//    buttonImageOnly: true,
//    changeYear: true,
//    numberOfMonths: 2,	
//});


$(function() {
    $( "button" )
    .button()
    .click(function( event ) {
        event.preventDefault();
    });
});


$(function() {
    $( document ).tooltip();
});

//Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $( "#tabs" ).tabs();
});
        
$(function() {
    $("button").button().click(function(event) {
        event.preventDefault();
    });
});

$(function() {
    $(document).tooltip();
});

// Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $("#tabs").tabs();
});
