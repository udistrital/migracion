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
			case "recibosSinPagar":
				$cadena_sql="SELECT ape_ano,ape_per ";
				$cadena_sql.="FROM   "; 
				$cadena_sql.="( ";
				$cadena_sql.="SELECT ape_ano, ";
				$cadena_sql.="   ape_per ";
				$cadena_sql.="FROM acasperi, acestmat ";
				$cadena_sql.="WHERE ape_per IN (1,3) ";
				$cadena_sql.="   AND ape_ano >= 2004 ";
				$cadena_sql.="   AND ape_ano = ema_ano ";
				$cadena_sql.="   AND ape_per = ema_per ";
				$cadena_sql.="   AND ema_pago <> 'S' ";
				$cadena_sql.="   AND ema_estado = 'A' ";
				$cadena_sql.="   AND ema_est_cod = ".$variable." ";
				$cadena_sql.="UNION ";
				$cadena_sql.="SELECT ape_ano, ";
				$cadena_sql.="   ape_per ";
				$cadena_sql.="FROM acasperi, acest ";
				$cadena_sql.="WHERE ape_per IN (1,3) ";
 				$cadena_sql.="  AND ape_estado <> 'X' ";
				$cadena_sql.="   AND est_cod = ".$variable." ";
				$cadena_sql.="   AND ape_ano|| ape_per >= DECODE(LENGTH(est_cod),7,(SUBSTR(est_cod,1,2)+1900),11,SUBSTR(est_cod,1,4))||DECODE(DECODE(LENGTH(est_cod),7,(SUBSTR(est_cod,3,1)),11,SUBSTR(est_cod,5,1)),1,1,2,3) ";
				$cadena_sql.="   AND NOT EXISTS (SELECT ema_ano||ema_per ";
				$cadena_sql.="                   FROM acestmat ";
				$cadena_sql.="			 WHERE acasperi.ape_ano||acasperi.ape_per = ema_ano||ema_per ";
				$cadena_sql.="			 AND ema_est_cod = ".$variable." ) ";
				$cadena_sql.=") as anio	 ";			   
				$cadena_sql.="ORDER BY ape_ano, ape_per ASC ";
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

			
			case "secuencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="NEXTVAL('seq_matricula') ";
				break;
				
								
			case "insertarCuotaECAES":
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
				$cadena_sql.=$variable[0].", ";//cod estudiante
				$cadena_sql.=$variable[1].", ";//carrera estudiante
				$cadena_sql.="0, ";//valor
				$cadena_sql.=$variable[12].", ";//valor extra
				$cadena_sql.=$variable[2].", ";//ano
				$cadena_sql.=$variable[3].", ";//per
				$cadena_sql.="1, ";//cuota
				$cadena_sql.="TO_DATE('".$variable[5]."','yyyy/mm/dd'), ";//fecha ord
				$cadena_sql.="TO_DATE('".$variable[11]."','yyyy/mm/dd'), ";//fecha extra
				$cadena_sql.="CURRENT_TIMESTAMP, ";//fecha
				$cadena_sql.="'A', ";//estado
				$cadena_sql.=$variable[6].", ";//secuencia
				$cadena_sql.=$variable[7].", ";//ano pago
				$cadena_sql.=$variable[8].", ";//per pago
				$cadena_sql.="'N', ";//pago
				$cadena_sql.="2, ";//imp recibo
				$cadena_sql.="'RECIBO SABER PRO'";//obs
				$cadena_sql.=")";
				//echo $cadena_sql;
				break;
				
			case "insertarConceptoECAES":
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
				$cadena_sql.=$variable[6].", ";
				$cadena_sql.="23, ";
				$cadena_sql.="14, ";
				$cadena_sql.=$variable[4]." ";
				$cadena_sql.=")";
				//echo $cadena_sql;
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
				$cadena_sql.="0, ";
				$cadena_sql.="0, ";
				$cadena_sql.="23, ";
				$cadena_sql.="1, ";
				$cadena_sql.="0";
				$cadena_sql.=")";
				//echo $cadena_sql;
				break;

			case "insertarSolicitud":
				//MySQL
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`codigo_est`, ";
				$cadena_sql.="`estado`, ";
				$cadena_sql.="`anno`, ";
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

			case "inactivarRecibosAnterioesEcaes":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACESTMAT "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="ema_estado='I' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ema_est_cod=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_ano=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_per=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_cuota=1 ";
				$cadena_sql.="AND ";
				$cadena_sql.="ema_pago<>'S' ";
                                $cadena_sql.="and ema_secuencia in (";
                                $cadena_sql.="select distinct aer_secuencia from acrefest ";
                                $cadena_sql.="where aer_ano=".$variable[2]." ";
                                $cadena_sql.="and aer_secuencia in ";
                                $cadena_sql.="(select ema_secuencia ";
                                $cadena_sql.="from acestmat ";
                                $cadena_sql.="where ema_ano=".$variable[2]." ";
                                $cadena_sql.="and ema_per=".$variable[3]." ";
                                $cadena_sql.="and ema_est_cod=".$variable[0].") ";
                                $cadena_sql.="and aer_refcod=14)";
                                break;
                            
                            
			case "fechaPago":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="ban_fecha_ecaes_ord,ban_fecha_ecaes_ext ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acbanco,acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_ano=ban_ano ";
				$cadena_sql.="AND ape_per=ban_per ";
				$cadena_sql.="AND ape_estado='A'";
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
	
			case "actualizarEstadoGeneraRecibo":
				$cadena_sql="UPDATE ";
				$cadena_sql.="mntac.acestecaes ";
				$cadena_sql.="SET eca_genero_recibo='S' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="eca_cod=".$variable[0];
				$cadena_sql.=" AND eca_ano=".$variable[2];
				$cadena_sql.=" AND eca_per=".$variable[3];
				
				break;
								
			case "valorECAES":
				$cadena_sql="SELECT ";
				$cadena_sql.="vlr_ecaes, ";
				$cadena_sql.="vlr_ecaes_ext ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi,acvlrscs ";
				$cadena_sql.="WHERE ape_ano= vlr_ano ";
				$cadena_sql.="AND ape_per= vlr_per ";
				$cadena_sql.="AND ape_estado='A' ";				
				break;				
				
			case "carreraEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable." ";
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
		//echo "<br><br>".$cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
