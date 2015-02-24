<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/habilitarProcesoEvaldocente/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
//$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");



//echo $cadena_sql."<br>";
//var_dump($_REQUEST)."<br>";
$variable['id']=$_REQUEST['id'];

$cadena_sql = $this->sql->cadena_sql("consultarEncabezadosEditar", $variable);
$registroFormatos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=armarFormularios";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=editarEncabezados";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&id=".$_REQUEST['id'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionEncabezados";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Editar Encabezados de los Formularios de Evaluación Docente";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
/*$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = "information";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);*/
$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);
    
    //-------------Control Textarea-----------------------
    $esteCampo="encabezado1";
    $atributos["id"]=$esteCampo;
    //$atributos["name"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Encabezado";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["columnas"]=125;
    $atributos["filas"]=4;
    $atributos["estiloArea"]="areaTexto";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["valor"]=$registroFormatos[0]['enc_nombre'];
    $atributos["validar"]="required";
    echo $this->miFormulario->campoTextArea($atributos);
    unset($atributos);
    
      
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);   
    
    $esteCampo = "botonGuardar";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = ""; 
   //$atributos["estilo"]="jqueryui";
    $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
    //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos["tipoSubmit"]="jquery";
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["nombreFormulario"] = $nombreFormulario;
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
   
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
     $esteCampo="botonCancelar";
     $atributos["id"]=$esteCampo;
     $atributos["tabIndex"]=$tab++;
     $atributos["verificar"]="";
     $atributos["tipo"]="boton";
     $atributos["nombreFormulario"] = $nombreFormulario;
     $atributos["cancelar"]=true;
     $atributos["tipoSubmit"] = "jquery";
     //$atributos["onclick"]=true;
     $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
     echo $this->miFormulario->campoBoton($atributos);
     unset($atributos);

    //-------------Control cuadroTexto con campos ocultos-----------------------
    //Para pasar variables entre formularios o enviar datos para validar sesiones
    $atributos["id"] = "formSaraData"; //No cambiar este nombre
    $atributos["tipo"] = "hidden";
    $atributos["obligatorio"] = false;
    $atributos["etiqueta"] = "";
    $atributos["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    echo $this->miFormulario->division("fin");
    //Fin del Formulario
    echo $this->miFormulario->formulario("fin");
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
 



