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

class sql_copiarHorarios extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "listaPeriodos":
                            switch ($variable['estado'])
                        {
                        case "anterior":
                            $variable['estado']="'P','A'";
                            break;
                        case "nuevo":
                            $variable['estado']="'A','X'";
                            break;
                        }
                            
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per PERIODO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_estado IN (".$variable['estado'].") ";
				$cadena_sql.="ORDER BY ape_ano DESC ";
				break;

			case "periodoNuevo":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per PERIODO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_estado IN ('A','X') ";  
				$cadena_sql.="ORDER BY ape_ano DESC ";
				break;
			
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(CURRENT_TIMESTAMP, 'YYYYMMDDHH24MISS') FECHA  ";
//				$cadena_sql.="FROM ";
				//$cadena_sql.="dual";
                                                             
				break;
                            
                        case "infoCurso":
                                $cadena_sql="SELECT  ";
                                $cadena_sql.=" CUR_ID CUR_ID, ";
                                $cadena_sql.=" CUR_APE_ANO ANIO, ";
                                $cadena_sql.=" CUR_APE_PER PER, ";
                                $cadena_sql.=" CUR_ASI_COD ESPACIO, ";
                                $cadena_sql.=" CUR_GRUPO GRUPO, ";
                                $cadena_sql.=" CUR_CRA_COD PROYECTO, ";
                                $cadena_sql.=" CUR_DEP_COD FACULTAD, ";
                                $cadena_sql.=" CUR_NRO_CUPO CUPOS, ";
                                $cadena_sql.=" CUR_ESTADO ESTADO, ";
                                $cadena_sql.=" CUR_CAP_MAX MAX_CAPACIDAD, ";
                                $cadena_sql.=" coalesce(CUR_HOR_ALTERNATIVO,0) HOR_ALTERNATIVO, ";
                                $cadena_sql.=" coalesce(CUR_TIPO,0) TIPO ";
                                $cadena_sql.=" FROM accursos ";
                                $cadena_sql.=" WHERE cur_grupo>0 ";
                                if(isset($variable['grupo']))
                                        {$cadena_sql.=" AND cur_grupo=".$variable['grupo'];}
                                if(isset($variable['espacio']))
                                        {$cadena_sql.=" AND cur_asi_cod=".$variable['espacio'];}
                                $cadena_sql.=" AND cur_cra_cod=".$variable['proyecto'];
                                $cadena_sql.=" AND cur_ape_ano=".$variable['anio'];
                                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                                $cadena_sql.=" AND cur_estado='A'";
                                $cadena_sql.=" ORDER BY CUR_ASI_COD ASC, CUR_GRUPO ASC ";
                        break;

                        case "insertarCurso":
                                $cadena_sql=" INSERT INTO accursos ";
                                $cadena_sql.="(cur_id,cur_ape_ano, cur_ape_per, cur_asi_cod, cur_grupo, cur_cra_cod, cur_dep_cod, cur_nro_cupo, cur_nro_ins, cur_estado, cur_exa, cur_hab, cur_cap_max, cur_hor_alternativo, cur_tipo)";
                                $cadena_sql.=" VALUES(";
                                $cadena_sql.="'".$variable['cur_id']."',";
                                $cadena_sql.="'".$variable['anio']."',";
                                $cadena_sql.="'".$variable['periodo']."',";
                                $cadena_sql.="'".$variable['espacio']."',";
                                $cadena_sql.="'".$variable['grupo']."',";
                                $cadena_sql.="'".$variable['proyecto']."',";
                                $cadena_sql.="'".$variable['facultad']."',";
                                $cadena_sql.="'".$variable['cupos']."',";
                                $cadena_sql.="'0',";
                                $cadena_sql.="'A',";
                                $cadena_sql.="'30',";
                                $cadena_sql.="'70',";
                                if($variable['max_capacidad']=='' || $variable['max_capacidad']<$variable['cupos'])
                                        { $cadena_sql.="'".$variable['cupos']."',";}
                                else {$cadena_sql.="'".$variable['max_capacidad']."',";}
                                $cadena_sql.="'".$variable['hor_alternativo']."',";
                                $cadena_sql.="'".$variable['tipo']."')";

                                break;      
                         
                         case "buscarHorario":
                                $cadena_sql="SELECT DISTINCT ";
                                $cadena_sql.="HOR_ID HOR_ID, ";
                                $cadena_sql.="HOR_ID_CURSO CURSO_ID, ";
                                $cadena_sql.="HOR_DIA_NRO DIA, ";
                                $cadena_sql.="HOR_HORA HORA, ";
                                $cadena_sql.="HOR_ALTERNATIVA ALTERNATIVO, ";
                                $cadena_sql.="HOR_SAL_ID_ESPACIO COD_SALON, ";
                                $cadena_sql.="HOR_ESTADO HOR_ESTADO, ";  
                                $cadena_sql.="SAL_ESTADO SAL_ESTADO ";  
                                $cadena_sql.="FROM achorarios ";
                                $cadena_sql.="INNER JOIN gesalones ON hor_sal_id_espacio=sal_id_espacio ";
                                $cadena_sql.="INNER JOIN gesubtipo_espacio ON sal_cod_sub=ges_cod_sub ";
                                if(isset($variable['ano']) && isset($variable['periodo']))
                                {  
                                    $cadena_sql.="INNER JOIN accursos ON cur_id=hor_id_curso ";
                                }
                                $cadena_sql.=" WHERE hor_estado='A'";
                                $cadena_sql.=" AND sal_estado='A'";
                                $cadena_sql.=" AND ges_asigna_clase='1'";
                                if(isset($variable['cur_id']))
                                {
                                    $cadena_sql.=" AND hor_id_curso=".$variable['cur_id'];
                                }
                                if(isset($variable['dia']) && isset($variable['hora']) && isset($variable['salon']))
                                {  
                                    $cadena_sql.=" AND HOR_DIA_NRO=".$variable['dia'];
                                    $cadena_sql.=" AND HOR_HORA=".$variable['hora'];
                                    $cadena_sql.=" AND HOR_SAL_ID_ESPACIO='".$variable['salon']."'";
                                }
                                if(isset($variable['ano']) && isset($variable['periodo']))
                                {  
                                    $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                                    $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                                }
                                break;

                         case "registrar_horario":
                                $cadena_sql='INSERT INTO achorarios ';
                                $cadena_sql.='(hor_id,hor_id_curso,hor_dia_nro,hor_hora,hor_alternativa,hor_estado,hor_sal_id_espacio)';
                                $cadena_sql.='VALUES(';
                                $cadena_sql.="'".$variable['hor_id']."', ";
                                $cadena_sql.="'".$variable['cur_id']."', ";
                                $cadena_sql.="'".$variable['dia']."', ";
                                $cadena_sql.="'".$variable['hora']."', ";
                                $cadena_sql.="'".$variable['alternativo']."', ";
                                $cadena_sql.="'".$variable['estado']."', ";
                                $cadena_sql.="'".$variable['salon']."'";
                                $cadena_sql.=")";
                        break;     
                    
                        case "valida_fecha":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24mmss') FEC_INI, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                                $cadena_sql.="TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24mmss') FEC_FIN, ";
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
                                $cadena_sql.="ACE_COD_EVENTO =".$variable['evento'];
                                $cadena_sql.=" AND ";
                                $cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                        break;
                            
                        case "salonesRegistrados":
                                $cadena_sql.="SELECT hor_sal_id_espacio ";
                                $cadena_sql.="FROM mntac.achorario_2012 ";
                                $cadena_sql.="WHERE hor_sed_cod=".$variable['sede'];
                                $cadena_sql.=" AND hor_dia_nro=".$variable['dia'];
                                $cadena_sql.=" AND hor_hora=".$variable['hora'];
                                $cadena_sql.=" AND hor_ape_ano=".$variable['anio'];
                                $cadena_sql.=" AND hor_ape_per=".$variable['periodo'];
                                $cadena_sql.=" AND hor_sal_id_espacio<>'".$variable['salon'];
                                
                         break;    
				
			case "listaProyectos":
				$cadena_sql="SELECT cra_cod, cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_emp_nro_iden = ".$variable['usuario']." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC ";
				break;
			
			case "verificaRegistro":
				$cadena_sql="SELECT * ";
				$cadena_sql.="FROM ";
				$cadena_sql.="achorper ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="hpe_cra_cod = ".$variable['proyecto'];
				$cadena_sql.=" AND hpe_ape_ano_ant=".$variable['anioAnterior'];
                                $cadena_sql.=" AND hpe_ape_per_ant=".$variable['perAnterior'];
                                $cadena_sql.=" AND hpe_ape_ano_nvo=".$variable['anioNuevo'];
                                $cadena_sql.=" AND hpe_ape_per_nvo=".$variable['perNuevo'];
				$cadena_sql.=" AND";
				$cadena_sql.=" hpe_estado = 'A'";
				break;

			case "insertarAnioPer":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="achorper ";
				$cadena_sql.="(";
				$cadena_sql.="hpe_cra_cod, ";
				$cadena_sql.="hpe_ape_ano_ant, ";
				$cadena_sql.="hpe_ape_per_ant, ";
				$cadena_sql.="hpe_ape_ano_nvo, ";
				$cadena_sql.="hpe_ape_per_nvo, ";
				$cadena_sql.="hpe_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable['proyecto'].", ";
				$cadena_sql.=$variable['anioAnterior'].", ";
				$cadena_sql.=$variable['perAnterior'].", ";
				$cadena_sql.=$variable['anioNuevo'].", ";
				$cadena_sql.=$variable['perNuevo'].", ";
				$cadena_sql.="'A'";
				$cadena_sql.=")";
				break;

			case "carrera":
				$cadena_sql="SELECT cra_cod COD_PROYECTO ";
                                $cadena_sql.=", cra_nombre NOML_PROYECTO ";    
                                $cadena_sql.=", cra_abrev NOMC_PROYECTO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_cod = ".$variable['proyecto']." ";
				break;

			case "borrarPeriodo":
				$cadena_sql="DELETE ";
				$cadena_sql.="achorper ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.=" hpe_cra_cod=".$variable['proyecto']."";
                        	$cadena_sql.=" AND hpe_ape_ano_ant=".$variable['anioAnterior']."";
                                $cadena_sql.=" AND hpe_ape_per_ant=".$variable['perAnterior']."";
                                $cadena_sql.=" AND hpe_ape_ano_nvo=".$variable['anioNuevo']."";
                                $cadena_sql.=" AND hpe_ape_per_nvo=".$variable['perNuevo']."";
                            	break;

                        case 'siguienteCurso':
                                $cadena_sql="select nextval('cursos')";
                                break; 

                        case 'siguienteHorario':
                                $cadena_sql="select nextval('horarios')";
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
