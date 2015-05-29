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

class sql_adminMenuHorario extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
                            
			case "proyecto_curricular":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_cod CODIGO, ";
                                $this->cadena_sql.="cra_nombre NOMBRE_LARGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACCRA ";
				$this->cadena_sql.="WHERE ";
                                $this->cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
                        case "periodo":
                                $this->cadena_sql=" SELECT ape_ano ANIO, ape_per PERIODO, ape_estado ESTADO FROM ACASPERI ";
                                $this->cadena_sql.=" WHERE ape_estado IN ('A','P','X','V')";
                                $this->cadena_sql.=" ORDER BY ape_ano ASC, ape_per ASC";
                                break;
				
			case "verificaCalendario":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="fua_fecha_recibo(".$variable.")";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="DUAL ";
				break;	
                            
                        case "consultaAsignatura":
                                $this->cadena_sql="SELECT DISTINCT ";
                                $this->cadena_sql.="asi_cod COD_ESPACIO ";
                                $this->cadena_sql.=",asi_nombre NOM_ESPACIO ";
                                $this->cadena_sql.=",cur_cra_cod PROYECTO ";                        
                                $this->cadena_sql.="FROM ";
                                $this->cadena_sql.="accursos ";
                                $this->cadena_sql.=",acasi ";
                                $this->cadena_sql.="where ";
                                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                                if ($variable['tipoBusca']=='nombre')
                                    //{ $this->cadena_sql.="and asi_nombre like UPPER('%".$variable['asignatura']."%') ";}
                                    { $this->cadena_sql.="and UPPER(translate(asi_nombre, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE UPPER(translate('%".$variable['asignatura']."%', 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) ";}
                                else{ $this->cadena_sql.="and cur_asi_cod='".$variable['asignatura']."' ";}
                                if ($variable['anio'] && $variable['periodo'])
                                    {
                                    $this->cadena_sql.="and cur_ape_ano='".$variable['anio']."' ";
                                    $this->cadena_sql.="and cur_ape_per='".$variable['periodo']."' ";
                                    }
                                break;    
                                
                        case "nombreAsignatura":
                                $this->cadena_sql="SELECT DISTINCT ";
                                $this->cadena_sql.="asi_cod COD_ESPACIO ";
                                $this->cadena_sql.=",asi_nombre NOM_ESPACIO ";
                                $this->cadena_sql.=",cur_cra_cod PROYECTO ";                        
                                $this->cadena_sql.="FROM ";
                                $this->cadena_sql.="accursos ";
                                $this->cadena_sql.=",acasi ";
                                $this->cadena_sql.="where ";
                                $this->cadena_sql.="ASI_COD=CUR_ASI_COD ";
                                $this->cadena_sql.="and cur_cra_cod='".$variable['proyecto']."' ";
                                if ($variable['tipoBusca']=='nombre')
                                    //{ $this->cadena_sql.="and asi_nombre like UPPER('%".$variable['asignatura']."%') ";}
                                    { $this->cadena_sql.="and UPPER(translate(asi_nombre, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) LIKE UPPER(translate('%".$variable['asignatura']."%', 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU')) ";}
                                else{ $this->cadena_sql.="and cur_asi_cod='".$variable['asignatura']."' ";}
 
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
