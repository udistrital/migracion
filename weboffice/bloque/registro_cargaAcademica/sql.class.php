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

class sql_registroCargaAcademica extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);
		
		switch($opcion)
		{
			case "anioper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='".$variable[10]."'";	
				break;
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
			case "carreras":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod||'#'||'".$variable[10]."', cra_abrev ";
				$cadena_sql.="FROM accra ";
				$cadena_sql.="WHERE cra_emp_nro_iden = ".$variable[1]." ";
				$cadena_sql.="AND cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC";
				break;
			case "proyecto":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, cra_nombre ";
				$cadena_sql.="FROM accra ";
				$cadena_sql.="WHERE cra_emp_nro_iden = ".$variable[1]." ";
				$cadena_sql.="AND cra_estado = 'A' ";
				$cadena_sql.="AND cra_cod = ".$variable[5]." ";
				$cadena_sql.="ORDER BY cra_cod ASC";
				break;
			case "dia":
				$cadena_sql="SELECT ";
				$cadena_sql.="dia_cod, ";
				$cadena_sql.="dia_nombre, ";
				$cadena_sql.="dia_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dia_estado='A'";
				break;
			case "hora":
				$cadena_sql="SELECT ";
				$cadena_sql.="hor_cod, ";
				$cadena_sql.="hor_larga, ";
				$cadena_sql.="hor_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gehora ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="hor_estado='A' ";
				$cadena_sql.="ORDER BY hor_cod ASC";
				break;
			case "curso":
				$cadena_sql="SELECT ";
				$cadena_sql.="ASI_COD, ";
				$cadena_sql.="ASI_NOMBRE, ";
				$cadena_sql.="CUR_NRO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASPERI, ACASI, ACCRA, ACCURSO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_COD =".$variable[5]." " ;
				$cadena_sql.=" AND ";
				$cadena_sql.="ASI_COD = ".$variable[6]." ";
				$cadena_sql.=" AND ";
				$cadena_sql.="CUR_NRO = ".$variable[7]." ";
				$cadena_sql.=" AND ";
				$cadena_sql.="APE_ESTADO ='".$variable[10]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ANO = CUR_APE_ANO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = CUR_APE_PER ";
				$cadena_sql.="AND ";
				$cadena_sql.="ASI_COD = CUR_ASI_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CRA_COD = CUR_CRA_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ESTADO = 'A' ";
				$cadena_sql.="order by ASI_COD, CUR_NRO asc ";
				break;
			case "horarioCursoRegistrado":
				$cadena_sql="SELECT ";
				$cadena_sql.="registro, ";
				$cadena_sql.="asignatura, ";
				$cadena_sql.="grupo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_horario_curso_registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asignatura=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="grupo=".$variable[7]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dia=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="hora=".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anio=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo=".$variable[2]."";
				break;
			case "horarioCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="asignatura, ";
				$cadena_sql.="grupo, ";
				$cadena_sql.="dia, ";
				$cadena_sql.="hora, ";
				$cadena_sql.="tipsal ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_horario_curso_registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asignatura=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="grupo=".$variable[7]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anio=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="tipsal!='N'";
				break;
			case "cuentaHorarioCursoRegistrado":
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT(asignatura) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="v_horario_curso_registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asignatura=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="grupo=".$variable[7]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anio=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo=".$variable[3]."";
				break;
			case "docentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="doc_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="doc_nro_iden NOT IN (0) ";
				$cadena_sql.="ORDER BY doc_nro_iden ASC";
				break;
			case "consultaDocentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="doc_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="doc_nombre LIKE UPPER('".$variable[9]."') ";
				$cadena_sql.="OR ";
				$cadena_sql.="doc_apellido LIKE UPPER('".$variable[11]."') ";
				$cadena_sql.="OR ";
				$cadena_sql.="doc_nro_iden='".$variable[8]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="doc_nro_iden NOT IN (0) ";
				$cadena_sql.="ORDER BY doc_nro_iden ASC";
				break;      
			case "horarioDocentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="dia,hora,asignatura,grupo,docente,anio,periodo ";
				$cadena_sql.="from v_horario_docente_registrado, achorario_2012 ";
				$cadena_sql.="where ";
                                $cadena_sql.="asignatura = hor_asi_cod ";
				$cadena_sql.="AND ";
                                $cadena_sql.="anio = hor_ape_ano ";
				$cadena_sql.="AND ";
                                $cadena_sql.="periodo = hor_ape_per ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="grupo = hor_nro ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="dia = hor_dia_nro ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="hora = hor_hora ";
				$cadena_sql.="AND ";
				$cadena_sql.="docente = ".$variable[8]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="anio = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="periodo = ".$variable[3]."";
                                $cadena_sql.="AND ";
                                $cadena_sql.="hor_sed_cod <> 0 ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="hor_sal_id_espacio not like 'PAS%' ";
				break;
                            
			case "registrarCarga":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="accarga ";
				$cadena_sql.="(";
				$cadena_sql.="car_ape_ano, ";
				$cadena_sql.="car_ape_per, ";
				$cadena_sql.="car_cra_cod, ";
				$cadena_sql.="car_doc_nro_iden, ";
				$cadena_sql.="car_cur_asi_cod, ";
				$cadena_sql.="car_cur_nro, ";
				$cadena_sql.="car_estado, ";
				$cadena_sql.="car_nro_hrs ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="$variable[3], ";
				$cadena_sql.="$variable[5], ";
				$cadena_sql.="$variable[8], ";
				$cadena_sql.="$variable[6], ";
				$cadena_sql.="$variable[7], ";
				$cadena_sql.="'A', ";
				$cadena_sql.="$variable[16] ";
				$cadena_sql.=")";
				break;
			case "tipoVinculacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="tvi_cod, ";
				$cadena_sql.="tvi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="actipvin ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="tvi_estado='A'";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod NOT IN (0) ";
				$cadena_sql.="ORDER BY tvi_cod ASC";
				break;
			 case "consultarTipVin":
				$cadena_sql="SELECT ";
				$cadena_sql.="dtv_tvi_cod, ";
				$cadena_sql.="tvi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="actipvin, acdoctipvin  ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="tvi_cod=dtv_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_ape_per=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_cra_cod=".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_doc_nro_iden=".$variable[8]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_estado='A'";
				break;
			 case "registrarTipVin":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acdoctipvin ";
				$cadena_sql.="(";
				$cadena_sql.="dtv_ape_ano, ";
				$cadena_sql.="dtv_ape_per, ";
				$cadena_sql.="dtv_cra_cod, ";
				$cadena_sql.="dtv_doc_nro_iden, ";
				$cadena_sql.="dtv_tvi_cod, ";
				$cadena_sql.="dtv_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="$variable[3], ";
				$cadena_sql.="$variable[5], ";
				$cadena_sql.="$variable[8], ";
				$cadena_sql.="$variable[11], ";
				$cadena_sql.="'A' ";
				$cadena_sql.=")";
				break;
			case "totalHorasCarga":
				$cadena_sql="SELECT ";       
				$cadena_sql.="SUM(car_nro_hrs) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accarga ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="car_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_ape_per=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cra_cod=".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cur_asi_cod=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cur_nro=".$variable[7]."";
				break;
			 case "modificarTipVin":
				$cadena_sql="UPDATE acdoctipvin ";
				$cadena_sql.="SET ";
				$cadena_sql.="dtv_tvi_cod=".$variable[11]." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dtv_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_ape_per=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_cra_cod=".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_doc_nro_iden=".$variable[8]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_estado='A'";
				break;
			case "cursoDocentes":
				$cadena_sql="SELECT car_doc_nro_iden, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido, ";
				$cadena_sql.="car_nro_hrs "; 
				$cadena_sql.="FROM accarga,acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="car_cur_asi_cod=".$variable[6]." ";
				$cadena_sql.="AND car_cur_nro=".$variable[7]." "; 
				$cadena_sql.="AND car_estado='A' ";
				$cadena_sql.="AND car_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND car_ape_per=".$variable[3]." ";
				$cadena_sql.="AND car_doc_nro_iden=doc_nro_iden";
				break;
			case "datosDocente":
				$cadena_sql="SELECT car_doc_nro_iden, ";
				$cadena_sql.="doc_nombre||' '||doc_apellido, ";
				$cadena_sql.="car_nro_hrs "; 
				$cadena_sql.="FROM accarga,acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="car_doc_nro_iden=".$variable[8]." ";
				$cadena_sql.="AND car_estado='A' ";
				$cadena_sql.="AND car_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND car_ape_per=".$variable[3]." ";
				$cadena_sql.="AND car_doc_nro_iden=doc_nro_iden";
				break;
			case "cargaDocente":
				$cadena_sql="SELECT car_cur_asi_cod,asi_nombre,car_cur_nro,car_nro_hrs ";
				$cadena_sql.="FROM accarga,accurso,acasi ";
				$cadena_sql.="WHERE car_cur_asi_cod=cur_asi_cod ";
				$cadena_sql.="AND car_cur_nro=cur_nro ";
				$cadena_sql.="AND car_doc_nro_iden=".$variable[8]." ";
				$cadena_sql.="AND car_cur_asi_cod=asi_cod ";
				$cadena_sql.="AND cur_ape_ano=".$variable[2]." ";
				$cadena_sql.="AND cur_ape_per=".$variable[3]." ";
				$cadena_sql.="AND car_cra_cod=".$variable[5]." ";
				$cadena_sql.="AND car_ape_ano=cur_ape_ano ";
				$cadena_sql.="AND car_ape_per=cur_ape_per ";    
				break;
			case "borraCarga":
				$cadena_sql="DELETE ";
				$cadena_sql.="accarga ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="car_doc_nro_iden = ".$variable[8]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_ape_ano = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_ape_per = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cra_cod = ".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cur_asi_cod = ".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="car_cur_nro = ".$variable[7]." ";
				break;
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24mmss'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24mmss'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ESTADO = '".$variable[10]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cra_cod = '".$variable[5]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 88 ";
				$cadena_sql.="AND ";
				$cadena_sql.="'".$variable[9]."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
				break;

			case "verificaCatedra":
				$cadena_sql="SELECT ";
				$cadena_sql.="asi_cod, ";
				$cadena_sql.="asi_ind_catedra ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asi_cod = '".$variable[6]."' ";
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
