<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
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



if(isset($_REQUEST['funcion'])&&$_REQUEST['funcion']=="registrarIncidente"){
	if(!isset($_REQUEST['categoria'])) {
		echo '<div style="text-align: center"><p><b>';
		echo $this->lenguaje->getCadena("errorCategoria");
		echo "</b></p></div>";
		exit;
	}if(!isset($_REQUEST['tipoUsuario'])) {
		echo '<div style="text-align: center"><p><b>';
		echo $this->lenguaje->getCadena("errorTipoUsuario");
		echo "</b></p></div>";
		exit;
	}if(!isset($_REQUEST['descripcion'])||$_REQUEST['descripcion']=="") {
		echo '<div style="text-align: center"><p><b>';
		echo $this->lenguaje->getCadena("errorDescripcion");
		echo "</b></p></div>";
		exit;
	}
	
}