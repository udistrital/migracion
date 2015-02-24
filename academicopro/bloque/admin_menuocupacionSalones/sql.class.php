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

class sql_adminMenuOcupacion extends sql
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
                                $this->cadena_sql.=" WHERE ape_estado IN ('A','P','X')";
                                $this->cadena_sql.=" ORDER BY ape_ano ASC, ape_per ASC";
                                break;
			
                        case 'consultarSedes':
                                     case "sede":
                                $this->cadena_sql="SELECT sed_cod COD_SEDE ";
                                $this->cadena_sql.=",sed_id NOMC_SEDE ";
                                $this->cadena_sql.=",sed_nombre NOML_SEDE ";
                                $this->cadena_sql.=",sed_id ID_SEDE ";
                                $this->cadena_sql.=" FROM gesede  ";
                                $this->cadena_sql.=" WHERE sed_estado = 'A' ";
                                $this->cadena_sql.=" AND sed_id IS NOT null ";
                                $this->cadena_sql.=" ORDER BY sed_nombre ";                       
                                break;

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
