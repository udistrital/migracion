<?php
require_once('dir_relativo.cfg');
require_once(dir_script."class.phpmailer.php");
//LLAMADO DE: coor_form_contacto_doc.php

$redir='dec_frm_contacto_coor.php';

$mail = new phpmailer();
$mail->FromName = $_REQUEST["su_email"];
$mail->FromName = $_REQUEST["su_email"];
$mail->Host     = "mail.udistrital.edu.co";
$mail->Mailer   = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "computo@udistrital.edu.co";
$mail->Password = "oas20021";
$mail->Timeout  = 120;
$mail->Charset  = "utf-8";
$mail->IsHTML(false);

if(empty($_REQUEST['su_email']))
{
	header("Location: $redir?error_login=10");
}
elseif(empty($_REQUEST['email']))
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
	$to = "To:".$_REQUEST["email"]."\n";
	$from = "From:".$_REQUEST["su_email"]."\n";
	$sujeto = $_REQUEST["asunto"];
	$cuerpo= "Fecha de envio: ".$fecha."\n\n";
	$cuerpo.= "Nombre: " . $_REQUEST["su_email"] . "\n";
	$cuerpo.= $_REQUEST["mensaje"]."\n\n";
	$cuerpo.= "DECANO DE LA FACULTAD.";
	
	$mail->Body    = $cuerpo;
	$mail->Subject = $sujeto;
	$mail->AddAddress($_REQUEST["email"]);
		
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