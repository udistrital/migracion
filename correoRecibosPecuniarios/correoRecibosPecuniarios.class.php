<?php

class envioCorreo{

    
    public function __construct() {
     
        require_once("phpmailer/class.phpmailer.php");
        require_once ("DatoConexion.php");
        require_once ("connection/FabricaDbConexion.class.php");
        $this->miFabricaConexiones = new FabricaDBConexion ();
        $this->crearConexiones();

    }
    
    private function crearConexiones(){

		
		$datosConexion = new DatoConexion ();
		
		$resultado=true;
		
		// 1. Crear conexion a ORACLE:
		$datosConexion->setDatosConexion ( "oracle" );
		$this->miFabricaConexiones->setRecursoDB ( "oracle", $datosConexion );
		$this->conexionOracle = $this->miFabricaConexiones->getRecursoDB ( "oracle" );		
		if (! $this->conexionOracle) {
			error_log ('NO SE CONECTO A ORACLE' );
			$this->mensajeError='Error 1';
			return false;
		}
	
		return true;	
	}

    function enviarCorreoRecibosPecuniarios($anio,$secuencia){
        
        $datosRecibo = $this->consultarDatosRecibo($anio,$secuencia);
        $enviado='';

        if(is_array($datosRecibo)){
                if($datosRecibo[0]['COD_CONCEPTO']==5 || $datosRecibo[0]['COD_CONCEPTO']==8 || $datosRecibo[0]['COD_CONCEPTO']==9 ){
                    //si es certificado de notas, derechos de grado o duplicado de diploma busca correo de la secretaria de la facultad
                    switch ($datosRecibo[0]['COD_FACULTAD']) {
                        case 32:
                            $codSecretaria=81;
                            break;
                        case 33:
                            $codSecretaria=84;
                            break;
                        case 23:
                            $codSecretaria=82;
                            break;
                        case 24:
                            $codSecretaria=83;
                            break;
                        case 101:
                            //$codSecretaria=; se debe registrar la sec academica de ASAB
                            break;
                            
                        default:
                            break;
                    }
                    $resultado = $this->consultarCorreoDependencia($codSecretaria);
                    $correo = $resultado[0]['CORREO'];
                }elseif($datosRecibo[0]['COD_CONCEPTO']==6 || $datosRecibo[0]['COD_CONCEPTO']==13 ){
                        //si es constancia de estudio Y curso vacacional busca correo de proyecto
                        $resultado = $this->consultarCorreoProyecto($datosRecibo[0]['COD_PROYECTO']);
                        $correo = $resultado[0]['CORREO'];
                }elseif($datosRecibo[0]['COD_CONCEPTO']==10 ){
                        //Si es duplicado de carnet busca correo de admisiones
                        $resultado = $this->consultarCorreoDependencia(20);
                        $correo = $resultado[0]['CORREO'];
                }
               // $correo='maritza_callejas@yahoo.com.ar';
echo "<br>mail ".$correo;
                if($correo){
                    $asunto= "NOTIFICACION DE PAGO RECIBO No.".$datosRecibo[0]['SECUENCIA'];
                    $contenidoCorreo= "Buen d&iacute;a, <br><br>Se ha recibido el pago por concepto de ".$datosRecibo[0]['CONCEPTO']." del recibo con secuencia No.".$datosRecibo[0]['SECUENCIA']. " del a&ntilde;o ".$datosRecibo[0]['ANIO'].", por parte del estudiante ".$datosRecibo[0]['ESTUDIANTE']." con código ".$datosRecibo[0]['COD_ESTUDIANTE'].".";
                    $contenidoCorreo.= "<br><br>Nota: no responde este correo, es un servicio autom&aacute;tico.";
                    $enviado=$this->enviarCorreo($correo, $asunto,$contenidoCorreo);
                }

        }
        return $enviado;
    }
    
    function consultarDatosRecibo($anio,$secuencia) {
	$datos= array('anioRecibo'=>$anio,
			'secuencia'=>$secuencia);
        $cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "consultar_recibo", $datos );
        return $resultadoReg=$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
    }

    function consultarCorreoProyecto($codProyecto) {
        $cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "consultar_correo_proyecto", $codProyecto );
        return $resultadoReg=$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "busqueda" );
	
    }

    function consultarCorreoDependencia($codDependencia) {
        $cadenaSql = $this->miFabricaConexiones->getCadenaSql ( "consultar_correo_dependencia", $codDependencia );
        $resultadoReg=$this->conexionOracle->ejecutarAcceso ( $cadenaSql, "busqueda" );
	return $resultadoReg;
    }
    
    function enviarCorreo($correo, $asunto,$contenidoCorreo){
        $mail = new phpmailer();
        
        //$mail->Host     = "mail.udistrital.edu.co";
        $mail->Host     = "200.69.103.49";
        $mail->FromName = "Correos Universidad Distrital";
        $mail->From     = "condor@udistrital.edu.co";
        $mail->Mailer   = "smtp";
        $mail->SMTPAuth = true;
        $mail->Username = "condor@udistrital.edu.co";
        $mail->Password = "CondorOAS2012";
        $mail->Timeout  = 120;
        $mail->Charset  = "utf-8";

        //Cuerpo del mensaje
        $mail->Body    = $contenidoCorreo;
        //Este el asunto
        $mail->Subject = $asunto;

        $mail->IsHTML(true);

        //las direcciones de envio
        $mail->AddAddress("$correo");
        
        if(!$mail->Send()) {
                echo $this->lenguaje->getCadena("errorMail")."<br>";;
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                $enviado = "N";

        } else {
                echo "enviado";
                $enviado = "S";

        }
        $mail->ClearAllRecipients();
        return $enviado;

    }
    
    function procesarCorreo($tipo,$anio,$referencia){
        switch ($tipo) {
            case 'recibosPecuniarios':
                $this->enviarCorreoRecibosPecuniarios($anio,$referencia);
                break;

            default:
                break;
        }
    }
    
    
}

$anio= (isset($_REQUEST['anio'])?$_REQUEST['anio']:'');
$secuencia= (isset($_REQUEST['secuencia'])?$_REQUEST['secuencia']:'');
if($anio && $secuencia){
    $objRecibos = new envioCorreo();
    $objRecibos->procesarCorreo("recibosPecuniarios",$anio,$secuencia);
}

?>
