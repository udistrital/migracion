<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_claves extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		//$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "tipoUsuario":
				$cadena_sql="SELECT cla_tipo_usu ";
				$cadena_sql.="FROM ";	 
				$cadena_sql.="geclaves ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo ='".$variable[1]."' ";
				break;

			case "correoEstudiante":
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod,est_nombre,TRIM(LOWER(eot_email)),TRIM(LOWER(eot_email_ins)) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest,acestotr ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod='".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod = eot_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="(trim(eot_email) like('%".$variable[0]."%') ";
				$cadena_sql.="OR ";
				$cadena_sql.="trim(eot_email_ins) like('%".$variable[0]."%')) ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_estado_est IN('A','B','H','J','L','T','V') ";
				break;
				
			case "correoDocente":
				$cadena_sql="SELECT ";
				$cadena_sql.=" doc_nro_iden,doc_nombre||' '||doc_apellido,TRIM(LOWER(doc_email)),TRIM(LOWER(doc_email_ins)) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="doc_nro_iden = ".$variable[1]." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="(trim(doc_email) like('%".$variable[0]."%') ";
				$cadena_sql.="OR ";
				$cadena_sql.="trim(doc_email_ins) like('%".$variable[0]."%')) ";
				break;

			case "correoFuncionario":
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_nro_iden,emp_nombre,TRIM(LOWER(emp_email)) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntpe.peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden = ".$variable[1]." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="trim(emp_email) like('%".$variable[0]."%') ";
				$cadena_sql.="AND ";
				$cadena_sql.="emp_estado_e != 'R'";
				break;
			case "modificaClaveOracle":
				$cadena_sql="UPDATE ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="SET ";
				$cadena_sql.="cla_clave='".$variable[4]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo = ".$variable[5]." ";
				break;
			case "modificaClaveMySQL":
				$cadena_sql="UPDATE ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="SET ";
				$cadena_sql.="cla_clave='".$variable[4]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo = '".$variable[5]."' ";
				break;
			default:
				$cadena_sql="";
				break;
		}
		//echo "<br>".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
