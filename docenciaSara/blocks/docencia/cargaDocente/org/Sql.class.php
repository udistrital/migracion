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
				$cadena_sql.="DOC_NRO_IDEN, ";
				$cadena_sql.="DOC_NOMBRE|| ' ' ||DOC_APELLIDO ";
                                //$cadena_sql.="CONCAT(DOC_NOMBRE, ' ' ,DOC_APELLIDO) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACDOCENTE ";
                                
                                if(is_numeric($variable))
                                    {
                                        $cadena_sql.="WHERE ";
                                        $cadena_sql.="DOC_NRO_IDEN like '%".strtoupper(trim($variable))."%' ";
                                    }else
                                        {
                                            $cadena_sql.="WHERE ";
                                            $cadena_sql.="UPPER(DOC_NOMBRE) like '%".strtoupper(trim($variable))."%' ";
                                            $cadena_sql.="OR ";
                                            $cadena_sql.="UPPER(DOC_APELLIDO) like '%".strtoupper(trim($variable))."%' ";
                                        }
				$cadena_sql.="AND ";
				$cadena_sql.="DOC_ESTADO = 'A'  ";
                                        
				break;
                            
                        case "buscarDocentes":
				$cadena_sql="SELECT ";
				$cadena_sql.="DOC_NRO_IDEN, ";
				$cadena_sql.="DOC_NOMBRE|| ' ' ||DOC_APELLIDO ";
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
                             
				$cadena_sql="SELECT TVI_COD, TVI_NOMBRE ";
				$cadena_sql.="FROM ACTIPVIN ";
				$cadena_sql.="ORDER BY 1 ";
				break;    
                            
			case "datosDocente":

				$cadena_sql="SELECT ";
				$cadena_sql.="tipvin.TVI_NOMBRE, cra.CRA_NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTDO.HISTORICO_ESTADO_DOCENTE estadoc ";
				$cadena_sql.="JOIN MNTAC.actipvin tipvin ON tipvin.TVI_COD = estadoc.TIPO_VINCULACION ";
                                $cadena_sql.="JOIN MNTAC.ACCRA cra ON cra.CRA_COD = estadoc.ID_PROYECTO_CURRICULAR ";
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
				$cadena_sql.="ASI_COD, ";
				$cadena_sql.="ASI_NOMBRE, ";
				$cadena_sql.="CUR_GRUPO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASPERI, ACASI, ACCRA, ACCURSOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_COD =".$variable." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ANO = CUR_APE_ANO ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = CUR_APE_PER ";
				$cadena_sql.="AND ";
				$cadena_sql.="ASI_COD = CUR_ASI_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CRA_COD = CUR_CRA_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ESTADO = 'A' ";
				$cadena_sql.="order by ASI_COD, CUR_GRUPO asc ";
				break;  
                            
                        case "buscarCursosProyectoTodos":
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="ASI_COD, ";
				$cadena_sql.="ASI_NOMBRE, ";
				$cadena_sql.="CUR_GRUPO ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASPERI, ACASI, ACCRA, ACCURSOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_COD =".$variable[0]." " ;
				$cadena_sql.="AND ";
				$cadena_sql.="APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ASI_COD = CUR_ASI_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CRA_COD = CUR_CRA_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="CUR_ESTADO = 'A' ";
				$cadena_sql.="order by ASI_COD, CUR_GRUPO asc ";
				break;     
                            
                        case "horarioCurso":
                            
                                $cadena_sql="SELECT DISTINCT CUR_ASI_COD, CUR_GRUPO, HOR_DIA_NRO, HOR_HORA, SED_NOMBRE, SAL_NOMBRE ";
                                $cadena_sql.="FROM ACCURSOS ";
                                $cadena_sql.="JOIN ACHORARIOS ON HOR_ID_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN GESALONES ON SAL_ID_ESPACIO = HOR_SAL_ID_ESPACIO ";
                                $cadena_sql.="JOIN GESEDE ON SAL_SED_ID = SED_ID ";
                                $cadena_sql.="where CUR_APE_ANO = 2013 ";
                                $cadena_sql.="AND CUR_APE_PER = 1 ";
                                $cadena_sql.="AND CUR_ASI_COD = 1 ";
                                $cadena_sql.="AND CUR_GRUPO = 245 ";
                                /*$cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CUR_ASI_COD = ".$variable[0]." ";
                                $cadena_sql.="AND CUR_GRUPO = ".$variable[1]." ";*/
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
				break; 
                            
                         case "horarioCursoTabCurso":
                            
                                $cadena_sql="SELECT DISTINCT CUR_ASI_COD, CUR_GRUPO, HOR_DIA_NRO, DIA_NOMBRE, HOR_HORA, HOR_LARGA, SED_NOMBRE, SAL_NOMBRE, HOR_ID ";
                                $cadena_sql.="FROM ACCURSOS ";
                                $cadena_sql.="JOIN ACHORARIOS ON HOR_ID_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN GESALONES ON SAL_ID_ESPACIO = HOR_SAL_ID_ESPACIO ";
                                $cadena_sql.="JOIN GESEDE ON SAL_SED_ID = SED_ID ";
                                $cadena_sql.="JOIN GEDIA ON GEDIA.DIA_COD = HOR_DIA_NRO ";
                                $cadena_sql.="JOIN GEHORA ON GEHORA.HOR_COD = HOR_HORA ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CUR_ASI_COD = ".$variable[0]." ";
                                $cadena_sql.="AND CUR_GRUPO = ".$variable[1]." ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
                                $cadena_sql.="ORDER BY 3,5 ";
				break;  
                            
                         case "cargaCurso":
                            
                                $cadena_sql="SELECT CAR_CRA_COD, CRA_NOMBRE, CAR_DOC_NRO_IDEN, DOC_NOMBRE, DOC_APELLIDO, CAR_NRO_HRS ";
                                $cadena_sql.="FROM ACCARGA ";
                                $cadena_sql.="JOIN ACDOCENTE ON DOC_NRO_IDEN = CAR_DOC_NRO_IDEN ";
                                $cadena_sql.="JOIN ACCRA ON CAR_CRA_COD = CRA_COD ";
                                $cadena_sql.="where CAR_APE_ANO = 2013 ";
                                $cadena_sql.="AND CAR_APE_PER = 1 ";
                                $cadena_sql.="AND CAR_CUR_ASI_COD = 1 ";
                                $cadena_sql.="AND CAR_CUR_NRO = 245 ";
                                /*$cadena_sql.="where CAR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CAR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CAR_CUR_ASI_COD = ".$variable[0]." ";
                                $cadena_sql.="AND CAR_CUR_NRO = ".$variable[1]." ";*/
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;   
                            
                         case "cargaCursoTab":
                                $cadena_sql="SELECT DISTINCT CRA_COD, CRA_NOMBRE, CAR_DOC_NRO, DOC_NOMBRE, DOC_APELLIDO, TVI_NOMBRE, CAR_TIP_VIN, COUNT(*) as horas ";
                                $cadena_sql.=" FROM MNTAC.ACCARGAS ";
                                $cadena_sql.="JOIN ACHORARIOS ON HOR_ID = CAR_HOR_ID ";
                                $cadena_sql.="JOIN ACCURSOS ON HOR_ID_CURSO = CUR_ID ";
                                $cadena_sql.="JOIN ACDOCENTE ON DOC_NRO_IDEN = CAR_DOC_NRO ";
                                $cadena_sql.="JOIN ACCRA ON CUR_CRA_COD = CRA_COD  ";
                                $cadena_sql.="JOIN ACTIPVIN ON TVI_COD = CAR_TIP_VIN ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CUR_ASI_COD = ".$variable[0]." ";
                                $cadena_sql.="AND CUR_GRUPO = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
                                $cadena_sql.="GROUP BY CRA_COD, CRA_NOMBRE, CAR_DOC_NRO, DOC_NOMBRE, DOC_APELLIDO,TVI_NOMBRE,CAR_TIP_VIN";
				break;  
                            
                        case "cargaCursoTabDocente":
                                $cadena_sql="SELECT DISTINCT CAR_DOC_NRO, CAR_HOR_ID ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";
                                $cadena_sql.="JOIN ACHORARIOS ON HOR_ID = CAR_HOR_ID ";
                                $cadena_sql.="JOIN ACCURSOS ON HOR_ID_CURSO = CUR_ID ";
                                $cadena_sql.="where CUR_APE_ANO = ".$variable[2]." ";
                                $cadena_sql.="AND CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND CUR_ASI_COD = ".$variable[0]." ";
                                $cadena_sql.="AND CUR_GRUPO = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_DOC_NRO = ".$variable[4]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND CUR_ESTADO = 'A' ";
				break;  
                            
                        case "cruceHorarioTabDocente":
			
                                $cadena_sql="SELECT dia.DIA_NOMBRE, hora.HOR_LARGA ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS carga ";
                                $cadena_sql.="JOIN ACHORARIOS horario ON carga.CAR_HOR_ID = horario.HOR_ID ";
                                $cadena_sql.="JOIN GEDIA dia ON dia.DIA_COD = horario.HOR_DIA_NRO ";
                                $cadena_sql.="JOIN GEHORA hora ON hora.HOR_COD = horario.HOR_HORA ";
                                $cadena_sql.="WHERE carga.CAR_DOC_NRO = '".$variable[4]."'  ";
                                $cadena_sql.="AND carga.CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND (horario.HOR_DIA_NRO, horario.HOR_HORA) IN  ";
                                $cadena_sql.="(SELECT horario2.HOR_DIA_NRO, horario2.HOR_HORA ";
                                $cadena_sql.="FROM ACHORARIOS horario2 ";
                                $cadena_sql.="JOIN ACCURSOS curso2 ON horario2.HOR_ID_CURSO = curso2.CUR_ID ";
                                $cadena_sql.="WHERE curso2.CUR_ASI_COD = ".$variable[0]."  ";
                                $cadena_sql.="AND curso2.CUR_GRUPO = ".$variable[1]." ";
                                $cadena_sql.="AND curso2.CUR_APE_ANO = ".$variable[2]."  ";
                                $cadena_sql.="AND curso2.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND curso2.CUR_ESTADO = 'A') ";
                               
				break;  
                        
                       case "cruceHorarioTabDocenteHora":
			
                                $cadena_sql="SELECT dia.DIA_NOMBRE, hora.HOR_LARGA ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS carga ";
                                $cadena_sql.="JOIN ACHORARIOS horario ON carga.CAR_HOR_ID = horario.HOR_ID ";
                                $cadena_sql.="JOIN GEDIA dia ON dia.DIA_COD = horario.HOR_DIA_NRO ";
                                $cadena_sql.="JOIN GEHORA hora ON hora.HOR_COD = horario.HOR_HORA ";
                                $cadena_sql.="WHERE carga.CAR_DOC_NRO = '".$variable[4]."'  ";
                                $cadena_sql.="AND carga.CAR_ESTADO = 'A' ";
                                $cadena_sql.="AND (horario.HOR_DIA_NRO, horario.HOR_HORA) IN  ";
                                $cadena_sql.="(SELECT horario2.HOR_DIA_NRO, horario2.HOR_HORA ";
                                $cadena_sql.="FROM ACHORARIOS horario2 ";
                                $cadena_sql.="JOIN ACCURSOS curso2 ON horario2.HOR_ID_CURSO = curso2.CUR_ID ";
                                $cadena_sql.="WHERE curso2.CUR_ASI_COD = ".$variable[0]."  ";
                                $cadena_sql.="AND curso2.CUR_GRUPO = ".$variable[1]." ";
                                $cadena_sql.="AND curso2.CUR_APE_ANO = ".$variable[2]."  ";
                                $cadena_sql.="AND curso2.CUR_APE_PER = ".$variable[3]." ";
                                $cadena_sql.="AND horario2.HOR_ID = ".$variable[5]." ";
                                $cadena_sql.="AND curso2.CUR_ESTADO = 'A') ";
                               
				break;     
                            
                        case "buscarCursoNoSeleccionado":
                                $cadena_sql="SELECT CAR_ID, CAR_HOR_ID, CAR_DOC_NRO, CAR_TIP_VIN, CAR_ESTADO ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";                                
                                $cadena_sql.="where CAR_HOR_ID = ".$variable[0]." ";
                                $cadena_sql.="AND CAR_DOC_NRO = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_TIP_VIN = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;
                            
                        case "buscarCursoSiSeleccionado":
                                $cadena_sql="SELECT CAR_ID, CAR_HOR_ID, CAR_DOC_NRO, CAR_TIP_VIN, CAR_ESTADO ";
                                $cadena_sql.="FROM MNTAC.ACCARGAS ";                                
                                $cadena_sql.="where CAR_HOR_ID = ".$variable[0]." ";
                                $cadena_sql.="AND CAR_DOC_NRO = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_TIP_VIN = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;    
                            
                        case "inhabilitarCarga":
                            
                                $cadena_sql="UPDATE ";
                                $cadena_sql.="MNTAC.ACCARGAS ";
                                $cadena_sql.="SET CAR_ESTADO = 'I' ";
                                $cadena_sql.="where CAR_HOR_ID = ".$variable[0]." ";
                                $cadena_sql.="AND CAR_DOC_NRO = ".$variable[1]." ";
                                $cadena_sql.="AND CAR_TIP_VIN = ".$variable[2]." "; 
                                $cadena_sql.="AND CAR_ESTADO = 'A' ";
				break;    
                            
                        case "guardarCargaCursoModificar":
                            
                                $cadena_sql="INSERT INTO MNTAC.ACCARGAS ";
                                $cadena_sql.="(CAR_HOR_ID, CAR_DOC_NRO, CAR_TIP_VIN, CAR_ESTADO)  ";
                                $cadena_sql.="VALUES ( ";
                                $cadena_sql.=" ".$variable[0].", " ;
                                $cadena_sql.=" ".$variable[1].", " ;
                                $cadena_sql.=" ".$variable[2].", " ;
                                $cadena_sql.=" '".$variable[3]."') " ;
				break;    
                            
                        case "guardarCargaCurso":
                            
                                $cadena_sql="INSERT INTO MNTAC.ACCARGAS ";
                                $cadena_sql.="(CAR_HOR_ID, CAR_DOC_NRO, CAR_TIP_VIN, CAR_ESTADO)  ";
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
		return $cadena_sql;

	}
}
?>
