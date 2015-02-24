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
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
			case "carrerasPublicaron":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_dep_cod FACULTAD, ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACASPERI,ACCRA,ACCALEVENTOS ";
				$this->cadena_sql.="WHERE ";
				$this->cadena_sql.="CRA_COD=ACE_CRA_COD ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="CRA_ESTADO='A' ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="ACE_COD_EVENTO=14 ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="ACE_ANIO=APE_ANO ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="ACE_PERIODO=APE_PER ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="APE_ESTADO='A' ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="CRA_COD NOT IN (0,999) ";
				$this->cadena_sql.="ORDER BY CRA_DEP_COD,CRA_TIP_CRA,CRA_COD";
				break;
                            
			case "carrerasNoPublicaron":
				$this->cadena_sql="SELECT DISTINCT ";
				$this->cadena_sql.="cra_dep_cod FACULTAD, ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACASPERI,ACCRA,ACCALEVENTOS ";
				$this->cadena_sql.="WHERE ";
				$this->cadena_sql.="CRA_COD=ACE_CRA_COD ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="CRA_ESTADO='A' ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="ACE_ANIO=APE_ANO ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="ACE_PERIODO=APE_PER ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="APE_ESTADO='A' ";
				$this->cadena_sql.="AND ";
				$this->cadena_sql.="CRA_COD NOT IN (0,999) ";
				break;
				
			case "proyecto_curricular":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACCRA ";
				$this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
                        case "periodo":

                            $this->cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
                            $this->cadena_sql.=" ape_per PERIODO, ";
                            $this->cadena_sql.=" ape_estado ESTADO ";
                            $this->cadena_sql.="  FROM acasperi ";
                            $this->cadena_sql.=" WHERE ape_estado IN ('A')";
                            $this->cadena_sql.=" ORDER BY ape_estado  ASC";
                        break;


                       case "consultaGrupos":
                                $this->cadena_sql="select ";
                                $this->cadena_sql.="cur_nro ";
                                $this->cadena_sql.=",cur_asi_cod ";
                                $this->cadena_sql.=",asi_nombre ";
                                $this->cadena_sql.=",cur_nro_cupo ";
                                $this->cadena_sql.=",cur_nro_ins ";
                                $this->cadena_sql.=",(cur_nro_cupo-cur_nro_ins) ";
                                $this->cadena_sql.=",cur_cap_max  ";
                                $this->cadena_sql.="from ";
                                $this->cadena_sql.="accurso ";
                                $this->cadena_sql.=",acpen ";
                                $this->cadena_sql.=",acasi ";
                                $this->cadena_sql.="where ";
                                $this->cadena_sql.="PEN_ASI_COD=CUR_ASI_COD ";
                                $this->cadena_sql.="and asi_cod=pen_asi_Cod ";
                                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                                $this->cadena_sql.="and pen_nro='".$variable[1]."' ";
                                $this->cadena_sql.="and pen_cra_cod='".$variable[0]."' ";
                                $this->cadena_sql.="and cur_asi_cod='".$variable['espacio']."' ";
                                $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                                $this->cadena_sql.="ORDER BY asi_nombre ASC, cur_nro ASC ";
                                break;

                      case "consultaGruposTodos":
                                $this->cadena_sql="SELECT ";
                                $this->cadena_sql.="cur_nro GRUPO ";
                                $this->cadena_sql.=",cur_asi_cod COD_ESPACIO ";
                                $this->cadena_sql.=",asi_nombre NOM_ESPACIO ";
                                $this->cadena_sql.=",cur_nro_cupo CUPOS ";
                                $this->cadena_sql.=",(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END) INSCRITOS ";
                                $this->cadena_sql.=",(cur_nro_cupo-(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END)) DISPONIBLES ";
                                $this->cadena_sql.=",cur_cra_cod PROYECTO ";
                                $this->cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
                                $this->cadena_sql.="FROM ";
                                $this->cadena_sql.="accurso ";
                                $this->cadena_sql.=",acasi ";
                                $this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                                $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                                $this->cadena_sql.="ORDER BY cur_asi_cod ASC, cur_nro ASC ";
                                break;				


                    case "consultaGruposRapida":
                                $this->cadena_sql="select ";
                                $this->cadena_sql.="cur_nro GRUPO ";
                                $this->cadena_sql.=",cur_asi_cod COD_ESPACIO ";
                                $this->cadena_sql.=",asi_nombre NOM_ESPACIO ";
                                $this->cadena_sql.=",cur_nro_cupo CUPOS ";
                                $this->cadena_sql.=",(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END) INSCRITOS ";
                                $this->cadena_sql.=",(cur_nro_cupo-(CASE WHEN cur_nro_ins IS NULL THEN 0 ELSE cur_nro_ins END)) DISPONIBLES ";
                                $this->cadena_sql.=",cur_cra_cod PROYECTO ";   
                                $this->cadena_sql.=",cur_cap_max MAX_CAPACIDAD ";
                                $this->cadena_sql.="from ";
                                $this->cadena_sql.="accurso ";
                                $this->cadena_sql.=",acasi ";
                                $this->cadena_sql.="where ";
                                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                                if ($variable['tipoBusca']=='nombre')
                                    //{ $this->cadena_sql.="and asi_nombre like UPPER('%".$variable['asignatura']."%') ";}
                                    { $this->cadena_sql.="and UPPER(translate(asi_nombre, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE UPPER(translate('%".$variable['asignatura']."%', 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) ";}
                                else{ $this->cadena_sql.="and cur_asi_cod='".$variable['asignatura']."' ";}
                                $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                                $this->cadena_sql.="ORDER BY cur_asi_cod ASC, cur_nro ASC ";
                                break;
				
			case "verificaCalendario":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="fua_fecha_recibo(".$variable.")";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="DUAL ";
				break;	
                            
                        case "resumenCurso":
                                $this->cadena_sql="SELECT DISTINCT ";
			        $this->cadena_sql.="cur_ape_ano ANIO, ";
			        $this->cadena_sql.="cur_ape_per PERIODO, ";
				$this->cadena_sql.="cur_asi_cod COD_ESPACIO, ";
                                $this->cadena_sql.="asi_nombre NOM_ESPACIO, ";
				$this->cadena_sql.="cur_nro GRUPO, ";
				$this->cadena_sql.="cur_cra_cod COD_PROYECTO ";
                                $this->cadena_sql.="FROM accurso ";
                                $this->cadena_sql.="INNER JOIN acasi ON asi_cod = cur_asi_cod "; 
                                $this->cadena_sql.="WHERE cur_cra_cod='".$variable['proyecto']."' ";
                                $this->cadena_sql.="AND cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
                                if($variable['asignatura'])
                                    {$this->cadena_sql.="AND cur_asi_cod='".$variable['asignatura']."' ";}
                                $this->cadena_sql.="ORDER BY cur_asi_cod, cur_nro ";
				break;	    
                            
                       case "resumenHorarioCurso":
                                $this->cadena_sql="SELECT DISTINCT ";
			        $this->cadena_sql.="hor.hor_dia_nro DIA, ";
				$this->cadena_sql.="hor.hor_hora HORA_C, ";
                                $this->cadena_sql.="h.hor_larga HORA_L, ";
                                $this->cadena_sql.="h.hor_rango HORA_R, ";
				$this->cadena_sql.="cur.cur_nro GRUPO, ";
				$this->cadena_sql.="hor.hor_sed_cod COD_SEDE, ";
                                $this->cadena_sql.="sede.sed_id NOM_SEDE, ";
				$this->cadena_sql.="hor.hor_sal_id_espacio COD_SALON_NVO, ";
                                $this->cadena_sql.="salon.sal_cod COD_SALON_OLD, ";
                                $this->cadena_sql.="salon.sal_nombre NOM_SALON, ";
				$this->cadena_sql.="cur.cur_cra_cod COD_PROYECTO, ";
                                $this->cadena_sql.="salon.sal_edificio ID_EDIFICIO, ";
                                $this->cadena_sql.="edif.edi_nombre NOM_EDIFICIO ";
                                $this->cadena_sql.="FROM accurso cur ";
                                $this->cadena_sql.="LEFT OUTER JOIN achorario_2012 hor ON hor.hor_asi_cod=cur.cur_asi_cod ";
                                $this->cadena_sql.="AND hor.hor_nro=cur.cur_nro ";
                                $this->cadena_sql.="AND hor.hor_ape_ano=cur.cur_ape_ano ";
                                $this->cadena_sql.="AND hor.hor_ape_per=cur.cur_ape_per ";
                                $this->cadena_sql.="LEFT OUTER JOIN gesalon_2012 salon ON hor.hor_sal_id_espacio = salon.sal_id_espacio AND salon.sal_estado='A' ";
                                $this->cadena_sql.="LEFT OUTER JOIN gehora h ON h.hor_cod=hor.hor_hora AND h.hor_estado='A' ";
                                $this->cadena_sql.="LEFT OUTER JOIN gesede sede ON hor.hor_sed_cod=sede.sed_cod ";
                                $this->cadena_sql.="LEFT OUTER JOIN geedificio edif ON salon.sal_edificio=edif.edi_cod ";
				$this->cadena_sql.="WHERE cur.cur_cra_cod='".$variable['proyecto']."' ";
                                $this->cadena_sql.="AND cur.cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="AND cur.cur_asi_cod='".$variable['asignatura']."' ";
                                $this->cadena_sql.="AND cur.cur_ape_per='".$variable['periodo']."' ";
                                $this->cadena_sql.="AND cur.cur_nro='".$variable['grupo']."' ";
                                $this->cadena_sql.="AND hor.hor_dia_nro='".$variable['dia']."' ";
                                $this->cadena_sql.="ORDER BY hor.hor_dia_nro, hor.hor_hora ";
              			break;	     
                            
                            case "fechaactual":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FECHA  ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="dual";
				break;
                            
                            case "valida_fecha":
                                $this->cadena_sql="SELECT ";
                                $this->cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24mmss') FEC_INI, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24mmss') FEC_FIN, ";
                                $this->cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') FEC_FIN_DIA ";
                                $this->cadena_sql.="FROM ";
                                $this->cadena_sql.="accaleventos ";
                                $this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="ACE_ANIO =".$variable['anio'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_PERIODO =".$variable['periodo'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_CRA_COD =".$variable['proyecto'];
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="ACE_COD_EVENTO IN (13,87) ";
                                $this->cadena_sql.=" AND ";
                                $this->cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                            break;
                            
			
			default:
				$this->cadena_sql="";
				break;
		}
		//echo $this->cadena_sql."<br>";
		return $this->cadena_sql;
	}
	
	
}
?>
