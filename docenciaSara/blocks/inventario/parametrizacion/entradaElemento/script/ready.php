<?php 
//Se coloca esta condición para evitar cargar algunos scripts en el formulario de confirmación de entrada de datos.
if(!isset($_REQUEST["opcion"])||(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]!="confirmar")){

?>
	$(function() {
		$("#entradaElemento").submit(function() {
	
			var filasGrilla = $('#gridElementos').jqGrid('getRowData');
	
			var datos = JSON.stringify(filasGrilla);
	
			// Pasar la grilla a un control del formulario
	
			$("#grillaElementos").val(datos);
			
			$resultado=$("#entradaElemento").validationEngine("validate");
	
			if ($resultado) {
				// console.log(filasGrilla);
				return true;
			}
			
			return false;
		});
	});
	
	// Asociar el widget de validación al formulario
	$("#entradaElemento").validationEngine({
		promptPosition : "centerRight",
		scroll : false
	});
	$("#jqgridElementos").validationEngine({
		promptPosition : "centerRight",
		scroll : false
	});
	
	// Asociar el widget para selección de fecha a los campos
	$("#fechaFactura").datepicker({
		showOn : 'both',
		buttonImage : 'theme/basico/img/calendar.png',
		buttonImageOnly : true,
		changeYear : true,
		numberOfMonths : 2,
	});
	
	$("#fechaTipoEntrada").datepicker({
		showOn : 'both',
		buttonImage : 'theme/basico/img/calendar.png',
		buttonImageOnly : true,
		changeYear : true,
		numberOfMonths : 2,
	});
	
	// Asociar el widget para texto enriquecido al campo textarea
	
	$("#observacion").jqte();
	
	// Asociar el widget para selección a los campos tipo select puede generar
	// problemas si no encuentra datos
	
	$("#idTipoEntrada").select2();
	
	$("#idOrdenadorGasto").select2();
	
	$("#idDependenciaSupervisora").menu();
	
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
<?php 
}elseif(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="confirmar"){
?>
	$(function() {
	for(var i=0;i<=datos.length;i++){ jQuery("#gridConfirmarElementos").jqGrid('addRowData',i+1,datos[i])}
	});

<?php
}
?>
