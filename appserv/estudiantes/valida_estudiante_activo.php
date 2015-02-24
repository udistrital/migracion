<?PHP
include_once("../clase/multiConexion.class.php");

	$conexion=new multiConexion();
	$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
	
	
	$qry_estado = "SELECT est_estado_est FROM acest WHERE est_cod = ".$_SESSION['usuario_login'];

	$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_estado,"busqueda");

	//echo "ESTADO".$registro[0][0];
	
	if(!isset($registro[0][0])){
		   die('<link href="../script/estilo.css" rel="stylesheet" type="text/css">
		    	<br><br><br><center><div class="aviso_mensaje"><br>
			En este momento hay demasiadas conexiones...Por favor intente mas tarde. 
			</br></br></div>');
		   exit;	
	}else{
		if(($registro[0][0]!='A') && ($registro[0][0]!='B')){
			header("Location: ../err/err_add_oficializado.php");
		}		
	}
	

?>
