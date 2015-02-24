<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlarmarFormulariosEvaldocente extends sql {
	
	
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
                         case "buscarPeriodo": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="acasperiev_id, ";
				$cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo, ";
                                $cadena_sql.="acasperiev_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="acasperiev_estado IN ('A') ";
                                break; 
			
			case "consultarTipoEvaluacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="tipo_id, ";
                                $cadena_sql.="tipo_nombre ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_tipo_evaluacion ";
                                break;
                            
                        case "buscarFormatos": 
				$cadena_sql="SELECT ";
				$cadena_sql.="fto_id, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="fto_descripcion, ";
                                $cadena_sql.="fto_porcentaje, ";
                                $cadena_sql.="fto_estado ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_formato ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="fto_numero =".$variable['fto_numero']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_id =".$variable['periodo']." ";
                                break;
                            
                        case "consultarFormatos": 
				$cadena_sql="SELECT ";
				$cadena_sql.="fto_id, ";
                                $cadena_sql.="a.acasperiev_id, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="fto_descripcion, ";
                                $cadena_sql.="fto_porcentaje, ";
                                $cadena_sql.="fto_estado, ";
                                $cadena_sql.="tipo_nombre, ";
                                $cadena_sql.="b.tipo_id, ";
                                $cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_formato a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_tipo_evaluacion b ON a.tipo_id=b.tipo_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev c ON a.acasperiev_id=c.acasperiev_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                //$cadena_sql.="AND ";
                                 //$cadena_sql.="fto_estado='A'";
                                //$cadena_sql.="ORDER BY fto_id"; 
                                break;
                            
                        case "consultarFormatosAsociar": 
				$cadena_sql="SELECT ";
				$cadena_sql.="fto_id, ";
                                $cadena_sql.="fto_descripcion ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_formato a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_tipo_evaluacion b ON a.tipo_id=b.tipo_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev c ON a.acasperiev_id=c.acasperiev_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                //$cadena_sql.="AND ";
                                 //$cadena_sql.="fto_estado='A'";
                                //$cadena_sql.="ORDER BY fto_id"; 
                                break;     
                        
                        case "insertaFormatos":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_formato ";
				$cadena_sql.="( ";
				//$cadena_sql.="instructivo_id, ";
				$cadena_sql.="tipo_id, ";
                                $cadena_sql.="acasperiev_id, ";
				$cadena_sql.="fto_numero, ";
				$cadena_sql.="fto_descripcion, ";
                                $cadena_sql.="fto_porcentaje, ";
                                $cadena_sql.="fto_estado, ";
                                $cadena_sql.="fto_fec_registro ";
                                $cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
				$cadena_sql.="".$variable['tipoEvaluacion'].", ";
                                $cadena_sql.="".$variable['periodo'].", ";
				$cadena_sql.="'".$variable['fto_numero']."', ";
                                $cadena_sql.="'".$variable['descripcion']."', ";
				$cadena_sql.="".$variable['porcentaje'].", ";
                                $cadena_sql.="'".$variable['estado']."', ";
				$cadena_sql.="'".$variable['fechaHoy']."' ";
                               	$cadena_sql.=")";
				break;
                       
                       case "consultarFormatosEditar": 
				$cadena_sql="SELECT ";
				$cadena_sql.="fto_id, ";
                                $cadena_sql.="fto_numero, ";
                                $cadena_sql.="fto_descripcion, ";
                                $cadena_sql.="fto_porcentaje, ";
                                $cadena_sql.="fto_estado, ";
                                $cadena_sql.="tipo_nombre, ";
                                $cadena_sql.="b.tipo_id ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_formato a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_tipo_evaluacion b ON a.tipo_id=b.tipo_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="fto_numero= '".$variable['formatoNumero']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_id =".$variable['periodo']." ";
                                break;
                            
                       case "actualizaFormatos":
                                $cadena_sql="UPDATE autoevaluadoc.evaldocente_formato ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="tipo_id='".$variable['tipEvaluacion']."', ";
                                $cadena_sql.="fto_numero='".$variable['fto_num']."', ";
                                $cadena_sql.="fto_porcentaje='".$variable['porcentaje']."', ";
                                $cadena_sql.="fto_estado='".$variable['estado']."', ";
                                $cadena_sql.="fto_descripcion='".$variable['descripcion']."', ";
                                $cadena_sql.="fto_fec_registro= '".$variable['fechaHoy']."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="fto_numero='".$variable['fto_numero']."' ";
                                break;
                            
                       case "buscarEncabezados": 
				$cadena_sql="SELECT ";
				$cadena_sql.="enc_nombre ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_encabezado ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="enc_nombre = '".$variable['encabezado']."' ";
                                break;
                            
                       case "insertaEncabezados":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_encabezado ";
				$cadena_sql.="( ";
				//$cadena_sql.="instructivo_id, ";
				$cadena_sql.="enc_nombre ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
				$cadena_sql.="'".$variable['encabezado']."' ";
				$cadena_sql.=")";
				break;
                            
                      case "buscaEncabezados": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="enc_id, ";
				$cadena_sql.="enc_nombre ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_encabezado ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="enc_id<>0 ";
                                $cadena_sql.="ORDER BY enc_id ASC ";
                                break;
                            
                     case "consultarEncabezadosEditar": 
				$cadena_sql="SELECT ";
				$cadena_sql.="enc_nombre ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_encabezado ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="enc_id =".$variable['id']."";
                                break;       
                            
                      case "actualizaEncabezados":
                                $cadena_sql="UPDATE autoevaluadoc.evaldocente_encabezado ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="enc_nombre='".$variable['encabezado1']."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="enc_id='".$variable['id']."' ";
                                break;  
                            
                      case "consultarTipoPregunta":
                                $cadena_sql="SELECT ";
				$cadena_sql.="tip_preg_id, ";
                                $cadena_sql.="tip_preg_nombre ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_tipo_pregunta ";
                                break;       
                            
                      case "consultarPreguntas":
                                $cadena_sql="SELECT ";
				$cadena_sql.="preg_id, ";
                                $cadena_sql.="a.acasperiev_id, ";
                                $cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo, ";
                                $cadena_sql.="a.tip_preg_id, ";
                                $cadena_sql.="tip_preg_nombre, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="preg_valor_pregunta, ";
                                $cadena_sql.="preg_estado ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_pregunta a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev b ON  b.acasperiev_id=a.acasperiev_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_tipo_pregunta c ON  c.tip_preg_id=a.tip_preg_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="preg_id<>0 ";
                                $cadena_sql.="ORDER BY preg_id ASC ";
                                break;
                            
                     case "insertaPreguntas":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_pregunta ";
				$cadena_sql.="( ";
                                $cadena_sql.="acasperiev_id, ";
				$cadena_sql.="tip_preg_id, ";
				$cadena_sql.="preg_pregunta, ";
				$cadena_sql.="preg_fec_registro, ";
                                $cadena_sql.="preg_valor_pregunta, ";
                                $cadena_sql.="preg_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
                                $cadena_sql.="".$variable['periodo'].", ";
				$cadena_sql.="".$variable['tipoPregunta'].", ";
				$cadena_sql.="'".$variable['pregunta']."', ";
                                $cadena_sql.="'".$variable['fechaHoy']."', ";
				$cadena_sql.="".$variable['valorPregunta'].", ";
                                $cadena_sql.="'".$variable['estado']."' ";
				$cadena_sql.=")";
				break;
                            
                      case "buscarPreguntas": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="preg_id, ";
				$cadena_sql.="preg_pregunta ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_pregunta ";
                                $cadena_sql.="WHERE ";
                                //$cadena_sql.="preg_descripcion =ltrim(rtrim('".$variable['preg']."')) ";
                                //$cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_id =".$variable['periodo']." ";
                                break; 
                          
                        case "consultarPreguntasEditar":
                                $cadena_sql="SELECT ";
				$cadena_sql.="preg_id, ";
                                $cadena_sql.="a.acasperiev_id, ";
                                $cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo, ";
                                $cadena_sql.="a.tip_preg_id, ";
                                $cadena_sql.="tip_preg_nombre, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="preg_valor_pregunta, ";
                                $cadena_sql.="preg_estado ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_pregunta a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev b ON  b.acasperiev_id=a.acasperiev_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_tipo_pregunta c ON  c.tip_preg_id=a.tip_preg_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="preg_id=".$variable['preguntaId']." ";
                                break;  
                            
                         case "actualizaPreguntas":
                                $cadena_sql="UPDATE autoevaluadoc.evaldocente_pregunta ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="tip_preg_id='".$variable['tipPregunta']."', ";
                                $cadena_sql.="preg_pregunta='".$variable['pregunta']."', ";
                                $cadena_sql.="preg_valor_pregunta=".$variable['valorPregunta'].", ";
                                $cadena_sql.="preg_estado='".$variable['estado']."', ";
                                $cadena_sql.="preg_fec_registro= '".$variable['fechaHoy']."'";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="preg_id=".$variable['preguntaId']." ";
                                break;
                         
                         case "Preguntas": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="preg_id, ";
				$cadena_sql.="preg_pregunta ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_pregunta a ";
				$cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev b ON  b.acasperiev_id=a.acasperiev_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="preg_id<>0 ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_estado='A' ";
                                break;  
                
                         case "buscarFormularios": 
			 	$cadena_sql="SELECT ";
                                $cadena_sql.="fto_id, ";
			 	$cadena_sql.="a.enc_id, ";
                                $cadena_sql.="a.preg_id, ";
                                $cadena_sql.="enc_nombre, ";
                                $cadena_sql.="preg_pregunta, ";
                                $cadena_sql.="preg_valor ";
                                $cadena_sql.="FROM ";
			 	$cadena_sql.="autoevaluadoc.evaldocente_formulario a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_encabezado b ON b.enc_id=a.enc_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON c.preg_id=a.preg_id ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_acasperiev d ON d.preg_id=a.preg_id ";
                                $cadena_sql.="WHERE ";
                                /*$cadena_sql.="fto_id =".$variable['formatoId']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="enc_id =".$variable['encabezados']." ";*/
                                //$cadena_sql.="AND ";
                                //$cadena_sql.="preg_id =".$variable['preguntas']." ";
                                $cadena_sql.="fto_id =".$variable['formatoId']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="a.preg_id =".$variable['preguntas']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="preg_pregunta<>'' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="a.acasperiev_id =".$variable['periodo']." ";
                                break;
                         
                         case "insertaFormulario":
			 	$cadena_sql="INSERT INTO ";
			 	$cadena_sql.="autoevaluadoc.evaldocente_formulario ";
			 	$cadena_sql.="( ";
                                $cadena_sql.="acasperiev_id, ";
                                $cadena_sql.="fto_id, ";
				$cadena_sql.="enc_id, ";
				$cadena_sql.="preg_id, ";
				$cadena_sql.="form_fec_registro, ";
                                $cadena_sql.="form_estado ";
			 	$cadena_sql.=") ";
			 	$cadena_sql.="VALUES ";
			 	$cadena_sql.="( ";
			 	//$cadena_sql.="'' , ";
                                $cadena_sql.="".$variable['periodo'].", ";
                                $cadena_sql.="".$variable['formatoId'].", ";
			 	$cadena_sql.="".$variable['encabezados'].", ";
				$cadena_sql.="".$variable['preguntas'].", ";
                                $cadena_sql.="'".$variable['fechaHoy']."', ";
			 	$cadena_sql.="'".$variable['estado']."' ";
			 	$cadena_sql.=")";
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
                                $cadena_sql.="ORDER BY form_id ASC";
                                break;
                         
                         case "editarFormulario": 
			 	$cadena_sql="UPDATE ";
                                $cadena_sql.="autoevaluadoc.evaldocente_formulario ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="form_estado='I' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="form_id=".$variable['formularioId']."";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_id =".$variable['periodo']." ";
			 	break;
                        
                         case "consultarTipoVinculacion": //EN ORACLE
                                $cadena_sql="SELECT ";
                                $cadena_sql.="tvi_cod, ";
                                $cadena_sql.="tvi_nombre, ";
                                $cadena_sql.="tvi_estado ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="mntac.actipvin ";
                                $cadena_sql.="ORDER BY tvi_cod ASC ";
                                break;
                         
                        case "buscarAsociacion": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="ftvd_id, ";
                                $cadena_sql.="fto_id, ";
                                $cadena_sql.="ftvd_tvi_cod ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="fto_id=".$variable['formatos']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ftvd_tvi_cod=".$variable['vinculacionDocentes']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ftvd_estado='A'";
                                break;
                        
                         case "insertaAsociacion":
			 	$cadena_sql="INSERT INTO ";
			 	$cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc ";
			 	$cadena_sql.="( ";
                                $cadena_sql.="fto_id, ";
                                $cadena_sql.="ftvd_tvi_cod, ";
                                $cadena_sql.="ftvd_periodo, ";
			 	$cadena_sql.="ftvd_estado ";
                                $cadena_sql.=") ";
			 	$cadena_sql.="VALUES ";
			 	$cadena_sql.="( ";
			 	//$cadena_sql.="'' , ";
                                $cadena_sql.="".$variable['formatos'].", ";
                                $cadena_sql.="".$variable['vinculacionDocentes'].", ";
                                $cadena_sql.="".$variable['periodo'].", ";
                                $cadena_sql.="'A'";
			 	$cadena_sql.=")";
			 	break;
                          
                         case "consultarAsociacion": 
                                $cadena_sql="SELECT ";
                                $cadena_sql.="ftvd_id, ";
                                $cadena_sql.="a.fto_id , ";
                                $cadena_sql.="fto_descripcion, ";
                                $cadena_sql.="ftvd_tvi_cod, ";
                                $cadena_sql.="ftvd_estado ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc a ";
                                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato b ON b.fto_id=a.fto_id ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="b.acasperiev_id =".$variable['periodo']." ";
                                break;
                            
                         case "cambiarEstadoAsociacion": 
			 	$cadena_sql="UPDATE ";
				$cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc ";
				$cadena_sql.="SET ";
				$cadena_sql.="ftvd_estado = '".$variable['estadoFinal']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ftvd_estado ='".$variable['estadoInicial']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ftvd_id =".$variable['asociacion']." ";
                                break;   
                            
                }
                //echo $cadena_sql."<br>";
		return $cadena_sql;

	}
}
?>
