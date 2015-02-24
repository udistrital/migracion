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

class sql_aceptaAcuerdo extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "periodoActual":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";	
			break;
			case "consultarDeudor":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdeudores ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="deu_est_cod={$variable}";
				$cadena_sql.="AND ";
				$cadena_sql.="deu_estado='A'";
			break;
			case "insertarCompromiso":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."reciboAceptaAcuerdo(codigo_est,anno,periodo,fecha,estado) VALUES(";
				$cadena_sql.="'{$variable[0]}', ";
				$cadena_sql.="'{$variable[1]}', ";
				$cadena_sql.="'{$variable[2]}', ";
				$cadena_sql.="'{$variable[3]}', ";
				$cadena_sql.="1) ";
			break;				
			case "desbloquearRecibos":
				$cadena_sql="UPDATE acestmat ";
				$cadena_sql.="SET ";
				$cadena_sql.="ema_imp_recibo=0 ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod={$variable[0]} ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_ano={$variable[1]} ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_per={$variable[2]} ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_estado='A' ";

			break;							
			default:
				$cadena_sql="";
			break;
		}
		//echo "<br>$opcion=".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
