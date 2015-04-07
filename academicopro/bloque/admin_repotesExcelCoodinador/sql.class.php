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

class sql_ReportesExcelCoodinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
			
                        case "periodo":

                            $this->cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
                            $this->cadena_sql.=" ape_per PERIODO, ";
                            $this->cadena_sql.=" ape_estado ESTADO ";
                            $this->cadena_sql.="  FROM acasperi ";
                            $this->cadena_sql.=" WHERE ape_estado IN ('A')";
                            $this->cadena_sql.=" ORDER BY ape_estado  ASC";
                        break;
	
				case "verificaCalendario":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="fua_fecha_recibo(".$variable.")";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="DUAL ";
				break;	
  
				case "rescatarCarrera":
					$this->cadena_sql="SELECT ";
					$this->cadena_sql.="CRA_NOMBRE ";
					$this->cadena_sql.="FROM ";
					$this->cadena_sql.="ACCRA ";
					$this->cadena_sql.="WHERE CRA_COD=".$variable['proyecto'];
				break;	
				
				case "resumenCurso":
				$this->cadena_sql="SELECT DISTINCT ";
				$this->cadena_sql.="cur_ape_ano ANIO, ";
				$this->cadena_sql.="cur_ape_per PERIODO, ";
				$this->cadena_sql.="cur_asi_cod COD_ESPACIO, ";
				$this->cadena_sql.="asi_nombre NOM_ESPACIO, ";
				$this->cadena_sql.="cur_grupo GRUPO, ";
				$this->cadena_sql.="cur_cra_cod COD_PROYECTO ";
				$this->cadena_sql.="FROM accursos ";
				$this->cadena_sql.="INNER JOIN acasi ON asi_cod = cur_asi_cod "; 
				$this->cadena_sql.="WHERE cur_cra_cod='".$variable['proyecto']."' ";
				$this->cadena_sql.="AND cur_ape_ano='".$variable['anio']."' ";
				$this->cadena_sql.="AND cur_ape_per='".$variable['periodo']."' ";
				if($variable['asignatura'])
				{$this->cadena_sql.="AND cur_asi_cod='".$variable['asignatura']."' ";}
				$this->cadena_sql.="ORDER BY cur_asi_cod, cur_grupo ";
				break;	    
                            
                       case "resumenHorarioCurso":
				$this->cadena_sql="SELECT DISTINCT ";
				$this->cadena_sql.="hor.hor_dia_nro DIA, ";
				$this->cadena_sql.="hor.hor_hora HORA_C, ";
				$this->cadena_sql.="h.hor_larga HORA_L, ";
				$this->cadena_sql.="h.hor_rango HORA_R, ";
				$this->cadena_sql.="cur.cur_grupo GRUPO, ";
				$this->cadena_sql.="sede.sed_cod COD_SEDE, ";
				$this->cadena_sql.="sede.sed_id NOM_SEDE, ";
				$this->cadena_sql.="hor.hor_sal_id_espacio COD_SALON_NVO, ";
				$this->cadena_sql.="salon.sal_cod COD_SALON_OLD, ";
				$this->cadena_sql.="salon.sal_nombre NOM_SALON, ";
				$this->cadena_sql.="cur.cur_cra_cod COD_PROYECTO, ";
				$this->cadena_sql.="salon.sal_edificio ID_EDIFICIO, ";
				$this->cadena_sql.="edif.edi_nombre NOM_EDIFICIO ";
				$this->cadena_sql.="FROM accursos cur ";
				$this->cadena_sql.="LEFT OUTER JOIN achorarios hor ON hor.hor_id_curso=cur.cur_id ";
				$this->cadena_sql.="LEFT OUTER JOIN gesalones salon ON hor.hor_sal_id_espacio = salon.sal_id_espacio AND salon.sal_estado='A' ";
				$this->cadena_sql.="LEFT OUTER JOIN gehora h ON h.hor_cod=hor.hor_hora AND h.hor_estado='A' ";
				$this->cadena_sql.="LEFT OUTER JOIN geedificio edif ON salon.sal_edificio=edif.edi_cod ";
				$this->cadena_sql.="LEFT OUTER JOIN gesede sede ON edif.edi_sed_id=sede.sed_id ";
				$this->cadena_sql.="WHERE cur.cur_cra_cod='".$variable['proyecto']."' ";
				$this->cadena_sql.="AND cur.cur_ape_ano='".$variable['anio']."' ";
				$this->cadena_sql.="AND cur.cur_asi_cod='".$variable['asignatura']."' ";
				$this->cadena_sql.="AND cur.cur_ape_per='".$variable['periodo']."' ";
				$this->cadena_sql.="AND cur.cur_grupo='".$variable['grupo']."' ";
				$this->cadena_sql.="AND hor.hor_dia_nro='".$variable['dia']."' ";
				$this->cadena_sql.="ORDER BY hor.hor_dia_nro, hor.hor_hora ";
              			break;	     
                            
                            case "fechaactual":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FECHA  ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="dual";
				break;
                            
                           case "resumenOcupacion":
                                $this->cadena_sql="SELECT DISTINCT ";
			        $this->cadena_sql.="cur.cur_ape_ano ANIO, ";
                                $this->cadena_sql.="cur.cur_ape_per PERIODO, ";
                                $this->cadena_sql.="hor.hor_dia_nro COD_DIA, ";
                                $this->cadena_sql.="d.dia_nombre DIA, ";
				$this->cadena_sql.="hor.hor_hora HORA_C, ";
                                $this->cadena_sql.="h.hor_larga HORA_L, ";
                                $this->cadena_sql.="h.hor_rango HORA_R, ";
				$this->cadena_sql.="cur.cur_grupo GRUPO, ";
                                $this->cadena_sql.="asig.asi_cod COD_ESPACIO, ";
                                $this->cadena_sql.="asig.asi_nombre ESPACIO, ";
                                $this->cadena_sql.="sede.sed_id ID_SEDE, ";
                                $this->cadena_sql.="sede.sed_nombre NOM_SEDE, ";
				$this->cadena_sql.="hor.hor_sal_id_espacio COD_SALON_NVO, ";
                                $this->cadena_sql.="salon.sal_cod COD_SALON_OLD, ";
                                $this->cadena_sql.="salon.sal_nombre NOM_SALON, ";
                                $this->cadena_sql.="salon.sal_ocupantes CAP_SALON, ";
				$this->cadena_sql.="cur.cur_cra_cod COD_PROYECTO, ";
                                $this->cadena_sql.="cra.cra_nombre PROYECTO, ";
                                $this->cadena_sql.="salon.sal_edificio ID_EDIFICIO, ";
                                $this->cadena_sql.="edif.edi_nombre NOM_EDIFICIO, ";
                                $this->cadena_sql.="cur.cur_nro_ins INSCRITOS, ";
                                $this->cadena_sql.="doc_nombre || ' ' || doc_apellido DOCENTE ";
                                $this->cadena_sql.="FROM accursos cur ";
                                $this->cadena_sql.="INNER JOIN accra cra ON cra.cra_cod=cur.cur_cra_cod ";
                                $this->cadena_sql.="INNER JOIN acasi asig ON asig.asi_cod=cur.cur_asi_cod ";
                                $this->cadena_sql.="LEFT OUTER JOIN achorarios hor ON hor.hor_id_curso=cur.cur_id ";
				$this->cadena_sql.="LEFT OUTER JOIN gesalones salon ON hor.hor_sal_id_espacio = salon.sal_id_espacio AND salon.sal_estado='A' ";
                                $this->cadena_sql.="LEFT OUTER JOIN gedia d ON d.dia_cod=hor.hor_dia_nro  ";
                                $this->cadena_sql.="LEFT OUTER JOIN gehora h ON h.hor_cod=hor.hor_hora AND h.hor_estado='A' ";
                                $this->cadena_sql.="LEFT OUTER JOIN geedificio edif ON salon.sal_edificio=edif.edi_cod ";                                                             
                                $this->cadena_sql.="LEFT OUTER JOIN gesede sede ON edif.edi_sed_id=sede.sed_id ";
                                $this->cadena_sql.="LEFT OUTER Join Accargas Carga On Carga.Car_hor_id=hor_id "; 
                                $this->cadena_sql.="LEFT OUTER join acdocente on doc_nro_iden=car_doc_nro and doc_estado='A' ";
				$this->cadena_sql.="WHERE cur.cur_ape_ano='".$variable['anio']."' ";
                                $this->cadena_sql.="AND cur.cur_ape_per='".$variable['periodo']."' ";
                                if($variable['sede']!='')
                                    {$this->cadena_sql.="AND (sede.sed_id like upper('%".$variable['sede']."%') OR sede.sed_nombre like upper('%".$variable['sede']."%') ) ";
                                    }
                                if($variable['edificio']!='')
                                    {$this->cadena_sql.="AND (edif.edi_cod like upper('%".$variable['edificio']."%') OR edif.edi_nombre like upper('%".$variable['edificio']."%') )  ";
                                     }    
                                if($variable['salon']!='')
                                    {$this->cadena_sql.="AND (hor.hor_sal_id_espacio like upper('%".$variable['salon']."%') OR salon.sal_nombre like upper('%".$variable['salon']."%') )  ";
                                     }
                                if($variable['proyecto']!='')
                                    {   if(is_numeric($variable['proyecto'])) 
                                            {$this->cadena_sql.="AND cur.cur_cra_cod='".$variable['proyecto']."' " ; }
                                        else
                                            { $this->cadena_sql.="AND  cra.cra_nombre like UPPER('%".$variable['proyecto']."%') " ; }
                                    }
                                if($variable['espacio']!='')
                                    {   if(is_numeric($variable['espacio'])) 
                                            {$this->cadena_sql.="AND asig.asi_cod='".$variable['espacio']."' " ; }
                                        else
                                            { $this->cadena_sql.="AND asig.asi_nombre like UPPER('%".$variable['espacio']."%') " ; }
                                    }
                                if($variable['dia']!='')
                                    {   if(is_numeric($variable['dia'])) 
                                            {$this->cadena_sql.="AND hor.hor_dia_nro='".$variable['dia']."' " ; }
                                        else
                                            { $this->cadena_sql.="AND  d.dia_nombre like UPPER('%".$variable['dia']."%') " ; }
                                    }
                                if($variable['hora']!='')
                                    {$this->cadena_sql.="AND hor.hor_hora='".$variable['hora']."' ";}
                                $this->cadena_sql.="ORDER BY hor.hor_dia_nro, hor.hor_hora, cur.cur_grupo, asig.asi_cod ";
                                
                               break;	     
                            
			
			default:
				$this->cadena_sql="";
				break;
		}
		return $this->cadena_sql;
	}
	
	
}
?>
