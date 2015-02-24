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

$conexion = "autoevaluadoc";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarAnioPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//echo $cadena_sql."<br>";

//echo "año".$registroPeriodo[0][0];

$valorCodificado="pagina=habilitarEvaluacion";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardar";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$esteCampo = "mensajePeriodo";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = "information";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
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
    
    
    //-------------Control Mensaje-----------------------
   /* $esteCampo = "informacion";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "";
    $atributos["tipo"] = "message";
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->cuadroMensaje($atributos);*/

    //------------------Control Lista Desplegable------------------------------
    $esteCampo = "periodoAcademico";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = 0;
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "150px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 250;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = "Período académico: ";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("consultarAnioPeriodo");
    $atributos["baseDatos"] = "autoevaluadoc";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);

    
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
    //unset($atributos);
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

    echo $this->miFormulario->formulario("fin");
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
