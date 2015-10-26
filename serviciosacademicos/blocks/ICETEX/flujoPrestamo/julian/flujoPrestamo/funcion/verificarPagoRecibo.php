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

/*if($_REQUEST["modulo"]==51||$_REQUEST["modulo"]==52){
	$this->notificacionRecibos();
	
	exit;
}*/

$datoBusqueda['codigo'] = $_REQUEST['valorConsulta'];
if(!isset($_REQUEST['periodo'])){
    //consultar periodo actual
	$cadena_sqlD = $this->sql->cadena_sql("periodoActual",'');
	$regPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");
	$_REQUEST["periodo"] = $regPeriodo[0]['periodo'];
}

$datoBusqueda['anio'] = substr($_REQUEST['periodo'], 0, 4);
$datoBusqueda['per'] = substr($_REQUEST['periodo'], 5, 1);

//Revisa si existen recibos creados en el año y periodo en curso
$cadena_sql = $this->sql->cadena_sql("consultarRecibosCreados",$datoBusqueda);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoRecibo");
	echo "</b></p></div>";
	exit;
}

//Revisa si algun recibo se ha pagado
$validaPago = false;
foreach ($registros as $reg){
	if($reg[1]=='S') $validaPago = true;
}




//Si se pago alguno el estado a actualizar es el 2
if($validaPago ==true) {
	$this->estado = 2;
	//Actualiza Estado del flujo
	$this->actualizarEstadoFlujo();
	echo json_encode(true);
}else{
	echo '<br><div style = "font-style:italic;text-align: center;"><b>'.$this->lenguaje->getCadena("esperaPago")."</b></div>";
	exit;
}



return true;




