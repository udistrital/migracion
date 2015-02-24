//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
 $("#registroEvaluacionDocente").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

jQuery(document).ready(function(){
			// binds form submission and fields to the validation engine
			jQuery("#registroEvaluacionDocente").validationEngine();
                         
                        Sbi.sdk.services.setBaseUrl({
                            
                        protocol: 'http'     
                        , host: 'intelligentia.udistrital.edu.co'
                        , port: '8080'
                        , contextPath: 'SpagoBI'
                        , controllerPath: 'servlet/AdapterHTTP'  
                    });
                    execTest1 = function() {
                       alert('JMMMM');           
		    var url = Sbi.sdk.api.getDocumentUrl({
				documentLabel: 'RteConEvaDocEst'
                                , executionRole: '/spagobi/admin'
				, parameters: {id_docente: 79708124,anno:2014,semestre:1}
				, displayToolbar: false
				, displaySliders: false
				, iframe: {
					style: 'border: 0px;'
				}
                                
			});
		    document.getElementById('execiframe').src = url;
		};
                });


$("#perAcad").select2();
$("#periodo").select2();
$("#tiporesultados").select2();

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
 
 $('#tablaDocentes').dataTable( {
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
                "aaSorting": [[ 3, "asc" ]],
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
 
 $('#tablaAsignaturas').dataTable( {
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
 
  $('#tablaDocentesCoordinadores').dataTable( {
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
 $('#tablaTiposEvaluacion').dataTable( {
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
 $('#tablaDocenteSinEvaluar').dataTable( {
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
 $('#tablaEstudiantesSinEvaluar').dataTable( {
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
 $('#tablaResultados').dataTable( {
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


