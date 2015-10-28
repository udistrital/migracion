<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/admisiones/resultados/";
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

$conexion1="admisionesAdmin";
$esteRecursoDB1 = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);
if (!$esteRecursoDB1) {

    echo "//Este se considera un error fatal";
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
$variable['id_evento']=6;

$cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
$registroeventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registroeventos[0]['des_id']>0)
{
    if($cierto==1)
    {
        $variable['carreras']=9003;
        $cadena_sql = $this->sql->cadena_sql("buscarContenidoColilla", $variable);
        $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        $valorCodificado="pagina=administracion";
        $valorCodificado.="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=guardar";
        if(isset($_REQUEST['usuario']))
        {    
            $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        }
        if(isset($_REQUEST['tipo']))
        {    
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        }
        if(isset($_REQUEST['rba_id']))
        {    
            $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
        }

        $valorCodificado="pagina=resultados";
        $valorCodificado.="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=consultarResultados";
        $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
        $valorCodificado.="&id_periodo=".$variable['id_periodo'];
        $valorCodificado.="&anio=".$variable['anio'];
        $valorCodificado.="&periodo=".$variable['periodo'];
        $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);

        $atributos["id"] = "marcoAgrupacionFechas";
        $atributos["estilo"] = "jqueryui";
        $atributos["leyenda"] = " ";
        echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
        unset($atributos);


        $tab = 1;

        //---------------Inicio Formulario (<form>)--------------------------------
        $atributos["id"] = $nombreFormulario;
        $atributos["tipoFormulario"] = "multipart/form-data";
        $atributos["metodo"] = "POST";
        $atributos["nombreFormulario"] = $nombreFormulario;
        $verificarFormulario = "1";
        echo $this->miFormulario->formulario("inicio", $atributos);
        unset($atributos);


        //-------------Control Mensaje-----------------------
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
        $tipo = 'message';
        $mensaje = "<center><h3>CONSULTA DE RESULTADOS DEL PROCESO DE ADMISiONES PARA EL  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</h3></center>";
        $mensaje.='<br><p align="justify">Para poder ver los documentos que contienen la informaci&oacute;n, necesita un visor de archivos pdf. Si no tiene instalado uno, por favor desc&aacute;rguelo de aqu&iacute;:&nbsp;&nbsp;<a href="http://www.adobe.com/es/products/acrobat/readstep2.html" target="_blank"><img src="'.$rutaBloque.'/images/reader_icon.gif" width="21" height="20" border="0"></a></p>';
        $mensaje.="Nota: La asignación de cupos, fue reglamentada por el Consejo Académico proporcionalmente a la demanda de inscritos. <br>Mayores informes carrera 8 No 40 - 62 Admisiones, Edificio Sabio Caldas  primer piso.";
        
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
                 
        $rutaArchivos=$this->miConfigurador->getVariableConfiguracion("raizArchivoDocumentos");
    
        $directorioArchivos = opendir($rutaArchivos); //ruta actual

                //DOCUMENTOS CARGADOS
                $atributos["id"] = "marcoDocumentosAdjuntos";
                $atributos["estilo"] = "jqueryui";
                $atributos["leyenda"] = "Lista de archivos cargados.";
                echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
                unset($atributos);
                
                echo "<table id='tablaArchivosResultados'>";
                echo "<thead>
		            <tr>
                		<th>Nombre archivo</th>
		                <th>Ver</th>
		           </tr>
		        </thead>
		        <tbody>";
                $i=0;
                $flag=0;
                while ($archivo = readdir($directorioArchivos)) //obtenemos un archivo y luego otro sucesivamente
                {
                    if ($archivo!='..' && $archivo!='.')
                    {
                        $dividimos=  explode('_', $archivo);
                        if(trim($variable['anio'])==trim($dividimos[2]) && trim($variable['periodo'])==trim($dividimos[3]))
                        {
                            
                              
                            //Comparamos que el código dela carrera sea igual al del archivo
                            if($dividimos[1]=='especiales')
                            {
                                $variable['carrera']="especiales";
                                $variable['prefijo']=$dividimos[0];
                                                                
                                $variables ="pagina=resultados"; //pendiente la pagina para modificar parametro                                                        
                                $variables.="&opcion=verArchivo";
                                $variables.="&action=".$esteBloque["nombre"];
                                $variables.="&usuario=". $_REQUEST['usuario'];
                                $variables.="&tipo=1";
                                //$variables.="&id_tipIns=".$registro[$i][0];
                                $variables.="&archivo=".$archivo;
                                $variables.="&bloque=".$esteBloque["id_bloque"];
                                $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                                $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                                
                                echo "<tr>";
                                	echo "<td>";
		                                echo "<a href='".$variables."' TARGET='_blank'> ";
		                                $esteCampo = "mensaje";
		                                $atributos["id"] = "mensaje"; 
		                                $atributos["etiqueta"] = "";
		                                $atributos["estilo"] = "campoCuadroTexto";
		                                $atributos ["tamanno"]="pequenno";
		                                $atributos["tipo"] = $tipo;
		                                $atributos["mensaje"] = "Especiales";
		                                echo $this->miFormulario->campoMensaje($atributos);
		                                unset($atributos);
		                                echo "</a>";
	                                echo "</td>";
	                                echo "<td>";
		                                echo "<a href='".$variables."' TARGET='_blank'> ";
		                                echo "<img src='".$rutaBloque."/images/pdfmini.png' width='15px'> ";
		                                echo "</a>";
	                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    $i++;    
                    }
                }
        echo "<tbody>
        	</table>";
        echo $this->miFormulario->marcoAgrupacion("fin");

    }
}
else
{
    $atributos["id"]="divNoEncontroRegistro";
    $atributos["estilo"]="marcoBotones";
    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio",$atributos);

    //-------------Control Boton-----------------------
    $esteCampo = "eventoCerrado";
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



