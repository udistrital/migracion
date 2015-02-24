<?PHP
	include_once("../clase/multiConexion.class.php");

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
	$estcod=$_SESSION['usuario_login'];

	//Funcion que nos retorna S o N dependiendo si el estudiante ya tiene preinscripcion.
	$cod_consul= "SELECT ";
	$cod_consul.= "mntac.fua_realizo_preins($estcod) ";
	$cod_consul.= "FROM dual";
		   
	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
	

	//echo "-".$cod_consul;
	//echo "(".$registro[0][0].")";

	if($registro[0][0]=="N"){
	   die('<link href="../script/estilo.css" rel="stylesheet" type="text/css">
	    	<br><br><br><center><div class="aviso_mensaje"><br>
		En este momento el sistema no registra preinscripci&oacute;n de su PROYECTO CURRICULAR, 
	    	por lo tanto esta opci&oacute;n se habilitar&aacute; 
	    	cuando este proceso se haya realizado.<br><br>
		<b>Para mayor informaci&oacute;n consulte con su PROYECTO CURRICULAR.</b></br></br></div>');
	   exit;
	}

		   	   

?>
