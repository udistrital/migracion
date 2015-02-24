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

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

// validamos los datos que llegan
if (isset ( $_REQUEST ['identificacionFinalConsulta'] ) && $_REQUEST ['identificacionFinalConsulta'] != '-1') {
	$identificacion = $_REQUEST ['identificacionFinalConsulta'];
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
$cadena_sql = $this->sql->cadena_sql ( "consultarExperiencia", $arreglo );
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
                    <th>Entidad</th>
                    <th>Cargo</th>                    
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Número de Acta CIARP-UD</th>
			        <th>Fecha Acta CIARP-UD</th>
					<th>Número de Caso de Acta</th>	
                    <th>Duracion</th>
                    <th>Puntaje</th>
                    <th>Modificar</th>
                </tr>
            </thead>
            <tbody>";
	
	for($i = 0; $i < count ( $resultadoExperiencia ); $i ++) {
		$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
		$variable .= "&opcion=modificar";
		$variable .= "&usuario=" . $miSesion->getSesionUsuarioId ();
		$variable .= "&identificacion=" . $resultadoExperiencia [$i] [0];
		$variable .= "&idExperiencia=" . $resultadoExperiencia [$i] [12];
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		$mostrarHtml = "<tr>
                    <td><center>" . $resultadoExperiencia [$i] [0] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [10] . " " . $resultadoExperiencia [$i] [11] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [1] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [2] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [3] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [4] . "</center></td>
                     <td><center>" . $resultadoExperiencia [$i] [5] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [6] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [7] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [8] . "</center></td>
                    <td><center>" . $resultadoExperiencia [$i] [9] . "</center></td>
                    		
                    <td><center>
                    		<a href='" . $variable . "'>                        
                            <img src='" . $rutaBloque . "/images/edit.png' width='15px'> 
                        </a>
                  </center> </td>
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