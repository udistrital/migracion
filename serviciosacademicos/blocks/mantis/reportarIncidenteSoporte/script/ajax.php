<?php
/**
 *
 * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

//configurar path de incluir
$new_include_path = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" )
					."/blocks/".$esteBloque["grupo"]."/".$esteBloque["nombre"]."/";
set_include_path($new_include_path);

//Lenguaje

$nombreClaseLenguaje = "Lenguaje" . $esteBloque ["nombre"];
$lenguaje = new $nombreClaseLenguaje ($new_include_path);

//URL base
$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );


$url=$this->miConfigurador->getVariableConfiguracion("host");
$url.=$this->miConfigurador->getVariableConfiguracion("site");
$url.="/index.php?";

//Variables
$cadenaACodificar="pagina=".$this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&action=index.php";
$cadenaACodificar.="&bloqueNombre=".$esteBloque["nombre"];
$cadenaACodificar.="&bloqueGrupo=".$esteBloque["grupo"];
$cadenaACodificar.="&usuario=".$_REQUEST["usuario"];
$cadenaACodificar.="&modulo=".$_REQUEST["modulo"];
$cadenaACodificar.="&sessionId=".$_REQUEST["sessionId"];

//Codificar las variables
$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");

//Cadena codificada para recibir lista
$cadenaACodificar1=$cadenaACodificar."&funcion=obtenerDeudasInterfaz";
$cadena1=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar1,$enlace);
//cadena codificada para recibir formulario de creacion
$cadenaACodificar2=$cadenaACodificar."&funcion=nuevoDeuda";
$cadena2=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar2,$enlace);
//cadena codificada para crear elemento
$cadenaACodificar3=$cadenaACodificar."&funcion=editarDeudas&metodo=crear";
$cadena3=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar3,$enlace);
//cadena codificada para Activar Desactivar Elemento
$cadenaACodificar4=$cadenaACodificar."&funcion=desactivarDeuda";
$cadena4=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar4,$enlace);
//cadena codificada para Editar Elemento
$cadenaACodificar5=$cadenaACodificar."&funcion=editarDeuda";
$cadena5=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar5,$enlace);
//cadena codificada para Actualizar Elemento
$cadenaACodificar6=$cadenaACodificar."&funcion=actualizarDeuda";
$cadena6=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar6,$enlace);
//cadena codificada para Editar los Elementos
$cadenaACodificar7=$cadenaACodificar."&funcion=editarDeudas&metodo=nuevo";
$cadena7=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar7,$enlace);
//cadena codificada para consultar Usuario operacion metodo Operacion
$cadenaACodificar8=$cadenaACodificar."&funcion=editarDeudas&metodo=operacion";
$cadena8=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar8,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz
$cadenaACodificar9=$cadenaACodificar."&funcion=editarDeudas&metodo=interfaz&divRespuesta=resultadoUsuario";
$cadena9=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar9,$enlace);
//cadena codificada para consultar Deudas del usuario
$cadenaACodificar10=$cadenaACodificar."&funcion=editarDeudas&metodo=nuevo";
$cadena10=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar10,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar11=$cadenaACodificar."&funcion=editarDeudas&metodo=interfaz&soloConsulta=true";
$cadena11=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar11,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar12=$cadenaACodificar."&funcion=registrarIncidente&estado=cerrado";
$cadena12=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar12,$enlace);
//cadena codificada para consultar Usuario interfaz metodo interfaz tab 2
$cadenaACodificar13=$cadenaACodificar."&funcion=registrarIncidente&estado=abierto";
$cadena13=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar13,$enlace);


$urlObtenerListaElementos = $url.$cadena1;
$urlObtenerNuevoElemento = $url.$cadena2;
$urlCambiarEstadoElemento = $url.$cadena4;
$urlEditarElemento=$url.$cadena5;
$urlActualizarElemento=$url.$cadena6;
$urlConsultarElemento=$url.$cadena7;
$urlConsultarDeudasUsuario=$url.$cadena10;
?>

<script type='text/javascript'>

	
	function consultarUsuario(){
		if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena8?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var resultado = document.getElementById("resultadoUsuario");
	            	resultado.innerHTML = jresp;;
			       }
	        });
		 
		}
	}

	function enviarRequerimientoCerrado(){
		if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena12?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	$('#formulario').trigger("reset");
	            	var resultado = document.getElementById("resultado");
	            	resultado.innerHTML = jresp;
	            	
			       }
	        });
		 
		}
	}

	function enviarRequerimientoAbierto(){
		if($("#formulario").validationEngine('validate')!=false){
			var data = $("#formulario").serialize();
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena13?>&"+data,
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	$('#formulario').trigger("reset");
	            	var resultado = document.getElementById("resultado");
	            	resultado.innerHTML = jresp;
	            	
			       }
	        });
		 
		}
	}

	

	function consultarUsuarioInterfaz(){
			$.ajax({
	            url: "<?php echo $url;?>",
	            data: "<?php echo $cadena9?>",
	            type:"post",
	            dataType: "html",
	            success: function(jresp){
	            	var edicion = document.getElementById("edicion");
	            	edicion.innerHTML = jresp;
	            	$("#tabs").tabs( "option", "active", 0 );
			       }
	        });
		 
		
	}

	

</script>