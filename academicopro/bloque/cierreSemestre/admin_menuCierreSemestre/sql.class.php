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
  public $configuracion;


  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }
    
	function cadena_sql($opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
                            
			case "proyecto_curricular":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
                                $cadena_sql.="cra_nombre NOMBRE_LARGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
                                $cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
                        case "periodo":
                            //El periodo se establece a P para los cierres de los posgrados posterior a la fecha del pregrado 26/12/2013
                            //El período se cambia de P a A para el proceso de cierre de pregrado
                                $cadena_sql=" SELECT ape_ano ANIO, ape_per PERIODO, ape_estado ESTADO FROM ACASPERI ";
                                $cadena_sql.=" WHERE ape_estado IN ('A','P','X')";
                                $cadena_sql.=" ORDER BY ape_ano ASC, ape_per ASC";
                                break;
				
			case "verificaCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="fua_fecha_recibo(".$variable.")";
				$cadena_sql.="FROM ";
				$cadena_sql.="DUAL ";
				break;	
                        
                        case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
				break;    
                           
			
			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
