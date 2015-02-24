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

class sql_generarFactura extends sql
{
	function cadena_sql($configuracion,$tipo,$variable="")
		{
			
			switch($tipo)
			{
				case "select":
					$cadena_sql="SELECT ";
					$cadena_sql.="`id_solicitud_recibo`, ";
					$cadena_sql.="`id_usuario`, ";
					$cadena_sql.="`codigo_est`, ";
					$cadena_sql.="`estado`, ";
					$cadena_sql.="`fecha` ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."solicitud_recibo "; 
					$cadena_sql.="WHERE ";
					$cadena_sql.="`estado`=1 ";
					$cadena_sql.="AND ";
					$cadena_sql.="`id_usuario`='".$variable["id_usuario"]."'";
					//estado=0 solicitud no procesada
					//estado=1 solicitud en proceso
					//estado=2 solicitud procesada
					break;
			}
						
		
			return $cadena_sql;
		
		}
}
?>
