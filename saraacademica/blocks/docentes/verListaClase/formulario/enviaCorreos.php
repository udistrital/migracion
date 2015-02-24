<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/docentes/verListaClase";
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

$conexion = "docente";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$cadena_sql = $this->sql->cadena_sql("consultarAnioPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['anio']=$registroPeriodo[0][0];
$variable['per']=$registroPeriodo[0][1];
$variable['estado']=$registroPeriodo[0][2];

$valorCodificado="pagina=listaClase";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=enviarCorreos";
$variablaVacia=0;
if(isset($_REQUEST['mail']))
{
    $valorCodificado.="&docente=".$_REQUEST['docente'];
    $valorCodificado.="&mailDocente=".$_REQUEST['mailDocente'];
    $correos=$_REQUEST['mail'];
}
else
{
    $variable['asignatura']=$_REQUEST['asignatura'];
    $variable['grupo']=$_REQUEST['grupo'];
    $variable['usuario']=$_REQUEST['usuario'];
    
    $cadena_sql = $this->sql->cadena_sql("consultaListaClase", $variable);
    $registroLista = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");    
    
    $variableVacia='';
    for($j=0; $j<=count($registroLista)-1; $j++)
    {
        $valorCodificado.="&docente=".$registroLista[$j][2];
        $valorCodificado.="&mailDocente=".$registroLista[$j][3];
        
        if(!is_array($registroLista))
        {
            $variableVacia=$variableVacia.",". $registroLista[$j][1];
            $correos=$variableVacia;
        }
        else
        {
            $variableVacia=$variableVacia.",". $registroLista[$j][1];
            $correos=$variableVacia;
        }
    }
}


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

$atributos["id"] = "marcoAgrupacionPreguntas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Envio de correo electrónico";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
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
$tipo = 'message';
$mensaje = "Formulario para envío de correo a estudiantes.";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);
        
//-------------Control cuadroTexto-----------------------
$esteCampo="para";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Digite el correo electrónico";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 225;
$atributos["tamanno"]="50";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
$atributos["valor"]=$correos;
//$atributos["validar"]="required, min[6]";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

$esteCampo="asunto";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Digite el correo electrónico";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["anchoEtiqueta"] = 225;
$atributos["tamanno"]="50";
$atributos["tipo"]="text";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
//$atributos["validar"]="required, min[6]";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control Textarea-----------------------
$esteCampo="contenido";
$atributos["id"]=$esteCampo;
//$atributos["name"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]="Descripción";
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"] = true;
$atributos["columnas"]=110;
$atributos["filas"]=4;
$atributos["estiloArea"]="areaTexto";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
echo $this->miFormulario->campoTextArea($atributos);
unset($atributos);


$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);   

$esteCampo = "botonEnviar";
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
/*$esteCampo="botonCancelar";
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
unset($atributos);*/
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
echo $this->miFormulario->formulario("fin");
echo $this->miFormulario->marcoAGrupacion("fin");
echo $this->miFormulario->division("fin");


    