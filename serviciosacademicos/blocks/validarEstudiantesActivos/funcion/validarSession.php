<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 *  La ruta absoluta del bloque está definida en $this->ruta
 */
include_once("core/auth/Sesion.class.php");

$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}

if(!isset($esteBloque ["nombre"])) 
	$esteBloque ["nombre"] = $_REQUEST['bloqueNombre'];
$nombreClaseSql = "Sql" . $esteBloque ["nombre"];
$sql = new $nombreClaseSql ();

$nombreClaseLenguaje = "Lenguaje" . $esteBloque ["nombre"];
$lenguaje = new $nombreClaseLenguaje ();

if(isset($_REQUEST['sessionId'])){
	$miSesion=Sesion::singleton();
	$miSesion->borrarSesionExpirada();
	$sessionId = $_REQUEST['sessionId'];
	$parametro['variable']='idUsuario';
	$parametro['sesionId']=$sessionId;
	$cadenaSql = $sql->cadena_sql ( "buscarValorSesion", $parametro );
	$registro = $esteRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
	if($registro==null){
		echo $this->lenguaje->getCadena("errorUsuario");
		exit;
	}
}else{
	echo $this->lenguaje->getCadena("errorUsuario");
	exit;
}


