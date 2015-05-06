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
			case "verficaAprobacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="aap_est_cod, ";
				$cadena_sql.="aap_ape_ano, ";
				$cadena_sql.="aap_ape_per, ";
				$cadena_sql.="aap_cuota ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACESTMATAPR ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="aap_est_cod = ".$variable[0]." ";
				$cadena_sql.="AND aap_ape_ano = ".$variable[1]." ";
				$cadena_sql.="AND aap_ape_per = ".$variable[2]." ";
				$cadena_sql.="AND aap_cuota = ".$variable[3]."";
				break;

			case "insertarAprobacion":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="ACESTMATAPR ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="(SELECT est_cra_cod FROM ACEST WHERE est_cod=".$variable[0]."),";
				$cadena_sql.=$variable[0].",";
				$cadena_sql.=$variable[1].",";
				$cadena_sql.=$variable[2].",";
				$cadena_sql.=$variable[3].",";
				$cadena_sql.="to_date('".$variable[4]."','dd/mm/yy'),";				
				$cadena_sql.="'".$variable[5]."',";
				$cadena_sql.=$variable[6].",";
				$cadena_sql.="CURRENT_TIMESTAMP,";
				$cadena_sql.="'".$variable[7]."')";								
																
				break;
			case "recibosSinPagar":
				$cadena_sql="SELECT ape_ano,ape_per ";
				$cadena_sql.="FROM ( SELECT ape_ano, ape_per ";
				$cadena_sql.="		FROM acasperi, acestmat ";
				$cadena_sql.="		WHERE ape_per IN (1,3) ";
				$cadena_sql.="		AND ape_ano >= 2004 ";
				$cadena_sql.="		AND ape_ano = ema_ano ";
				$cadena_sql.="		AND ape_per = ema_per ";
				$cadena_sql.="		AND ema_pago <> 'S' ";
				$cadena_sql.="		AND ema_estado = 'A' ";
				$cadena_sql.="		AND ema_est_cod = ".$variable." ";
				$cadena_sql.="		UNION ";
				$cadena_sql.="		SELECT ape_ano, ape_per ";
				$cadena_sql.="		FROM acasperi, acest ";
				$cadena_sql.="		WHERE ape_per IN (1,3) ";
				$cadena_sql.="		AND ape_estado <> 'X' ";
				$cadena_sql.="		AND est_cod = ".$variable." ";
				$cadena_sql.="		AND (ape_ano::text || ape_per::text )::int >= ";
				$cadena_sql.="  ((case when char_LENGTH(est_cod::text)=7 ";
				$cadena_sql.="		then (SUBSTR(est_cod::text,1,2)::int+1900) ";
				$cadena_sql.="		when char_LENGTH(est_cod::text)=11 ";
				$cadena_sql.="		then SUBSTR(est_cod::text,1,4)::int end)::text ";
				$cadena_sql.="		|| ";
				$cadena_sql.="  (case when (case when char_LENGTH(est_cod::text)=7 ";
				$cadena_sql.="			then (SUBSTR(est_cod::text,3,1)) ";
				$cadena_sql.="			when char_LENGTH(est_cod::text)=11 ";
				$cadena_sql.="			then SUBSTR(est_cod::text,5,1) end)::int=1 ";
				$cadena_sql.="			then 1 ";
				$cadena_sql.="			when (case when char_LENGTH(est_cod::text)=7 ";
				$cadena_sql.="			then (SUBSTR(est_cod::text,3,1)) ";
				$cadena_sql.="			when char_LENGTH(est_cod::text)=11 ";
				$cadena_sql.="			then SUBSTR(est_cod::text,5,1) end)::int=2 ";
				$cadena_sql.="			then 3 end)::text)::int ";
				$cadena_sql.="	AND NOT EXISTS (SELECT ema_ano::text||ema_per::text FROM acestmat WHERE acasperi.ape_ano::text||acasperi.ape_per::text = ema_ano::text||ema_per::text AND ema_est_cod = ".$variable." ) ) as periodo ";
				$cadena_sql.="ORDER BY ape_ano, ape_per ASC ";
			break;
				
			case "verificaEstudianteCoordinador":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest,geusucra ";
				$cadena_sql.="WHERE (est_cra_cod = usucra_cra_cod AND est_cod=".$variable[0]." AND usucra_nro_iden=".$variable[1].") ";
				$cadena_sql.="OR ";
				$cadena_sql.="(est_cra_cod = usucra_cra_cod AND usucra_cra_cod=999 AND usucra_nro_iden=".$variable[1].") ";

			break;
			case "verificaEstudianteSecretario":
				//Oracle
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acsecretario,accra,acest,peemp,geusucra ";
				$cadena_sql.="WHERE  ";
				$cadena_sql.="( ";
				$cadena_sql.="cra_dep_cod= sec_dep_cod ";
				$cadena_sql.="and cra_cod= est_cra_cod ";
				$cadena_sql.="and sec_cod=emp_cod ";
				$cadena_sql.="and sec_estado='A' ";
				$cadena_sql.="and emp_nro_iden=".$variable[1]." ";
				$cadena_sql.="and est_cod=".$variable[0]."  ";
				$cadena_sql.="and usucra_nro_iden=emp_nro_iden ";
				$cadena_sql.="and usucra_cra_cod= est_cra_cod ";
				$cadena_sql.=") ";
				$cadena_sql.="OR ";
				$cadena_sql.="(est_cra_cod =usucra_cra_cod AND usucra_cra_cod=999 AND usucra_nro_iden=".$variable[1].")";


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

			
			case "fechaPago":
				//MySQL
				$cadena_sql="SELECT ";
				$cadena_sql.="`ordinaria` ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."fechasPago ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cuota=1";
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
				$cadena_sql.="cra_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = est_cra_cod";
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
		//echo "<br>".$cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
