//Para que funcione el dataTable(),
//$('#example').dataTable();
// Asociar el widget de validación al formulario

var $formValidar = $("#inscripcionAdmisiones");
        // Asociar el widget de validación al formulario
        
 $("#inscripcionAdmisiones").validationEngine({
            promptPosition : "centerRight", 
            scroll: false
        });

$(function() {
    $("#inscripcionAdmisiones").submit(function() {
        var resultado=$("#inscripcionAdmisiones").validationEngine("validate");
        if (resultado) {
            // console.log(filasGrilla);
            return true;
        }
        return false;
    });
    
    /*$formValidar.formToWizard({
                submitButton: 'botonContinuarA',
                showProgress: true, 
                nextBtnName: 'Siguiente >>',
                prevBtnName: '<< Anterior',
                showStepNo: true,                
                validateBeforeNext: function() {
                                     
                        return $formValidar.validationEngine( 'validate' );
                }
            });*/
});



$("#evento").select2();
$("#anio").select2();
$("#periodo").select2();
$("#estadoNuevo").select2();
$("#carreras").select2();
$("#medio").select2();
$("#prestentaPor").select2();
$("#tipoInscripcion").select2();
$("#pais").select2();
$("#departamento").select2();
$("#municipio").select2({
        placeholder: "Select a Color",
        width: "200px"
    });
$("#sexo").select2();
$("#estadoCivil").select2();
$("#localidadResidencia").select2();
$("#estratoResidencia").select2();
$("#estratoCosteara").select2();
$("#tipDocActual").select2();
$("#tipDocIcfes").select2();
$("#tipoSangre").select2();
$("#rh").select2();
$("#tipoIcfes").select2();
$("#localidadColegio").select2();
$("#tipoColegio").select2();
$("#valido").select2();
$("#numSemestres").select2();
$("#discapacidad").select2();
$("#cancelo").select2();
$("#carreraCursando").select2();
$("#carreraInscribe").select2();

$('#registroIcfes2').change(function() {
 
    //Se obtiene el valor del campo
    $campo = $('#registroIcfes2').val();
    $otrocampo=$('#carrera').val();
    $fecha= new Date();
    $vigencia=$fecha.getFullYear()-4;

    $res = $campo.substring(2,6);
    
    //Se compara el año actual con el año de vigencia
    if($res < $vigencia){
        alert ("Ingrese un SNP válido. Recuerde que debe ser mínimo del " + $vigencia);
        return false;
    }
    else{
        return true;
    }
});

$('#registroIcfes1').change(function() {
 
    //Se obtiene el valor del campo
    $campo = $('#registroIcfes1').val(); //>= 2000 en adelante
    $otrocampo=$('#carrera').val();
    $fecha= new Date();
    $vigencia=$fecha.getFullYear()-15;

    $res = $campo.substring(2,6);
    
    //Se compara el año actual con el año de vigencia
    if($res < $vigencia){
        alert ("Ingrese un SNP válido. Recuerde que debe ser mínimo del " + $vigencia);
        return false;
    }
    else{
        return true;
    }
});

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
                "aaSorting": [[0, "asc" ]],
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
                "aaSorting": [[ 1, "asc" ]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bJQueryUI": true,
                "bAutoWidth": true        
 } );
 
 $('#fechaNac').datepicker({
//$('#fechaIni'+ i).datepicker({    
        dateFormat: 'dd/mm/yy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
        changeMonth: true, changeYear: true, yearRange: '-100:+0',
        minDate: "-100Y",  maxDate:"-0D -0M -12Y"
        });
        
$(document).ready(function()  {
    var caracteres = 100;
    $("#counter").html("le quedan <strong>"+  caracteres+"</strong> caracteres.");
    $("#observaciones").keyup(function(){
        if($(this).val().length > caracteres){
        $(this).val($(this).val().substr(0, caracteres));
        }
    var quedan = caracteres -  $(this).val().length;
    $("#counter").html("le quedan <strong>"+  quedan+"<strong> caracteres.");
    if(quedan <= 10)
    {
        $("#counter").css("color","red");
    }
    else
    {
        $("#counter").css("color","black");
    }
    });
}); 

$(document).ready(function()  {
    var caracteres = 100;
    $("#counter").html("le quedan <strong>"+  caracteres+"</strong> caracteres.");
    $("#motivo").keyup(function(){
        if($(this).val().length > caracteres){
        $(this).val($(this).val().substr(0, caracteres));
        }
    var quedan = caracteres -  $(this).val().length;
    $("#counter").html("le quedan <strong>"+  quedan+"<strong> caracteres.");
    if(quedan <= 10)
    {
        $("#counter").css("color","red");
    }
    else
    {
        $("#counter").css("color","black");
    }
    });
}); 

$(document).ready(function()  {
    var caracteres = 100;
    $("#counterMotivo").html("le quedan <strong>"+  caracteres+"</strong> caracteres.");
    $("#motivoTransferencia").keyup(function(){
        if($(this).val().length > caracteres){
        $(this).val($(this).val().substr(0, caracteres));
        }
    var quedan = caracteres -  $(this).val().length;
    $("#counterMotivo").html("le quedan <strong>"+  quedan+"<strong> caracteres.");
    if(quedan <= 10)
    {
        $("#counterMotivo").css("color","red");
    }
    else
    {
        $("#counterMotivo").css("color","black");
    }
    });
}); 
/*$("div#print_button").click(function(){
    $("div.PrintArea").printArea();
})*/

/*$("#PrintButton").live("click", function () {
         var divContents = $("#printthisdiv").html();
         var printWindow = window.open('', '', 'height=600,width=800');
         printWindow.document.write('<html><head><title>Comprobante de inscripción</title>');
 	 printWindow.document.write('<link href=\"general.css\" rel=\"stylesheet\" type=\"text/css\" />');
         printWindow.document.write('</head><body >');
         printWindow.document.write(divContents);
         printWindow.document.close();
         printWindow.print();
     });*/

//agregar y quitar elementos
$(document).ready(function () {

    $('.add_field').click(function () {

        var input = $('#subirArchivo');
        var clone = input.clone(true);
        clone.removeAttr('id');
        clone.val('');
        clone.appendTo('.subir_archivo');

    });

    $('.remove_field').click(function () {

        if ($('.subir_archivo input:last-child').attr('id') != 'subirArchivo') {
        $('.subir_archivo input:last-child').remove();
        }

    });

});

$(document).ready(function(){
   $(document).bind("contextmenu",function(e){
       return false;
   });
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


