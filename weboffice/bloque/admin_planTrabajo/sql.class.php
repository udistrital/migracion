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

class sql_registro_PlanTrabajo extends sql
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
			
			case "seleccionarPeriodo":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per, ape_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado in ('A','V','P')";
				break;
			
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
				
			case "listaProyectos":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC ";
				break;
			 
			case "listaDecanos":
				$cadena_sql="SELECT dep_cod, dep_nombre, emp_nro_iden, emp_nombre ";
				$cadena_sql.="FROM gedep, peemp ";
				$cadena_sql.="WHERE dep_emp_cod = emp_cod ";
				$cadena_sql.="AND emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND emp_car_cod = 218 ";
				$cadena_sql.="AND emp_estado_e <> 'R'";
				break;
			
			case "listaProyectosDecano":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM gedep, accra, acdocente ";
				$cadena_sql.="WHERE dep_cod = ".$variable[3]." ";
				$cadena_sql.="AND dep_cod = cra_dep_cod ";
				$cadena_sql.="AND cra_estado = 'A' ";
				$cadena_sql.="AND cra_emp_nro_iden = doc_nro_iden ";
				$cadena_sql.="AND doc_estado = 'A' ";
				$cadena_sql.="ORDER BY 1 ASC"; 
				break;
  
			case "listaFacultades":
				$cadena_sql="SELECT ";
				$cadena_sql.="distinct(cra_dep_cod), ";
				$cadena_sql.="dep_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra, gedep ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_estado='A' ";
				$cadena_sql.="AND dep_cod = cra_dep_cod ";
				$cadena_sql.="AND cra_dep_cod != 0 ";
				$cadena_sql.="AND dep_estado ='A' ";
				$cadena_sql.="ORDER BY 1 ";
				break;

			case "listaDocentes":
                                $cadena_sql=" SELECT DISTINCT(doc_nro_iden) cedula,";
                                $cadena_sql.=" (trim(doc_apellido)||' '||trim(doc_nombre)) nombre";
                                $cadena_sql.=" FROM acasperi";
                                $cadena_sql.=" INNER JOIN accursos ON cur_ape_ano=ape_ano";
                                $cadena_sql.=" AND ape_per = cur_ape_per ";
                                $cadena_sql.=" INNER JOIN achorarios ON cur_id=hor_id_curso";
                                $cadena_sql.=" INNER JOIN accargas ON hor_id=car_hor_id";
                                $cadena_sql.=" INNER JOIN accra ON cra_cod = cur_cra_cod";
                                $cadena_sql.=" INNER JOIN acdocente ON car_doc_nro = doc_nro_iden";
                                $cadena_sql.=" WHERE ape_estado = '".$variable[10]."' ";
                                $cadena_sql.=" AND cra_cod = ".$variable[3]." ";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" AND hor_estado='A'";
                                $cadena_sql.=" ORDER BY 2 ASC";
				break;
				
			case "cargalectiva":
				$cadena_sql="SELECT ";
				$cadena_sql.="cle_ape_ano, ";
				$cadena_sql.="cle_ape_per, ";
				$cadena_sql.="cle_doc_nro_iden, ";
				$cadena_sql.="dac_nombre, ";
				$cadena_sql.="substr(cle_asi_nombre,1,30) , ";
				$cadena_sql.="cle_asi_nombre, ";
				$cadena_sql.="dia_nombre, ";
				$cadena_sql.="hor_larga, ";
				$cadena_sql.="sed_nombre, ";
				$cadena_sql.="sed_abrev, ";
				$cadena_sql.="sal_cod, ";
				$cadena_sql.="tvi_nombre, ";
				$cadena_sql.="decode(tvi_cod,1,'PL',6,'PL','VE') ";
				$cadena_sql.="FROM gedia, gehora, gesede, gesalon, acdocactividad, v_accargalectiva, mntac.actipvin ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dac_cod = cle_dac_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_dia_nro = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_hora = ".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="sed_cod = cle_sed_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="sed_cod = sal_sed_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="sal_cod = cle_sal_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod = cle_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_doc_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_dia_nro= dia_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_hora= hor_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_ape_ano = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_ape_per = ".$variable[2]." ";
				$cadena_sql.="ORDER BY dia_cod, hor_cod ASC"; 
				break;
				
			case "cargaactividades":
				$cadena_sql="SELECT ";
				$cadena_sql.="DPT_APE_ANO, ";
				$cadena_sql.="DPT_APE_PER, ";
				$cadena_sql.="DPT_DOC_NRO_IDEN, ";
				$cadena_sql.="DAC_NOMBRE, ";
				$cadena_sql.="substr(DAC_NOMBRE,1,30), ";
				$cadena_sql.="DIA_ABREV, ";
				$cadena_sql.="HOR_LARGA, ";
				$cadena_sql.="SED_ABREV, ";
				$cadena_sql.="TO_CHAR(sed_nombre), ";
				$cadena_sql.="SAL_DESCRIP, ";
				$cadena_sql.="sal_cod, ";
				$cadena_sql.="DPT_FECHA, ";
				$cadena_sql.="DPT_ESTADO, ";
				$cadena_sql.="DIA_COD, ";
				$cadena_sql.="HOR_COD, ";
				$cadena_sql.="DPT_DAC_COD, ";
				$cadena_sql.="DAC_INTENSIDAD, ";
				$cadena_sql.="tvi_nombre, ";
				$cadena_sql.="decode(tvi_cod,1,'PL',6,'PL',0,'SD','VE') ";
				$cadena_sql.="FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalon,actipvin ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_COD = DPT_DAC_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DIA_NRO = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DIA_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_HORA = ".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="HOR_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_COD = DPT_SED_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_SED_COD = DPT_SED_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_COD = DPT_SAL_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod=dpt_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dia_cod= dpt_dia_nro ";
  				$cadena_sql.="AND ";
  				$cadena_sql.="hor_cod= dpt_hora ";
				$cadena_sql.="ORDER BY 11,12 ";
				break; 
				
			case "actividades":
				$cadena_sql="SELECT ";
				$cadena_sql.="DAC_COD, ";
				$cadena_sql.="DAC_NOMBRE, ";
				$cadena_sql.="DAC_INTENSIDAD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACDOCACTIVIDAD ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DAC_COD > 1 ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_ESTADO = 'A' ";
				$cadena_sql.="ORDER BY 1";
				break;
			
			case "tipoVinculacion";
				$cadena_sql="SELEC ";
				$cadena_sql.="distinct dtv_tvi_cod, ";
				$cadena_sql.="tvi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdoctipvin,acasperi,actipvin ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_ano=dtv_ape_ano ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod=dtv_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_per=dtv_ape_per ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_estado='".$variable[10]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_doc_nro_iden= ".$variable[0]." ";
				break;
						
			case "cuentaActividad":
                                $cadena_sql=" SELECT tvi_nombre,";
                                $cadena_sql.=" actividades,";
                                $cadena_sql.=" carga,";
                              //  $cadena_sql.=" DECODE(tvi_cod,1,'PL',6,'PL',8,'PL',0,'SD','VE'),";

                                $cadena_sql.=" CASE WHEN TVI_COD=1 THEN 'PL' ";
                                $cadena_sql.=" WHEN TVI_COD=6 THEN 'PL' ";
                                $cadena_sql.=" WHEN TVI_COD=8 THEN 'PL' ";
                                $cadena_sql.=" WHEN TVI_COD=0 THEN 'SD' ";
                                $cadena_sql.=" ELSE 'VE' END TIPO";
                                
                                
					            $cadena_sql.=" FROM (SELECT tvi_cod, tvi_nombre,";
                                $cadena_sql.=" (SELECT COUNT(car_tip_vin)";
                                $cadena_sql.=" FROM accargas";
                                $cadena_sql.=" INNER JOIN achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano=ape_ano AND cur_ape_per=ape_per";
                                $cadena_sql.=" WHERE ape_estado='".$variable[10]."'";
                                $cadena_sql.=" AND car_doc_nro=".$variable[4]."";
                                $cadena_sql.=" AND car_estado='A'";
                                $cadena_sql.=" AND hor_estado='A'";
                                $cadena_sql.=" AND cur_estado='A'";
                                $cadena_sql.=" AND car_tip_vin=actipvin.tvi_cod";
                                $cadena_sql.=" GROUP BY car_tip_vin) carga,";
                                $cadena_sql.=" (SELECT count(dpt_hora) numactividades";
                                $cadena_sql.=" from acdocplantrabajo";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=dpt_ape_ano AND ape_per=dpt_ape_per";
                                $cadena_sql.=" WHERE ape_estado='".$variable[10]."'";
                                $cadena_sql.=" AND dpt_doc_nro_iden=".$variable[4]."";
                                $cadena_sql.=" AND dpt_estado='A'";
                                $cadena_sql.=" AND dpt_tvi_cod=actipvin.tvi_cod) actividades";
                                $cadena_sql.=" FROM actipvin ) A" ;
                                $cadena_sql.=" WHERE (carga+actividades) <> 0";
                                $cadena_sql.=" ORDER BY tvi_cod ASC";
						break;
				
		
                        case 'asignaturasCargaLectiva':
                            
                                $cadena_sql=" SELECT DISTINCT cur_asi_cod, asi_nombre from accargas";
                                $cadena_sql.=" INNER JOIN achorarios ON hor_id=car_hor_id";
                                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                                $cadena_sql.=" INNER JOIN acasi ON asi_cod=cur_asi_cod";
                                $cadena_sql.=" WHERE car_doc_nro=".$variable[4]."";
                                $cadena_sql.=" AND ape_estado='".$variable[10]."'";
                                break;
		
			case "totalPorActividad":
				$cadena_sql="SELECT COUNT(DPT_HORA) ";
				$cadena_sql.="FROM acdocplantrabajo,acasperi ";
				$cadena_sql.="WHERE APE_ANO = DPT_APE_ANO ";
				$cadena_sql.="AND APE_PER = DPT_APE_PER ";
				$cadena_sql.="AND APE_ESTADO = '".$variable[10]."' ";
				$cadena_sql.="AND DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND DPT_DAC_COD = ".$variable[5]." ";
				$cadena_sql.="AND DPT_ESTADO = 'A' ";
				$cadena_sql.="AND DPT_TVI_COD = ".$variable[8]."";
				break;
				
			case "intensidadActividad":
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT(DPT_HORA) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DAC_COD =".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_TVI_COD =".$variable[8]."";
				break;
				
			case "cuentaIntensidad":
				$cadena_sql="SELECT ";
				$cadena_sql.="DAC_INTENSIDAD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocactividad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DAC_COD = ".$variable[5]."";
				break;
				
			case "cruceCarga":
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM "; 
				$cadena_sql.="v_accargalectiva ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cle_doc_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_dia_nro = ".$variable[3]." "; 
				$cadena_sql.="AND ";
				$cadena_sql.="cle_hora = ".$variable[4]."";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_ape_ano=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cle_ape_per=".$variable[2]." ";
				break;
			
			case "cruceActividad";
				$cadena_sql = "SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DIA_NRO = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_HORA =".$variable[4]."";
				break;
			
			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="(";
				$cadena_sql.="DPT_APE_ANO, ";
				$cadena_sql.="DPT_APE_PER, ";
				$cadena_sql.="DPT_DOC_NRO_IDEN, ";
				$cadena_sql.="DPT_DAC_COD, ";
				$cadena_sql.="DPT_DIA_NRO, ";
				$cadena_sql.="DPT_HORA, ";
				$cadena_sql.="DPT_SED_COD, ";
				$cadena_sql.="DPT_SAL_COD, ";
				$cadena_sql.="DPT_FECHA, ";
				$cadena_sql.="DPT_ESTADO, ";
				$cadena_sql.="DPT_TVI_COD ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[1], ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="$variable[0], ";
				$cadena_sql.="$variable[5], ";
				$cadena_sql.="$variable[3], ";
				$cadena_sql.="$variable[4], ";
				$cadena_sql.="$variable[6], ";
				$cadena_sql.="$variable[7], ";
				$cadena_sql.="'".$variable[9]."', ";
				$cadena_sql.="'A', ";
				$cadena_sql.="$variable[8] ";
				$cadena_sql.=")";
				break;
				
			case "borraActividad":
				$cadena_sql="DELETE ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dpt_doc_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_ape_ano = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_ape_per = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_dia_nro = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_hora = ".$variable[4]."";
				break;
			
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD')), ";
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
				$cadena_sql.="ACE_COD_EVENTO = 41 ";
				$cadena_sql.="AND ";
				$cadena_sql.="'".$variable[9]."' BETWEEN TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')) AND TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd')) ";
				break;
				
			case "insertaObservacion":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acdocplantrabajobs ";
				$cadena_sql.="(";
				$cadena_sql.="DPO_APE_ANO, ";
				$cadena_sql.="DPO_APE_PER, ";
				$cadena_sql.="DPO_DOC_NRO_IDEN, ";
				$cadena_sql.="DPO_OBS, ";
				$cadena_sql.="DPO_ESTADO ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="'A' ";
				$cadena_sql.=")";
				break;
				
			case "consultaObservacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="DPO_APE_ANO, ";
				$cadena_sql.="DPO_APE_PER, ";
				$cadena_sql.="DPO_DOC_NRO_IDEN, ";
				$cadena_sql.="DPO_OBS, ";
				$cadena_sql.="DPO_ESTADO ";
				$cadena_sql.="FROM ACDOCPLANTRABAJOBS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPO_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND DPO_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND DPO_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND DPO_ESTADO = 'A'";
				break;
				
			case "modificaObservacion":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACDOCPLANTRABAJOBS ";
				$cadena_sql.="SET ";
				$cadena_sql.="DPO_OBS ='".$variable[3]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPO_APE_ANO = ".$variable[1]."  ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPO_APE_PER = ".$variable[2]."  ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPO_DOC_NRO_IDEN = ".$variable[0]."";
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
