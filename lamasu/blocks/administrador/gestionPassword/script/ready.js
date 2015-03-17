//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
 $("#gestionPassword").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#gestionPassword").submit(function() {
        var resultado=$("#gestionPassword").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$("#evento").select2();
$("#periodoAcademico").select2();


$(function() {
    $( "button" )
    .button()
    .click(function( event ) {
        event.preventDefault();
    });
});


/*$(function() {
    $( document ).tooltip();
});*/

//Asociar el widget tabs a la división cuyo id es tabs
$(function() {
    $( "#tabs" ).tabs();
});


