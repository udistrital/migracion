//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
 $("#adminEvaluacionesExtemporaneas").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

jQuery(document).ready(function(){
			// binds form submission and fields to the validation engine
			jQuery("#adminEvaluacionesExtemporaneas").validationEngine();
		});


$("#perAcad").select2();
$("#tipoEvaluacionExt").select2();

$('#tablaCarreras').dataTable( {
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
 
 $('#tablaFormulario').dataTable( {
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
 
 $('#tablaCargaAcademica').dataTable( {
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


