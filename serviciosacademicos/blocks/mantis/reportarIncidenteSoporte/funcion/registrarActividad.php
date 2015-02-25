<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

$conexion="mantis";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}


$parametros['usuario'] = $_REQUEST['usuario'];
$parametros['mantis'] = $mantis;


$cadena_sql = $this->sql->cadena_sql("resgistroLog",$parametros);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql);


if($registros!=true){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoRegistroLog");
	echo "</b></p></div>";
	exit;
}



