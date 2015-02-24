<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
require_once("clase/config.class.php");
	setlocale(LC_MONETARY, 'en_US');
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable();
	 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_xajax extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
	function cadena_sql($configuracion,$tipo,$variable)
		{
			switch($tipo)
			{
					
			case "buscar_max":
						$this->cadena_sql="SELECT ";
						$this->cadena_sql.="(ACRECRA_COD) ";
						$this->cadena_sql.="FROM ";
						$this->cadena_sql.="MNTAC.ACRECRA ";
						break;
						
			case "guarda_acre2":
						$this->cadena_sql="INSERT INTO ";
						$this->cadena_sql.="MNTAC.ACRECRA ";
						$this->cadena_sql.="(";
						$this->cadena_sql.="ACRECRA_COD, "; 
						$this->cadena_sql.="ACRE_COD_CRA, ";
						$this->cadena_sql.="ACRE_COD_TIPO, ";
						$this->cadena_sql.="ACRE_FECHA, ";
						$this->cadena_sql.="ACRE_RESOLUCION, ";
						$this->cadena_sql.="ACRE_DURACION, ";
						$this->cadena_sql.="ACRE_ENTIDAD ";
						$this->cadena_sql.=") ";
						$this->cadena_sql.="VALUES (";
						$this->cadena_sql.="'".$variable[0]."', ";
						$this->cadena_sql.="'".$variable[1]."', ";
						$this->cadena_sql.="'".$variable[2]."', ";
						$this->cadena_sql.="".$variable[3].", ";
						$this->cadena_sql.="".$variable[4].", ";
						$this->cadena_sql.="'".$variable[5]."', ";
						$this->cadena_sql.="'".$variable[6]."' ";
						$this->cadena_sql.=")";
						break;
						
				case "guarda_acre":
						$this->cadena_sql="INSERT INTO ";
						$this->cadena_sql.="MNTAC.ACRECRA ";
						$this->cadena_sql.="(";
						$this->cadena_sql.="ACRECRA_COD, "; 
						$this->cadena_sql.="ACRE_COD_CRA, ";
						$this->cadena_sql.="ACRE_COD_TIPO, ";
						$this->cadena_sql.="ACRE_FECHA, ";
						$this->cadena_sql.="ACRE_RESOLUCION, ";
						$this->cadena_sql.="ACRE_DURACION, ";
						$this->cadena_sql.="ACRE_ENTIDAD ";
						$this->cadena_sql.=") ";
						$this->cadena_sql.="VALUES (";
						$this->cadena_sql.="'".$variable[0]."', ";
						$this->cadena_sql.="'".$variable[1]."', ";
						$this->cadena_sql.="'".$variable[2]."', ";
						$this->cadena_sql.="'', ";
						$this->cadena_sql.="'', ";
						$this->cadena_sql.="'', ";
						$this->cadena_sql.="'' ";
						$this->cadena_sql.=")";
						break;			
			
			case "busca_acred":
						//ORACLE
						$this->cadena_sql="SELECT ";
						$this->cadena_sql.="CRA.CRA_NOMBRE, ";
						$this->cadena_sql.="ACRE.ACRECRA_COD, "; 
						$this->cadena_sql.="ACRE.ACRE_COD_CRA, ";
						$this->cadena_sql.="ACRE.ACRE_COD_TIPO, ";
						$this->cadena_sql.="ACRE.ACRE_RESOLUCION, ";
						$this->cadena_sql.="ACRE.ACRE_FECHA, ";
						$this->cadena_sql.="ACRE.ACRE_DURACION, ";
						$this->cadena_sql.="ACRE.ACRE_ENTIDAD ";
						$this->cadena_sql.="FROM ";
						$this->cadena_sql.="MNTAC.ACRECRA ACRE ";
						$this->cadena_sql.="INNER JOIN ";
						$this->cadena_sql.="MNTAC.ACCRA CRA ";
						$this->cadena_sql.="ON ACRE.ACRE_COD_CRA = CRA.CRA_COD ";				
						$this->cadena_sql.="WHERE ";
						$this->cadena_sql.="ACRE.ACRECRA_COD= ";
						$this->cadena_sql.="'".$variable."'";
						//echo $this->cadena_sql;
						break;	
				
				case "actualiza_acred":
						$this->cadena_sql="UPDATE ";
						$this->cadena_sql.="MNTAC.ACRECRA "; 
						$this->cadena_sql.="SET "; 
						$this->cadena_sql.="ACRE_COD_TIPO=";
						$this->cadena_sql.="'".$variable[2]."', ";
						$this->cadena_sql.="ACRE_FECHA=";
						$this->cadena_sql.="".$variable[3].", ";
						$this->cadena_sql.="ACRE_RESOLUCION=";
						$this->cadena_sql.=" '".$variable[4]."', ";
						$this->cadena_sql.="ACRE_DURACION=";
						$this->cadena_sql.="'".$variable[5]."', ";
						$this->cadena_sql.="ACRE_ENTIDAD=";
						$this->cadena_sql.="'".$variable[6]."' ";
						$this->cadena_sql.=" WHERE ";
						$this->cadena_sql.="ACRECRA_COD=";
						$this->cadena_sql.="'".$variable[0]."' ";
						$this->cadena_sql.=" AND ";
						$this->cadena_sql.="ACRE_COD_CRA=";
						$this->cadena_sql.="'".$variable[1]."'";
						break;							
					
			}
		return $this->cadena_sql;
		
		}
}
?>
