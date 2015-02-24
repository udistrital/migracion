<?php
$valor="informacionCertificado";
$cadenaFinal=$cadenaACodificar."&funcion=".$valor;
$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");
$estaUrl=$url. $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaFinal,$enlace);

?>
<script>
function mostrar(elem, request, response){
	$.ajax({
		url: "<?php echo $estaUrl?>",
		dataType: "json",
		data: {
			idUsuario:	$("#idUsuario").val()		
		},
		 success: function(data) {
			 
			// Javascript function JSON.parse to parse JSON data 
		      if(!isNaN(data[0].FECHA_RETIRO)){
		    	  $("#texto").css("display","block");		      
			      } else{
		    	  $("#texto").css("display","none");			      
			      }		 
			 $("#idBuscado").val(data[0].IDENTIFICACION);
			 $("#nameCiudad").val(data[0].LUGAR_EXPEDICION);
		}
			 
	});
};
</script>