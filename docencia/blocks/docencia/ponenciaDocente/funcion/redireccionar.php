<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
} else {

	$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( "pagina" );
	switch ($opcion) {

		case "Inserto" :
				
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=confirma";
				
			if ($valor != "") {
				$variable .= "&docente=" . $valor;
			}
			break;

		case "noInserto" :
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=error";
			if ($valor != "") {
				$variable .= "&docente=" . $valor;
			}
			break;

		case "Actualizo" :
				
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=mensajeActualizar";
			if ($valor != "") {
				$variable .= "&video=" . $valor;
				$variable .= "&docente=" . $_REQUEST["identificacion"];
			}
			break;

		case "noActualizo" :
				
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=mensajenoActualizar";
			if ($valor != "") {
				$variable .= "&video=" . $valor;
				$variable .= "&docente=" . $_REQUEST["identificacion"];
			}
			break;

		case "maxPonencias" :
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=maxPonencias";
			if ($valor != "") {
				$variable .= "&docente=" . $valor;
			}
			break;

		case "maxPonenciasNal" :
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=maxPonenciasNal";
			if ($valor != "") {
				$variable .= "&docente=" . $valor;
			}
			break;

		case "maxPonenciasInternal" :
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=mensaje";
			$variable .= "&mensaje=maxPonenciasInternal";
			if ($valor != "") {
				$variable .= "&docente=" . $valor;
			}
			break;

		case "confirmarNuevo" :
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&opcion=confirmar";
			if ($valor != "") {
				$variable .= "&id_sesion=" . $valor;
			}
			break;

		case "paginaPrincipal" :
			$variable = "pagina=" . $miPaginaActual;
			break;

		default :
			$variable = "pagina=" . $miPaginaActual;
			break;
	}

	foreach ( $_REQUEST as $clave => $valor ) {
		unset ( $_REQUEST [$clave] );
	}

	$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
	$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $variable );

	$_REQUEST [$enlace] = $variable;
	$_REQUEST ["recargar"] = true;
}

?>