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

class sql_adminSolicitud extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "registroTotal":
			
				$cadena_sql="SELECT  "; 
				$cadena_sql.="est_nombre,"; 
				$cadena_sql.="est_cra_cod,"; 
				$cadena_sql.="est_diferido,"; 
				$cadena_sql.="cra_nombre,"; 
				$cadena_sql.="eot_email,"; 
				$cadena_sql.="eot_email_ins,"; 
				$cadena_sql.="(CASE WHEN (est_acuerdo ='2011004' and est_estado_est='B') then 'MATRICULADO'
                                            WHEN (est_acuerdo ='2011004' and est_estado_est='J') then 'VACACIONES'
                                            WHEN (est_acuerdo ='2011004' and est_estado_est='F') then 'NO SE MATRICULO'
                                            WHEN (est_acuerdo ='2011004' and est_estado_est='K') then 'NO REPORTO NOTAS'
                                            ELSE
                                            estado_descripcion
                                            END) estado_nombre,"; 
				$cadena_sql.="est_nro_iden,"; 
				$cadena_sql.="est_tipo_iden,"; 
				$cadena_sql.="est_acuerdo "; 
				$cadena_sql.="FROM accra,acest,acestotr,acestado  ";
				$cadena_sql.="WHERE est_cod=".$variable;
				$cadena_sql.="AND cra_cod=est_cra_cod   ";
				$cadena_sql.="AND est_cod=eot_cod   ";
				$cadena_sql.="AND est_estado_est=estado_cod  ";
				
				
				break;		
			case "noticias":
			
				$cadena_sql="SELECT  "; 
				$cadena_sql.="CME_AUTOR,"; 
				$cadena_sql.="CME_TITULO,"; 
				$cadena_sql.="TO_CHAR(CME_FECHA_INI,'dd/Mon/yyyy'),";
				$cadena_sql.="CME_HORA_INI,";
				$cadena_sql.="TO_CHAR(CME_FECHA_FIN,'dd/Mon/yyyy'),"; 
				$cadena_sql.="CME_MENSAJE  ";
				$cadena_sql.="FROM accoormensaje  ";
				$cadena_sql.="WHERE CME_CRA_COD = (SELECT est_cra_cod FROM acest WHERE est_cod=$variable)";
				$cadena_sql.="AND CME_TIPO_USU IN(0,51,52)   ";
				$cadena_sql.="AND TO_NUMBER(TO_CHAR(sysdate,'yyyymmdd')) BETWEEN   ";		
				$cadena_sql.="TO_NUMBER(TO_CHAR(CME_FECHA_INI,'yyyymmdd')) AND TO_NUMBER(TO_CHAR(CME_FECHA_FIN,'yyyymmdd'))";  

				break;

			case "tipoUsuario":
			
				$cadena_sql="SELECT  "; 
				$cadena_sql.="cla_tipo_usu "; 
				$cadena_sql.="FROM  ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="WHERE cla_codigo=".$variable." ";
				$cadena_sql.="AND cla_estado='A' ";				
				break;	
				
			case "menuAyuda":
				$cadena_sql="SELECT  "; 
				$cadena_sql.="id_menuAyuda,"; 
				$cadena_sql.="nombre "; 
				$cadena_sql.="FROM  ";
				$cadena_sql.=$configuracion["prefijo"]."menuAyuda ";
				$cadena_sql.="WHERE tipoUsuario IN(0,".$variable.")  ";
				break;	
				
			case "contAyuda":
				$cadena_sql="SELECT  "; 
				$cadena_sql.="id_ayuda,"; 
				$cadena_sql.="nombre, "; 
				$cadena_sql.="contenido "; 				
				$cadena_sql.="FROM  ";
				$cadena_sql.=$configuracion["prefijo"]."ayuda ";
				$cadena_sql.="WHERE menu=".$variable;
				break;	
			
			case "evaluacionDocente":
				$cadena_sql="SELECT  "; 
				$cadena_sql.=" *"; 
				$cadena_sql.=" FROM  ";
				$cadena_sql.=" ACASPERI,ACEVAPROEST ";
				$cadena_sql.=" WHERE ape_ano=epe_ape_ano";
				$cadena_sql.=" AND ape_per=epe_ape_per";
				$cadena_sql.=" AND ape_estado='A'";
				$cadena_sql.=" AND epe_est_cod=".$variable;
				break;
				
			case "evaluacionCalendario":
				$cadena_sql="SELECT  "; 
				$cadena_sql.=" *"; 
				$cadena_sql.=" FROM  ";
				$cadena_sql.=" ACASPERI,ACCALEVENTOS ";
				$cadena_sql.=" WHERE ape_ano=ace_anio";
				$cadena_sql.=" AND ape_per=ace_periodo";
				$cadena_sql.=" AND ape_estado='A'";
				$cadena_sql.=" AND sysdate BETWEEN ace_fec_ini AND ace_fec_fin";
				$cadena_sql.=" AND ace_cod_evento=11";
				$cadena_sql.=" AND ace_cra_cod=".$variable;
				break;	
				
			case "consultarAyuda":
				$cadena_sql="SELECT  "; 
				$cadena_sql.="nombre, "; 
				$cadena_sql.="contenido "; 				
				$cadena_sql.="FROM  ";
				$cadena_sql.=$configuracion["prefijo"]."ayuda ";
				$cadena_sql.="WHERE id_ayuda=".$variable;
				break;	
				
			case "recibosPendientes":
				$cadena_sql="SELECT ";
				$cadena_sql.="mntac.fua_recibos_pendientes(".$variable.") ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
				
			default:
				$cadena_sql="";
				break;
		}
		//echo "<br>".$cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
