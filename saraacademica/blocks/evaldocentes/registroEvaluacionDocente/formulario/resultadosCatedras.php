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

$cadena_sql = $this->sql->cadena_sql("resultadosEvaluacionCatedras", $variable);
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
$mensaje = "RESULTADOS EVALUACIÓN DOCENTE<br>
            PERIODO ACADÉMICO ".$registroPeriodo[0][1]." ";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

if($registro)
{	
        echo "<table id='tablaResultados'>";
        
        echo "<thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Vinculación</th>
                    <th>Valor Evaluación</th>
                    <th>Porcentaje</th>
                    <th>Tipo Evaluación</th>
                    <th>Cod. carrera</th>
                    <th>Carrera</th>
                    <th>Facultad</th>
                    <th>Subtotal</th>
               </tr>
            </thead>
            <tbody>";
        $valorFinal=0;
        for($j=0; $j<=count($registro)-1; $j++)
        {
            $variable['docente']=$registro[$j][1];
            $variable['carrera']=$registro[$j][4];
            
            $cadena_sql = $this->sql->cadena_sql("consultaDocentes", $variable);
            $registroDocetes = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda");
          
            $variable['tipoId']=$registro[$j][2];
            $cadena_sql = $this->sql->cadena_sql("consultarTipoEvaluacion", $variable);
            $registroTipEvaluacion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");   
            
            $cadena_sql = $this->sql->cadena_sql("datosCarrera", $variable);
            $registroFacultad = $esteRecursoBDORA->ejecutarAcceso($cadena_sql, "busqueda"); 
            
            $calificacion=round($registro[$j][0],2);
                        
            $subtotal=$calificacion*($registro[$j][3]/100);
            $variable['subtotal']=$subtotal;
            $variable['tipoId']=$registro[$j][2];
            
            $cadena_sql = $this->sql->cadena_sql("consultaResultados", $variable);
            $registroResultados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda"); 
            
            /*if(!is_array($registroResultados))
            {    
                $cadena_sql = $this->sql->cadena_sql("insertaResultados", $variable);
                $registroInsertResultados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            }
            else
            {
                $cadena_sql = $this->sql->cadena_sql("modificaResultados", $variable);
                $registroModResultados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
            } 
            
            $cadena_sql = $this->sql->cadena_sql("sumaResultados", $variable);
            $sumaResultados = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda"); */
            
            echo "<tr>
                    <td align='center'>".$registro[$j][1]."</td>
                    <td>".$registroDocetes[0][1]."</td>
                    <td>".$registroDocetes[0][4]."</td>    
                    <td align='center'>".$calificacion."</td>
                    <td align='center'>".$registro[$j][3]."%</td>    
                    <td align='center'>".$registroTipEvaluacion[0][2]."</td>
                    <td align='center'>".$registro[$j][4]."</td>
                    <td>".$registroDocetes[0][5]."</td>
                    <td>".$registroFacultad[0][3]."</td>
                    <td>".$subtotal."</td>";
                echo "</tr>";
           
        }
               
        echo "</tbody>";
        
        echo "</table>";
        
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







