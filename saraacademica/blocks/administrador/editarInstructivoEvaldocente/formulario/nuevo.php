<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/inicioEvaldocente/formulario/";
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

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable=$_REQUEST['tipoEvaluacion'];
$cadena_sql = $this->sql->cadena_sql("buscarInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=editarInstructivo";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardar";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&tipoEvaluacion=".$_REQUEST['tipoEvaluacion'];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "";
$atributos["tipo"] = "message";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);

//

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);


//-------------Control Mensaje-----------------------
//$esteCampo = "MMM";
//$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
//$atributos["etiqueta"] = "";
//$atributos["estilo"] = "";
//$atributos["tipo"] = "message";
//$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
//echo $this->miFormulario->cuadroMensaje($atributos);


//-------------Control cuadroTextArea-----------------------
    $esteCampo='observacion';
    $atributos["id"]="elm1";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=false;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["columnas"]=80;
    $atributos["filas"]=24;
    $atributos["valor"]=$registro[0]['instructivo_texto'];
    $atributos["estilo"]="jqueryui";
    echo $this->miFormulario->campoTextArea($atributos);
    unset($atributos);
 
 
//
//
//-------------Fin de Conjunto de Controles----------------------------
    echo $this->miFormulario->marcoAGrupacion("fin");

//------------------Division para los botones-------------------------
    $atributos["id"] = "botones";
    $atributos["estilo"] = "marcoBotones";
    echo $this->miFormulario->division("inicio", $atributos);
    

   //-------------Control Boton-----------------------
    $esteCampo = "botonGuardar";
    $atributos["id"] = "guardar";
    $atributos["tabIndex"] = $tab++;
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = "";
    $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
    $atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["nombreFormulario"] = $nombreFormulario;
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
//-------------Fin Control Boton----------------------

   /* //-------------Control Boton-----------------------
    $esteCampo = "botonCancelar";
    $atributos["verificar"]="true";
    $atributos["tipo"]="boton";
    $atributos["id"]="botonCancelar";
    $atributos["tipoSubmit"] = "";
    $atributos["tabIndex"]=$tab++;
    $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    //-------------Fin Control Boton---------------------- */
//
//------------------Fin Division para los botones-------------------------


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





