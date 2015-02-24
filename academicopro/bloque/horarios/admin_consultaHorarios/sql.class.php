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

class sql_adminConsultaHorario extends sql
{
	function cadena_sql($opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
			case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
				break;
                            
			case "proyecto_curricular":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
                                $cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
			case "periodo":

				$cadena_sql=" SELECT ape_ano ANIO, ";
				$cadena_sql.=" ape_per PERIODO, ";
				$cadena_sql.=" ape_estado ESTADO ";
				$cadena_sql.="  FROM acasperi ";
				$cadena_sql.=" WHERE ape_estado IN ('A')";
			break;


		   case "consultaGrupos":
					$cadena_sql="select ";
					$cadena_sql.=",cur_id CURSO";
					$cadena_sql.=",cur_grupo GRUPO";
					$cadena_sql.=",cur_asi_cod COD_ESPACIO";
					$cadena_sql.=",asi_nombre NOM_ESPACIO";
					$cadena_sql.=",cur_nro_cupo CUPOS";
					$cadena_sql.=",cur_nro_ins INSCRITOS";
					$cadena_sql.=",(cur_nro_cupo-cur_nro_ins) DISPONIBLES";
					$cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
					$cadena_sql.="from ";
					$cadena_sql.="accursos ";
					$cadena_sql.=",acpen ";
					$cadena_sql.=",acasi ";
					$cadena_sql.="where ";
					$cadena_sql.="PEN_ASI_COD=CUR_ASI_COD ";
					$cadena_sql.="and asi_cod=pen_asi_Cod ";
					$cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
					$cadena_sql.="and pen_nro='".$variable[1]."' ";
					$cadena_sql.="and pen_cra_cod='".$variable[0]."' ";
					$cadena_sql.="and cur_asi_cod='".$variable['espacio']."' ";
					$cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
					$cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
					$cadena_sql.="ORDER BY asi_nombre ASC, cur_grupo ASC ";
					break;

		  case "consultaGruposTodos":
					$cadena_sql="SELECT ";
					$cadena_sql.="cur_id CURSO";
					$cadena_sql.=",cur_grupo GRUPO ";
					$cadena_sql.=",cur_asi_cod COD_ESPACIO ";
					$cadena_sql.=",asi_nombre NOM_ESPACIO ";
					$cadena_sql.=",cur_nro_cupo CUPOS ";
					$cadena_sql.=",(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END) INSCRITOS ";
					$cadena_sql.=",(cur_nro_cupo-(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END)) DISPONIBLES ";
					$cadena_sql.=",cur_cra_cod PROYECTO ";
					$cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
					$cadena_sql.="FROM ";
					$cadena_sql.="accursos ";
					$cadena_sql.=",acasi ";
					$cadena_sql.="WHERE ";
					$cadena_sql.="ASI_COD=CUR_ASI_COD ";
					$cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
					$cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
					$cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
					$cadena_sql.="ORDER BY cur_asi_cod ASC, cur_grupo ASC ";
					break;				


			case "consultaGruposRapida":
					$cadena_sql="select ";
					$cadena_sql.="cur_id CURSO ";
					$cadena_sql.=",cur_grupo GRUPO ";
					$cadena_sql.=",cur_asi_cod COD_ESPACIO ";
					$cadena_sql.=",asi_nombre NOM_ESPACIO ";
					$cadena_sql.=",cur_nro_cupo CUPOS ";
					$cadena_sql.=",(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END) INSCRITOS ";
					$cadena_sql.=",(cur_nro_cupo-(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END)) DISPONIBLES ";
					$cadena_sql.=",cur_cra_cod PROYECTO ";   
					$cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
					$cadena_sql.="from ";
					$cadena_sql.="accursos ";
					$cadena_sql.=",acasi ";
					$cadena_sql.="where ";
					$cadena_sql.="ASI_COD=CUR_ASI_COD ";
					$cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
					if ($variable['tipoBusca']=='nombre')
						//{ $cadena_sql.="and asi_nombre like UPPER('%".$variable['asignatura']."%') ";}
						{ $cadena_sql.="and UPPER(translate(asi_nombre, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE UPPER(translate('%".$variable['asignatura']."%', 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) ";}
					else{ $cadena_sql.="and cur_asi_cod='".$variable['asignatura']."' ";}
					$cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
					$cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
					$cadena_sql.="ORDER BY cur_asi_cod ASC, cur_grupo ASC ";
			break;
			
			case "verificaCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="fua_fecha_recibo(".$variable.")";
				$cadena_sql.="FROM ";
				$cadena_sql.="DUAL ";
			break;	
                            
			case "resumenCurso":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="cur_ape_ano ANIO, ";
				$cadena_sql.="cur_ape_per PERIODO, ";
				$cadena_sql.="cur_asi_cod COD_ESPACIO, ";
				$cadena_sql.="asi_nombre NOM_ESPACIO, ";
				$cadena_sql.="cur_grupo GRUPO, ";
				$cadena_sql.="cur_cra_cod COD_PROYECTO ";
				$cadena_sql.="FROM accursos ";
				$cadena_sql.="INNER JOIN acasi ON asi_cod = cur_asi_cod "; 
				$cadena_sql.="WHERE cur_cra_cod='".$variable['proyecto']."' ";
				$cadena_sql.="AND cur_ape_ano='".$variable['anio']."' ";
				$cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
				if($variable['asignatura'])
				{$cadena_sql.="AND cur_asi_cod='".$variable['asignatura']."' ";}
				$cadena_sql.="ORDER BY cur_asi_cod, cur_grupo ";
			break;	    
						
			case "resumenHorarioCurso":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="hor.hor_dia_nro DIA, ";
				$cadena_sql.="hor.hor_hora HORA_C, ";
				$cadena_sql.="h.hor_larga HORA_L, ";
				$cadena_sql.="h.hor_rango HORA_R, ";
				$cadena_sql.="cur.cur_grupo GRUPO, ";
				$cadena_sql.="sede.sed_cod COD_SEDE, ";
				$cadena_sql.="sede.sed_id NOM_SEDE, ";
				$cadena_sql.="hor.hor_sal_id_espacio COD_SALON_NVO, ";
				$cadena_sql.="salon.sal_cod COD_SALON_OLD, ";
				$cadena_sql.="salon.sal_nombre NOM_SALON, ";
				$cadena_sql.="cur.cur_cra_cod COD_PROYECTO, ";
				$cadena_sql.="salon.sal_edificio ID_EDIFICIO, ";
				$cadena_sql.="edif.edi_nombre NOM_EDIFICIO ";
				$cadena_sql.="FROM accursos cur ";
				$cadena_sql.="LEFT OUTER JOIN achorarios hor ON hor.hor_id_curso=cur.cur_id ";
				$cadena_sql.="LEFT OUTER JOIN gesalones salon ON hor.hor_sal_id_espacio = salon.sal_id_espacio AND salon.sal_estado='A' ";
				$cadena_sql.="LEFT OUTER JOIN gehora h ON h.hor_cod=hor.hor_hora AND h.hor_estado='A' ";
				$cadena_sql.="LEFT OUTER JOIN geedificio edif ON salon.sal_edificio=edif.edi_cod ";
				$cadena_sql.="LEFT OUTER JOIN gesede sede ON edif.edi_sed_id=sede.sed_id ";
				$cadena_sql.="WHERE cur.cur_cra_cod='".$variable['proyecto']."' ";
				$cadena_sql.="AND cur.cur_ape_ano='".$variable['anio']."' ";
				$cadena_sql.="AND cur.cur_asi_cod='".$variable['asignatura']."' ";
				$cadena_sql.="AND cur.cur_ape_per='".$variable['periodo']."' ";
				$cadena_sql.="AND cur.cur_grupo='".$variable['grupo']."' ";
				$cadena_sql.="AND hor.hor_dia_nro='".$variable['dia']."' ";
				$cadena_sql.="ORDER BY hor.hor_dia_nro, hor.hor_hora ";
			break;	     
						
			case "valida_fecha":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss') FEC_INI, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') FEC_FIN, ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') FEC_FIN_DIA ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACE_ANIO =".$variable['anio'];
				$cadena_sql.=" AND ";
				$cadena_sql.="ACE_PERIODO =".$variable['periodo'];
				$cadena_sql.=" AND ";
				$cadena_sql.="ACE_CRA_COD =".$variable['proyecto'];
				$cadena_sql.=" AND ";
				$cadena_sql.="ACE_COD_EVENTO IN (13,87) ";
				$cadena_sql.=" AND ";
				$cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24miss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24miss') ";
			break;
                    
			default:
				$cadena_sql="";
			break;
		}
		return $cadena_sql;
	}
	
	
}
?>
