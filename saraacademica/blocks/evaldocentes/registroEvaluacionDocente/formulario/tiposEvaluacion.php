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

$cadena_sql = $this->sql->cadena_sql("tiposEvaluacion", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$esteCampo = "mensajeEstadoEvaluacion";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "";
$atributos["tipo"] = "message";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);

if($registro)
{	
        echo "<table id='tablaTiposEvaluacion'>";
        
        echo "<thead>
                <tr>
                    <th>Id.</th>
                    <th>Tipo de Evaluación</th>
                    <th>Ver estado de evaluación</th>
               </tr>
            </thead>
            <tbody>";
        
        for($i=0;$i<count($registro);$i++)
        {
            $variable ="pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro                                                        
            $variable.="&opcion=estadoEvaluacion";
            $variable.="&usuario=". $_REQUEST['usuario'];
            $variable.="&anio=".$registroPeriodo[0]['acasperiev_anio'];
            $variable.="&per=".$registroPeriodo[0]['acasperiev_periodo'];
            $variable.="&tipoId=".$registro[$i][0];
            //$variable.="&periodo=".$variable['periodo'];
             $variable.="&tipo=". $_REQUEST['tipo'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            
             
            echo "<tr>
                    <td align='center'>".$registro[$i][0]."</td>
                    <td><a href='".$variable."'>".$registro[$i][1]."</a></td>
                    <td align='center'><a href='".$variable."'>               
                        <img src='".$rutaBloque."/images/edit.png' width='15px'> 
                        </a></td>
                </tr>";
            unset($variable);
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







