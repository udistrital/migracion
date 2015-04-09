<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'val-email.php');
require_once(dir_script."class.phpmailer.php");
//LLAMADO DE: coor_form_contacto_doc.php

$mail = new phpmailer();
$mail->From     = $_REQUEST["su_email"];
$mail->FromName = $_REQUEST["nombre"];
$mail->Host     = "mail.udistrital.edu.co";
$mail->Mailer   = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "computo@udistrital.edu.co";
$mail->Password = "capitaloas2011";
$mail->Timeout  = 120;
$mail->Charset  = "utf-8";
$mail->IsHTML(false);

$redir='coor_form_contacto_doc.php';

if(empty($_REQUEST['nombre']))
{
	header("Location: $redir?error_login=19");
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
	$to_mail = $_REQUEST['email'];
	$emails = explode(",",$to_mail);
	//$from .= "From:".$_REQUEST["email"]."\n";
		
	if (isset ($from))
		$from.= "From:" . $_REQUEST["su_email"] ."\r\n"; //from=de
	else
		$from= "From:" . $_REQUEST["su_email"] ."\r\n"; //from=de
	
	$sujeto = $_REQUEST["asunto"];
	$cuerpo .= "Fecha de envio: ".$fecha."\n\n";
	$cuerpo .= $_REQUEST["mensaje"]."\n\n";
	$cuerpo .= "COORDINACIÓN DEL PROYECTO CURRICULAR.";
		
	if( !isset($_FILES['archivo']) ){
		echo 'Ha habido un error, tienes que elegir un archivo<br/>';
	}else{
		$nombre = $_FILES['archivo']['name'];
		$nombre_tmp = $_FILES['archivo']['tmp_name'];
		$tipo = $_FILES['archivo']['type'];
		$tamano = $_FILES['archivo']['size'];
	
		if( $_FILES['archivo']['error'] > 0 ){
			echo 'Error: ' . $_FILES['archivo']['error'] . '<br/>';
		}else{
			if( file_exists( '/usr/local/apache/htdocs/appserv/admisiones/images/'.$nombre) ){
				echo '<br/>El archivo ya existe: ' . $nombre;
			}else{
				move_uploaded_file($nombre_tmp, "/usr/local/apache/htdocs/appserv/admisiones/images/" . $nombre);
				//echo "<br/>Guardado en: " . "/usr/local/apache/htdocs/appserv/admisiones/images/" . $nombre;
			}
		}
	}
	
	header("Location: $redir?error_login=18");
	$rutadoc = "/usr/local/apache/htdocs/appserv/admisiones/images/" . $nombre;	
	$mail->AddAttachment($rutadoc);
	$mail->Body    = $cuerpo;
	$mail->Subject = $sujeto;
	
	//$mail->AddAddress($_POST["email"]);
	
	foreach ($emails as $correos => $correo){
		$mail->AddAddress($correo);
	}
	//$rutadoc = stripslashes($_REQUEST["archivo"]);
	
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