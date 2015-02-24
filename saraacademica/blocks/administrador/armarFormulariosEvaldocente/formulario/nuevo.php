<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/armarFormulariosEvaldocente/";
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
$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo']=$registroPeriodo[0]['acasperiev_id'];

$cadena_sql = $this->sql->cadena_sql("consultarFormatos", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$valorCodificado="pagina=editarInstructivo";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardar";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "";
$atributos["tipo"] = "message";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);

if($registro)
{	
        echo "<table id='tablaFormatos'>";
        
        echo "<thead>
                <tr>
                    <th>Formato No.</th>
                    <th>Formato</th>
                    <th>Evaluador</th>
                    <th>Periodo académico</th>
                    <th>Estado</th>
                    <th>Armar formulario</th>
               </tr>
            </thead>
            <tbody>";
        
        for($i=0;$i<count($registro);$i++)
        {
            $variable ="pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
            $variable.="&opcion=armarFormulario";
            $variable.="&usuario=". $_REQUEST['usuario'];
            $variable.="&formatoNumero=".$registro[$i][2];
            $variable.="&formatoId=".$registro[$i][0];
            $variable.="&periodo=".$registroPeriodo[0]['acasperiev_id'];
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            
            echo "<tr>
                    <td align='center'>".$registro[$i][2]."</td>
                    <td><a href='".$variable."'>".$registro[$i][3]."</a></td>
                    <td align='center'>".$registro[$i][6]."</td>    
                    <td align='center'>".$registro[$i][8]."-".$registro[$i][9]."</td>
                    <td align='center'>".$registro[$i][5]."</td>
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







