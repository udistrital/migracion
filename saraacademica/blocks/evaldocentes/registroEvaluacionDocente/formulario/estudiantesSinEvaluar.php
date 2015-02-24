<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/registroEvaluacionDocente/";
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


//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "ESTUDIANES SIN REGISTROS DE EVALUACIÓN<br>
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

if($registroEstudiantesSinEvaluar)
{	
        echo "<table id='tablaEstudiantesSinEvaluar'>";
        
        echo "<thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Correo</th>
                    <th>Cod. Carrera</th>
                    <th>Carrera</th>
                    <th>Cod. Facultad</th>
                    <th>Facultad</th>
                    <th>Enviar correo</th>
               </tr>
            </thead>
            <tbody>";
        
        for($j=0; $j<=count($registroEstudiantesSinEvaluar)-1; $j++)
        {
            $variables ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
            $variables.="&opcion=enviaCorreos";
            $variables.="&usuario=".$_REQUEST['usuario'];
            $variables.="&email=".$registroEstudiantesSinEvaluar[$j][3].",".$registroEstudiantesSinEvaluar[$j][4];
            $variables.="&tipo=".$_REQUEST['tipo'];
            $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);    
            echo "<tr>
                    <td align='center'>".$registroEstudiantesSinEvaluar[$j][0]."</td>
                    <td>".$registroEstudiantesSinEvaluar[$j][1]."</td>
                    <td align='center'>".$registroEstudiantesSinEvaluar[$j][2]."</td>    
                    <td>".$registroEstudiantesSinEvaluar[$j][3].",<br>".$registroEstudiantesSinEvaluar[$j][4]."</td>
                    <td align='center'>".$registroEstudiantesSinEvaluar[$j][5]."</td>
                    <td>".$registroEstudiantesSinEvaluar[$j][6]."</td>
                    <td align='center'>".$registroEstudiantesSinEvaluar[$j][9]."</td>
                    <td>".$registroEstudiantesSinEvaluar[$j][10]."</td>
                    <td align='center'><a href='".$variables."'>               
                    <img src='".$rutaBloque."/images/email.png' width='25px'> 
                    </a></td>    
                </tr>";
           
        }
               
        echo "</tbody>";
        
        echo "</table>";
        unset($variable);
        $variables ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
        $variables.="&opcion=enviaCorreos";
        $variables.="&tipo=".$_REQUEST['tipo'];
        $variables.="&usuario=".$_REQUEST['usuario'];
        $variables.="&facultad=".$_REQUEST['facultad'];
        $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);  
        echo "<table align='center'>
            <tr>
                <td align='center'>
                    <a href='".$variables."'>               
                    <img src='".$rutaBloque."images/email.png' width='35px'> 
                    <br>Enviar correos
                    </a>
                </td>
            </tr>
        </table>";
}else
{
	$atributos["id"]="divNoEncontroRegistro";
	$atributos["estilo"]="marcoBotones";
        //$atributos["estiloEnLinea"]="display:none"; 
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control Boton-----------------------
	$esteCampo = "noEncontroRegistro";
	$atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
	$atributos["etiqueta"] = "";
	$atributos["estilo"] = "centrar";
	$atributos["tipo"] = 'error';
	$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
	echo $this->miFormulario->cuadroMensaje($atributos);
         unset($atributos); 
	//-------------Fin Control Boton----------------------
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
}

echo $this->miFormulario->division("fin");







