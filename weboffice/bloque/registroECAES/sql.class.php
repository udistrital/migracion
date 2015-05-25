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

class sql_registroInscripcionECAES extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="doc_nro_iden ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedep, accra, acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="doc_nro_iden=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dep_cod = cra_dep_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_emp_nro_iden = doc_nro_iden ";
				$cadena_sql.="AND ";
				$cadena_sql.="doc_estado = 'A' ";
				break;
				
			case "mostrarEstudiante":
				$cadena_sql="SELECT ";
				$cadena_sql.="pee_cra_cod, ";
				$cadena_sql.="pee_cod, ";
				$cadena_sql.="pee_nombre, ";
				$cadena_sql.="pee_semestre, ";
				$cadena_sql.="pee_porcentaje_cursado ";
				$cadena_sql.="pee_genero_recibo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra, v_presentar_ecaes ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="pee_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod=pee_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_emp_nro_iden=".$variable[2]."";
				
				break;
				
			case "mostrarEstudianteAsistente":
				$cadena_sql="SELECT ";
				$cadena_sql.="pee_cra_cod, ";
				$cadena_sql.="pee_cod, ";
				$cadena_sql.="pee_nombre, ";
				$cadena_sql.="pee_semestre, ";
				$cadena_sql.="pee_porcentaje_cursado ";
				$cadena_sql.="pee_genero_recibo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra, v_presentar_ecaes ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="pee_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod=pee_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod IN (".$variable[1].")";
				
				break;
				
			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acestecaes ";
				$cadena_sql.="(";
				$cadena_sql.="eca_ano, ";
				$cadena_sql.="eca_per, ";
				$cadena_sql.="eca_cod, ";
				$cadena_sql.="eca_genero_recibo, ";
				$cadena_sql.="eca_pago_recibo, ";
				$cadena_sql.="eca_estado, ";
				$cadena_sql.="eca_presento ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[1], ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="$variable[0], ";
				$cadena_sql.="'N', ";
				$cadena_sql.="'N', ";
				$cadena_sql.="'A', ";
				$cadena_sql.="'$variable[3]' ";
				$cadena_sql.=")";
				break;
				
			case "verificaAnoper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ";
				$cadena_sql.="ape_per, ";
				$cadena_sql.="ape_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";
				break;
				
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'), ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi,accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_CRA_COD =cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 84 ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_emp_nro_iden=".$variable[4]."";
				break;
				
			case "validaFechasAsistente":
				$cadena_sql="SELECT ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'), ";
				$cadena_sql.="coalesce(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi,accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_CRA_COD =cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 84 ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod IN (".$variable.")";
				break;
				
			case "verificaRegistro":
				$cadena_sql="SELECT ";
				$cadena_sql.="eca_ano, ";
				$cadena_sql.="eca_per, ";
				$cadena_sql.="eca_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acestecaes ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="eca_ano=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="eca_per=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="eca_cod=".$variable[0]." ";
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
