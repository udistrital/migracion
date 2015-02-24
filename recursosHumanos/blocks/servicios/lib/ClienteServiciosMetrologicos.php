<?php
	require_once('nusoap.php');
	$client = new soapclientw('http://serviciospub.sic.gov.co/Sic/WebServices/ServiciosMetrologicos.php?wsdl', true);
	$result = $client->call('serviciosmetrologia', array('ano_radi' => 10,'nume_radi' => 120247,'cont_radi' =>' '));
	echo "<pre>";print_r($result);
	
?>
