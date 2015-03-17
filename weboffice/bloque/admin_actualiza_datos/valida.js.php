<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/lib/jquery.js" type="text/javascript"></script>
<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/lib/jquery.metadata.js" type="text/javascript"></script>
<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/formulario/jquery.validate.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php";
?>


<script type="text/javascript">

	$.validator.setDefaults({
		submitHandler: function() { 
			document.submit();
		}
	});

	$().ready(function() {
		$("#datos_basicos").validate();
		$("#info_familiar").validate();
		$("#info_academica").validate();
		$("#info_socioeco").validate();
		$("#info_adicional").validate();
	});
	
	
	function cargarDatos(salida,opcion,campo) {
		var valor=$(campo).val();
		$.ajax({
		type: 'GET',
		url: '<?=$indice?>',
		data: "formulario=<?=$this->funcion->cripto->codificar_url("no_pagina=admin_actualiza_datos",$configuracion)?>&jxajax="+opcion+"&valor="+valor,
		success: function(respuesta) {
			$(salida).html(respuesta);
		}
		});
	}
	
		
	
</script>