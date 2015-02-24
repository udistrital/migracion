<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
} else {
	
	$miSesion = Sesion::singleton ();
	
	$conexion = "estructura";
	$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
	
	if ($_REQUEST ['contexto_entidad'] == '1') {
		
		$arregloDatos = array (
				$_REQUEST ['idComunicacion'],
				$_REQUEST ['nombre_revista'],
				$_REQUEST ['contexto_entidad'],
				trim($_REQUEST ['pais']='COL'),
				$_REQUEST ['indexacion'],
				$_REQUEST ['ISSN'],
				$_REQUEST ['año'],
				$_REQUEST ['volumen'],
				$_REQUEST ['No'],
				$_REQUEST ['paginas'],
				$_REQUEST ['titulo_articulo'],
				$_REQUEST ['no_autores'],
				$_REQUEST ['autoresUD'],
				$_REQUEST ['fecha_publicacion'],
				$_REQUEST ['numeActa'],
				$_REQUEST ['fechaActa'],
				$_REQUEST ['numeCaso'],
				$_REQUEST ['puntaje'],
				$_REQUEST ['detalleDocencia']
		);
	} else {
		
		$arregloDatos = array (
				$_REQUEST ['idComunicacion'],
				$_REQUEST ['nombre_revista'],
				$_REQUEST ['contexto_entidad'],
				$_REQUEST ['pais'],
				trim($_REQUEST ['indexacionInternacional']),
				$_REQUEST ['ISSN'],
				$_REQUEST ['año'],
				$_REQUEST ['volumen'],
				$_REQUEST ['No'],
				$_REQUEST ['paginas'],
				$_REQUEST ['titulo_articulo'],
				$_REQUEST ['no_autores'],
				$_REQUEST ['autoresUD'],
				$_REQUEST ['fecha_publicacion'],
				$_REQUEST ['numeActa'],
				$_REQUEST ['fechaActa'],
				$_REQUEST ['numeCaso'],
				$_REQUEST ['puntaje'],
				$_REQUEST ['detalleDocencia']
		);
	}
	
	$id_corta = $_REQUEST ['idComunicacion'];	
	$sql = $this->cadena_sql = $this->sql->cadena_sql ( "actualizarComunicacion", $arregloDatos );
	$resultadoComunicacion = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "acceso" );
	
	$arregloLogEvento = array (
			'comunicacion_corta',
			$arregloDatos,
			$miSesion->getSesionUsuarioId (),
			$_SERVER ['REMOTE_ADDR'],
			$_SERVER ['HTTP_USER_AGENT'] 
	);
	
	$argumento = json_encode ( $arregloLogEvento );
	$arregloFinalLogEvento = array (
			$miSesion->getSesionUsuarioId (),
			$argumento 
	);
	
	$cadena_sql = $this->sql->cadena_sql ( "registrarEvento", $arregloFinalLogEvento );
	$registroAcceso = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "acceso" );
	
	if ($resultadoComunicacion) {
		$this->funcion->redireccionar ( 'Actualizo', $id_corta );
	} else {
		$this->funcion->redireccionar ( 'noActualizo', $id_corta );
	}
}

?>