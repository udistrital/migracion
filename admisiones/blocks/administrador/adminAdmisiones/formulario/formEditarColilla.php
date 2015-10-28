<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['codColilla']=$_REQUEST['codColilla'];
$cadena_sql = $this->sql->cadena_sql("consultarColillasEditar", $variable);
$registroColilla = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=administracion";
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=editarColilla";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&codColilla=".$_REQUEST['codColilla'];
$valorCodificado.="&estadoActual=".$_REQUEST['estado'];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Colilla: ".$_REQUEST['nombre'];
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);
////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "<span class='textoNegrita textoPequenno'>Colilla que va a editar: ".$_REQUEST['nombre']."<br>";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);
$tab = 1;
 
//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);
    
    $esteCampo="nombreNuevo";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Nombre de la colilla.";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 135;
    $atributos["tamanno"]="35";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required";
    $atributos["valor"]=$_REQUEST['nombre'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

    $esteCampo="carrerasNuevas";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Código de la carrera.";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 135;
    $atributos["tamanno"]="4";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required";
    $atributos["valor"]=$_REQUEST['carrera'];
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    $esteCampo = "estadoNuevo";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = 1;
    $atributos["evento"] = 0;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "125px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 135;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = "Estado: ";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = array(array('A', 'Activo'),array('I', 'Inactivo'));
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    //-------------Control cuadroTextArea-----------------------
    $esteCampo="contenidoNuevo";
    $atributos["id"]="contenidoNuevo";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=false;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["columnas"]=125;
    $atributos["filas"]=25;
    $atributos["valor"]=$registroColilla[0]['colilla_contenido'];
    $atributos["estilo"]="jqueryui";
    echo $this->miFormulario->campoTextArea($atributos);
    unset($atributos);
    
    //------------------Division para los botones-------------------------
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);
    
   //-------------Control Boton-----------------------
    $esteCampo = "botonActualizar";
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
    //$atributos["tipoSubmit"] = "jquery";
    //$atributos["onclick"]=true;
    $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    unset($atributos);
//-------------Fin Control Boton----------------------

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

//-------------Fin de Conjunto de Controles----------------------------
echo $this->miFormulario->marcoAgrupacion("fin");

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");

