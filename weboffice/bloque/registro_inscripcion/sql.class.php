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

class sql_registroInscripcionGrado extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="INITCAP(LOWER(est_nombre)), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_tipo_iden, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="cra_nombre, ";
				$cadena_sql.="est_direccion, ";
				$cadena_sql.="est_telefono, ";
				$cadena_sql.="eot_email, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_exp) mun_exp, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_res) mun_res, ";
				$cadena_sql.="eot_tel_cel, ";
				$cadena_sql.="(SELECT mun_cod FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_res) mun_res, ";
				$cadena_sql.="est_lib_militar, ";
				$cadena_sql.="est_nro_dis_militar, ";
				$cadena_sql.="est_sexo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="accra, ";
				$cadena_sql.="acestotr, ";
				$cadena_sql.="gemunicipio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cra_cod=cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="eot_cod=est_cod "; 
				break;
			
			case "consultarAnioPer":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";
				break;
			
			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acinsgrado ";
				$cadena_sql.="(";
				$cadena_sql.="ing_ano, ";
				$cadena_sql.="ing_per, ";
				$cadena_sql.="ing_cra_cod, ";
				$cadena_sql.="ing_est_cod, ";
				$cadena_sql.="ing_nom_trabajo, ";
				$cadena_sql.="ing_director, ";
				$cadena_sql.="ing_tipo_trabajo, ";
				$cadena_sql.="ing_acta ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[5], ";
				$cadena_sql.="$variable[6], ";
				$cadena_sql.="$variable[1], ";
				$cadena_sql.="$variable[0], ";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="$variable[3], ";
				$cadena_sql.="'".$variable[4]."', ";
				$cadena_sql.="'".$variable[7]."'";
				$cadena_sql.=")";
				break;
				
			case "verificaRegistro":
				$cadena_sql="SELECT ";
				$cadena_sql.="ing_ano, ";
				$cadena_sql.="ing_per, ";
				$cadena_sql.="ing_cra_cod, ";
				$cadena_sql.="ing_est_cod, ";
				$cadena_sql.="ing_estado, ";
				$cadena_sql.="ing_acta ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acinsgrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ing_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ing_estado='A'";
				break;
				
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'), ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_CRA_COD =".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 83";
				break;
				
			case "muestraInscripcion":
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="INITCAP(LOWER(est_nombre)), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_tipo_iden, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="cra_nombre, ";
				$cadena_sql.="est_direccion, ";
				$cadena_sql.="est_telefono, ";
				$cadena_sql.="eot_email, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_exp) mun_exp, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_res) mun_res, ";
				$cadena_sql.="eot_tel_cel, ";
				$cadena_sql.="ing_nom_trabajo, ";
				$cadena_sql.="dir_nombre ||' '|| dir_apellido, ";
				$cadena_sql.="ing_tipo_trabajo, ";
				$cadena_sql.="ing_estado, ";
				$cadena_sql.="ing_fecha, ";
				$cadena_sql.="ing_acta, ";
				$cadena_sql.="est_lib_militar ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="accra, ";
				$cadena_sql.="acestotr, ";
				$cadena_sql.="acinsgrado, ";
				$cadena_sql.="acdirectorgrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ing_est_cod=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ing_cra_cod=cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="eot_cod=est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod=ing_est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dir_nro_iden=ing_director ";
				$cadena_sql.="AND ";
				$cadena_sql.="ing_estado='A'";
				break;
				
			case "municipios":
				$cadena_sql="SELECT ";
				$cadena_sql.="mun_cod, ";
				$cadena_sql.="mun_nombre, ";
				$cadena_sql.="dep_cod, ";
				$cadena_sql.="dep_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gemunicipio, gedepartamento ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dep_cod = mun_dep_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="mun_estado='A' ";
				$cadena_sql.="ORDER BY mun_nombre ASC ";
				break;
				
			case "editarMunicipioExp":
				$cadena_sql="UPDATE ";
				$cadena_sql.="acestotr ";
				$cadena_sql.="SET ";
				$cadena_sql.="eot_cod_mun_exp='".$variable[0]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="eot_cod=".$variable[1]."";
				break;
                            
			case "editarLibretaMilitar":
				$cadena_sql="UPDATE ";
				$cadena_sql.="acest ";
				$cadena_sql.="SET ";
				$cadena_sql.="est_lib_militar='".$variable[0]."', ";
				$cadena_sql.="est_nro_dis_militar='".$variable[1]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod=".$variable[2]."";
				break;
					
			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>