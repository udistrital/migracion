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

unset($variable);
$variable['usuario']=$_REQUEST['usuario'];
$variable['respuestaId']=$_REQUEST['respuestaId'];
$variable['formatoNumeto']=$_REQUEST['formatoNumeto'];
$variable['preguntaNumeto']=$_REQUEST['preguntaNumeto'];
$variable['respuesta']=$_REQUEST['respuesta'];
$variable['estado']=$_REQUEST['estado'];
$variable['perAcad']=$_REQUEST['perAcad'];
//$variable="&tipo=".$_REQUEST['tipo']; 

$valorCodificado="pagina=evaluacionesExtemporaneas";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=editarRespuesta";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&respuestaId=".$_REQUEST['respuestaId'];
$valorCodificado.="&formatoNumeto=".$_REQUEST['formatoNumeto'];
$valorCodificado.="&preguntaNumeto=".$_REQUEST['preguntaNumeto'];
$valorCodificado.="&respuesta=".$_REQUEST['respuesta'];
$valorCodificado.="&formatoId=".$_REQUEST['formatoId'];
$valorCodificado.="&docenteNombre=".$_REQUEST['docenteNombre'];
$valorCodificado.="&nombreCarrera=".$_REQUEST['nombreCarrera'];
$valorCodificado.="&perAcad=".$_REQUEST['perAcad'];
$valorCodificado.="&documentoId=".$_REQUEST['documentoId'];
$valorCodificado.="&carrera=".$_REQUEST['carrera'];
$valorCodificado.="&asignatura=".$_REQUEST['asignatura'];
$valorCodificado.="&grupo=".$_REQUEST['grupo'];
$valorCodificado.="&tipoVinculacion=".$_REQUEST['tipoVinculacion'];
$valorCodificado.="&nombreVinculacion=".$_REQUEST['nombreVinculacion'];
$valorCodificado.="&tipoId=".$_REQUEST['tipoId'];
$valorCodificado.="&formularioId=".$_REQUEST['formularioId'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];            
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionFormatos";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Editar Evaluación Docente";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$tipo = 'information';
$mensaje = "Estimado usuario, de acuerdo con lo solicitado por parte de la Of. de Evaluación Docente, en el Oficio No. PEVD-428-2013, 2013IE48705, del 16 de diciembre de 2013,
            a continuación se le presenta un formulario para modificar la evaluación correspondiente a la pregunta No. ".$_REQUEST['preguntaNumeto']."
            del formato No. ".$_REQUEST['formatoNumeto'].", si está seguro de continuar, digite el valor de la respuesta a editar. Cualquier reclamación al respecto debe ser responsabilidad de la Oficina de Evaluación Docente.";


    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);
    
    
    //------------------Control Lista Desplegable------------------------------
    //-------------Control cuadroTexto-----------------------
    $esteCampo="respuestaNueva";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Nombre del formato";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 125;
    $atributos["tamanno"]="5";
    $atributos["maximoTamanno"]="4";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
    $atributos["validar"]="required,custom[integer],max[5],min[0]";
    $atributos["valor"]=$_REQUEST['respuesta'];
    //$atributos["validar"]="required,number";
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //------------------Control Lista Desplegable------------------------------
    //-------------Control cuadroTexto-----------------------
    /*$esteCampo = "estadoNuevo";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = $_REQUEST['estado'];
    $atributos["evento"] = 2;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "150px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 125;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = "Estado: ";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = array(array('A', 'A'),array('I', 'I'));
    $atributos["baseDatos"] = "";
    $atributos["otraOpcionEtiqueta"]= $_REQUEST['estado'];
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);*/
    
    echo "&nbsp;&nbsp;&nbsp;Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME='estadoNuevo'>
   <OPTION VALUE='A'>A</OPTION>
   <OPTION VALUE='I'>I</OPTION>
   </SELECT> ";
    
    //-------------Control Textarea-----------------------
    $esteCampo="justificacion";
    $atributos["id"]=$esteCampo;
    //$atributos["name"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Descripción";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["columnas"]=125;
    $atributos["filas"]=4;
    $atributos["estiloArea"]="areaTexto";
    $atributos["estilo"]="jqueryui";
    $atributos["obligatorio"] = true;
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