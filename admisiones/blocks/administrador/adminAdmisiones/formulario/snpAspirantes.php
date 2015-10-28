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

$conexion = "admisionesAdmin";
$esteRecursoDBora = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDBora) {

    echo "//Este se considera un error fatal";
    exit;
}

if($cierto==1)
{
    $cadena_sql = $this->sql->cadena_sql("consultarCarreras", $variable);
    $registroCarreras = $esteRecursoDBora->ejecutarAcceso($cadena_sql, "busqueda");
    
    if($_REQUEST['tipoInscripcion']=='nuevos')
    {    
        $cadena_sql = $this->sql->cadena_sql("consultarInscripcionAcaspw", $variable);
        $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    }
    else
    {
        $cadena_sql = $this->sql->cadena_sql("consultarInscripcionTransferencia", $variable);
        $registroInscripcion = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    }
    
    //$valorCodificado="pagina=habilitarEvaluacion";
    $valorCodificado="&action=".$esteBloque["nombre"];
    $valorCodificado.="&opcion=guardarPines";
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
    $atributos["leyenda"] = "";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
    unset($atributos);
    
    $tab = 1;
   
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
   //-------------Control Mensaje-----------------------
   $tipo = 'message';
   $mensaje = "<H3><center>CONSULTA Y MODIFICACIÓN DE SNP <br>".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</H3>";


   $esteCampo = "mensaje";
   $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
   $atributos["etiqueta"] = "";
   $atributos["estilo"] = "centrar";
   $atributos["tipo"] = $tipo;
   $atributos["mensaje"] = $mensaje;
   echo $this->miFormulario->cuadroMensaje($atributos);
   unset($atributos);
    
   //------------------Division para los botones-------------------------
    
    unset($atributos);
    
    echo $this->miFormulario->formulario("fin");
    
    //-------------Fin de Conjunto de Controles----------------------------
    echo $this->miFormulario->marcoAgrupacion("fin");
    
    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");
    
    if(is_array($registroInscripcion))
    {
        $variables ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
        $variables.="&opcion=exportarSnp";
        $variables.="&action=".$esteBloque["nombre"];
        $variables.="&usuario=". $_REQUEST['usuario'];
        $variables.="&tipo=".$_REQUEST['tipo'];
        $variables.="&tipoInscripcion=".$_REQUEST['tipoInscripcion'];
        $variables.="&id_periodo=".$variable['id_periodo'];
        $variables.="&bloque=".$esteBloque["id_bloque"];
        $variables.="&bloqueGrupo=".$esteBloque["grupo"];
        $variables = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variables, $directorio);

        echo "<a href='".$variables."'>";
        //Mensaje letras registro ICFES
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "campoCuadroTexto";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = "<center>Exportar SNP a archivo texto.</center>";
        echo $this->miFormulario->campoMensaje($atributos);
        unset($atributos);
        echo "</a>";
        
        echo "<table id='tablaCarreras'>";
        echo "<thead>
                <tr>
                    <th>Credencial</th>
                    <th>Identificación ICFES</th>
                    <th>Correo electrónico</th>
                    <th>Teléfono</th>
                    <th>Carrera</th>
                    <th>SNP ICFES</th>
                    <th>Editar</th>
               </tr>
            </thead>
            <tbody>";
             
        for($i=0;$i<count($registroInscripcion);$i++)
        {
            if(isset($registroInscripcion[$i]['aspw_id']))
            {
                $id_aspw=$registroInscripcion[$i]['aspw_id'];
                $numeroSnp=$registroInscripcion[$i]['aspw_snp'];
                $credencial=$registroInscripcion[$i]['rba_asp_cred'];
                $numIdenIcfes=$registroInscripcion[$i]['aspw_nro_iden_icfes'];
                $email=$registroInscripcion[$i]['aspw_email'];
                $telefono=$registroInscripcion[$i]['aspw_telefono'];
                $carrera=$registroInscripcion[$i]['aspw_cra_cod'];
                $evento=$registroInscripcion[$i]['des_id'];
            }
            elseif(isset($registroInscripcion[$i]['atr_id']))
            {
                $id_aspw=$registroInscripcion[$i]['atr_id'];
                $numeroSnp=$registroInscripcion[$i]['atr_snp'];
                $credencial=$registroInscripcion[$i]['rba_asp_cred'];
                $numIdenIcfes=$registroInscripcion[$i]['atr_nro_iden_icfes'];
                $email=$registroInscripcion[$i]['atr_email'];
                $telefono=$registroInscripcion[$i]['atr_telefono'];
                $carrera=$registroInscripcion[$i]['atr_cra_cod'];
                $evento=$registroInscripcion[$i]['ti_id'];
            }
            else
            {
                echo "No hay registros...";
            }    
        
            $variable ="pagina=administracion"; //pendiente la pagina para modificar parametro                                                        
            $variable.="&opcion=editarSnp";
            //$variable.="&action=".$esteBloque["nombre"];
            $variable.="&usuario=". $_REQUEST['usuario'];
            $variable.="&id_aspw=".$id_aspw;
            $variable.="&numeroSnp=".$numeroSnp;
            $variable.="&tipo=".$_REQUEST['tipo'];
            $variable.="&evento=".$evento;
            $variable.="&numIdenIcfes=".$numIdenIcfes;
            $variable.="&tipoInscripcion=".$_REQUEST['tipoInscripcion'];
            $variable.="&bloque=".$esteBloque["id_bloque"];
            $variable.="&bloqueGrupo=".$esteBloque["grupo"];
            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
            
            echo "<tr>
                    <td align='center'>".$credencial."</td>
                    <td align='center'>".$numIdenIcfes."</td>
                    <td align='center'>".$email."</td>
                    <td align='center'>".$telefono."</td>
                    <td align='center'>".$carrera."</td>    
                    <td align='center'>".$numeroSnp."</td>
                    <td align='center'><a href='".$variable."'>               
                    <img src='".$rutaBloque."/images/edit.png' width='15px'> 
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

    //------------------Fin Division para las pestañas-------------------------
    //echo $this->miFormulario->division("fin");
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
