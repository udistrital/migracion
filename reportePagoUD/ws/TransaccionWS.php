<?php

include_once("../aplicativo/ProcesadorServicio.php");


ini_set ( 'soap.wsdl_cache_enabled', '0' );

$servicio = new SoapServer ( 'TransaccionWS.wsdl');
$servicio->setClass ("ProcesadorServicio");

try {
	$servicio->handle ();
} catch ( Exception $e ) {
	$servicio->fault ( 'Sender', $e->getMessage () );
}


?>
