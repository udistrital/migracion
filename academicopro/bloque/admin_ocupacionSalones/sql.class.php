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

class sql_AdminOcupacion extends sql
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
                                
                        case 'reporteSalones':
                            $this->cadena_sql=" select dep_cod COD_FACULTAD";
                            $this->cadena_sql.=" ,dep_nombre FACULTAD";
                            $this->cadena_sql.=" ,edi_nombre NOM_EDIFICIO";
                            $this->cadena_sql.=" ,sal_id_espacio COD_SALON";
                            $this->cadena_sql.=" ,sal_nombre NOM_SALON";
                            $this->cadena_sql.=" ,sal_ocupantes CAPACIDAD";
                            $this->cadena_sql.=" ,get_nombre TIPO_ESPACIO";
                            $this->cadena_sql.=" ,DECODE(ges_asigna_clase,0,'NO',1,'SI') ASIGNA_CLASE";
                            $this->cadena_sql.=" from gesalones";
                            $this->cadena_sql.=" inner join geedificio on sal_edificio=edi_cod";
                            $this->cadena_sql.=" inner join gesubtipo_espacio on sal_cod_sub=ges_cod_sub";
                            $this->cadena_sql.=" inner join gedep on sal_id_fac=dep_id_fac";
                            $this->cadena_sql.=" inner join getipo_espacio on sal_get_cod_es=get_cod_es";
                            $this->cadena_sql.=" where SAL_ESTADO='A'";
                            if (isset($variable['facultad']))
                            {
                                $this->cadena_sql.=" and sal_sed_id='".$variable['facultad']."'";
                            }
                            $this->cadena_sql.=" UNION";
                            $this->cadena_sql.=" select 0 COD_FACULTAD";
                            $this->cadena_sql.=" ,'POR ASIGNAR' FACULTAD";
                            $this->cadena_sql.=" ,edi_nombre NOM_EDIFICIO";
                            $this->cadena_sql.=" ,sal_id_espacio COD_SALON";
                            $this->cadena_sql.=" ,sal_nombre NOM_SALON";
                            $this->cadena_sql.=" ,sal_ocupantes CAPACIDAD";
                            $this->cadena_sql.=" ,'POR ASIGNAR' TIPO_ESPACIO";
                            $this->cadena_sql.=" ,'SI' ASIGNA_CLASE";
                            $this->cadena_sql.=" from gesalones";
                            $this->cadena_sql.=" inner join geedificio on sal_edificio=edi_cod";
                            $this->cadena_sql.=" WHERE sal_sed_id='PAS'";
                            $this->cadena_sql.=" ORDER BY FACULTAD,NOM_EDIFICIO,NOM_SALON";
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
