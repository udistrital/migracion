<?php
$rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";

include_once($rutaClases."/mail/class.phpmailer.php");
include_once($rutaClases."/mail/class.smtp.php");

$mail = new PHPMailer();     
			
//configuracion de cuenta de envio
$mail->Host     = "mail.udistrital.edu.co";
$mail->Mailer   = "smtp";
$mail->SMTPAuth = true;
$mail->Username = "evaldocente@udistrital.edu.co";
$mail->Password = "evaldocente2014";
$mail->Timeout  = 1200;
$mail->Charset  = "utf-8";
$mail->IsHTML(false);

//remitente
$fecha = date("d-M-Y g:i:s A");
$to_mail=explode(",",$_REQUEST['para']);
$to_mail1=isset($to_mail)?$to_mail:'';

$i=0;
while (isset($to_mail1[$i])) {
    $mail->AddAddress($to_mail1[$i]);
$i++;
}
$mail->From     = 'evaldocente@udistrital.edu.co';
$mail->FromName = 'OFICINA DE EVALUACIÓN DOCENTE';
$mail->Subject = $_REQUEST['asunto'];
$contenido=$_REQUEST['contenido']."\n";
$contenido.= "\nEste mesaje ha sido enviado desde el módulo de evaluación docente. Favor no responder!!!";
$mail->Body=$contenido;
//$mail->AddAddress($to_mail1);
if(!$mail->Send())
{
        ?>
        <script language='javascript'>
        alert('Error! El mensaje no pudo ser enviado!');
        </script>
        <?
        $this->funcion->redireccionar ("paginaPrincipal");
}
else
{
    ?>
    <script language='javascript'>
    alert('El mensaje se envió correctamente!');
    </script>
    <?
    $this->funcion->redireccionar ("paginaPrincipalAdministrador");
}    


$mail->ClearAllRecipients();
$mail->ClearAttachments();
?>

