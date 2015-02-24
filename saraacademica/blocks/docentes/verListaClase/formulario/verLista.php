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
$variable['asignatura']=$_REQUEST['asignatura'];
$variable['grupo']=$_REQUEST['grupo'];
$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("consultaListaClase", $variable);
$registroLista = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "<center>LISTA DE CLASE PERIODO ACADÉMICO ".$registroPeriodo[0][0]."-".$registroPeriodo[0][1]. "</center>";


$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);


if($registroLista)
{	
        echo "<table id='tablaCarga'>";
        
        echo "<thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Enviar correo</th>
               </tr>
            </thead>
            <tbody>";
       
        for($j=0; $j<=count($registroLista)-1; $j++)
        {
            $variables ="pagina=listaClase"; //pendiente la pagina para modificar parametro                                                        
            $variables.="&opcion=enviaCorreos";
            $variables.="&usuario=".$_REQUEST['usuario'];
            $variables.="&codigo=".$registroLista[$j][4];
            $variables.="&mailDocente=".$registroLista[$j][3];
            $variables.="&docente=".$registroLista[$j][2];
            $variables.="&mail=".$registroLista[$j][1];
            $variables.="&tipo=".$_REQUEST['tipo'];
            $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);    
            echo "<tr>
                    <td align='center'>".$registroLista[$j][4]."</td>
                    <td>".$registroLista[$j][0]."</td>
                    <td align='center'><a href='".$variables."' title='Haga click aquí para enviar correo al estudiante.'>".$registroLista[$j][1]."</a></td>    
                    <td align='center'><a href='".$variables."' title='Haga click aquí para enviar correo al estudiante.'>               
                    <img src='".$rutaBloque."/images/email.jpg' width='18px'> 
                    </a></td>    
                </tr>";
           
        }
               
        echo "</tbody>";
        echo "</table>";
        unset($variable);
        $variables ="pagina=listaClase"; //pendiente la pagina para modificar parametro                                                        
        $variables.="&opcion=enviaCorreos";
        $variables.="&usuario=".$_REQUEST['usuario'];
        $variables.="&asignatura=".$_REQUEST['asignatura'];
        $variables.="&grupo=".$_REQUEST['grupo'];
        $variables.="&tipo=".$_REQUEST['tipo'];
        $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);  
        echo "<table align='center'>
            <tr>
                <td align='center'>
                    <a href='".$variables."'>               
                    <img src='".$rutaBloque."/images/email.jpg' width='35px'> 
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


