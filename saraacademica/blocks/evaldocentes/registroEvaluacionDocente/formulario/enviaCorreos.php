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

    echo "//Este se considera un error fatal";
    exit;
}
$cadena_sql = $this->sql->cadena_sql("consultarAnioPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
$variable['anio']=$registroPeriodo[0]['acasperiev_anio'];
$variable['per']=$registroPeriodo[0]['acasperiev_periodo'];
$variable['tipoId']=1;

$cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
$registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("estudiantesEvaluaron", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$conexion1 = "autoevaluadoc";
$esteRecursoBDORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoBDORA) {

    echo "Este se considera un error fatal";
    exit;
}


$valorCodificado="pagina=evaluacionDocente";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=enviarCorreos";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
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
$mensaje = "ENVÍO DE CORREO ELECTRÓNICO A ESTUDIANTES INCRITOS<br>
            PERIODO ACADÉMICO ".$registroPeriodo[0][1]." ";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);
        
$variablaVacia=0;
for($i=0; $i<=count($registro)-1; $i++)
{
    if(!is_array($registro))
    {
        $variablaVacia=$variablaVacia.",". $registro[$i][0];
        $variable['vacia']=$variablaVacia;
    }
    else
    {
        $variablaVacia=$variablaVacia.",". $registro[$i][0];
        $variable['vacia']=$variablaVacia;
    }
}
$variable['facultad']=$_REQUEST['facultad'];
$cadena_sql = $this->sql->cadena_sql("listaEstudiantesSinEvaluar", $variable);
$registroEstudiantesSinEvaluar = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");

if(isset($_REQUEST['email']))
{
    $correos=$_REQUEST['email'];
}
else
{
$variableVacia='evaldocente@udistrital.edu.co';
    for($j=0; $j<=count($registroEstudiantesSinEvaluar)-1; $j++)
    {
        if(!is_array($registroEstudiantesSinEvaluar))
        {
            $variableVacia=$variableVacia.",". $registroEstudiantesSinEvaluar[$j][3];
            $correos=$variableVacia;
        }
        else
        {
            $variableVacia=$variableVacia.",". $registroEstudiantesSinEvaluar[$j][3];
            $correos=$variableVacia;
        }
    }
}
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


    