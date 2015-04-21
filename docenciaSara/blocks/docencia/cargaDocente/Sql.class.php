<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlCargaDocente extends sql {


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
                         case "buscarNombreDocente":
				$cadena_sql="SELECT ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_nombre|| ' ' ||doc_apellido ";
                                //$cadena_sql.="CONCAT(doc_nombre, ' ' ,doc_apellido) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACDOCENTE ";
                                
                                if(is_numeric($variable))
                                    {
                                        $cadena_sql.="WHERE ";
                                        $cadena_sql.="doc_nro_iden like '%".strtoupper(trim($variable))."%' ";
                                    }else
                                        {
                                            $cadena_sql.="WHERE ";
                                            $cadena_sql.="UPPER(doc_nombre) like '%".strtoupper(trim($variable))."%' ";
                                            $cadena_sql.="OR ";
                                            $cadena_sql.="UPPER(doc_apellido) like '%".strtoupper(trim($variable))."%' ";
                                        }
				$cadena_sql.="AND ";
				$cadena_sql.="DOC_ESTADO = 'A'  ";
                                        
				break;
                            
                        case "buscarDocentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="doc_nro_iden, ";
				$cadena_sql.="doc_nombre|| ' ' ||doc_apellido ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACDOCENTE ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DOC_ESTADO = 'A' ";
				break;    
                                        
			case "periodosActivos":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'-'||ape_per, ";
				$cadena_sql.="ape_ano||'-'||ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
                                //$cadena_sql.="ape_estado NOT IN ('I') ";
                                $cadena_sql.="ape_estado IN ('A','X') ";
                                $cadena_sql.="order by ape_ano, ape_per ASC ";
				break;
                            
                        case "tipoVinculacion":
                             
				$cadena_sql="SELECT tvi_cod, tvi_nombre ";
				$cadena_sql.="FROM ACTIPVIN ";
				$cadena_sql.="ORDER BY 1 ";
				break;    
                            
			case "datosDocente":

				$cadena_sql="SELECT ";
				$cadena_sql.="tipvin.tvi_nombre, cra.cra_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTDO.HISTORICO_ESTADO_DOCENTE estadoc ";
				$cadena_sql.="JOIN MNTAC.actipvin tipvin ON tipvin.tvi_cod = estadoc.TIPO_VINCULACION ";
                                $cadena_sql.="JOIN MNTAC.ACCRA cra ON cra.cra_cod = estadoc.ID_PROYECTO_CURRICULAR ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="NUMERO_DOCUMENTO= ".$variable;
				break;
                            
                        case "carreras":    
                                $cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, cra_abrev ";
				$cadena_sql.="FROM accra ";
				$cadena_sql.="WHERE cra_emp_nro_iden = ".$variable." ";
				$cadena_sql.="AND cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod ASC";
                                
                                break;

                        case "buscarCursoProyecto":
				$cadena_sql="SELECT ";
				$cadena_sql.="asi_cod, ";
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="cur_grupo, ";
				$cadena_sql.="cur_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASPERI, ACASI, ACCRA, ACCURSOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_cod =".$variable." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ANO = CUR_APE_ANO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = CUR_APE_PER ";
				$cadena_sql.="AND ";
				$cadena_sql.="asi_cod = cur_asi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = CUR_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="cur_ape_ano=ape_ano ";
				$cadena_sql.="AND ";
				$cadena_sql.="cur_ape_per=ape_per ";
				$cadena_sql.="order by asi_cod, cur_grupo asc ";
				break;  
                            
                        case "buscarCursosProyectoTodos":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="asi_cod, ";
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="cur_grupo, ";
				$cadena_sql.="CUR_cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASPERI, ACASI, ACCRA, ACCURSOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_cod =".$variable[0]." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="asi_cod = cur_asi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = CUR_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ESTADO = 'A' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="cur_ape_ano=ape_ano ";
				$cadena_sql.="AND ";
				$cadena_sql.="cur_ape_per=ape_per ";
				$cadena_sql.="order by asi_cod, cur_grupo asc ";
				break;     
                            
                        case "horarioCurso":
                            
                                $cadena_sql="SELECT DISTINCT cur_asi_cod, cur_grupo, hor_dia_nro, hor_hora, sed_nombre, sal_nombre ";
                                $cadena_sql.="FROM ACCURSOS ";
                                $cadena_sql.="JOIN ACHORARIOS ON hor_id_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN GESALONES ON SAL_ID_ESPACIO = HOR_SAL_ID_ESPACIO ";
                                $cadena_sql.="JOIN GESEDE ON SAL_SED_ID = SED_ID ";
                                $cadena_sql.="where CUR_APE_ANO = 2013 ";
                                $cadena_sql.="AND CUR_APE_PER = 1 ";
                                $cadena_sql.="AND cur_asi_cod = 1 ";
                                $cadena_sql.="AND cur_grupo = 245 ";
                                /*$cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND cur_grupo = ".$variable[1]." ";*/
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
				break; 
                            
                         case "horarioCursoTabCurso":
                            
                                $cadena_sql="SELECT DISTINCT cur_asi_cod, cur_grupo, hor_dia_nro, dia_nombre, hor_hora, hor_larga, sed_nombre, sal_nombre, hor_id ";
                                $cadena_sql.="FROM ACCURSOS ";
                                $cadena_sql.="JOIN ACHORARIOS ON hor_id_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN GESALONES ON SAL_ID_ESPACIO = HOR_SAL_ID_ESPACIO ";
                                $cadena_sql.="JOIN GESEDE ON SAL_SED_ID = SED_ID ";
                                $cadena_sql.="JOIN GEDIA ON GEDIA.DIA_COD = hor_dia_nro ";
                                $cadena_sql.="JOIN GEHORA ON GEHORA.HOR_COD = hor_hora ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND cur_cra_cod = ".$variable[4]." ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
                                $cadena_sql.="ORDER BY 3,5 ";
				break;  
                            
                         case "cargaCurso":
                            
                                $cadena_sql="SELECT car_cra_cod, cra_nombre, car_doc_nro_iden, doc_nombre, doc_apellido, car_nro_hrs ";
                                $cadena_sql.="FROM ACCARGA ";
                                $cadena_sql.="JOIN ACDOCENTE ON doc_nro_iden = CAR_doc_nro_iden ";
                                $cadena_sql.="JOIN ACCRA ON car_cra_cod = cra_cod ";
                                $cadena_sql.="where CAR_APE_ANO = 2013 ";
                                $cadena_sql.="AND CAR_APE_PER = 1 ";
                                $cadena_sql.="AND CAR_cur_asi_cod = 1 ";
                                $cadena_sql.="AND CAR_CUR_NRO = 245 ";
                                /*$cadena_sql.="where CAR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CAR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CAR_cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND CAR_CUR_NRO = ".$variable[1]." ";*/
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;   
                            
                         case "cargaCursoTab":
                                $cadena_sql="SELECT DISTINCT cra_cod, cra_nombre, car_doc_nro, doc_nombre, doc_apellido, tvi_nombre, car_tip_vin, COUNT(*) as horas ";
                                $cadena_sql.=" FROM MNTAC.ACCARGAS ";
                                $cadena_sql.="JOIN ACHORARIOS ON hor_id = CAR_hor_id ";
                                $cadena_sql.="JOIN ACCURSOS ON hor_id_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN ACDOCENTE ON doc_nro_iden = car_doc_nro ";
                                $cadena_sql.="JOIN ACCRA ON CUR_cra_cod = cra_cod  ";
                                $cadena_sql.="JOIN ACTIPVIN ON tvi_cod = car_tip_vin ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
                                $cadena_sql.="AND cur_cra_cod = ".$variable[4]." ";
                                $cadena_sql.="GROUP BY cra_cod, cra_nombre, car_doc_nro, doc_nombre, doc_apellido,tvi_nombre,car_tip_vin";
				break;  
                            
                        case "cargaCursoTabDocente":
                                $cadena_sql="SELECT DISTINCT car_doc_nro, CAR_hor_id ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";
                                $cadena_sql.="JOIN ACHORARIOS ON hor_id = CAR_hor_id ";
                                $cadena_sql.="JOIN ACCURSOS ON hor_id_CURSO = CUR_ID ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND car_doc_nro = ".$variable[4]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
                                $cadena_sql.="AND cur_cra_cod = ".$variable[5]." ";
				break;  
                            
                        case "cruceHorarioTabDocente":
			
                                $cadena_sql="SELECT dia.dia_nombre, hora.hor_larga ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS carga ";
                                $cadena_sql.="JOIN ACHORARIOS horario ON carga.CAR_hor_id = horario.hor_id ";
                                $cadena_sql.="JOIN GEDIA dia ON dia.DIA_COD = horario.hor_dia_nro ";
                                $cadena_sql.="JOIN GEHORA hora ON hora.HOR_COD = horario.hor_hora ";
                                $cadena_sql.="WHERE carga.car_doc_nro = '".$variable[4]."'  ";
                                $cadena_sql.="AND carga.CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND (horario.hor_dia_nro, horario.hor_hora) IN  ";
                                $cadena_sql.="(SELECT horario2.hor_dia_nro, horario2.hor_hora ";
                                $cadena_sql.="FROM ACHORARIOS horario2 ";
                                $cadena_sql.="JOIN ACCURSOS curso2 ON horario2.hor_id_CURSO = curso2.CUR_ID ";
                                $cadena_sql.="WHERE curso2.cur_asi_cod = ".$variable[0]."  ";
                                $cadena_sql.="AND curso2.cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND curso2.CUR_APE_ANO = ".$variable[2]."  ";
                                $cadena_sql.="AND curso2.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND curso2.CUR_ESTADO = 'A') ";
                               
				break;  
                        
                       case "cruceHorarioTabDocenteHora":
			
                                $cadena_sql="SELECT dia.dia_nombre, hora.hor_larga ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS carga ";
                                $cadena_sql.="JOIN ACHORARIOS horario ON carga.CAR_hor_id = horario.hor_id ";
                                $cadena_sql.="JOIN GEDIA dia ON dia.DIA_COD = horario.hor_dia_nro ";
                                $cadena_sql.="JOIN GEHORA hora ON hora.HOR_COD = horario.hor_hora ";
                                $cadena_sql.="JOIN ACCURSOS curso ON horario.hor_id_CURSO = curso.CUR_ID ";
                                $cadena_sql.="WHERE carga.car_doc_nro = '".$variable[4]."'  ";
                                $cadena_sql.="AND carga.CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND curso.CUR_APE_ANO = ".$variable[2]."  ";
                                $cadena_sql.="AND curso.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND (horario.hor_dia_nro, horario.hor_hora) IN  ";
                                $cadena_sql.="(SELECT horario2.hor_dia_nro, horario2.hor_hora ";
                                $cadena_sql.="FROM ACHORARIOS horario2 ";
                                $cadena_sql.="JOIN ACCURSOS curso2 ON horario2.hor_id_CURSO = curso2.CUR_ID ";
                                $cadena_sql.="WHERE curso2.cur_asi_cod = ".$variable[0]."  ";
                                $cadena_sql.="AND curso2.cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND curso2.CUR_APE_ANO = ".$variable[2]."  ";
                                $cadena_sql.="AND curso2.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND horario2.hor_id = ".$variable[5]." ";
                                $cadena_sql.="AND curso2.CUR_ESTADO = 'A') ";
                               
				break;     
                            
                       case "crucePlanTrabDocenteHora":
			
                                $cadena_sql=" SELECT dia.dia_nombre, hora.hor_larga ";
                                $cadena_sql.=" FROM acdocplantrabajo plant";
                                $cadena_sql.=" JOIN GEDIA dia ON dia.DIA_COD = plant.dpt_dia_nro";
                                $cadena_sql.=" JOIN GEHORA hora ON hora.HOR_COD = plant.dpt_hora";
                                $cadena_sql.=" WHERE dpt_doc_nro_iden='".$variable[4]."'";
                                $cadena_sql.=" AND dpt_ape_ano=".$variable[2]."";
                                $cadena_sql.=" AND dpt_ape_per=".$variable[3]."";
                                $cadena_sql.=" AND dpt_estado='A'";
                                $cadena_sql.=" AND (plant.dpt_dia_nro, plant.dpt_hora) IN (SELECT horario2.hor_dia_nro, horario2.hor_hora ";
                                $cadena_sql.="FROM ACHORARIOS horario2 ";
                                $cadena_sql.="JOIN ACCURSOS curso2 ON horario2.hor_id_CURSO = curso2.CUR_ID ";
                                $cadena_sql.="WHERE curso2.cur_asi_cod = ".$variable[0]." ";
                                $cadena_sql.="AND curso2.cur_grupo = ".$variable[1]." ";
                                $cadena_sql.="AND curso2.CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND curso2.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND horario2.hor_id = ".$variable[5]." ";
                                $cadena_sql.="AND curso2.CUR_ESTADO = 'A') ";
				break;     
                            
                        case "buscarCursoNoSeleccionado":
                                $cadena_sql="SELECT CAR_ID, CAR_hor_id, car_doc_nro, car_tip_vin, CAR_ESTADO ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";                                
                                $cadena_sql.="where CAR_hor_id = ".$variable[0]." ";
                                $cadena_sql.="AND car_doc_nro = ".$variable[1]." ";
                                $cadena_sql.="AND car_tip_vin = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;
                            
                        case "buscarCursoSiSeleccionado":
                                $cadena_sql="SELECT CAR_ID, CAR_hor_id, car_doc_nro, car_tip_vin, CAR_ESTADO ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";                                
                                $cadena_sql.="where CAR_hor_id = ".$variable[0]." ";
                                $cadena_sql.="AND car_doc_nro = ".$variable[1]." ";
                                $cadena_sql.="AND car_tip_vin = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;    
                            
                        case "inhabilitarCarga":
                            
                                $cadena_sql="DELETE ";
                                $cadena_sql.="MNTAC.ACCARGAS ";
                                $cadena_sql.="WHERE CAR_hor_id = ".$variable[0]." ";
                                $cadena_sql.="AND car_doc_nro = ".$variable[1]." ";
                                $cadena_sql.="AND car_tip_vin = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;    
                            
                        case "guardarCargaCursoModificar":
                            
                                $cadena_sql="INSERT INTO MNTAC.ACCARGAS ";
                                $cadena_sql.="(car_hor_id, car_doc_nro, car_tip_vin, car_estado)  ";
                                $cadena_sql.="VALUES ( ";
                                $cadena_sql.=" ".$variable[0].", " ;
                                $cadena_sql.=" ".$variable[1].", " ;
                                $cadena_sql.=" ".$variable[2].", " ;
                                $cadena_sql.=" '".$variable[3]."') " ;
				break;    
                            
                        case "guardarCargaCurso":
                            
                                $cadena_sql="INSERT INTO MNTAC.ACCARGAS ";
                                $cadena_sql.="(car_hor_id, car_doc_nro, car_tip_vin, car_estado)  ";
                                $cadena_sql.="VALUES ( ";
                                $cadena_sql.=" ".$variable[0].", " ;
                                $cadena_sql.=" ".$variable[1].", " ;
                                $cadena_sql.=" ".$variable[2].", " ;
                                $cadena_sql.=" '".$variable[3]."') " ;
				break;    
				/**
				 * Clausulas genéricas. se espera que estén en todos los formularios
				 * que utilicen esta plantilla
				 */

			case "iniciarTransaccion":
				$cadena_sql="START TRANSACTION";
				break;

			case "finalizarTransaccion":
				$cadena_sql="COMMIT";
				break;

			case "cancelarTransaccion":
				$cadena_sql="ROLLBACK";
				break;


			case "eliminarTemp":

				$cadena_sql="DELETE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tempFormulario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_sesion = '".$variable."' ";
				break;

			case "insertarTemp":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."tempFormulario ";
				$cadena_sql.="( ";
				$cadena_sql.="id_sesion, ";
				$cadena_sql.="formulario, ";
				$cadena_sql.="campo, ";
				$cadena_sql.="valor, ";
				$cadena_sql.="fecha ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";

				foreach($_REQUEST as $clave => $valor) {
					$cadena_sql.="( ";
					$cadena_sql.="'".$idSesion."', ";
					$cadena_sql.="'".$variable['formulario']."', ";
					$cadena_sql.="'".$clave."', ";
					$cadena_sql.="'".$valor."', ";
					$cadena_sql.="'".$variable['fecha']."' ";
					$cadena_sql.="),";
				}

				$cadena_sql=substr($cadena_sql,0,(strlen($cadena_sql)-1));
				break;

			case "rescatarTemp":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_sesion, ";
				$cadena_sql.="formulario, ";
				$cadena_sql.="campo, ";
				$cadena_sql.="valor, ";
				$cadena_sql.="fecha ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tempFormulario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_sesion='".$idSesion."'";
				break;

		}
                //echo $cadena_sql."<br><br>";
		return $cadena_sql;

	}
}
?>