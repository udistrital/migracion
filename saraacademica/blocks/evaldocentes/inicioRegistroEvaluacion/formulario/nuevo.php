<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioRegistroEvaluacion/formulario/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
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

$conexion1 = "autoevaluadoc";
$esteRecursoDBORA=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
                                        
if (!$esteRecursoDBORA) {

    echo "//Este se considera un error fatal";
    exit;
}

$variable['usuario']=$_REQUEST['usuario'];
//$variable['usuario']=79715783;;

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultarInstructivo", "");
$registroInstructivo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultaCarreras", $variable);
$registroCarreras = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultarCarga", $variable);
$registroCarga = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");  

$cadena_sql = $this->sql->cadena_sql("consultaAsignaturas", $variable);
$registroAsignaturas = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("consultaCoordinadores", $variable);
$registroCoordinadores = $esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=armarFormularios";
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardarFormatos";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

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
$cierto=0;
for($i=0; $i<=count($registroInstructivo)-1; $i++)
{
    $accion=$_REQUEST['tipo'];
    switch($accion)
    {
            case 4: //Evaluación Por Coordinadores
                  if($registroInstructivo[$i][1]==3)
                  { 
                    if(is_array($registroCarreras))
                    {
                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                        $variable.="&opcion=nuevo";
                        $variable.="&usuario=". $_REQUEST['usuario'];
                        $variable.="&periodoId=". $registroPeriodo[0][0];
                        $variable.="&anio=". $registroPeriodo[0][1];
                        $variable.="&periodo=". $registroPeriodo[0][2];
                        $variable.="&tipo=".$_REQUEST['tipo'];
                        
                        $tipo = 'message';
                        $mensaje = '<center><h2>BIENVENIDO AL PROCESO DE EVALUACIÓN DOCENTE PERIODO ACADÉMICO '.$registroPeriodo[0][1].'-'.$registroPeriodo[0][2].'</h2></center>
                                   <br>'.$registroInstructivo[$i][2].'<br>
                                   Para seguir con el proceso de evaluación haga clikc en <b>"Continuar".</b>';
                        
                    }
                    else
                    {
                        $cierto=1;
                        $tipo = 'information';
                        $mensaje = "Usuario no reconocido como Coordinador en el Sistema, o Coordinador sin docentes registrados. ";
                    }  
                    
                  }  
            break;
            
            case 16: //Evaluación Por Decanos
                  if($registroInstructivo[$i][1]==3)
                  { 
                    if(is_array($registroCoordinadores))
                    {
                        $tipo = 'message';
                        $mensaje = '<center><h2>BIENVENIDO AL PROCESO DE EVALUACIÓN DOCENTE PERIODO ACADÉMICO '.$registroPeriodo[0][1].'-'.$registroPeriodo[0][2].'</h2></center>
                                   <br>'.$registroInstructivo[$i][2].'<br>
                                   Para seguir con el proceso de evaluación haga clikc en <b>"Continuar".</b>';
                    
                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                        $variable.="&opcion=nuevo";
                        $variable.="&usuario=". $_REQUEST['usuario'];
                        $variable.="&periodoId=". $registroPeriodo[0][0];
                        $variable.="&anio=". $registroPeriodo[0][1];
                        $variable.="&periodo=". $registroPeriodo[0][2];
                        $variable.="&tipo=".$_REQUEST['tipo'];
                                                
                    }
                    else
                    {
                        $cierto=1;
                        $tipo = 'information';
                        $mensaje = "Usuario no reconocido como Decano. ";
                    }  
                    
                  } 
            break;

            case 30: //Autoevaluación
                  if($registroInstructivo[$i][1]==2)
                  {    
                    if(is_array($registroCarga))
                    {
                        $tipo = 'message';
                        $mensaje = '<center><h2>BIENVENIDO AL PROCESO DE EVALUACIÓN DOCENTE PERIODO ACADÉMICO '.$registroPeriodo[0][1].'-'.$registroPeriodo[0][2].'</h2></center>
                                   <br>'.$registroInstructivo[$i][2].'<br>
                                   Para seguir con el proceso de evaluación haga clikc en <b>"Continuar".</b>';
                        
                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                        $variable.="&opcion=nuevo";
                        $variable.="&usuario=". $_REQUEST['usuario'];
                        $variable.="&periodoId=". $registroPeriodo[0][0];
                        $variable.="&anio=". $registroPeriodo[0][1];
                        $variable.="&periodo=". $registroPeriodo[0][2];
                        $variable.="&tipo=".$_REQUEST['tipo'];
                                                
                    }
                    else
                    {
                        $cierto=1;
                        $tipo = 'information';
                        $mensaje = "Usuario sin registros de carga académica. ";
                    }   
                  } 
            break;

            case 51: //Evaluación Estudiantes Horas
                  if($registroInstructivo[$i][1]==1)
                  {    
                    if(is_array($registroAsignaturas))
                    {
                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                        $variable.="&opcion=nuevo";
                        $variable.="&usuario=". $_REQUEST['usuario'];
                        $variable.="&periodoId=". $registroPeriodo[0][0];
                        $variable.="&anio=". $registroPeriodo[0][1];
                        $variable.="&periodo=". $registroPeriodo[0][2];
                        $variable.="&tipo=".$_REQUEST['tipo'];
                        
                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio); 
                        echo "<center><a href='".$variable."'><img src='".$rutaBloque."/imagenes/boton_continuar.png'></a></center>";
                        
                        $tipo = 'message';
                        $mensaje = '<center><h2>BIENVENIDO AL PROCESO DE EVALUACIÓN DOCENTE PERIODO ACADÉMICO '.$registroPeriodo[0][1].'-'.$registroPeriodo[0][2].'</h2></center>
                                   <br>'.$registroInstructivo[$i][2].'<br>
                                   Para seguir con el proceso de evaluación haga clikc en <b>"Continuar".</b>';
                        $cierto=1;
                    }
                    else
                    {
                        $cierto=1;
                        $tipo = 'information';
                        $mensaje = "No tiene asignaturas registradas en el sistema.<br>	Consulte en su Coordinaci&oacute;n de Carrera ";
                    }   
                  } 
            break;
            
             case 52: //Evaluación Estudiantes Créditos
                  if($registroInstructivo[$i][1]==1)
                  {    
                    if(is_array($registroAsignaturas))
                    {
                        $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
                        $variable.="&opcion=nuevo";
                        $variable.="&usuario=". $_REQUEST['usuario'];
                        $variable.="&periodoId=". $registroPeriodo[0][0];
                        $variable.="&anio=". $registroPeriodo[0][1];
                        $variable.="&periodo=". $registroPeriodo[0][2];
                        $variable.="&tipo=".$_REQUEST['tipo'];
                        
                        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio); 
                        echo "<center><a href='".$variable."'><img src='".$rutaBloque."/imagenes/boton_continuar.png'></a></center>";
                        
                        $tipo = 'message';
                        $mensaje = '<center><h2>BIENVENIDO AL PROCESO DE EVALUACIÓN DOCENTE PERIODO ACADÉMICO '.$registroPeriodo[0][1].'-'.$registroPeriodo[0][2].'</h2></center>
                                   <br>'.$registroInstructivo[$i][2].'<br>
                                   Para seguir con el proceso de evaluación haga clikc en <b>"Continuar".</b>';
                        $cierto=1;
                    }
                    else
                    {
                        $cierto=1;
                        $tipo = 'information';
                        $mensaje = "No tiene asignaturas registradas en el sistema.<br>	Consulte en su Coordinaci&oacute;n de Carrera ";
                    }   
                  } 
            break;  
            
    }
}

//$mensaje = 'Evaluación Docente';

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

if($cierto==1)
{
    echo "";
}
else
{   
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio); 
    echo "<center><a href='".$variable."'><img src='".$rutaBloque."/imagenes/boton_continuar.png'></a></center>";
    
}

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

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");



