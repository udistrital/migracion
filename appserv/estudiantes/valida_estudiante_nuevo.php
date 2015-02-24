<?PHP
//Validar si es estudiante nuevo.
include_once("../clase/multiConexion.class.php");

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


	$substr_cod="SELECT mntac.fua_primiparo(".$_SESSION['usuario_login'].") FROM dual";

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$substr_cod,"busqueda");


	
	if(isset($registro[0][0])){
		if($registro[0][0]=='S'){
			header("Location: ../err/err_add_estnue.php");
		}	
	}else{
		   die('<link href="../script/estilo.css" rel="stylesheet" type="text/css">
		    	<br><br><br><center><div class="aviso_mensaje"><br>
			En este momento hay demasiadas conexiones...Por favor intente mas tarde. 
			</br></div>');
		   exit;	
	}
	
?>

	   
