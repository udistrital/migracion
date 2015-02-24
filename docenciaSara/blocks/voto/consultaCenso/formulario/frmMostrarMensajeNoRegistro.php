<?php

$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");
echo "<img alt='' src='".$directorio."formulario/superior.jpg' >";


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$datos = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['datos']);
$datos = unserialize(urldecode($datos));
$datos = $datos[0];

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$valorCodificado = "pagina=index";
$valorCodificado.="&action=" . $esteBloque["nombre"];
$valorCodificado.="&opcion=actualizarDatos";
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado.="&idRegistro=" . $datos['censo_id_registro'];
$valorCodificado.="&votacionMensaje=" . $datos['votacion_mensaje'];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

//-------------------------------Mensaje-------------------------------------
$esteCampo = "mensaje3";
$atributos["id"] = $esteCampo;
$atributos["obligatorio"] = false;
$atributos["estilo"] = "jqueryui";
$atributos["etiqueta"] = "simple";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoMensaje($atributos);

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);


//-------------Control texto-----------------------
$esteCampo = "informacionNoRegistro";
$atributos["tamanno"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["etiqueta"] = "";
$atributos["texto"] = $this->lenguaje->getCadena($esteCampo);
$atributos["columnas"] = ""; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);

//------------------Division para los botones-------------------------
$atributos["id"] = "botones";
$atributos["estilo"] = "marcoBotones";
echo $this->miFormulario->division("inicio", $atributos);

//-------------Control Boton-----------------------
$esteCampo="botonCancelar";
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["id"]=$esteCampo;
$atributos["cancelar"]="true";
$atributos["tabIndex"]=$tab++;
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"] = "formSaraData"; //No cambiar este nombre
$atributos["tipo"] = "hidden";
$atributos["obligatorio"] = false;
$atributos["etiqueta"] = "";
$atributos["valor"] = $valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//Fin del Formulario
echo $this->miFormulario->formulario("fin");

//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");
?>
