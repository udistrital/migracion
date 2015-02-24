<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
//require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'msql_ano_per.php');
include_once(dir_script.'class_nombres.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

$redir = 'est_msg_error.php';

		$estcod = $_SESSION['usuario_login'];
		
		$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
		$estcra = $registroCarrera[0][0];
	
		$nom = new Nombres;
		$nombre = $nom->rescataNombre($estcod,"NombreEstudiante");

		$registroFecha=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT SYSDATE FROM dual","busqueda");
		
		$fecha = $registroFecha[0][0];

		$estado = 'A';
		
		$transaccion = 'AD';
		
		$registroHora=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual","busqueda");
		
		$hora = $registroHora[0][0];
	
		//valida requisitos
		
		$asicod ="SELECT 'S' ";
		$asicod.="FROM v_acestmatins,acest ";
		$asicod.="WHERE emi_est_cod = ".$estcod." ";
		$asicod.="AND emi_est_cod = est_cod ";
		$asicod.="AND emi_asi_cod = ".$_REQUEST['asicod']." ";
		$asicod.="AND emi_nro_sem = ".$_REQUEST['asisem']." ";
		$asicod.="AND emi_pen_nro = ".$_REQUEST['pensum']." ";
		$asicod.="AND est_estado_est IN('A','B')";
									 
									 
		$registroAsignatura=$conexion->ejecutarSQL($configuracion,$accesoOracle,$asicod,"busqueda");									 


		if($registroAsignatura[0][0] != 'S'){

		   $cadena_sql= "INSERT INTO accondorlog VALUES(";
		   $cadena_sql.=$estcod.","; 
		   $cadena_sql.=$_SESSION['usuario_nivel'].","; 
		   $cadena_sql.=$_REQUEST['asicod'].","; 
		   $cadena_sql.=$_SERVER['REMOTE_ADDR'].","; 
		   $cadena_sql.=$fecha.","; 
		   $cadena_sql.=$estado.","; 
		   $cadena_sql.=$transaccion.","; 
		   $cadena_sql.=$hora.")";
		   
		   $registroTransaccion=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"");
		   
		   session_destroy();
		   die("<center><h3><font color='#FF0000'>Se&ntilde;or(a): ".$nombre.", esta transacci&oacute;n no est&aacute; permitida.</font><br>Esta acci&oacute;n fue grabada en un regitro de auditoria <br> y ser&aacute; reportada a su respectiva coordinaci&oacute;n.</h3></center>");
		   exit;
		}
	
//fin valida requisitos

		if($_REQUEST['estcod'] != $_SESSION['usuario_login']){
		
		   $cadena_sql= "INSERT INTO accondorlog VALUES(";
		   $cadena_sql.=$_SESSION['usuario_login'].","; 
		   $cadena_sql.=$_SESSION['usuario_nivel'].","; 
		   $cadena_sql.=$_POST['estcod'].","; 
		   $cadena_sql.=$_SERVER['REMOTE_ADDR'].","; 
		   $cadena_sql.=$fecha.","; 
		   $cadena_sql.=$estado.","; 
		   $cadena_sql.="'CU',"; 
		   $cadena_sql.=$hora.")";
		   
		   $registroTransaccion=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"");
		   
		   session_destroy();
		  die("<center><h3><font color='#FF0000'>Se&ntilde;or(a): ".$nombre.", esta transacci&oacute;n no est&aacute; permitida.</font><br>Esta acci&oacute;n fue grabada en un regitro de auditoria <br> y ser&aacute; reportada a su respectiva coordinaci&oacute;n.</h3></center>");
		   exit;
		}
		else{
		   $cadena_sql.= "BEGIN ";	
		   $cadena_sql.= ":OBS := pck_pr_adicionescancelaciones.fua_control_adicion(";
		   $cadena_sql.=$ano.","; 
		   $cadena_sql.=$per.","; 
		   $cadena_sql.=$_SESSION['usuario_login'].","; 
		   $cadena_sql.=$estcra.","; 
		   $cadena_sql.=$_REQUEST['asicod'].","; 
		   $cadena_sql.=$_REQUEST['asigru'].","; 
		   $cadena_sql.=$_REQUEST['asisem'].","; 		   
		   $cadena_sql.=$_REQUEST['pensum']."); "; 
		   $cadena_sql.="END;";		   
		 	/*
			101=hay cruce
			100=exito
			106=no hay cupo
			105=no hay cupo
			*/
		   $salida=$conexion->salidaSQL($configuracion,$accesoOracle,":OBS",4);
		   $registroCambioGrupo=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");		 

		   echo "<script>parent.location.href='est_fre_inscripcion.php?mensaje=".$registroCambioGrupo[0][0]."'</script>"; 
		}		

?>
