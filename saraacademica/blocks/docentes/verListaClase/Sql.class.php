<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlverListaClase extends sql {
	
	
	var $miConfigurador;
	
	
	function __construct(){
		$this->miConfigurador=Configurador::singleton();
	}
	

	function cadena_sql($tipo,$variable="") {
		 
		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */
		
		$prefijo=$this->miConfigurador->getVariableConfiguracion("prefijo");
		$idSesion=$this->miConfigurador->getVariableConfiguracion("id_sesion");
		 
		switch($tipo) {
			 
			/**
			 * Clausulas específicas
			 */
                         case "consultarAnioPeriodo": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="ape_ano, ";
				$cadena_sql.="ape_per, ";
                                $cadena_sql.="ape_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="mntac.acasperi ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ape_estado ='A' ";//No olvidar cambiaraaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                                break;
                            
                        case "consultarCarga": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="distinct DOC_NRO_IDEN, ";
                                $cadena_sql.="LTRIM(doc_nombre||'  '||doc_apellido) nombre, ";
                                $cadena_sql.="dep_cod, "; 
                                $cadena_sql.="dep_nombre, ";
                                $cadena_sql.="cur_cra_cod, ";
                                $cadena_sql.="cra_nombre, ";
                                $cadena_sql.="car_tip_vin, ";
                                $cadena_sql.="tvi_nombre, ";
                                $cadena_sql.="cur_asi_cod, ";
                                $cadena_sql.="asi_nombre, ";
                                $cadena_sql.="(lpad(cur_cra_cod,3,0)||'-'||cur_grupo), ";
                                $cadena_sql.="cur_nro_ins, ";
                                $cadena_sql.="tra_nivel, ";
                                $cadena_sql.="cur_id ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="mntac.accargas,mntac.acdocente,mntac.actipvin,mntac.acasi,accra a,";
                                $cadena_sql.="mntac.gedep,mntac.accursos,mntac.achorarios,mntac.acasperi b,mntac.ACTIPCRA ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="dep_cod = cra_dep_cod ";
                                $cadena_sql.="AND car_tip_vin = tvi_cod ";
                                $cadena_sql.="AND asi_cod = cur_asi_cod "; 
                                $cadena_sql.="AND cur_ape_ano = ape_ano ";
                                $cadena_sql.="AND cur_ape_per = ape_per ";
                                $cadena_sql.="AND ape_estado = '".$variable['estado']."' ";
                                $cadena_sql.="AND car_hor_id = hor_id ";
                                $cadena_sql.="AND hor_id_curso=cur_id ";
                                $cadena_sql.="AND doc_nro_iden =".$variable['usuario']." ";
                                $cadena_sql.="AND cra_cod = cur_cra_cod ";
                                $cadena_sql.="AND doc_estado = 'A' ";
                                $cadena_sql.="AND cra_estado = 'A' ";
                                $cadena_sql.="AND car_doc_nro = doc_nro_iden ";
                                $cadena_sql.="AND cur_estado = 'A' ";
                                $cadena_sql.="AND car_estado = 'A' ";
                                $cadena_sql.="AND cra_tip_cra=tra_cod ";
                                $cadena_sql.="ORDER BY dep_cod, cur_cra_cod, cur_asi_cod, cur_id ASC";
                                break;    
			
  			case "consultaListaClase":
				$cadena_sql="SELECT ";
                                $cadena_sql.="distinct est_nombre, ";
                                $cadena_sql.="eot_email, ";
                                $cadena_sql.="doc_nombre||' '||doc_apellido, ";
                                $cadena_sql.="doc_email, ";
                                $cadena_sql.="ins_est_cod, ";
                                $cadena_sql.="ins_asi_cod, ";
                                $cadena_sql.="ins_gr ";
                                $cadena_sql.="FROM mntac.acasperi,acins,mntac.acest,mntac.acestotr, ";
                                $cadena_sql.="mntac.accargas,mntac.accursos,mntac.achorarios,mntac.acdocente ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ape_ano = ins_ano ";
                                $cadena_sql.="AND ape_per = ins_per ";
                                $cadena_sql.="AND ape_estado = '".$variable['estado']."' ";
                                $cadena_sql.="AND ins_asi_cod = '".$variable['asignatura']."' ";
                                $cadena_sql.="AND ins_gr = '".$variable['grupo']."' ";
                                $cadena_sql.="AND CUR_APE_ANO = INS_ANO ";
                                $cadena_sql.="AND CUR_APE_PER = INS_PER ";
                                $cadena_sql.="AND CUR_ASI_COD = INS_ASI_COD ";
                                $cadena_sql.="AND cur_id = ins_gr ";
                                $cadena_sql.="AND doc_nro_iden = car_doc_nro ";
                                $cadena_sql.="AND doc_nro_iden =".$variable['usuario']." ";
                                $cadena_sql.="AND doc_estado = 'A' ";
                                $cadena_sql.="AND est_cod = ins_est_cod ";
                                $cadena_sql.="AND est_cod = eot_cod ";
                                $cadena_sql.="AND car_hor_id = hor_id ";
                                $cadena_sql.="AND hor_id_curso=cur_id ";
                                $cadena_sql.="ORDER BY ins_est_cod ";
                                break;
                            
                        case "consultarFormularios": 
			 	$cadena_sql="SELECT ";
                                $cadena_sql.="a.fto_id, ";
			 	$cadena_sql.="a.enc_id, ";
                                $cadena_sql.="a.preg_id, ";
                                $cadena_sql.="enc_nombre, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="tip_preg_id, ";
                                $cadena_sql.="preg_valor_pregunta, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="form_id ";
                                $cadena_sql.="FROM ";
			 	$cadena_sql.="autoevaluadoc.evaldocente_formulario a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_encabezado b ON b.enc_id=a.enc_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON c.preg_id=a.preg_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON d.fto_id=a.fto_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="a.fto_id=".$variable['formatoId']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="form_estado='A' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="d.tipo_id =".$variable['tipoId']." ";
                                $cadena_sql.="ORDER BY form_id ASC";
                                break;
                            
                            case "consultarPreguntasTipoPregunta": 
			 	$cadena_sql="SELECT ";
                                $cadena_sql.="a.fto_id, ";
			 	$cadena_sql.="a.enc_id, ";
                                $cadena_sql.="a.preg_id, ";
                                $cadena_sql.="enc_nombre, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="tip_preg_id, ";
                                $cadena_sql.="preg_valor_pregunta, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="form_id ";
                                $cadena_sql.="FROM ";
			 	$cadena_sql.="autoevaluadoc.evaldocente_formulario a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_encabezado b ON b.enc_id=a.enc_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON c.preg_id=a.preg_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON d.fto_id=a.fto_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="a.fto_id=".$variable['formatoId']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="form_estado='A' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="tip_preg_id= ".$variable['tipoPregunta']."";
                                $cadena_sql.="ORDER BY form_id ASC";
                                break; 
                            
                            case "consultarAsociacion": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="ftvd_id, ";
                                $cadena_sql.="a.fto_id, ";
                                $cadena_sql.="ftvd_tvi_cod ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato b ON b.fto_id=a.fto_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ftvd_tvi_cod=".$variable['tipoVinculacion']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ftvd_periodo=".$variable['periodo']." ";
                                 $cadena_sql.="AND ";
                                $cadena_sql.="tipo_id=".$variable['tipoId']." "; 
                                $cadena_sql.="AND ";
                                $cadena_sql.="ftvd_estado='A'";
                                break;
                            
                            case "consultarEvaluacion": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="a.form_id, ";
                                $cadena_sql.="resp_preg_num, ";
                                $cadena_sql.="resp_identificacion_evaluado, ";
                                $cadena_sql.="resp_anio, ";
                                $cadena_sql.="resp_periodo, ";
                                $cadena_sql.="resp_carrera, ";
                                $cadena_sql.="resp_asignatura, ";
                                $cadena_sql.="resp_grupo, ";
                                $cadena_sql.="resp_fec_registro, ";
                                $cadena_sql.="resp_respuesta, ";
                                $cadena_sql.="resp_estado, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="resp_id ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_respuesta a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON b.preg_id=c.preg_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON b.fto_id=d.fto_id ";
                                $cadena_sql.="WHERE ";
                                if(isset($variable['formularioId']))
                                {    
                                    $cadena_sql.="a.form_id IN (".$variable['formularioId'].") ";
                                }
                                else
                                {
                                    $cadena_sql.="a.form_id IN (0) ";
                                }    
                                $cadena_sql.="AND ";
                                $cadena_sql.="resp_identificacion_evaluado='".$variable['documentoId']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="resp_anio=".$variable['anio']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="resp_periodo=".$variable['per']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="resp_carrera=".$variable['carrera']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="tipo_id=".$variable['tipoId']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="resp_estado='A'";
                                break;
                            
                         case "actualizarEvaluacion":
                                $cadena_sql="UPDATE autoevaluadoc.evaldocente_respuesta ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="resp_respuesta=".$variable['respuestaNueva'].", ";
                                $cadena_sql.="resp_estado='".$variable['estadoNuevo']."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="resp_id=".$variable['respuestaId']."";
                                break;
                            
                            case "insertaEvaluacion":
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.="autoevaluadoc.evaldocente_respuesta (";
                                $cadena_sql.="form_id, ";
                                $cadena_sql.="resp_preg_num, ";
                                $cadena_sql.="resp_identificacion_evaluador, ";
                                $cadena_sql.="resp_identificacion_evaluado, ";
                                $cadena_sql.="resp_anio, ";
                                $cadena_sql.="resp_periodo, ";
                                $cadena_sql.="resp_carrera, ";
                                $cadena_sql.="resp_asignatura, ";
                                $cadena_sql.="resp_grupo, ";
                                $cadena_sql.="resp_fec_registro, ";
                                $cadena_sql.="resp_respuesta, ";
                                $cadena_sql.="resp_estado) ";
                                $cadena_sql.="VALUES ( ";
                                $cadena_sql.="".$variable['formularioId'].", ";
                                $cadena_sql.="".$variable['preguntaNumero'].", ";
                                $cadena_sql.="".$variable['usuario'].", ";
                                $cadena_sql.="".$variable['documentoId'].", ";
                                $cadena_sql.="".$variable['anio'].", ";
                                $cadena_sql.="".$variable['periodo'].", ";
                                $cadena_sql.="".$variable['carrera'].", ";
                                $cadena_sql.="".$variable['asignatura'].", ";
                                $cadena_sql.="'".$variable['grupo']."', ";
                                $cadena_sql.="'".$variable['fechaHoy']."', ";
                                $cadena_sql.="".$variable['respuesta'].", ";
                                $cadena_sql.="'".$variable['estado']."' ";
                                $cadena_sql.=")";
                                break;
                            
                            case "insertaObservacion":
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.="autoevaluadoc.evaldocente_observaciones (";
                                $cadena_sql.="form_id, ";
                                $cadena_sql.="obs_identificacion_evaluador, ";
                                $cadena_sql.="obs_identificacion_evaluado, ";
                                $cadena_sql.="obs_anio, ";
                                $cadena_sql.="obs_periodo, ";
                                $cadena_sql.="obs_carrera, ";
                                $cadena_sql.="obs_asignatura, ";
                                $cadena_sql.="obs_grupo, ";
                                $cadena_sql.="obs_fec_registro, ";
                                $cadena_sql.="obs_observaciones, ";
                                $cadena_sql.="obs_estado) ";
                                $cadena_sql.="VALUES ( ";
                                $cadena_sql.="".$variable['formularioObsId'].", ";
                                $cadena_sql.="".$variable['usuario'].", ";
                                $cadena_sql.="".$variable['documentoId'].", ";
                                $cadena_sql.="".$variable['anio'].", ";
                                $cadena_sql.="".$variable['periodo'].", ";
                                $cadena_sql.="".$variable['carrera'].", ";
                                $cadena_sql.="'".$variable['asignatura']."', ";
                                $cadena_sql.="'".$variable['grupo']."', ";
                                $cadena_sql.="'".$variable['fechaHoy']."', ";
                                $cadena_sql.="'".$variable['observaciones']."', ";
                                $cadena_sql.="'".$variable['estado']."' ";
                                $cadena_sql.=")";
                                break;
                            
                            case "insertaLog":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_log ";
				$cadena_sql.="( ";
				$cadena_sql.="log_evento, ";
                                $cadena_sql.="log_fecha_reg ";
                                $cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
				$cadena_sql.="'".$variable['log']."', ";
                               	$cadena_sql.="'".$variable['fechaHoy']."' ";
                               	$cadena_sql.=")";
				break;
                            
                            case "consultarTipoEvaluacion": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="tipo_id, ";
                                $cadena_sql.="tipo_nombre, ";
                                $cadena_sql.="UPPER(tipo_descripcion) ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_tipo_evaluacion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="tipo_id=".$variable['tipoId']." ";
                                break;
                            
                          case "consultarTipoEvaluacionExtemporanea": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="tipo_id, ";
                                $cadena_sql.="tipo_nombre, ";
                                $cadena_sql.="UPPER(tipo_descripcion) ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_tipo_evaluacion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="tipo_id IN (2,3)";
                                break;
                           
                         case "buscarPreguntas":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="preg_id, ";
                                $cadena_sql.="preg_pregunta ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_pregunta ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="acasperi_id=".$variable['perAcad']." ";
                                $cadena_sql.=" ";
                                $cadena_sql.="tipo_id IN (2,3)";
                                break;
                                                                
                }
                
		return $cadena_sql;

	}
}
?>
