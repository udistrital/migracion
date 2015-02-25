<?php
/**
 *
 * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

//URL base
$url=$this->miConfigurador->getVariableConfiguracion("host");
$url.=$this->miConfigurador->getVariableConfiguracion("site");
$url.="/index.php?";

//Variables
$cadenaACodificar="pagina=".$this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&action=index.php";
$cadenaACodificar.="&bloqueNombre=".$esteBloque["nombre"];
$cadenaACodificar.="&bloqueGrupo=".$esteBloque["grupo"];
$cadenaACodificar.="&sessionId=".$_REQUEST["sessionId"];

//Codificar las variables
$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");

//Cadena codificada para recibir lista
$cadenaACodificar1=$cadenaACodificar."&funcion=obtenerServicios";
$cadena1=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar1,$enlace);
//cadena codificada para recibir formulario de creacion
$cadenaACodificar2=$cadenaACodificar."&funcion=nuevoServicio";
$cadena2=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar2,$enlace);
//cadena codificada para crear elemento
$cadenaACodificar3=$cadenaACodificar."&funcion=procesarListado";
$cadena3=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar3,$enlace);
//cadena codificada para Activar Desactivar Elemento
$cadenaACodificar4=$cadenaACodificar."&funcion=desactivarServicio";
$cadena4=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar4,$enlace);
//cadena codificada para Editar Elemento
$cadenaACodificar5=$cadenaACodificar."&funcion=editarServicio";
$cadena5=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar5,$enlace);
//cadena codificada para Actualizar Elemento
$cadenaACodificar6=$cadenaACodificar."&funcion=actualizarServicio";
$cadena6=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar6,$enlace);
//cadena codificada para Consultar Elemento
$cadenaACodificar7=$cadenaACodificar."&funcion=consultarServicio";
$cadena7=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar7,$enlace);



$urlObtenerListaElementos = $url.$cadena1;
$urlObtenerNuevoElemento = $url.$cadena2;
$urlCambiarEstadoElemento = $url.$cadena4;
$urlEditarElemento=$url.$cadena5;
$urlActualizarElemento=$url.$cadena6;
$urlConsultarElemento=$url.$cadena7;
?>

<script type='text/javascript'>

	function enviarListado(){
		$("#respuesta").show();
		document.getElementById('respuesta').innerHTML="<?php echo "...enviando..."?>";
		if($("#formulario").validationEngine('validate')!=false){
			//var data = $("#formulario").serialize();
			var file = document.getElementById("archivo");
			var data = new FormData();
			
			data.append("archivo", file.files[0]);
			$.ajax({
		            url: "<?php echo $url.$cadena3;?>",
		            data: data,
		            contentType: 'multipart/form-data', 
		            processData: false,
		            contentType: false,
		            type:"post",
		            dataType: "html",
		            success: function(jresp){
		            	document.getElementById('respuesta').innerHTML=jresp;
				       }
		        });
			}
		}


</script>