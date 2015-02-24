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

$conexion = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    
if($registro)
{
    for($i=0; $i<=count($registro)-1; $i++)
    {  
        if($registro[$i]['acasperiev_estado']=="A")
        {
            $variable['anio']=$registro[$i]['acasperiev_anio'];
            $variable['periodo']=$registro[$i]['acasperiev_periodo'];
        }
        else
        {
            $variable['anio']='';
            $variable['periodo']='';
        }

    }
}   

//$valorCodificado="pagina=habilitarEvaluacion";
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardarEventosCarrera";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&anio=".$variable['anio'];
$valorCodificado.="&periodo=".$variable['periodo'];
$valorCodificado.="&evento=".$_REQUEST['evento'];
$valorCodificado.="&carrera=".$_REQUEST['carrera'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "EVENTO: ".$_REQUEST['eventoNombre']." CARRERA: ".$_REQUEST['carreraNombre'];
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);
////-------------------------------Mensaje-------------------------------------
/*$esteCampo = "mensajePeriodo";
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
    
         
    //------------------Fecha Inicial------------------------------
    $esteCampo="fechaIni";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]="Fecha Inicial";
    //$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["tamanno"]="20";
    $atributos["ancho"] = 350;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["deshabilitado"] = true;
    $atributos["tipo"]="";
    $atributos["estilo"]="jqueryui";
    $atributos["anchoEtiqueta"] = 250;
    $atributos["validar"]="required";
    $atributos["categoria"]="fecha";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    $esteCampo="fechaFin";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]="Fecha Final";
    //$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["tamanno"]="20";
    $atributos["ancho"] = 350;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["deshabilitado"] = true;
    $atributos["tipo"]="";
    $atributos["estilo"]="jqueryui";
    $atributos["anchoEtiqueta"] = 250;
    $atributos["validar"]="required";
    $atributos["categoria"]="fecha";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    
    //------------------Division para los botones-------------------------
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);
   //-------------Control Boton-----------------------
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

