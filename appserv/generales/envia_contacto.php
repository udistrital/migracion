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

			
	/*$rutadoc = stripslashes($_REQUEST["archivo"]);	
	;*/
		
	
	if( !isset($_FILES['archivo']) ){
		echo 'Ha habido un error, tienes que elegir un archivo<br/>';
		//echo '<a href="index.html">Subir archivo</a>';
	}else{
		$nombre = $_FILES['archivo']['name'];
		$nombre_tmp = $_FILES['archivo']['tmp_name'];
		$tipo = $_FILES['archivo']['type'];
		$tamano = $_FILES['archivo']['size'];
	
		if( $_FILES['archivo']['error'] > 0 ){
			echo 'Error: ' . $_FILES['archivo']['error'] . '<br/>';
		}else{
			/*echo 'Nombre: ' . $nombre . '<br/>';
			echo 'Tipo: ' . $tipo . '<br/>';
			echo 'Tamaño: ' . ($tamano / 1024) . ' Kb<br/>';
			echo 'Guardado en: ' . $nombre_tmp;*/
	
			if( file_exists( '/usr/local/apache/htdocs/appserv/admisiones/images/'.$nombre) ){
				echo '<br/>El archivo ya existe: ' . $nombre;
			}else{
				move_uploaded_file($nombre_tmp, "/usr/local/apache/htdocs/appserv/generales/archivoCorreos/" . $nombre);
				//echo "<br/>Guardado en: " . "/usr/local/apache/htdocs/appserv/admisiones/images/" . $nombre;
			}
		}	
	}
	
	header("Location: $redir?error_login=18");
	$rutadoc = "/usr/local/apache/htdocs/appserv/generales/archivoCorreos/" . $nombre;
	$mail->AddAttachment($rutadoc);	
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
