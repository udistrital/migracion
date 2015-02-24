<script src="<?=$configuracion["host"].$configuracion["site"].$configuracion["javascript"]?>/jquery.js" type="text/javascript"></script>

<?PHP
$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
?>


<script type="text/javascript">

	function aplicarReglamento(url){
	      <? $cripto=new encriptar();?>

	      $.ajax({
		      type: 'GET',
			  dataType: 'script',
		      url: '<?=$indice?>',
		      data: "formulario="+url,
			  beforeSend: function() {
				$("#progresoCierre").html("Ejecutando Aplicar Reglamento...."); 
			  },
		      success: function(respuesta) {
			   	//eval(respuesta);
				$("#progresoCierre").html("El proceso Aplicar Reglamento ha finalizado!! Registros procesados:"+respuesta); 
				//$('#progresoCierre').html(respuesta);
		      }
	      });
		  
	}
	
	
	function cambiarEstados(url){
	      <? $cripto=new encriptar();?>

	      $.ajax({
		      type: 'GET',
			  dataType: 'script',
		      url: '<?=$indice?>',
		      data: "formulario="+url,
			  beforeSend: function() {
				$("#progresoCierre").html("Ejecutando Cambiar Estados...."); 
			  },
		      success: function(respuesta) {
			   	//eval(respuesta);
				$("#progresoCierre").html("El proceso Cambiar Estados ha finalizado!! Registros procesados:"+respuesta); 
				//$('#progresoCierre').html(respuesta);
		      }
	      });
		  
	}
	
	
</script>
