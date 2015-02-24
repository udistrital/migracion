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
			case "valoraPagar":
				$cadena_sql="SELECT emb_valor_matricula ";
				$cadena_sql.="FROM   "; 
				$cadena_sql.="v_acestmatbruto ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emb_est_cod = ".$variable." ";
				break;	
				
			case "consultarExencion":
				$cadena_sql="SELECT emb_valor_matricula ";
				$cadena_sql.="FROM   "; 
				$cadena_sql.="v_acestmatbruto ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emb_est_cod = ".$variable." ";
				break;					
							
			case "verificaEstudianteCoordinador":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest,geusucra ";
				$cadena_sql.="WHERE est_cra_cod  = usucra_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod= ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="usucra_nro_iden=".$variable[1];
			break;
			case "periodosRecibos":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||ape_per, ";
				$cadena_sql.="ape_ano||'-'||ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ape_per in (1,3) ";
				$cadena_sql.="and ape_ano||ape_per BETWEEN 20071 AND  ";
				$cadena_sql.="(select ape_ano||ape_per from acasperi where ape_estado='A' ) ";
				$cadena_sql.="ORDER BY ape_ano||ape_per DESC ";
			break;			
			case "recibosGeneradosEstudiante":

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
				$cadena_sql.="DECODE(EMA_ESTADO,'A','Activo','Inactivo'), ";
				$cadena_sql.="DECODE(EMA_IMP_RECIBO,1,'Bloqueado','Desbloq.'), ";	
				$cadena_sql.="DECODE(EMA_PAGO,'S','Pagado','Sin Pagar'), ";							
				$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'YYYY-MM-DD'), ";
				$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'YYYY-MM-DD'), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="cra_abrev, ";
				$cadena_sql.="est_estado_est ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMAT, ";
				$cadena_sql.="ACEST, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ";				
				$cadena_sql.="EMA_ANO= ".$variable[1]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="EMA_PER=".$variable[2]." ";	
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cra_cod = cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_est_cod = est_cod ";
				$cadena_sql.="ORDER BY ema_fecha desc";				
			break;
			
			case "secuencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="seq_matricula.NEXTVAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual ";
				break;
				
								
			case "insertarCuotaIndividual":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMAT ";
				$cadena_sql.="(";
				$cadena_sql.="ema_est_cod, ";
				$cadena_sql.="ema_cra_cod, ";
				$cadena_sql.="ema_valor, ";
				$cadena_sql.="ema_ext, ";
				$cadena_sql.="ema_ano, ";
				$cadena_sql.="ema_per, ";
				$cadena_sql.="ema_cuota, ";
				$cadena_sql.="ema_fecha_ord, ";
				$cadena_sql.="ema_fecha_ext, ";
				$cadena_sql.="ema_fecha, ";
				$cadena_sql.="ema_estado, ";
				$cadena_sql.="ema_secuencia, ";	
				$cadena_sql.="ema_ano_pago, ";	
				$cadena_sql.="ema_per_pago, ";
				$cadena_sql.="ema_pago, ";														
				$cadena_sql.="ema_imp_recibo, ";
				$cadena_sql.="ema_obs ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.="(SELECT est_cra_cod FROM acest WHERE est_cod=".$variable[0]."), ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.="(".$variable[1]."*1.2), ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[3].", ";
				$cadena_sql.=$variable[4].", ";
				$cadena_sql.="TO_DATE('".$variable[5]."','dd/mm/yy'), ";
				$cadena_sql.="TO_DATE('".$variable[6]."','dd/mm/yy'), ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'A', ";
				$cadena_sql.=$variable[7].", ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[3].", ";				
				$cadena_sql.="'N', ";
				$cadena_sql.="1, ";
				$cadena_sql.="'Reintegro' ";
				$cadena_sql.=")";
			
				break;
				
			case "insertarConceptoSeguro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACREFEST ";
				$cadena_sql.="(";
				$cadena_sql.="aer_ano, ";
				$cadena_sql.="aer_secuencia, ";
				$cadena_sql.="aer_bancod, ";
				$cadena_sql.="aer_refcod, ";
				$cadena_sql.="aer_valor ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[7].", ";
				$cadena_sql.="23, ";
				$cadena_sql.="2, ";
				$cadena_sql.="(SELECT vlr_seguro FROM acasperi,acvlrscs WHERE ape_ano= vlr_ano AND ape_per= vlr_per AND ape_estado='A'  )";
				$cadena_sql.=")";
	
				
				break;

			case "insertarControl":
			
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."controlRecibo "; 
				$cadena_sql.="(";
				$cadena_sql.="`usuario`,";
				$cadena_sql.="`fecha_registro`,";
				$cadena_sql.="`fecha_ordinaria`,";
				$cadena_sql.="`fecha_extra`, ";
				$cadena_sql.="`anno_pago`,";
				$cadena_sql.="`per_pago`,";
				$cadena_sql.="`valor_matricula`,";
				$cadena_sql.="`conceptos`,";
				$cadena_sql.="`codigo`,";				
				$cadena_sql.="`confirmacion`";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.=$variable[7].", ";	
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.=$variable[2].", ";				
				$cadena_sql.=$variable[3].", ";
				$cadena_sql.=$variable[4].", ";	
				$cadena_sql.=$variable[5].", ";
				$cadena_sql.="'".$variable[6]."', ";		
				$cadena_sql.=$variable[8].", ";								
				$cadena_sql.="'N'";
				$cadena_sql.=")";
		
				break;

			case "rescatarControlID":
			
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_controlRecibo` ";
				$cadena_sql.="FROM ".$configuracion["prefijo"]."controlRecibo "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="usuario=".$variable[0]." ";
				$cadena_sql.="AND codigo=".$variable[8]." ";
				$cadena_sql.="AND fecha_registro=".$variable[7]." ";				
				$cadena_sql.="AND confirmacion='N' ";												
				$cadena_sql.="ORDER BY id_controlRecibo DESC LIMIT 0,1 ";
	
				break;
				
			case "rescatarControl":
			
				$cadena_sql="SELECT ";
				$cadena_sql.="`fecha_ordinaria`,";
				$cadena_sql.="`fecha_extra`, ";
				$cadena_sql.="`anno_pago`,";
				$cadena_sql.="`per_pago`,";
				$cadena_sql.="`valor_matricula`,";
				$cadena_sql.="`conceptos`,";
				$cadena_sql.="`codigo` ";				
				$cadena_sql.="FROM ".$configuracion["prefijo"]."controlRecibo "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_controlRecibo=".$variable." ";
		
				break;				

			case "actualizaConfirmacion":
			
				$cadena_sql="UPDATE ".$configuracion["prefijo"]."controlRecibo "; 
				$cadena_sql.="SET confirmacion='S' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_controlRecibo=".$variable." ";
		
				break;									
				
			case "insertarConceptoMatricula":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACREFEST ";
				$cadena_sql.="(";
				$cadena_sql.="aer_ano, ";
				$cadena_sql.="aer_secuencia, ";
				$cadena_sql.="aer_bancod, ";
				$cadena_sql.="aer_refcod, ";
				$cadena_sql.="aer_valor ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[7].", ";
				$cadena_sql.="23, ";
				$cadena_sql.="1, ";
				$cadena_sql.=$variable[12]." ";
				$cadena_sql.=")";
	
				break;
			case "insertarSolicitud":
				//MySQL
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`codigo_est`, ";
				$cadena_sql.="`estado`, ";
				$cadena_sql.="`anno`, ";$cadena_sql.="AND ema_per_pago=".$variable[2]." ";
				$cadena_sql.="`periodo`, ";
				$cadena_sql.="`fecha`, ";
				$cadena_sql.="`cuota`, ";
				$cadena_sql.="`id_carrera`, ";
				$cadena_sql.="`tipoPlantilla`, ";
				$cadena_sql.="`secuencia`, ";				
				$cadena_sql.="`unidad`, ";
				$cadena_sql.="`observacion` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable[9]."', ";
				$cadena_sql.="'".$variable[0]."', ";
				$cadena_sql.="'1', ";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="'".time()."', ";
				$cadena_sql.="'1', ";
				$cadena_sql.="'".$variable[1]."', ";
				$cadena_sql.="'0', ";
				$cadena_sql.="'".$variable[6]."', ";			
				$cadena_sql.="'0', ";
				$cadena_sql.="'".$variable[10]."' ";
				$cadena_sql.=")";
				break;

				
			case "fechaPago":
				//MySQL
				$cadena_sql="SELECT ";
				$cadena_sql.="`extraordinaria` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."fechasPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cuota=1";
				break;
								
			case "totalGenerado":
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
				$cadena_sql.="CRA_EMP_NRO_IDEN=".$variable;
				break;
				
			case "valorSeguro":
				$cadena_sql="SELECT ";
				$cadena_sql.="vlr_seguro ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi,acvlrscs ";
				$cadena_sql.="WHERE ape_ano= vlr_ano ";
				$cadena_sql.="AND ape_per= vlr_per ";
				$cadena_sql.="AND ape_estado='A' ";				
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
				$cadena_sql.="est_cod =".$variable." ";
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

			case "verificaPosgrado":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="actipcra,accra,acest ";
				$cadena_sql.="WHERE tra_cod= cra_tip_cra  ";
				$cadena_sql.="AND cra_cod= est_cra_cod  ";
				$cadena_sql.="AND est_cod= ".$variable." ";
				$cadena_sql.="AND tra_nivel<>'PREGRADO'";
			break;
			
			case "verificaExisRecibo":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acestmat ";
				$cadena_sql.="WHERE ema_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ema_ano_pago=".$variable[1]." ";
				$cadena_sql.="AND ema_per_pago=".$variable[2]." ";
				$cadena_sql.="AND ema_estado<>'I' ";
			break;			

			case "verificaAprobacion":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="aap_est_cod, ";
				$cadena_sql.="aap_ape_ano, ";
				$cadena_sql.="aap_ape_per, ";
				$cadena_sql.="aap_cuota ";
				$cadena_sql.="FROM ";
				//$cadena_sql.="mntac.";
				$cadena_sql.="acestmatapr ";
				$cadena_sql.="WHERE aap_est_cod= ".$variable[0]." ";
				$cadena_sql.="AND aap_ape_ano= ".$variable[1]." ";
				$cadena_sql.="AND aap_ape_per= ".$variable[2]." ";
				$cadena_sql.="AND aap_cuota=1";
			break;
			
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'), ";
				$cadena_sql.="NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi,accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_CRA_COD =cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 79 ";
				$cadena_sql.="AND ";
				$cadena_sql.="CRA_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_emp_nro_iden=".$variable[4]."";
				break;		
                            
			case "validaFechasAsistentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="NVL(TO_CHAR(ACE_FEC_INI, 'yyyymmdd'), '0'), ";
				$cadena_sql.="NVL(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'), '0'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos,acasperi,accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="APE_ANO = ACE_ANIO ";
				$cadena_sql.="AND APE_PER = ACE_PERIODO ";
				$cadena_sql.="AND APE_ESTADO = 'A' ";
				$cadena_sql.="AND ACE_CRA_COD =cra_cod ";
				$cadena_sql.="AND ACE_COD_EVENTO = 79 ";
				$cadena_sql.="AND CRA_ESTADO = 'A' ";
				$cadena_sql.="AND cra_cod in (".$variable.")";
				break;					
			default:
				$cadena_sql="";
				break;
		}
	//echo "<br><br>".$cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
