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

class sql_adminMenuCierreSemestre extends sql
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
				
			case "verificaCalendario":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="fua_fecha_recibo(".$variable.")";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="DUAL ";
				break;	
                        
                        case "carreraCoordinador":
				$this->cadena_sql="SELECT ";
				$this->cadena_sql.="cra_cod CODIGO, ";
				$this->cadena_sql.="cra_abrev NOMBRE ";
				$this->cadena_sql.="FROM ";
				$this->cadena_sql.="ACCRA ";
				$this->cadena_sql.="WHERE ";
				$this->cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
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
