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

if(!isset($_REQUEST['periodo'])){
	//consultar periodo actual
	$cadena_sqlD = $this->sql->cadena_sql("periodoActual",'');
	$regPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");
	$_REQUEST["periodo"] = $regPeriodo[0]['PERIODO'];
}

//Asigna Variables
$parametros = array();
$parametros['codigo'] = $_REQUEST['valorConsulta'];
$parametros["anio"] = substr($_REQUEST['periodo'], 0, 4);
$parametros["per"] = substr($_REQUEST['periodo'], 5, 1);

$cadena_sqlD = $this->sql->cadena_sql("consultarIdentificacionCodigo",$parametros);

$registrosD = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");
$identificacion = 0;
if(!is_null($registrosD)){

	$identificacion = $registrosD[0][0];
}

//Asigna el estado por defecto al cual se va acambiar
$this->estado = 1;

//Actualiza Estado del flujo
$this->actualizarEstadoFlujo();

$tema='Solicitud de Crédito ICETEX';
$cuerpo ='El estudiante de código '.$_REQUEST['valorConsulta'].'('.$identificacion.') Ha realizado una soliciud de credito ICETEX<br>';
$cuerpo .=' Verificar si tiene el credito aprobado y registrarlo en el sistema academico';
$temaRegistro = 'SOLICITUD CREDITO';
$this->notificarBienestar($cuerpo , $tema, '',$temaRegistro);

echo json_encode(true);



