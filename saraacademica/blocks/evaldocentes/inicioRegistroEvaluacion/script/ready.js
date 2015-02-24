//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
$("#segundaClave").validationEngine({
    promptPosition : "centerRight", 
    scroll: false
});

$(function() {
    $("#segundaClave").submit(function() {
        $resultado=$("#segundaClave").validationEngine("validate");
        if ($resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$("#pregunta1").select2();
$("#pregunta2").select2();


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


