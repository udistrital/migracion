<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'val-email.php');
require_once(dir_script."class.phpmailer.php");

$mail = new phpmailer();
$mail->From     = $_REQUEST['email'];
$mail->FromName = $_REQUEST['nombre'];
$mail->Host     = "mail.udistrital.edu.co";
$mail->Mailer   = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "condor@udistrital.edu.co";
$mail->Password = "CondorOAS2012";
$mail->Timeout  = 120;
$mail->Charset  = "utf-8";
$mail->IsHTML(false);

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if(empty($_REQUEST['nombre']))
{
	header("Location: $redir?error_login=19");
}
elseif(empty($_REQUEST['pemail']))
{
       header("Location: $redir?error_login=15");
}
elseif(empty($_REQUEST['asunto']))
{
       header("Location: $redir?error_login=20");
}
else
{
	$fecha = date("d-M-Y g:i:s A");
	$to_mail = $_REQUEST['pemail'];			//to=para
		
	$emails = explode(",",$to_mail);
		
	if (isset ($from))
		$from.= "From:" . $_REQUEST["email"] ."\r\n"; //from=de
	else
		$from= "From:" . $_REQUEST["email"] ."\r\n"; //from=de
		
	$sujeto= $_REQUEST["asunto"];
	$cuerpo= "Fecha de envio: " . $fecha . "\n";
		
	$cuerpo.= "Nombre: " . $_REQUEST["nombre"] . "\n";
	$cuerpo.= "" . "\n";
	$cuerpo.= $_REQUEST["mensaje"] . "\n";	

	$rutadoc = stripslashes($_REQUEST["archivo"]);		
	$mail->AddAttachment($rutadoc);
	
	header("Location: $redir?error_login=18");
		
	
	$mail->Body    = $cuerpo;	
	$mail->Subject = $sujeto;
	
	// Bucle para enviar correos a varias direcciones
	foreach ($emails as $correos => $correo){
		$mail->AddAddress($correo);
	} 
				
	
	 
   	if(!$mail->Send())
   	{
   		header("Location: $redir?error_login=16");
   	}
	else
	{
		header("Location: $redir?error_login=18");
	}
    	$mail->ClearAllRecipients();
}
?>
