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
$cadena_sql = $this->sql->cadena_sql("anioper", $variable);
$resultAnioPer = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$ano=$resultAnioPer[0][0];
$per=$resultAnioPer[0][1];

for($i=$ano; $i>=$ano-2; $i--)
{
    $variable['anio']=$i;
    $cadena_sql = $this->sql->cadena_sql("certificadoFuncionarios", $variable);
    $registroFuncionarios = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $cadena_sql = $this->sql->cadena_sql("certificadoContratistas", $variable);
    $registroContratistas = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
}

if($registroFuncionarios || $registroContratistas)
{
        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);
        if(is_array($registroFuncionarios))
        {    
            $nombre=$registroFuncionarios[0][22];
            $identificacion=$registroFuncionarios[0][0];
        }
        else
        {    
            $nombre=$registroContratistas[0][2].' '.$registroContratistas[0][3].' '.$registroContratistas[0][4].' '.$registroContratistas[0][5];
            $identificacion=$registroContratistas[0][1];
        }        
        ////-------------------------------Mensaje-------------------------------------
        $tipo = 'message';
        $mensaje = "<center><span class='textoNegrita textoGrande textoCentrar'><br>CERTIFICADO DE INGRESOS Y RETENCIONES</span></center><br>
                    <span class='textoNegrita textoPequeno textoCentrar'>Nombre: ".$nombre."</span><br>
                    <span class='textoNegrita textoPequeno textoCentrar'>Identificación: ".$identificacion."</span><br>
                    <p class='textoJustificar'>
                        Seleccione el a&ntilde;o, haga Click en la imagen del PDF para generar el certificado de ingresos y retenciones.
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
                    <th>Año</th>";
                    if(is_array($registroFuncionarios))
                    {    
                        echo "<th>Certificado Funcionarios</th>";
                    }
                    if(is_array($registroContratistas))
                    {
                        for($k=0; $k<=count($registroContratistas)-1; $k++)
                        {
                            if($registroContratistas[$k][22]=='H')
                            {
                                echo "<th>Certificado Contratistas Honorarios</th>";
                            }
                            if($registroContratistas[$k][22]=='S')
                            {
                                echo "<th>Certificado Contratistas Salario</th>";
                            }
                        }
                    }
                    
            echo"</tr>
            </thead>
            <tbody>";
        
        for($i=$ano-1; $i>=$ano-2; $i--)
	{
           
            
             
            echo "<tr>
                    <td align='center'>".$i."</td>";
                    if(is_array($registroFuncionarios))
                    {
                        $variables ="pagina=gestionAdministrativos"; //pendiente la pagina para modificar parametro                                                        
                        $variables.="&opcion=certificadoIngresosRetenciones";
                        $variables.="&action=".$esteBloque["nombre"];
                        $variables.="&anio=".$i;
                        if(isset($_REQUEST['docuentoNumero']))
                        {    
                            $variables.="&usuario=".$_REQUEST['docuentoNumero'];
                        }
                        else
                        {    
                            $variables.="&usuario=".$_REQUEST['usuario'];
                        }    
                        $variables.="&tipoCertificado=funcionarios";
                        $variables.="&tipo=".$_REQUEST['tipo'];
                        $variables.="&bloque=".$esteBloque["id_bloque"];
                        $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                        $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);   
                        echo "  <td align='center'><a href='".$variables."'>";               
                        echo "  <img src='".$rutaBloque."images/pdf.png' width='25px'>"; 
                        echo "  </a></td>";
                    }
                    if(is_array($registroContratistas))
                    {
                        for($k=0; $k<=count($registroContratistas)-1; $k++)
                        {
                            if($registroContratistas[$k][22]=='H')
                            {
                                $variables ="pagina=gestionAdministrativos"; //pendiente la pagina para modificar parametro                                                        
                                $variables.="&opcion=certificadoIngresosRetenciones";
                                $variables.="&action=".$esteBloque["nombre"];
                                $variables.="&anio=".$i;
                                if(isset($_REQUEST['docuentoNumero']))
                                {    
                                    $variables.="&usuario=".$_REQUEST['docuentoNumero'];
                                }
                                else
                                {    
                                    $variables.="&usuario=".$_REQUEST['usuario'];
                                }
                                $variables.="&tipoCertificado=contratistasHonorarios";
                                $variables.="&tipo=".$_REQUEST['tipo'];
                                $variables.="&bloque=".$esteBloque["id_bloque"];
                                $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                                $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                                echo "  <td align='center'><a href='".$variables."'>";               
                                echo "  <img src='".$rutaBloque."images/pdf.png' width='25px'>"; 
                                echo "  </a></td>";
                            }
                            if($registroContratistas[$k][22]=='S')
                            {
                                $variables ="pagina=gestionAdministrativos"; //pendiente la pagina para modificar parametro                                                        
                                $variables.="&opcion=certificadoIngresosRetenciones";
                                $variables.="&action=".$esteBloque["nombre"];
                                $variables.="&anio=".$i;
                                if(isset($_REQUEST['docuentoNumero']))
                                {    
                                    $variables.="&usuario=".$_REQUEST['docuentoNumero'];
                                }
                                else
                                {    
                                    $variables.="&usuario=".$_REQUEST['usuario'];
                                }
                                $variables.="&tipoCertificado=contratistasSueldo";
                                $variables.="&tipo=".$_REQUEST['tipo'];
                                $variables.="&bloque=".$esteBloque["id_bloque"];
                                $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                                $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                                echo "  <td align='center'><a href='".$variables."'>";               
                                echo "  <img src='".$rutaBloque."images/pdf.png' width='25px'>"; 
                                echo "  </a></td>";
                            }
                        }
                    }   
                
            echo "</tr>";
           
        }
               
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







