<?php

include_once($this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/classes/mail/class.phpmailer.php");
include_once($this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/classes/mail/class.smtp.php");

$directorio = $this->miConfigurador->getVariableConfiguracion("host_amazon");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.= $this->miConfigurador->getVariableConfiguracion("enlace");

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

//$conexion = "estructura";
$conexion = "voto";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//echo "<br> este recurso ";var_dump($esteRecursoDB);

$cadenaSql = trim($this->sql->cadena_sql("egresados", ''));
$egresados = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

$enviados = '';
$noEnviados = '';

//Este arreglo se creó temporalmente para probar debe borrarse y dejar la información que viene de la BD
/*$egresados[0]['nombre'] = 'Edwin';
$egresados[0]['correo'] = 'esanchez1988@gmail.com';
$egresados[0]['identificacion'] = '1022348774';
$egresados[1]['nombre'] = 'Edwin';
$egresados[1]['identificacion'] = '1022348774';
$egresados[1]['correo'] = 'esanchez1988@gmail.com';
$egresados[2]['nombre'] = 'Edwin';
$egresados[2]['identificacion'] = '1022348774';
$egresados[2]['correo'] = 'esanchez1988@gmail.com';
*/
$j = $k = 0;
?>

<?php



for ($i = 0; $i < count($egresados); $i++) {
	
	/*$progreso = ((($i + 1) * 100 ) / (count($egresados)));
	echo $progreso;
	echo "<script>cambiarProgreso('".$progreso."');</script>";*/

    $fecha = date("d-M-Y  h:i:s A");

    if (($i % 2000 == 0) && ($i > 0)) {
        sleep(30);
        echo "<br> ---------------------------------------------- <br> enviando correos " . $fecha;
    }

    $tiempo = time();
    $tiempo = $tiempo + (60 * 60 * 2 * 24);

    $enlace = "pagina=confirmarDatos";
    $enlace.= "&nombre=" . $egresados[$i]['nombre'];
    $enlace.= "&correo=" . $egresados[$i]['correo'];
    $enlace.= "&identificacion=" . $egresados[$i]['identificacion'];
    $enlace.= "&tiempo=" . $tiempo;

    $enlace = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlace, $directorio);

    $mail = new phpmailer();
    $mail->From = "sistemaegresados@udistrital.edu.co";
    $mail->FromName = "Sistema de egresados Universidad Distrital Francisco Jose de Caldas";
    $mail->Host = "mail.udistrital.edu.co";
    $mail->Mailer = "smtp";
    $mail->SMTPAuth = true;
    $mail->Username = "condor@udistrital.edu.co";
    $mail->Password = "CondorOAS2012";
    $mail->Timeout = 120;
    $mail->Charset = "utf-8";
    $mail->IsHTML(TRUE);
	$asunto = "Datos de acceso";
	
	$html = "<div align='center'><img src='".$rutaBloque."/css/images/ud.jpg'></div>";
	$html .= "Respetado Destinatario(a) <br><br>";
	
	$html .= "Reciba un cordial saludo, <br><br>";
	
	$html .= "Nos permitimos informarle que su correo electrónico aparece asociado a la información de ".$egresados[$i]['nombre'].", en la base de datos del Sistema de Egresados de la Universidad Distrital Francisco José de Caldas.<br><br>";

	$html .= "Si usted, como propietario de la cuenta de correo, identifica que la información relacionada es errónea, por favor omita el resto del contenido del mensaje.<br><br>";
	
	$html .= "Si por el contrario, la información relacionada coincide con sus nombres y apellidos, lo invitamos a leer la siguiente información de su interés:<br><br> ";
	
	$html .= "<strong><b>Portal de Egresados Universidad Distrital Francisco José de Caldas</b></strong><br><hr><br>";
	$html .= "<blink><b>AVISO LEGAL</b>:</blink> La Universidad Distrital Francisco José de Caldas, informa que a través del presente mensaje solo autoriza el ingreso al Portal de Egresados a ".$egresados[$i]['nombre'].". El no acatamiento de esta autorización puede acarrear la consecuencias legales que se mencionan en la Ley 1273 del 5 de Enero de 2009 y las demás que apliquen. <br><br>";
	$html .= "Respetado egresado:<br><br>";
	$html .= "A través del siguiente enlace podrá registrar las credenciales de acceso al Portal de Egresados de la Universidad Distrital Francisco José de Caldas.<br><br><a href='".$enlace."'>Acceso al portal de egresados</a><br><br>";
	$html .= "La primera vez que acceda al Portal, se le solicitará la autorización para el tratamiento de datos, conforme a los estipulado en la Ley 1581 de 2012 y el decreto reglamentario 1377 de 2013. Además, para favorecer la aplicación de medidas de protección de la identidad, deberá registrar datos de contacto que permitan su posterior verificación.<br><br>";
	$html .= "En caso de alguna observación por favor dirigir un correo a <a href='computo@udistrital.edu.co'>computo@udistrital.edu.co</a> .<br><br>";
	$html .= "Este mensaje se ha generado automaticamente por el servidor de Asignación de clave de acceso al Sistema de Egresados. Se solicita no responder al remitente.<br><br>";
	$html .= "<br><hr><br>";
	$html .= "<font size='1'>Universidad Distrital Francisco José de Caldas PBX: (057) (1) 3239300 - 3238400 Sede principal: Carrera 7 No. 40B - 53</font>";
	$mail->Body = $html;
    $mail->Subject = $asunto;

    $mail->AddAddress($egresados[$i]['correo']);
    //$mail->Send();

    if (!$mail->Send()) {
        $mensaje = "\nLos datos se intentaron enviar al correo electronico: <b>" . $egresados[$i]['correo'] . "</b> de ".$egresados[$i]['identificacion']." pero el envio no fue exitoso<br/>";
        $noEnviados[$j]['identificacion'] = $egresados[$i]['identificacion'];
        $noEnviados[$j]['correo'] = $egresados[$i]['correo'];
        $noEnviados[$j]['nombre'] = $egresados[$i]['nombre'];
        $j++;
    } else {
        $enviados[$k]['identificacion'] = $egresados[$i]['identificacion'];
        $enviados[$k]['correo'] = $egresados[$i]['correo'];
        $enviados[$k]['nombre'] = $egresados[$i]['nombre'];

        $datos['id'] = $egresados[$i]['identificacion'];
        $datos['estado'] = 1;

        $cadenaSql = trim($this->sql->cadena_sql("actualizarEnvio", $datos));
        $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "");

        $k++;
        $mensaje = "\nLos datos fueron enviados al correo electronico <b> " . $egresados[$i]['correo'] . " </b> de ".$egresados[$i]['identificacion']." registrado en el sistema.<br/>";
    }

    echo "<br> " . $mensaje;
    $mail->ClearAllRecipients();
}

//$this->funcion->mostrarResultados($noEnviados, $enviados);
?>

