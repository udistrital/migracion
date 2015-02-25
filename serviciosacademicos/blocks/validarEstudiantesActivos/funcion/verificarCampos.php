<?php

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}


if(!isset($_REQUEST['funcion'])||$_REQUEST['funcion']=="")
	$this->error=true;




/*
if(isset($_REQUEST['nombre'])){
	$cadena_sql = $this->sql->cadena_sql("consultarNombreServicio",$_REQUEST['nombre']);
	$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
	if($registros!=null) {
		echo json_encode(utf8_encode($this->lenguaje->getCadena("errorNombre")));
		exit;
	}
}

switch($_REQUEST["funcion"]){
	case "nuevoServicio":
		break;
	case "obtenerServicios":
		break;
	case "crearServicio":
		if(!isset($_REQUEST['nombre'])||$_REQUEST['nombre']=="") $this->error=true;
		if(!isset($_REQUEST['descripccion'])||$_REQUEST['descripccion']=="") $this->error=true;
		if(!isset($_REQUEST['URICodificada'])||$_REQUEST['URICodificada']=="") $this->error=true;
		if(!isset($_REQUEST['grupo'])||$_REQUEST['grupo']=="") $this->error=true;
		if(!isset($_REQUEST['interno'])||$_REQUEST['interno']=="") $this->error=true;
		break;
	case "desactivarServicio":
		if(!isset($_REQUEST['id'])||$_REQUEST['id']=="") $this->error=true;
		break;
	case "editarServicio":
		if(!isset($_REQUEST['id'])||$_REQUEST['id']=="") $this->error=true;
		break;
	case "actualizarServicio":
		if(!isset($_REQUEST['id'])||$_REQUEST['id']=="") $this->error=true;
		if(!isset($_REQUEST['activo'])||$_REQUEST['activo']=="") $this->error=true;
		if(!isset($_REQUEST['descripccion'])||$_REQUEST['descripccion']=="") $this->error=true;
		if(!isset($_REQUEST['URICodificada'])||$_REQUEST['URICodificada']=="") $this->error=true;
		if(!isset($_REQUEST['grupo'])||$_REQUEST['grupo']=="") $this->error=true;
		if(!isset($_REQUEST['interno'])||$_REQUEST['interno']=="") $this->error=true;
		break;
	case "consultarServicio":
		if(!isset($_REQUEST['id'])||$_REQUEST['id']=="") $this->error=true;
		break;
}
*/