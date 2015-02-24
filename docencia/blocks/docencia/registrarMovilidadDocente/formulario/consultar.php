<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$miSesion = Sesion::singleton ();

$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );

$nombreFormulario = $esteBloque ["nombre"];

$conexion = "docencia";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

// validamos los datos que llegan
if (isset ( $_REQUEST ['docenteConsulta'] ) && $_REQUEST ['docenteConsulta'] != '') {
	$identificacion = $_REQUEST ['docenteConsulta'];
} else {
	$identificacion = '';
}

if (isset ( $_REQUEST ['facultad'] ) && $_REQUEST ['facultad'] != '-1') {
	$facultad = $_REQUEST ['facultad'];
} else {
	$facultad = '';
}

if (isset ( $_REQUEST ['proyecto'] ) && $_REQUEST ['proyecto'] != '-1') {
	$proyecto = $_REQUEST ['proyecto'];
} else {
	$proyecto = '';
}


$arreglo = array (
		$identificacion,
		$facultad,
		$proyecto,
		$proyecto 
);

{
	$tab = 1;
	
	include_once("core/crypto/Encriptador.class.php");
	$cripto=Encriptador::singleton();
	$valorCodificado="&opcion=nuevo";
	$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
	$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
	$valorCodificado=$cripto->codificar($valorCodificado);
	
	// ---------------Inicio Formulario (<form>)--------------------------------
	$atributos ["id"] = $nombreFormulario;
	$atributos ["tipoFormulario"] = "multipart/form-data";
	$atributos ["metodo"] = "POST";
	$atributos ["nombreFormulario"] = $nombreFormulario;
	$verificarFormulario = "1";
	echo $this->miFormulario->formulario ( "inicio", $atributos );
	
	// ------------------Division para los botones-------------------------
	$atributos ["id"] = "botones";
	$atributos ["estilo"] = "marcoBotones";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control Boton-----------------------
	$esteCampo = "botonVolver";
	$atributos ["id"] = $esteCampo;
	$atributos ["tabIndex"] = $tab ++;
	$atributos ["tipo"] = "boton";
	$atributos ["estilo"] = "";
	$atributos ["verificar"] = ""; // Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
	$atributos ["tipoSubmit"] = "jquery"; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
	$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
	$atributos ["nombreFormulario"] = $nombreFormulario;
	echo $this->miFormulario->campoBoton ( $atributos );
	unset ( $atributos );
	// -------------Fin Control Boton----------------------
	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
	
	// -------------Control cuadroTexto con campos ocultos-----------------------
	// Para pasar variables entre formularios o enviar datos para validar sesiones
	$atributos ["id"] = "formSaraData"; // No cambiar este nombre
	$atributos ["tipo"] = "hidden";
	$atributos ["obligatorio"] = false;
	$atributos ["etiqueta"] = "";
	$atributos ["valor"] = $valorCodificado;
	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	unset ( $atributos );
	
	echo $this->miFormulario->formulario ( "fin" );
}


$cadena_sql = $this->sql->cadena_sql ( "consultar", $arreglo );
$resultadoExperiencia = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
 
if ($resultadoExperiencia) {
	// -----------------Inicio de Conjunto de Controles----------------------------------------
	$esteCampo = "marcoDatosResultadoParametrizar";
	$atributos ["estilo"] = "jqueryui";
	$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
	// echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
	unset ( $atributos );
	
	echo "<table id='tablaTitulos'>";
	
	echo "<thead>
                <tr>
                   <th>Identificación</th>
                    <th>Nombres</th>
                    <th>Dependencia asume Tiquetes</th>
                    <th>Entidad asume Tiquetes</th>
                    <th>Dependencia asume Incripción</th>
                    <th>Entidad asume Incripción</th>
                    <th>Dependencia asume Viáticos</th>
                    <th>Entidad asume Viáticos</th>
                    <th>Archivo Ponencia</th>
                    <th>Archivo Pretentación Evento </th>
                    <th>Modificar</th>
                </tr>
            </thead>
            <tbody>";
	
	for($i = 0; $i < count ( $resultadoExperiencia ); $i ++) {
		$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
		$variable .= "&opcion=modificar";
		$variable .= "&usuario=" . $miSesion->getSesionUsuarioId ();
		$variable .= "&id_movilidad=" . $resultadoExperiencia [$i] ['id_movilidad'];
		$variable .= "&id_docente=" . $resultadoExperiencia [$i] ['id_docente'];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
                
                
                if(isset($resultadoExperiencia [$i] ['tiquetesdep']) && $resultadoExperiencia[$i]['tiquetesdep'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarDependencia", $resultadoExperiencia[$i]['tiquetesdep'] );
                        $resultadoDep = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $tiquetesDep = $resultadoDep[0]['nombre_facultad'];
                    }else
                        {
                            $tiquetesDep = "No Registra";
                        }
                
                if(isset($resultadoExperiencia [$i] ['inscripciondep']) && $resultadoExperiencia[$i]['inscripciondep'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarDependencia", $resultadoExperiencia[$i]['inscripciondep'] );
                        $resultadoDep = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $inscripcionDep = $resultadoDep[0]['nombre_facultad'];
                    }else
                        {
                            $inscripcionDep = "No Registra";
                        }
		
                if(isset($resultadoExperiencia [$i] ['viaticosdep']) && $resultadoExperiencia[$i]['viaticosdep'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarDependencia", $resultadoExperiencia[$i]['viaticosdep'] );
                        $resultadoDep = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $viaticosDep = $resultadoDep[0]['nombre_facultad'];
                    }else
                        {
                            $viaticosDep = "No Registra";
                        }
                
                if(isset($resultadoExperiencia [$i] ['tiquetesent']) && $resultadoExperiencia[$i]['tiquetesent'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarUniversidad", $resultadoExperiencia[$i]['tiquetesent'] );
                        $resultadoEnt = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $tiquetesEnt = $resultadoEnt[0]['nombre_universidad'];
                    }else
                        {
                            $tiquetesEnt = "No Registra";
                        }
                
                if(isset($resultadoExperiencia [$i] ['inscripcionent']) && $resultadoExperiencia[$i]['inscripcionent'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarUniversidad", $resultadoExperiencia[$i]['inscripcionent'] );
                        $resultadoEnt = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $inscripcionEnt = $resultadoEnt[0]['nombre_universidad'];
                    }else
                        {
                            $inscripcionEnt = "No Registra";
                        }
		
                if(isset($resultadoExperiencia [$i] ['viaticosent']) && $resultadoExperiencia[$i]['viaticosent'] != '')
                    {
                        $cadena_sql = $this->sql->cadena_sql ( "consultarUniversidad", $resultadoExperiencia[$i]['viaticosent'] );
                        $resultadoEnt = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
                        
                        $viaticosEnt = $resultadoEnt[0]['nombre_universidad'];
                    }else
                        {
                            $viaticosEnt = "No Registra";
                        }
		
		$mostrarHtml = "<tr>
                    <td><center>" . $resultadoExperiencia [$i] ['id_docente'] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] ['informacion_nombres'] . " " . $resultadoExperiencia [$i] ['informacion_apellidos'] . "</center></td>
                    <td><center>" . $tiquetesDep . "</center></td>
                    <td><center>" . $tiquetesEnt . "</center></td>
                    <td><center>" . $inscripcionDep . "</center></td>
                    <td><center>" . $inscripcionEnt . "</center></td>
                    <td><center>" . $viaticosDep . "</center></td>                    
                    <td><center>" . $viaticosEnt . "</center></td>
                    <td><center><A HREF=\"" . $resultadoExperiencia [$i] ['ruta_ponencia'] ."\">".$resultadoExperiencia [$i] ['nombre_ponencia']."</A></center></td>
                    <td><center><A HREF=\"" . $resultadoExperiencia [$i] ['ruta_aceptacion']."\">".$resultadoExperiencia [$i] ['nombre_aceptacion']."</A></center></td>
                    <td>
                        <center>
                            <a href='" . $variable . "'>                        
                                <img src='" . $rutaBloque . "/images/edit.png' width='15px'> 
                            </a>
                        </center> 
                    </td>
                </tr>";
		echo $mostrarHtml;
		unset ( $mostrarHtml );
		unset ( $variable );
	}
	
	echo "</tbody>";
	
	echo "</table>";
	
	// Fin de Conjunto de Controles
	// echo $this->miFormulario->marcoAgrupacion("fin");
} else {
	$nombreFormulario = $esteBloque ["nombre"];
	include_once ("core/crypto/Encriptador.class.php");
	$cripto = Encriptador::singleton ();
	$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" ) . "/imagen/";
	
	$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );
	
	$tab = 1;
	// ---------------Inicio Formulario (<form>)--------------------------------
	$atributos ["id"] = $nombreFormulario;
	$atributos ["tipoFormulario"] = "multipart/form-data";
	$atributos ["metodo"] = "POST";
	$atributos ["nombreFormulario"] = $nombreFormulario;
	$verificarFormulario = "1";
	echo $this->miFormulario->formulario ( "inicio", $atributos );
	
	$atributos ["id"] = "divNoEncontroEgresado";
	$atributos ["estilo"] = "marcoBotones";
	// $atributos["estiloEnLinea"]="display:none";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control Boton-----------------------
	$esteCampo = "noEncontroProcesos";
	$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
	$atributos ["etiqueta"] = $this->lenguaje->getCadena ( $esteCampo );
	$atributos ["estilo"] = "centrar";
	$atributos ["tipo"] = 'error';
	$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
	;
	echo $this->miFormulario->cuadroMensaje ( $atributos );
	unset ( $atributos );
	
	$valorCodificado = "pagina=" . $miPaginaActual;
	$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
	$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
	$valorCodificado = $cripto->codificar ( $valorCodificado );
	// -------------Fin Control Boton----------------------
	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
	// ------------------Division para los botones-------------------------
	$atributos ["id"] = "botones";
	$atributos ["estilo"] = "marcoBotones";
	echo $this->miFormulario->division ( "inicio", $atributos );
	

	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
	
	// -------------Control cuadroTexto con campos ocultos-----------------------
	// Para pasar variables entre formularios o enviar datos para validar sesiones
	$atributos ["id"] = "formSaraData"; // No cambiar este nombre
	$atributos ["tipo"] = "hidden";
	$atributos ["obligatorio"] = false;
	$atributos ["etiqueta"] = "";
	$atributos ["valor"] = $valorCodificado;
	echo $this->miFormulario->campoCuadroTexto ( $atributos );
	unset ( $atributos );
	
	// Fin del Formulario
	echo $this->miFormulario->formulario ( "fin" );
}

?>