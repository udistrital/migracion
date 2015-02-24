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

class sql_admin_certIngresosRetenciones extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "anioper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";	
				break;
			
			case "DatosUsuarios":
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_nombre, ";
				$cadena_sql.="emp_nro_iden ";
				$cadena_sql.="FROM ";
				$cadena_sql.="peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden=".$variable[0]." ";
				break;
				
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
				
			case "certificado":
				$cadena_sql="SELECT unique cir_emp_nro_iden, ";
				$cadena_sql.="cir_ano, ";
   				$cadena_sql.="cir_desde, ";
   				$cadena_sql.="to_char(cir_desde,'yyyy') desdea, ";
   				$cadena_sql.="to_char(cir_desde,'mm') desdem, ";
				$cadena_sql.="to_char(cir_desde,'dd') desded, ";
   				$cadena_sql.="cir_hasta, ";
   				$cadena_sql.="to_char(cir_hasta,'yyyy') hastaa, ";
				$cadena_sql.="to_char(cir_hasta,'mm') hastam, ";
   				$cadena_sql.="to_char(cir_hasta,'dd') hastad, ";
   				$cadena_sql.="cir_salario, ";
   				$cadena_sql.="cir_cesantia, ";
   				$cadena_sql.="cir_gastos_representacion, ";
   				$cadena_sql.="cir_pension, ";
   				$cadena_sql.="cir_otros, ";
   				$cadena_sql.="cir_total_ingresos, ";
   				$cadena_sql.="cir_aportes_salud, ";
   				$cadena_sql.="cir_aporte_voluntario, ";
   				$cadena_sql.="nvl(cir_aportes_pension,0), ";
   				$cadena_sql.="cir_exentas, ";
   				$cadena_sql.="cir_retencion, ";
   				$cadena_sql.="cir_estado, ";
   				$cadena_sql.="emp_Nombre, ";
   				$cadena_sql.="SYSDATE, ";
   				$cadena_sql.="to_char(sysdate,'yyyy') fechaa, ";
   				$cadena_sql.="to_char(sysdate,'mm') fecham, ";
   				$cadena_sql.="to_char(sysdate,'dd') fechad ";
				$cadena_sql.="FROM ";
				$cadena_sql.="prceringret2004, ";
				$cadena_sql.="peemp, ";
				$cadena_sql.="DUAL ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cir_emp_nro_iden = emp_nro_iden ";
   				$cadena_sql.="and cir_ano = 2009 ";
   				$cadena_sql.="and emp_nro_iden = 14211726 ";
   				$cadena_sql.="and emp_estado = 'A' ";
   				$cadena_sql.="and cir_estado = 'A' ";
				$cadena_sql.="ORDER BY emp_nombre ASC ";
				break; 
						
			default:
				$cadena_sql="";
				break;
		}
		//echo "<br>".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
