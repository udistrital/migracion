//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
 $("#gestionAdministrativos").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#gestionAdministrativos").submit(function() {
        var resultado=$("#gestionAdministrativos").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$("#evento").select2();
$("#periodoAcademico").select2();

$('#tablaCertificados').dataTable( {
               "sPaginationType": "full_numbers",
                "aaSorting": [[ 1, "desc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true    
 } );

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


