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
$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("consultarCarga", $variable);
$registroCarga = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

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


if($registroCarga)
{	
        echo "<table id='tablaCarga'>";
        
        echo "<thead>
                <tr>
                    <th>Código</th>
                    <th>Asignatura</th>
                    <th>Grupo</th>
                    <th>Inscritos</th>
                    <th>Carrera</th>
                    <th>Ver lista</th>
               </tr>
            </thead>
            <tbody>";
       
        for($j=0; $j<=count($registroCarga)-1; $j++)
        {
            $variables ="pagina=listaClase"; //pendiente la pagina para modificar parametro                                                        
            $variables.="&opcion=verLista";
            $variables.="&usuario=".$registroCarga[$j][0];
            $variables.="&asignatura=".$registroCarga[$j][8];
            $variables.="&grupo=".$registroCarga[$j][13];
            $variables.="&tipo=".$_REQUEST['tipo'];
            $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);    
            echo "<tr>
                    <td align='center'>".$registroCarga[$j][8]."</td>
                    <td align='center'><a href='".$variables."' title='Haga click aquí para ve la lista de estudiantes.'>".$registroCarga[$j][9]."</a></td>
                    <td align='center'>".$registroCarga[$j][10]."</td>    
                    <td align='center'>".$registroCarga[$j][11]."</td>
                    <td align='center'>".$registroCarga[$j][5]."</td>
                    <td align='center'><a href='".$variables."' title='Haga click aquí para ve la lista de estudiantes.'>               
                    <img src='".$rutaBloque."/images/lupa.jpg' width='18px'> 
                    </a></td>    
                </tr>";
           
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


