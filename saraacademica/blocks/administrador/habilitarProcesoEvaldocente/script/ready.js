//Para que funcione el dataTable(),
//$('#example').dataTable();

// Asociar el widget de validación al formulario
 $("#habilitarProcesoEvaldocente").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#habilitarProcesoEvaldocente").submit(function() {
        var resultado=$("#habilitarProcesoEvaldocente").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
});

$("#evento").select2();
$("#periodoAcademico").select2();

 $('#tablaPeriodos').dataTable( {
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
 $('#tablaEventos').dataTable( {
                "sPaginationType": "full_numbers"
 } );
$('#tablaPeriodos1').dataTable( {
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
 $('#tablaEventosCarrera').dataTable( {
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
//for(var i=0;i<100;i++)
//{
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
//}

/*$("#habilitarProcesoEvaldocente").validationEngine({
           promptPosition : "centerRight", 
           scroll: false
       });

       $(function() {
           $("#habilitarProcesoEvaldocente").submit(function() {
               $resultado=$("#habilitarProcesoEvaldocente").validationEngine("validate");
               if ($resultado) {
                   return true;
               }
               return false;
           });
       });*/
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


