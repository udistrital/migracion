<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'val-email.php');
//require_once(dir_script."class.phpmailer.php");

if (isset($_REQUEST['action'])) {
	$dest = $_REQUEST['email'];
	$head = "From: ".$_REQUEST['email']."\r\n";
	$head.= "To: ".$_REQUEST['pemail']."\r\n";
	$subj = "Subject: ".$_REQUEST['asunto']."\r\n";
	// Ahora creamos el cuerpo del mensaje
	$msg = "------------------------------- \n";
	$msg.= "         Comentarios            \n";
	$msg.= "------------------------------- \n";
	$msg.= "Nombre: ".$_REQUEST['nombre']."\n";
	$msg.= "Email:  ".$_REQUEST['email']."\n";
	$msg.= "Asunto: ".$_REQUEST["asunto"]."\n";
	$msg.= "Fecha :    ".date("D, d M Y")."\n";
	$msg.= "Hora: ".date("h:i:s a ")."\n";
	//$msg.= "Aexo: ".$_REQUEST['archivo']."\n";
	//$msg.= "IP:   ".$REMOTE_ADDR."\n";
	$msg.= "------------------------------- \n\n";
	
	$msg.= $_REQUEST['mensaje']."\n\n";
	$msg.= "------------------------------- \n";
	$msg.= " Mensaje enviado por  \n";
	
	// Enviamos el mensaje
 
	if (mail($dest,$subj, "Comentarios", $msg, $head)) {
		echo "prueba";
		$aviso = "Su mensaje fue enviado.";
	}
	 else
	 {
		echo "prueba1";
		$aviso = "Error de envío.";
	 }
}
?>