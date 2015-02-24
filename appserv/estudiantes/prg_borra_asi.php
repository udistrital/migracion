<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
//require_once('valida_cancelacion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'msql_ano_per.php');
include_once(dir_script.'class_nombres.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);

		$conexion=new multiConexion();
		$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

		$estcod = $_SESSION['usuario_login'];
		
		$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
		$estcra = $registroCarrera[0][0];

		$nom = new Nombres;
		$nombre = $nom->rescataNombre($estcod,"NombreEstudiante");
		
		
		$registroFecha=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT SYSDATE FROM dual","busqueda");
		
		$fecha = $registroFecha[0][0];



		$registroHora=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual","busqueda");
		
		$hora = $registroHora[0][0];
		
		$redir = 'est_msg_error.php';

		$estado = 'A';
		$transaccion = 'BO';


		//Valida asignaturas perdidas
		$QryAsiPer="SELECT 'S' 
		   	FROM v_acnotdef
			WHERE nde_cra_cod = ".$estcra."
			AND nde_est_cod = ".$_SESSION['usuario_login']."
			AND nde_asi_cod = ".$_POST['asicod']."
			AND nde_nota < 30
			AND nde_obs NOT IN(19,20)";
			
		$registroAsignatura=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAsiPer,"busqueda");	


		if($registroAsignatura[0][0] == 'S'){
		  echo "<script>parent.location.href='est_fre_inscripcion.php?mensaje=107'</script>"; 
		   exit;
		}
//Fin Valida asignaturas perdidas


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
		   $cadena_sql.= ":OBS :=  pck_pr_adicionescancelaciones.fua_control_cancelar(";
		   $cadena_sql.=$ano.","; 
		   $cadena_sql.=$per.","; 
		   $cadena_sql.=$_SESSION['usuario_login'].","; 
		   $cadena_sql.=$estcra.","; 
		   $cadena_sql.=$_POST['asicod'].","; 
		   $cadena_sql.=$_POST['asigru']."); "; 
		   $cadena_sql.="END;";		   
		 	/*
			101=hay cruce
			100=exito
			106=no hay cupo
			105=no hay cupo
			*/
		   //echo $cadena_sql."<br>"; 
		   $salida=$conexion->salidaSQL($configuracion,$accesoOracle,":OBS",4);
		   $registroCancelaGrupo=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");		 

		   echo "<script>parent.location.href='est_fre_inscripcion.php?mensaje=".$registroCancelaGrupo[0][0]."'</script>"; 
		}

?>
