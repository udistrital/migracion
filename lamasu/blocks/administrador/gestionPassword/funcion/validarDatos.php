<?php
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}

$conexion1="wconexionclave";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion1);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['usuario_id']=$_REQUEST['nombreUsuario'];
$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['codigoEstudiante']=$registro[0]['pus_usuario'];
$variable['documentoActual']=$_REQUEST['documentoActual'];

//Consulta los datos de los docentes
$cadena_sql = $this->sql->cadena_sql("datosEmpleados", $variable);
$resultado=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
if(!is_array($resultado))
{    
     //Consulta los datos delos empleados
    $cadena_sql = $this->sql->cadena_sql("datosDocentes", $variable);
    $resultado=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
   
    if(!is_array($resultado))
    {

	//Consulta los datos de los estudiantes
	    $cadena_sql = $this->sql->cadena_sql("datosEstudiantes", $variable);
	    $resultado=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
        if(!is_array($resultado))
        {
            //Consulta los datos de los asistentes
            $cadena_sql = $this->sql->cadena_sql("datosAsistentes", $variable);
            $resultado=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
            if(!is_array($resultado))
            {
                //Consulta los datos de los egresados
                $cadena_sql = $this->sql->cadena_sql("datosEgresados", $variable);
                $resultado=$esteRecursoDBORA->ejecutarAcceso($cadena_sql, "busqueda");
            }    
        }    
    }
}

$cierto=0;
$variablaVacia=0;
$i=1;
//Recorre todo los que me trae el request;
foreach ($_REQUEST as $clave => $valor)
{
    //echo $clave ."=>". $valor."<br>";
    $cadena = $clave;
    $buscar = "valores";
    $resultadoValores = strpos($cadena, $buscar);
    
    //Rescata solamente lo que diga valores
    if($resultadoValores !== FALSE)
    {
        if($valor=='Sin registro')
        {
            $valor='';
        }
        if($resultado[0][0]==$valor)
        {
            $cierto=$i;
        }
        if($resultado[0][2]==$valor)
        {
            $cierto=$i;
        }
        if($resultado[0][3]==$valor)
        {
            $cierto=$i;
        }
        if($resultado[0][5]==$valor)
        {
            $cierto=$i;
        }
        
        //Meto los resultados en un arreglo
        $variablaVacia=$variablaVacia.','.$cierto;
        /* @var $_REQUEST type */
        //$variable['preguntaNumero']=$i;
    $i++;    
    }
    
}

$valor=$variablaVacia;
$valores = explode(",", $valor);

if($valores[1]==1 && $valores[2]==2 && $valores[3]==3)
{
    $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";

    include_once($rutaClases."/mail/class.phpmailer.php");
    include_once($rutaClases."/mail/class.smtp.php");
    
    unset($variable);
    $fecha=date("m/d/Y");
    $fechaHoy=strtotime($fecha);
    
    $miCodificador=Encriptador::singleton();
    //$usuario ="159357645";
    //$identificacion = $_SESSION['usuario_login'];
    $indiceSaraLaverna = $this->miConfigurador->getVariableConfiguracion("host")."/lamasu/index.php?";
    $tipo=1;
    $tokenCondor = "l4v3rn42013!r3cup3raci0ncl4v3s2013";
    $tokenCondor = $miCodificador->codificar($tokenCondor);
    $opcion="temasys=";
    $variable="gestionPassword&pagina=recuperaClaves";
    $variable.="&usuario=".$miSesion->getSesionUsuarioId();
    $variable.="&tipo=".$tipo;
    $variable.="&token=".$tokenCondor;
    $variable.="&opcionPagina=recuperaPassword";
    $variable.="&mail=".$resultado[0][5];
    $variable.="&documentoActual=".$_REQUEST['documentoActual'];
    $variable.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
    $variable.="&nombre=".$resultado[0][1];
    $variable.="&fechaHoy=".$fechaHoy;
    //$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
    $variable=$miCodificador->codificar($variable);
    $enlaceLaverna=$indiceSaraLaverna.$opcion.$variable;
        
    if($resultado[0][5]=='')
    {
        ?>
        <script language='javascript'>
        alert('La dirección de correo electrónico no es válida.!');
        </script>
        <?
        $this->funcion->redireccionar ("paginaPrincipal");
    }    
    else
    {
    
        $enlace=$enlaceLaverna;

        $mail = new PHPMailer();     

        //configuracion de cuenta de envio
        $mail->Host     = "mail.udistrital.edu.co";
        $mail->Mailer   = "smtp";
        $mail->SMTPAuth = true;
        $mail->Username = "condor@udistrital.edu.co";
        $mail->Password = "CondorOAS2012";
        $mail->Timeout  = 1200;
        $mail->Charset  = "utf-8";
        $mail->IsHTML(false);

        //remitente
        $fecha = date("d-M-Y g:i:s A");
        $to_mail=explode(",",$resultado[0][5]);
        $to_mail1=isset($to_mail)?$to_mail:'';
        $i=0;
        while(isset($to_mail1[$i])){
            //echo $to_mail1[$i];
            $mail->AddAddress($to_mail1[$i]);
        $i++;
        }
        //$mail->AddAddress($to_mail1);
        if(isset($resultado[0][7]))
        {    
            $to_mail2=$resultado[0][7];
            //$to_mail2='jesusneirag@hotmail.com';
            $mail->AddCC($to_mail2);
        }
        $to_mail3 = 'recibos@correo.udistrital.edu.co';//Clave del correo recibos2012
        $mail->AddBCC($to_mail3);
        $mail->From='evaldocente@udistrital.edu.co';
        $mail->FromName='UNIVERSIDAD DISTRITAL FRANCISCO JOSÉ DE CALDAS';
        $mail->Subject=" Restaución de la contraseña del sistema de Gestión Académica";
        $contenido="Fecha de envio: " . $fecha . "\n";
        $contenido.= "Señor usuario, se ha recibido una solicitud de restauración de contraseña del usuario: ".$_REQUEST['nombreUsuario'].". Este mensaje ha sido generado automáticamente. Favor no responder. \n";
        $contenido.= "\nPara restablecer su contraseña, haga clic en el enlace siguiente (o copie y pegue la URL en su navegador):.\n";
        $contenido.= $enlace;
        $contenido.= "\nEste enlace caduca a las 11:59:59 p.m. del día que fue enviado este correo.\n \n";
        $mail->Body=$contenido;
        //$mail->AddAddress($to_mail1);
        if(!$mail->Send())
        {
                ?>
                <script language='javascript'>
                alert('Error! El mensaje no pudo ser enviado, es posible que la dirección de correo electrónico no sea válido.!');
                </script>
                <?
                $this->funcion->redireccionar ("paginaPrincipal");
        }
    else
    {
        $correoosEnviados=$resultado[0][5];
        if(isset($resultado[0][7]))
        {
            $correoosEnviados.=",".$resultado[0][7];
        }    
    ?>
    <script language='javascript'>
    alert('Se envió un enlace al correo: <?echo $correoosEnviados ?>, remitase a su correo, haga clic en el enlace (o copie y pegue la URL en su navegador), para poder continuar con la recuperación de su contraseña !');
    </script>
    <?
    $this->funcion->redireccionar ("paginaPrincipal");
}    


$mail->ClearAllRecipients();
$mail->ClearAttachments();

}

}
else
{
    $mensaje="Alguno de los datos son incorrectos...";
    $html="<script>alert('".$mensaje."');</script>";
    echo $html;
    $this->funcion->redireccionar ("iraValidacionDatos");
}    
