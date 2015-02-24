<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'val-email.php');
require_once(dir_script."class.phpmailer.php");
//LLAMADO DE: coor_form_contacto_doc.php

$redir='coor_form_contacto_doc.php';

$mail = new phpmailer();
$mail->From     = $_REQUEST["email"];
$mail->FromName = $_REQUEST["su_email"];
$mail->Host     = "mail.udistrital.edu.co";
$mail->Mailer   = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "computo@udistrital.edu.co";
$mail->Password = "2010oas";
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
	$from .= "From:".$_REQUEST["email"]."\n";
	$sujeto = $_REQUEST["asunto"];
	$cuerpo .= "Fecha de envio: ".$fecha."\n\n";
	$cuerpo .= $_REQUEST["mensaje"]."\n\n";
	$cuerpo .= "ASESOR UD.";
   
	$mail->Body    = $cuerpo;
	$mail->Subject = $sujeto;
	$mail->AddAddress($_POST["email"]);
	$rutadoc = stripslashes($_REQUEST["archivo"]);
	$mail->AddAttachment($rutadoc);
	
	if(!$mail->Send())
	{
		header("Location: $redir?error_login=16");
	}
	else
	{
		header("Location: $redir?error_login=18");
	}
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
}
?>