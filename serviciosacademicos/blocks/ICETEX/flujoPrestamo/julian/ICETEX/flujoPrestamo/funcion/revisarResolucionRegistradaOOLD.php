<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */


$conexion="icetex";


$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}


//Revisa si existe resolucion registrada para el recibo
$datoBusqueda['codigo']=$_REQUEST['valorConsulta'];
$datoBusqueda['anio']=2014;
$datoBusqueda['per']=1;
$cadena_sql = $this->sql->cadena_sql("consultarResolucionCredito",$datoBusqueda);
$registroResolucion = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

$validaResolucion = false;
foreach ($registroResolucion as $reg){
	if(is_numeric($reg[1]) && $reg[1]>0) $validaResolucion = true;
}

return $validaResolucion;




