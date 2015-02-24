
// Asociar el widget de validación al formulario
$("#entrada").validationEngine({promptPosition : "centerRight", scroll: false});

//Asociar el widget para selección de fecha a los campos
$("#fechaSalida").datepicker({
	showOn: 'both',
	buttonImage: 'theme/basico/img/calendar.png',
	buttonImageOnly: true,
	changeYear: true,
	numberOfMonths: 2,	
});

//Asociar el widget para texto enriquecido al campo textarea

$("#observacion").jqte();

//Asociar el widget para selección a los campos tipo select puede generar problemas si no encuentra datos

$("#idTipoEntrada").menu();

$("#idOrdenadorGasto").menu();

$("#idDependenciaSupervisora").menu();

$("#idSedes").menu();



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
