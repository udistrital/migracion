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

$variable['periodoId']=$_REQUEST['periodo'];
$variable['usuario']=$_REQUEST['usuario'];
$cadena_sql = $this->sql->cadena_sql("buscarAnioPeriodo", $variable);
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];
$variable['anio']=$registroPeriodo[0]['acasperiev_anio'];
$variable['per']=$registroPeriodo[0]['acasperiev_periodo'];


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
$mensaje = "Número estudiantes inscrtitos Vs estudiantes que han realizado la evaluación Docente <br>
            PERIODO ACADÉMICO ".$registroPeriodo[0][1]." ";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

$cadena_sql = $this->sql->cadena_sql("numeroEstudiantesInscritos", $variable);
$registroNumEstIns = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");

if(is_array($registroNumEstIns))
{       
        echo "<table id='tablaDocenteSinEvaluar'>";
        
        echo "<thead>
                <tr>
                    <th>Facultad</th>
                    <th>Carrera</th>
                    <th>Asignatura</th>
                    <th>Grupo</th>
                    <th>No. Inscritos</th>
                    <th>No. evaluaron</th>
               </tr>
            </thead>
            <tbody>";
        
        for($j=0; $j<=count($registroNumEstIns)-1; $j++)
        {
            $variable['asignatura']=$registroNumEstIns[$j][8];
            $variable['grupo']=$registroNumEstIns[$j][10];
            $cadena_sql = $this->sql->cadena_sql("numeroEstudiantesEvaluaron", $variable);
            $registroEstEvaluaron = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $cuenta=count($registroEstEvaluaron);
                                   
            echo "<tr>
                    <td align='center'>".$registroNumEstIns[$j][3]."</td>
                    <td>".$registroNumEstIns[$j][5]."</td>
                    <td>".$registroNumEstIns[$j][9]."</td>
                    <td align='center'>".$registroNumEstIns[$j][10]."</td>
                    <td>".$registroNumEstIns[$j][11]."</td>
                    <td align='center'>".$cuenta."</td>
                </tr>";
           
        }
               
        echo "</tbody>";
        
        echo "</table>";	
}
else
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







