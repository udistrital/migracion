<?php

include_once($this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/classes/mail/class.phpmailer.php");
include_once($this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/classes/mail/class.smtp.php");

$mail = new phpmailer();
$mail->From = "condor@udistrital.edu.co";
$mail->FromName = "Voto Electrónico Universidad Distrital Francisco Jose de Caldas";
$mail->Host = "mail.udistrital.edu.co";
$mail->Mailer = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "condor@udistrital.edu.co";
$mail->Password = "CondorOAS2012";
$mail->Timeout = 120;
$mail->Charset = "utf-8";
$mail->IsHTML(false);


$fecha = date("d-M-Y  h:i:s A");
$comen = "Mensaje generado automaticamente por el servidor de la Oficina Asesora de Sistemas.\n";
$comen.= "Este es su usuario y clave para ingresar al Banco de proveedores de la Universidad Distrital.\n\n";

$sujeto = "Datos de Acceso";
$cuerpo = "Fecha de envio: " . $fecha . "\n\n";

$cuerpo.="Gracias por realizar la actualización de datos para el proceso de votaciones de la Universidad Distrital Francisco Jose de Caldas \n\n";
$cuerpo.="Por favor tenga en$ cuenta los siguientes datos de acceso para el día de las votaciones:\n\n";
$cuerpo.="Usuario:        " . $usuario . "\n";
$cuerpo.="Clave Acceso:   " . $clave . "\n";

$cuerpo.=$comen . "\n\n";

$mail->Body = $cuerpo;
$mail->Subject = $sujeto;
$mail->AddAddress($correo);

if (!$mail->Send()) {
    $mensaje = "Los datos se intentaron enviar al correo electronico: <b>{$correo}</b> pero el envio no fue exitoso<br/>";
} else {
    $mensaje = "Estos datos fueron enviados al correo electronico: <b>{$correo}</b><br/>";
}
$mail->ClearAllRecipients();
?>
