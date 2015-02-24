<?php
	$pag = 4;
		//session_name($usuarios_sesion="Condorizado");//Autentificado");
		//session_register($usuarios_sesion="Condorizado");//Autentificado");
		session_start();
//echo "Pag 4".session_name($usuarios_sesion).$_SESSION['usuario_login'].$_SESSION['usuario_nivel'];		
		if(!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_nivel'])){
		   //session_destroy();
		   die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
		   exit;
		}
$_SESSION["sesion"] = $_SESSION["sesion"] + 1;
	//echo "Sesiones = ".$_SESSION["sesion"];
	$us = "autoevaluadoc";
	$key = "sjneira";
	$bd = "PRUEBA";
	//oci_close();
	$oci_cn = ocilogon($us, $key, $bd); 
	if (!$oci_cn) { // || !$oci_cn2 || !$oci_cn_pre)
	   //die("<p align='center'><b><font color='#FF0000'>-----: No hay conexi�n con la base de datos :-----</font></b>");
	   $e = OCIError();
	   append_logev($pag,$e['code'],$e['message'],"cnx",401);
	}


//}?>