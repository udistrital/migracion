<?php
// -----------------------------------------------------------------------\\
/*
1	M�dulo:			Evaluaci�n Docente periodo 2007-3
2	Nombre:			funparamspag.php
3	Descripci�n:	P�gina captura de par�metros del URL
4	Tipo:			Script PHP
5	Acceso a objetos: No
6	Aplicaci�n:		Sistema CONDOR
7	Ruta:			../evo73	
8	Ambiente:		Producci�n AIX 5.3 / OAS 10g R. 10.1.2
9	Fecha en producci�n:	2 de octubre de 2007
10	Elaborado por:			Carlos E. Rodr�guez J.
11	Revisi�n No.:	01
*/
// -----------------------------------------------------------------------\\
   //include ('funerr.php'); 
   //include ('funev2.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion('evaldocente');

   Response.$Buffer = false;
//------ Registro en la sesi�n ------------\\
		//***session_register($nuevousuario="newuser"); //
//------session_name($usuarios_sesion="Autentificado");
//------session_register($usuarios_sesion="Autentificado");
		//session_start();
		$usuario = $_SESSION["usuario_login"];	
//$vin = $_SESSION["usuario_nivel"];
//		echo "vin: $vin -- Usuario: $usuario";
		
//------ Obteniendo par�metros url o de sesi�n ------------\\ 
  	$vusec = @$_REQUEST["vusec"];
  	if ($vusec == ""){
	 		$vusec = 0;
	}
	$vinselactual = $_SESSION["vinsel".$vusec];
	$vu2sec = $vusec;
	//echo "vinsel_sec en sesion ".$_SESSION["vinsel".$vusec];
	//echo "Cra: ".$cra." sec ".$vusec."  Vinselactual ".$vinselactual;
?>
