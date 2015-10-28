//Para que funcione el dataTable(),
//$('#example').dataTable();
// Asociar el widget de validación al formulario
 $("#adminAdmisiones").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#adminAdmisiones").submit(function() {
        var resultado=$("#adminAdmisiones").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$("#medio").select2();
$("#evento").select2();
$("#anio").select2();
$("#periodo").select2();
$("#estadoNuevo").select2();
$("#preguntaTipo").select2();
$("#carrera").select2();
$("#tipoInscripcion").select2();
$("#tipoInscripcion1").select2();
$("#localidadColegio").select2();
$("#localidadResidencia").select2();
$("#estratoResidencia").select2();
$("#sexo").select2();
$("#serMilitar").select2();
$("#carreras").select2();
$("#tipoIcfes").select2();
$("#admision").select2();
$("#facultades").select2();

$('#tablaPeriodos').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[1, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaEventos').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[1, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaMedios').dataTable( {
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
 
 $('#tablaLocalidad').dataTable( {
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
 
 $('#tablaColillas').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 0, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 $('#tablaPines').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 0, "desc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 $('#tablaCarreras').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 0, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaTipIns').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 0, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaNoInscritos').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 0, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaTotalInscritos').dataTable( {
                "sPaginationType": "full_numbers",
                "aaSorting": [[ 1, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#tablaArchivosResultados').dataTable( {
     "sPaginationType": "full_numbers",
     "aaSorting": [[0, "asc" ]],
     "bPaginate": true,
     "bLengthChange": true,
     "bFilter": true,
     "bSort": true,
     "bInfo": true,
     "bJQueryUI": true,
     "bAutoWidth": true        
} );

$('#tablaDocumentacion').dataTable( {
     "sPaginationType": "full_numbers",
     "aaSorting": [[0, "asc" ]],
     "bPaginate": true,
     "bLengthChange": true,
     "bFilter": true,
     "bSort": true,
     "bInfo": true,
     "bJQueryUI": true,
     "bAutoWidth": true        
} );


 $('#fechaIni').datepicker({
//$('#fechaIni'+ i).datepicker({    
        dateFormat: 'mm/dd/yy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
        });
$('#fechaFin').datepicker({
        dateFormat: 'mm/dd/yy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
        });

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


