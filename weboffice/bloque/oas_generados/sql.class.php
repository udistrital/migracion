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
			case "desbloquear":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="SET ";
				$cadena_sql.="EMA_IMP_RECIBO = 0 ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_secuencia = ".$variable[1]." ";
				break;
			
			case "bloqueadoCompleto":
				//En ORACLE
				
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="ema_secuencia, ";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'YYYYMMDD'), ";
				$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'YYYYMMDD'), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="ROW_NUMBER() OVER (ORDER BY ema_est_cod asc) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT, ";
				$cadena_sql.="ACEST, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cra_cod = cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod = est_cod ";
				$cadena_sql.=") as A ";
//				$cadena_sql.=" WHERE ";
//				$cadena_sql.="R ";
//				$cadena_sql.="BETWEEN ";
//				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
//				$cadena_sql.="AND ";
//				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite superior				
				break;
				
			case "totalBloqueado":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	//Recibo Activo no impreso
				//$cadena_sql.="AND ";
				//$cadena_sql.="EMA_IMP_RECIBO=1 ";  //Recibo Bloqueado
				break;
				
			case "totalCarreraBloqueado":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) TOTAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_cra_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_ESTADO='A' ";	//Recibo Activo no impreso
				break;
				
			case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_ESTADO='A' ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="CRA_ABREV ASC ";
				break;
				
			case "datosEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="est_diferido, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="emb_valor_matricula vr_mat, ";
				$cadena_sql.="cra_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="V_ACESTMATBRUTO, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$valor." ";
				$cadena_sql.="AND ";
				$cadena_sql.="emb_est_cod = est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = est_cra_cod";
				break;
			
			
			case "estadistica":
				$cadena_sql="SELECT ";
				$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.id_carrera, ";
				$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.solicitud, ";
				$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.impresion, ";
				$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.anulacion, ";
				$cadena_sql.=$configuracion["prefijo"]."programa.nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion, ";
				$cadena_sql.=$configuracion["prefijo"]."programa ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_carrera=".$valor." ";
				break;
				
			case "tipoUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="cla_codigo, ";
				$cadena_sql.="cla_clave, ";
				$cadena_sql.="cla_tipo_usu, ";
				$cadena_sql.="cla_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo = ".$valor." ";
				$cadena_sql.="AND ";
				$cadena_sql.="( ";
				$cadena_sql.="cla_tipo_usu = 4 "; //Solo coordinadores o asistentes
				$cadena_sql.="OR ";
				$cadena_sql.="cla_tipo_usu = 4 "; //TO DO Tipo de Usuario Asistente de Coordinador
				$cadena_sql.=") ";			
				break;
			
			case "exencionSolicitud":
					$cadena_sql="SELECT ";
					$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.`nombre`, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.`porcentaje`, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.`etiqueta`, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.`tipo`, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.`soporte` ";	
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."solicitudExencion, ";
					$cadena_sql.=$configuracion["prefijo"]."exencion ";			
					$cadena_sql.="WHERE ";
					$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud=".$variable." ";
					$cadena_sql.="AND ";
					$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion=".$configuracion["prefijo"]."solicitudExencion.id_exencion";
					//echo $cadena_sql;
					break;
					
			case "deudaEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="deu_est_cod, ";
				$cadena_sql.="deu_cpto_cod, ";
				$cadena_sql.="deu_material, ";
				$cadena_sql.="deu_ano, ";
				$cadena_sql.="deu_per, ";
				$cadena_sql.="deu_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdeudores ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="deu_est_cod =".$variable." ";
				break;
					
			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
