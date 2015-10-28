<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}

if($variable['periodo']==1)
{
    $periodo="PRIMER";
}
elseif($variable['periodo']==3)
{
    $periodo="SEGUNDO";
} 
else
{
    $periodo=" ";
}

$variable['tipoInstructivo']="instructivo";

$cadena_sql = $this->sql->cadena_sql("buscarNombreInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
	
echo "<table align='center'>";
echo "<tbody>";
echo "<tr>";
echo "<td style=' font-size:11px; font-family: Arial;'>&nbsp;&nbsp;</td>";
for($i=0;$i<count($registro);$i++)
{
    $variable ="pagina=instructivo"; //pendiente la pagina para modificar parametro                                                        
    $variable.="&opcion=instructivo";
    $variable.="&seccion=".$registro[$i][1];
    $variable.="&tipo=".$_REQUEST['tipo']."";
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

    echo"<td bgcolor='LemonChiffon' align='center' valign='midle'  style=' font-size:11px; font-family: Arial;'>
        <a href='".$variable."'>".$registro[$i][1]."</a> 
        </td>";
}
$variables ="pagina=index"; //pendiente la pagina para modificar parametro                                                        
$variables.="&tipo=".$_REQUEST['tipo']."";
$variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);

echo"<td bgcolor='LemonChiffon' align='center' valign='midle'  style=' font-size:11px; font-family: Arial;'>
        <a href='".$variables."'>Cerrar</a> 
        </td>";

echo "</tr>";    
echo "</tbody>";
echo "</table>";	

unset($variable);
$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}
if(isset($_REQUEST['seccion']))
{   
    //var_dump($_REQUEST);
    
    $variable['seccion']=$_REQUEST['seccion'];
    $variable['tipoInstructivo']="instructivo";
    $cadena_sql = $this->sql->cadena_sql("buscarContenidoInstructivo", $variable);
    $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $valorCodificado="pagina=instructivo";
    $valorCodificado.="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarInstructivo";
    //$valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&id_periodo=".$variable['id_periodo'];
    $valorCodificado.="&insNombre=".$registroContenido[0][1];
    $valorCodificado.="&seccion=".$variable['seccion'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
    
    

    

        //-------------Control Mensaje-----------------------
   

    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "simple";
    $atributos["estilo"] = "";
    $atributos["tipo"] = 'message';
    $atributos["mensaje"] = "<center>PROCESO DE ADMISIONES ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</center><br>".stripslashes(html_entity_decode($registroContenido[0][2]));
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    //-------------Control cuadroTextArea-----------------------
   /* $esteCampo="instructivo";
    $atributos["id"]="instructivo";
    $atributos["tabIndex"]='';
    $atributos["obligatorio"]=false;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["columnas"]=125;
    $atributos["filas"]=1500;
    $atributos["valor"]=$registroContenido[0][2];
    $atributos["estilo"]="jqueryui";
    echo $this->miFormulario->campoTextArea($atributos);
    unset($atributos);*/
    
   
}
else
{
    $tipo = 'message';
    $mensaje = "<center>PROCESO DE ADMISIONES ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</center><br>
                Para acceder al insctructivo, haga click en el menú de la parte superior de la pantalla.";
    
    
    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    ?><center><img src='<?php echo $rutaBloque."formulario/ambiente.jpeg"?>'></center><?
}    


