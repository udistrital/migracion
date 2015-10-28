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

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1 = "aspirantes";
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

if($cierto==1)
{
    //$valorCodificado="pagina=habilitarEvaluacion";
    $valorCodificado="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarPdfResultados";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&id_periodo=".$variable['id_periodo'];
    $valorCodificado.="&anio=".$variable['anio'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&periodo=".$variable['periodo'];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    //-------------Fin de Conjunto de Controles----------------------------
    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = "Cargue de archivos PDF resultados ";
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
        $tipo = 'message';
        $mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ SUBIR LOS PDF CON LOS RESULTADOS A CADA PROYECTO CURRICULAR,
            <br><center>PERIODO ACADÉMICO ".$variable['anio']."-".$variable['periodo'].".</center><br><br>
            Los campos con * son obligatorios.";


        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
        
        //------------------Control Lista Desplegable------------------------------
        $esteCampo = "carreras";
        $atributos["id"] = $esteCampo;
        $atributos["tabIndex"] = $tab++;
        $atributos["seleccion"] = 1;
        $atributos["evento"] = 2;
        $atributos["columnas"] = "1";
        $atributos["limitar"] = false;
        $atributos["tamanno"] = 1;
        $atributos["ancho"] = "250px";
        $atributos["estilo"] = "jqueryui";
        $atributos["etiquetaObligatorio"] = true;
        $atributos["validar"] = "required";
        $atributos["anchoEtiqueta"] = 250;
        $atributos["obligatorio"] = true;
        $atributos["etiqueta"] = "Carrera a la que se inscribe: ";
        //-----De donde rescatar los datos ---------
        $atributos["cadena_sql"] = $this->sql->cadena_sql("carrerasOfrecidas");
        $atributos["baseDatos"] = "aspirantes";
        echo $this->miFormulario->campoCuadroLista($atributos);
        unset($atributos);
        
        $esteCampo="subirArchivo";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Seleccione archivo plano con los ICFES a registrar en el sistema.";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 250;
        $atributos["tamanno"]="35";
        $atributos["tipo"]="file";
        $atributos["estilo"]="jqueryui";
        //$atributos["validar"]="required";
        //$atributos["valor"]=$_REQUEST['medio'];
        $atributos["categoria"]="";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
        
        //------------------Division para los botones-------------------------
        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones";
        echo $this->miFormulario->division("inicio",$atributos);
       //-------------Control Boton-----------------------
        $esteCampo = "botonGuardar";
        $atributos["id"] = $esteCampo;
        $atributos["tabIndex"] = $tab++;
        $atributos["tipo"] = "boton";
        $atributos["estilo"] = ""; 
       //$atributos["estilo"]="jqueryui";
        $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
        //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos["tipoSubmit"]="jquery";
        $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
        $atributos["nombreFormulario"] = $nombreFormulario;
        echo $this->miFormulario->campoBoton($atributos);
        unset($atributos);

    //-------------Fin Control Boton----------------------

    //-------------Control Boton-----------------------
         $esteCampo="botonCancelar";
        $atributos["id"]=$esteCampo;
        $atributos["tabIndex"]=$tab++;
        $atributos["verificar"]="";
        $atributos["tipo"]="boton";
        $atributos["nombreFormulario"] = $nombreFormulario;
        $atributos["cancelar"]=true;
        $atributos["tipoSubmit"] = "jquery";
        //$atributos["onclick"]=true;
        $atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
        echo $this->miFormulario->campoBoton($atributos);
        unset($atributos);
    //-------------Fin Control Boton----------------------

    //-------------Control cuadroTexto con campos ocultos-----------------------
    //Para pasar variables entre formularios o enviar datos para validar sesiones
        $atributos["id"] = "formSaraData"; //No cambiar este nombre
        $atributos["tipo"] = "hidden";
        $atributos["obligatorio"] = false;
        $atributos["etiqueta"] = "";
        $atributos["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

    //Fin del Formulario
    echo $this->miFormulario->formulario("fin");

    //-------------Fin de Conjunto de Controles----------------------------
    echo $this->miFormulario->marcoAgrupacion("fin");

    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");

    //$tab = 2;


    echo "Lista de archivos PDF con resultados del proceso de admisiones:<hr/>";
      
    
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
                		<th>Cod. Carrera</th>
		                <th>Proyecto Curricular</th>
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
                        	$variable['carrera']=trim($dividimos[1]);
                        	
                        	//Consultamos las careras ofrecidas
                        	$cadena_sql = $this->sql->cadena_sql("carrerasOfrecidas", $variable);
                        	@$registroCarreras = $esteRecursoDB1->ejecutarAcceso($cadena_sql, "busqueda");
                        	
                        	//Comparamos que el código dela carrera sea igual al del archivo
                            if(trim($registroCarreras[0][0])==$dividimos[1])
                            {
                                $variable['carrera']=$registroCarreras[0][0];
                                $variable['prefijo']=$dividimos[0];
                                
                                
                                $variables ="pagina=admisiones"; //pendiente la pagina para modificar parametro                                                        
                                $variables.="&opcion=verArchivo";
                                $variables.="&action=".$esteBloque["nombre"];
                                $variables.="&usuario=". $_REQUEST['usuario'];
                                $variables.="&tipo=".$_REQUEST['tipo'];
                                //$variables.="&id_tipIns=".$registro[$i][0];
                                $variables.="&archivo=".$archivo;
                                $variables.="&bloque=".$esteBloque["id_bloque"];
                                $variables.="&bloqueGrupo=".$esteBloque["grupo"];
                                $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);
                                
                                echo "<tr>";
                                	echo "<td>";
                                		echo $registroCarreras[0][0];
                                	echo "</td>";
	                                echo "<td>";
		                                echo "<a href='".$variables."' TARGET='_blank'> ";
		                                $esteCampo = "mensaje";
		                                $atributos["id"] = "mensaje"; 
		                                $atributos["etiqueta"] = "";
		                                $atributos["estilo"] = "campoCuadroTexto";
		                                $atributos ["tamanno"]="pequenno";
		                                $atributos["tipo"] = $tipo;
		                                $atributos["mensaje"] = $registroCarreras[0][1];
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
else
{
    $nombreFormulario=$esteBloque["nombre"];

    include_once("core/crypto/Encriptador.class.php");
    $cripto=Encriptador::singleton();
    $directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

    $miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
    $tab=1;
    //---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"]=$nombreFormulario;
    $atributos["tipoFormulario"]="multipart/form-data";
    $atributos["metodo"]="POST";
    $atributos["nombreFormulario"]=$nombreFormulario;
    $verificarFormulario="1";
    echo $this->miFormulario->formulario("inicio",$atributos);

	$atributos["id"]="divErrores";
	$atributos["estilo"]="marcoBotones";
        //$atributos["estiloEnLinea"]="display:none"; 
	echo $this->miFormulario->division("inicio",$atributos);
	
	
            $tipo = 'information';
            $mensaje = 'No se encontrararon colillas registradas en el sistema, para continuar con el proceso haga click en "Continuar"...';
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso']; 
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	
	
	$esteCampo = "botonContinuar";
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos); 
        
        //------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
        
        //------------------Division para los botones-------------------------
	$atributos["id"]="botones";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control Boton-----------------------
	$esteCampo ="botonContinuar" ;
	$atributos["id"]=$esteCampo;
	$atributos["tabIndex"]=$tab++;
	$atributos["tipo"]="boton";
	$atributos["estilo"]="jquery";
	$atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
	$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
	$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
	$atributos["nombreFormulario"]=$nombreFormulario;
	echo $this->miFormulario->campoBoton($atributos);
	unset($atributos);
	//-------------Fin Control Boton----------------------
	
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
    
	//-------------Control cuadroTexto con campos ocultos-----------------------
	//Para pasar variables entre formularios o enviar datos para validar sesiones
	$atributos["id"]="formSaraData"; //No cambiar este nombre
	$atributos["tipo"]="hidden";
	$atributos["obligatorio"]=false;
	$atributos["etiqueta"]="";
	$atributos["valor"]=$valorCodificado;
	echo $this->miFormulario->campoCuadroTexto($atributos);
	unset($atributos);
	
        //Fin del Formulario
        echo $this->miFormulario->formulario("fin");

}
