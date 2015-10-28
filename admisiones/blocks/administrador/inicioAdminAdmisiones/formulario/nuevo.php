<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/inicioAdminAdmisiones/formulario/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
//$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque");
//echo "<img alt='' src='" . $directorio . "formulario/superior.jpg' >";


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
//$esteCampo = "";
//$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
//$atributos["etiqueta"] = "";
//$atributos["estilo"] = "centrar";
//$atributos["tipo"] = "information";
//
//$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
//echo $this->miFormulario->cuadroMensaje($atributos);

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);


//-------------Control Mensaje-----------------------
$esteCampo = "inicioEvaldocente";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "";
$atributos["tipo"] = "message";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);


//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
//$atributos["id"] = "formSaraData"; //No cambiar este nombre
//$atributos["tipo"] = "hidden";
//$atributos["obligatorio"] = false;
//$atributos["etiqueta"] = "";
//$atributos["valor"] = $valorCodificado;
//echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);


//Fin del Formulario
echo $this->miFormulario->formulario("fin");

//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");
?><center><img src='<?php echo $rutaBloque . "ambiente.jpeg"?>'></center>
