<?php
 function  append_logev($pag,$errcode,$errmsg,$fmto,$clave){
 	global $pag;
	global $valores;
	global $fmto;
	switch ($errcode){
	case "00001":	
		if ($fmto == 6 || $fmto == 10 || $fmto == 12 || $fmto == 13 || $fmto == 16) {
			$msg_err_log = "Su autoevaluaci&oacute;n NO fue guardada. Usted ya se autoevalu&oacute; previamente.";
		} elseif ($fmto == 8 || $fmto == 9 || $fmto == 11 || $fmto == 14) {
			$msg_err_log = "La evaluaci&oacute;n NO fue guardada. Ya se ha evaluado a este docente.";
		} elseif ($fmto == 7 || $fmto == 15) {
			$msg_err_log = "Su evaluaci&oacute;n NO fue guardada. Usted ya evalu&oacute; a este docente.";
		}
		break;
	case "00002":
	  	$msg_err_log = "Error 2"; 
		break;
	case "00933":
	  	$msg_err_log = "Mensaje de error: $pag, clave $clave, ".date("j/n/Y g:i:s A");
		break;
	case "00904":
	  	$msg_err_log = "Mensaje de error: $pag, clave $clave, ".date("j/n/Y g:i:s A");
		break;
	case "00921":
	  	$msg_err_log = "Mensaje de error: $pag, clave $clave, ".date("j/n/Y g:i:s A"); 
		break;
	case "00928":
	  	$msg_err_log = ""; //intenta grabar formato.php cuando en este se informa evaluaci�n ya realizada.. eqv. formato vacio..
		break;		
	case "00936":
	  	$msg_err_log = "Mensaje de error: $pag, clave $clave, ".date("j/n/Y g:i:s A");
		break;
	case "24327":
	  	$msg_err_log = "Error en el servidor.".date("j/n/Y g:i:s A"); //reiniciar servidor -bug persistencia PHP
		break;
	case "01034":
	  	$msg_err_log = "Mensaje de error: $pag, clave $clave, Oracle no disponible. ".date("j/n/Y g:i:s A");
		break;
		
	case "03113":
	  	$msg_err_log = "Error en el servidor.".date("j/n/Y g:i:s A");
		break;
	case "01017":
	  	$msg_err_log = "Error en la validaci&oacute;n: $pag, clave $clave, ".date("j/n/Y g:i:s A");
		//nombre de usuario/contrase�a incorrectos; conexi�n denegada in 
		break;
	case "1253":
	  	$msg_err_log = "Error de TNS al conectar al servidor de base de datos: $pag, clave $clave, ".date("j/n/Y g:i:s A");
		//nombre de usuario/contrase�a incorrectos; conexi�n denegada in 
		break;
	default:
		$msg_err_log = "Mensaje de error: $errcode $errmsg"; //Verificar si es necesario pasar estos parametros.

	}
	
/*------------------------------------- Deshabilitado para blades 
	$log = "logev.txt"; 
	$handle = fopen($log, 'a');
	if (!$handle) {
         echo "No se puede abrir el archivo log";
         exit;
    }
	$logreg = $_SERVER['HTTP_USER_AGENT']." - ".$_SERVER["HTTP_REFERER"]." - ".$_SESSION["usuario_login"]." - ".$handle.".-Pag: ".$pag."; Fmto: ".$fmto."; Key:".$clave." Msg: ".substr($errmsg,0,50)."; ".date("j/n/Y g:i:s A")."\n"; 
	if (fwrite($handle, $logreg) === FALSE) {
        echo "No puede escribir en el archivo log";
        exit;
	}
	fclose($handle);	
-  -----------------------------------------------------------------------------------------*/
 	//php5. //file_put_contents("logev.txt",".-Pag: ".$pag."; Fmto: ".$fmto."; Key:".$clave."; Msg: ".substr($errmsg,0,50)."; ".date("j/n/Y g:i:s A"),FILE_APPEND );
	print $msg_err_log;
	if (msg_err_log != ""){
		return true;
	}else return false;
 }
 
 // OCISessionBegin: ORA-28000: the account is locked in C:\AppServ\www\ev06\conexion.php on line 17
//*-----------------------------------------------
?>
