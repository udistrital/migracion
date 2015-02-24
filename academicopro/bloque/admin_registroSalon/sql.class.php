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

class sql_registroSalon extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="")
	{
			
	  switch($tipo)
             {  case "proyectos_curriculares":
                    $this->cadena_sql=" SELECT UNIQUE CRA_COD, CRA_NOMBRE ";
                    $this->cadena_sql.="FROM ".$configuracion['bd_proyecto'].".carrera ";
                    $this->cadena_sql.="INNER JOIN geusucra ON accra.cra_cod = geusucra.usucra_cra_cod ";
                    $this->cadena_sql.="WHERE geusucra.usucra_nro_iden = ".$variable;
                    $this->cadena_sql.=" ORDER BY CRA_NOMBRE";
                break;
                case "hora":
                    $this->cadena_sql="SELECT HOR_COD HORA_C, ";
                    $this->cadena_sql.="HOR_LARGA HORA_L ";
                    $this->cadena_sql.=" FROM gehora ";
                    $this->cadena_sql.=" WHERE HOR_COD= ".$variable;
                break;
                case "dia":
                    $this->cadena_sql="SELECT DIA_NOMBRE DIA ";
                    $this->cadena_sql.=" FROM gedia  ";
                    $this->cadena_sql.="WHERE DIA_COD= ".$variable;
                break;
            
                case "sede":
                    $this->cadena_sql="SELECT sed_cod COD_SEDE ";
                    $this->cadena_sql.=",sed_id NOMC_SEDE ";
                    $this->cadena_sql.=",sed_nombre NOML_SEDE ";
                    $this->cadena_sql.=",sed_id ID_SEDE ";
                    $this->cadena_sql.=" FROM gesede  ";
                    $this->cadena_sql.=" WHERE sed_estado = 'A' ";
                    $this->cadena_sql.=" AND sed_id IS NOT null ";
                    if($variable['sede']>=0)
                        {$this->cadena_sql.=" AND sed_id='".$variable['sede']."'";}
                    $this->cadena_sql.=" ORDER BY sed_nombre ";                       

                break;
                
                case "sede_codigo":
                    $this->cadena_sql="SELECT sed_cod COD_SEDE ";
                    $this->cadena_sql.=",sed_id NOMC_SEDE ";
                    $this->cadena_sql.=",sed_nombre NOML_SEDE ";
                    $this->cadena_sql.=",sed_id ID_SEDE ";
                    $this->cadena_sql.=" FROM gesede  ";
                    $this->cadena_sql.=" WHERE sed_estado = 'A' ";
                    $this->cadena_sql.=" AND sed_id IS NOT null ";
                    $this->cadena_sql.=" AND sed_cod='".$variable['sede']."'";        

                break;
                
                
                case "horario":
                    $this->cadena_sql='SELECT hor_sal_id_espacio SALON, ';
                    $this->cadena_sql.=' hor_sed_cod COD_SEDE ';
                    $this->cadena_sql.=' FROM achorario_2012 ';
                    $this->cadena_sql.=' WHERE hor_asi_cod='.$variable['espacio'];
                    $this->cadena_sql.=' AND hor_nro='.$variable['grupo'];
                    $this->cadena_sql.=' AND hor_dia_nro='.$variable['dia'];
                    $this->cadena_sql.=' AND hor_hora='.$variable['hora'];
                    if($variable['anio'] && $variable['periodo'])
                        {$this->cadena_sql.=' AND hor_ape_ano='.$variable['anio'];
                         $this->cadena_sql.=' AND hor_ape_per='.$variable['periodo'];}
                break;
                
                case "salon":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="SALON.SAL_COD COD_SALON_OLD,";
                $this->cadena_sql.="SALON.SAL_ID_ESPACIO COD_SALON_NVO, ";
                $this->cadena_sql.="SALON.SAL_NOMBRE NOM_SALON, ";
                $this->cadena_sql.="SALON.SAL_OCUPANTES CUPOS, ";
                $this->cadena_sql.="SALON.SAL_SED_COD COD_SEDE, ";
                $this->cadena_sql.="SALON.sal_edificio ID_EDIFICIO, ";
                $this->cadena_sql.="EDIF.edi_nombre NOM_EDIFICIO ";
                $this->cadena_sql.="FROM gesalon_2012 SALON , geedificio EDIF ";
                $this->cadena_sql.=" WHERE SALON.sal_edificio=EDIF.edi_cod ";
                if($variable['salon'])
                    {$this->cadena_sql.=" AND SALON.SAL_ID_ESPACIO='".$variable['salon']."'"; }
                elseif($variable['sede'])
                    {$this->cadena_sql.=" AND SALON.SAL_SED_COD='".$variable['sede']."'"; }
                break;
                
                case "salones":
                $this->cadena_sql=" SELECT ";
                $this->cadena_sql.="sal_id_espacio COD_SALON_NVO, ";
                $this->cadena_sql.="sal_nombre NOM_SALON, ";
                $this->cadena_sql.="sal_cod COD_SALON_OLD, ";
                $this->cadena_sql.="sal_ocupantes CUPOS, ";
                $this->cadena_sql.="sal_tipo TIPO_SALON, ";
                $this->cadena_sql.="sal_edificio ID_EDIFICIO, ";
                $this->cadena_sql.="edi_nombre NOM_EDIFICIO ";
                $this->cadena_sql.="from mntge.gesalon_2012, geedificio ";
                $this->cadena_sql.=" where SAL_ESTADO='A' ";
                $this->cadena_sql.=" AND sal_edificio=edi_cod ";
                $this->cadena_sql.=" AND sal_sed_cod=".$variable['sede'];
                
             if($variable['sede']!=0)
               {
                $this->cadena_sql.=" AND sal_ocupantes >1";
               // $this->cadena_sql.=" AND sal_ocupantes >=".$variable['cupos'];//filtro para busqueda de salon por capacidad
               // $this->cadena_sql.=" AND sal_ocupantes <".($variable['cupos']*1.5);//filtro para busqueda de salon por capacidad + la mitad
                $this->cadena_sql.=" AND sal_id_espacio NOT IN ";
                    $this->cadena_sql.="(SELECT hor_sal_id_espacio ";
                    $this->cadena_sql.="FROM mntac.achorario_2012 ";
                    $this->cadena_sql.="WHERE hor_sed_cod=".$variable['sede'];
                    $this->cadena_sql.=" AND hor_dia_nro=".$variable['dia'];
                    $this->cadena_sql.=" AND hor_hora=".$variable['hora'];
                    $this->cadena_sql.=" AND hor_ape_ano=".$variable['anio'];
                    $this->cadena_sql.=" AND hor_ape_per=".$variable['periodo'];
                    $this->cadena_sql.=" AND hor_sal_id_espacio<>'".$variable['salon'];
                    $this->cadena_sql.="' ) ORDER BY sal_cod"; //echo $busqueda;
                }   
                    
                 break;
                
                
                case "registrar_horario":
                    $this->cadena_sql='INSERT INTO achorario_2012 ';
                    $this->cadena_sql.='(hor_ape_ano,hor_ape_per,hor_asi_cod,hor_nro,hor_dia_nro,hor_hora,hor_sed_cod,hor_sal_id_espacio,hor_estado)';
                    $this->cadena_sql.=' VALUES(';
                    $this->cadena_sql.="".$variable['anio'].", ";
                    $this->cadena_sql.="".$variable['periodo'].", ";
                    $this->cadena_sql.="".$variable['espacio'].", ";
                    $this->cadena_sql.="".$variable['grupo'].", ";
                    $this->cadena_sql.="".$variable['dia'].", ";
                    $this->cadena_sql.="".$variable['hora'].", ";
                    $this->cadena_sql.="".$variable['sede'].", ";
                    $this->cadena_sql.="'".$variable['salon']."', ";
                    $this->cadena_sql.="'".$variable['estado']."'";
                    $this->cadena_sql.=")";
                break;                
              case "borrarhorario":
                    $this->cadena_sql='DELETE ';
                    $this->cadena_sql.=' FROM achorario_2012 ';
                    $this->cadena_sql.=' WHERE hor_asi_cod='.$variable['espacio'];
                    $this->cadena_sql.=' AND hor_nro='.$variable['grupo'];
                    $this->cadena_sql.=' AND hor_dia_nro='.$variable['dia'];
                    $this->cadena_sql.=' AND hor_hora='.$variable['hora'];
                    $this->cadena_sql.=' AND hor_ape_ano='.$variable['anio'];
                    $this->cadena_sql.=' AND hor_ape_per='.$variable['periodo'];
                break;
            
                case "consultaGrupos":
                    $this->cadena_sql="select distinct ";
                    $this->cadena_sql.="cur_nro GRUPO";
                    $this->cadena_sql.=",cur_asi_cod ESPACIO";
                    $this->cadena_sql.=",asi_nombre NOM_ESPACIO";
                    $this->cadena_sql.=",cur_nro_cupo CUPOS";
                    $this->cadena_sql.=",cur_nro_ins INSCRITOS";
                    $this->cadena_sql.=",(cur_nro_cupo-cur_nro_ins) DISPONIBLE ";
                    $this->cadena_sql.=",cur_cap_max  MAX_CAPACIDAD ";
                    $this->cadena_sql.="from ";
                    $this->cadena_sql.="accurso ";
                    $this->cadena_sql.=",acpen ";
                    $this->cadena_sql.=",acasi ";
                    $this->cadena_sql.="where ";
                    $this->cadena_sql.="PEN_ASI_COD=CUR_ASI_COD ";
                    $this->cadena_sql.="and asi_cod=pen_asi_Cod ";
                    $this->cadena_sql.="and cur_nro='".$variable['grupo']."' ";
                    $this->cadena_sql.="and cur_asi_cod='".$variable['espacio']."' ";
                    $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                    $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                    $this->cadena_sql.="ORDER BY asi_nombre ASC, cur_nro ASC ";
                    break;  
            
    	}
	return $this->cadena_sql;
    }
}
?>
