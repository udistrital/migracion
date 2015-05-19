<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/administrativos/gestionAdministrativos/";
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

$conexion = "funcionario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1="soporteoas";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

                                        
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
    exit;
}

if(isset($_REQUEST['docuentoNumero']))
{    
    $variable['usuario']=$_REQUEST['docuentoNumero'];
}
else
{    
    $variable['usuario']=$_REQUEST['usuario'];
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$cadena_sql = $this->sql->cadena_sql("datosCertificacion", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registro)
{
        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);
        
        $nombre=$registro[0][0].' '.$registro[0][1].' '.$registro[0][2].' '.$registro[0][3];
        $identificacion=$registro[0][5];

        ////-------------------------------Mensaje-------------------------------------
        $tipo = 'message';
        $mensaje = "<center><span class='textoNegrita textoGrande textoCentrar'><br>CERTIFICADOS DIVISIÓN RECURSOS HUMANOS</span></center><br>
                    <span class='textoNegrita textoPequeno textoCentrar'>Nombre: ".$nombre."</span><br>
                    <span class='textoNegrita textoPequeno textoCentrar'>Identificación: ".$identificacion."</span><br>
                    <p class='textoJustificar'>
                        Haga click en el pdf para seleccionar el certificado que va a generar.
                    </p> ";


        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);

	
        echo "<table id='tablaCertificados'>";
        
        echo "<thead>
                <tr>
                    <th>Datos básicos</th>
                    <th>Sueldo Básico Mensual</th>
                    <th>Salario Promedio mensual</th>
                    <th>Salario Promedio</th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr>";
            for($i=0; $i<=3; $i++)
            {
                if(is_array($registro))
                {
                    $variables ="pagina=gestionAdministrativos"; //pendiente la pagina para modificar parametro                                                        
                    $variables.="&opcion=digitarObservacion";
                    
                    if(isset($_REQUEST['docuentoNumero']))
                    {    
                        $variables.="&usuario=".$_REQUEST['docuentoNumero'];
                    }
                    else
                    {    
                        $variables.="&usuario=".$_REQUEST['usuario'];
                    }    
                    $variables.="&tipoCertificado=certificado".$i;
                    $variables.="&tipo=".$_REQUEST['tipo'];
                    $variables.="&bloque=".$esteBloque["id_bloque"];
                    $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                    $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                    echo "  <td align='center'><a href='".$variables."'>";               
                    echo "  <img src='".$rutaBloque."images/pdf.png' width='25px'>"; 
                    echo "  </a>";
                    echo " </td>";
                }
            }
            echo "</tr>";   
        echo "</tbody>";
        
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







