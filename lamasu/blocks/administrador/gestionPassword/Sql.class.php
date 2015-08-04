<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlgestionPassword extends sql {
	
	
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
                        case "buscarUsuario": 
                               $cadena_sql="SELECT DISTINCT ";
                               $cadena_sql.="usu_id, "; 
                               $cadena_sql.="usu_nombre, ";
                               $cadena_sql.="usu_apellido, ";
                               $cadena_sql.="usu_nro_doc_actual, ";
                               $cadena_sql.="cta_nombre_usuario, ";
                               $cadena_sql.="cta_clave, ";
                               $cadena_sql.="cta_fecha_actualizacion, "; 
                               $cadena_sql.="cta_estado, ";
                               $cadena_sql.="cta_fecha_ultimo_ingreso, ";
                               $cadena_sql.="apl_tiempo_cambio_clave, ";
                               $cadena_sql.="apl_url, ";
                               $cadena_sql.="pus_perf_id, ";
                               $cadena_sql.="pus_usuario ";
                               $cadena_sql.="FROM ";
                               $cadena_sql.="administracion.admin_usuario ";
                               $cadena_sql.="INNER JOIN administracion.admin_cuenta ON usu_id=cta_usu_id ";
                               $cadena_sql.="INNER JOIN administracion.admin_perfil_usuario ON usu_id=pus_usu_id "; 
                               $cadena_sql.="INNER JOIN administracion.admin_aplicacion ON pus_apl_id=apl_id ";
                               $cadena_sql.="WHERE ";
                               $cadena_sql.="cta_estado='1' ";
                               $cadena_sql.="AND ";
                               $cadena_sql.="cta_nombre_usuario='".$variable['usuario_id']."' ";
                               $cadena_sql.="AND ";
                               $cadena_sql.="cta_nombre_usuario=pus_usuario "; 
				/*if(isset($variable['tipo']))
				      { $cadena_sql.=" AND ";
				        $cadena_sql.="pus_perf_id='".$variable['tipo']."' ";
				      }	*/

                               break;
                            
                         case "actualizaPassword":
				$cadena_sql="UPDATE ";
				$cadena_sql.="administracion.admin_cuenta ";
				$cadena_sql.="SET ";
				$cadena_sql.="cta_clave = '".$variable['nuevaClave']."', ";
                                $cadena_sql.="cta_fecha_actualizacion = '".$variable['fecha']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cta_nombre_usuario ='".$variable['usuario_id']."'";
				break;
                           
                           case "actualizaPasswordORACLE":
				$cadena_sql="UPDATE ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="SET ";
				$cadena_sql.="cla_clave = '".$variable['nuevaClave']."' ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo ='".$variable['usuario_id']."'";
				break; 
                            
                            case "actualizaPasswordMySQL":
				$cadena_sql="UPDATE ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="SET ";
				$cadena_sql.="cla_clave = '".$variable['nuevaClave']."' ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo ='".$variable['usuario_id']."'";
				break;
                            
                            //En ORACLE
                            case "datosDocentes":
				$cadena_sql="SELECT doc_nro_iden, ";
                                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                                $cadena_sql.="doc_direccion, ";
                                $cadena_sql.="doc_telefono, ";
                                $cadena_sql.="doc_celular, ";
                                $cadena_sql.="doc_email ";
                                $cadena_sql.="FROM mntac.acdocente ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="doc_nro_iden IN ('".$variable['documentoActual']."') "; 
                                $cadena_sql.="AND doc_estado='A' ";
				$cadena_sql.="AND doc_estado_registro='A'";
                                break;
                            
                        case "datosEmpleados":
				$cadena_sql="SELECT emp_nro_iden, ";
                                $cadena_sql.="emp_nombre, ";
                                $cadena_sql.="emp_direccion, ";
                                $cadena_sql.="emp_telefono, ";
                                $cadena_sql.="emp_telefono_alt, ";
                                $cadena_sql.="emp_email ";
                                $cadena_sql.="FROM peemp ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="emp_nro_iden IN ('".$variable['documentoActual']."') "; 
                                $cadena_sql.="AND emp_fallecido='N' ";
                                $cadena_sql.="AND emp_estado_e NOT IN ('R')";
                                break;
                           
                        case "datosEstudiantes":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="est_nro_iden, ";
                                $cadena_sql.="est_nombre, ";
                                $cadena_sql.="est_direccion, ";
                                $cadena_sql.="est_telefono, ";
                                $cadena_sql.="eot_tel_cel, ";
                                $cadena_sql.="eot_email, ";
                                $cadena_sql.="est_estado_est, ";
                                $cadena_sql.="eot_email_ins ";
                                $cadena_sql.="FROM mntac.acest ";
                                $cadena_sql.="INNER JOIN mntac.acestotr ON est_cod=eot_cod ";
                                //$cadena_sql.="INNER JOIN acestado ON est_estado_est=estado_cod ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="est_nro_iden = '".$variable['documentoActual']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="est_cod='".$variable['codigoEstudiante']."' ";
				$cadena_sql.="AND est_estado_est NOT IN ('E')";
                                break;
                            
                        case "datosEgresados":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="egr_nro_iden, ";
                                $cadena_sql.="egr_nombre, ";
                                $cadena_sql.="egr_direccion_casa, ";
                                $cadena_sql.="egr_telefono_casa, ";
                                $cadena_sql.="egr_movil, ";
                                $cadena_sql.="egr_email, ";
                                $cadena_sql.="egr_est_cod ";
                                $cadena_sql.="FROM mntac.acegresado ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="egr_nro_iden = '".$variable['documentoActual']."' ";
                                $cadena_sql.="AND egr_est_cod =(SELECT max(egr.egr_est_cod) FROM mntac.acegresado egr WHERE egr.egr_nro_iden = '".$variable['documentoActual']."') ";
                                $cadena_sql.="ORDER BY egr_est_cod DESC ";
                                break;    
                        
                        case "datosAsistentes":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="uwd_codigo, ";
                                $cadena_sql.="(LTRIM(RTRIM(uwd_nombres)) ||' '|| LTRIM(RTRIM(uwd_apellidos))) nombre, ";
                                $cadena_sql.="uwd_direccion, ";
                                $cadena_sql.="uwd_telefono, ";
                                $cadena_sql.="uwd_celular, ";
                                $cadena_sql.="uwd_correo_electronico ";
                                $cadena_sql.="FROM geusuwebdatos ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="uwd_codigo = '".$variable['documentoActual']."'";
                                break;
                            
                        case "datosAleatorios":
                                $cadena_sql="SELECT * FROM ";
                                $cadena_sql.="( SELECT * FROM mntac.acdocente ";
                                $cadena_sql.="ORDER BY dbms_random.value ) ";
                                $cadena_sql.="WHERE rownum <= 1 ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="doc_estado='A'";
                                
                        case "verificaDatosDocentes":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="est_nro_iden, ";
                                $cadena_sql.="est_nombre, ";
                                $cadena_sql.="est_direccion, ";
                                $cadena_sql.="est_telefono, ";
                                $cadena_sql.="eot_tel_cel, ";
                                $cadena_sql.="eot_email, ";
                                $cadena_sql.="est_estado_est ";
                                $cadena_sql.="FROM mntac.acest ";
                                $cadena_sql.="INNER JOIN mntac.acestotr ON est_cod=eot_cod ";
                                $cadena_sql.="INNER JOIN acestado ON est_estado_est=estado_cod ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="est_nro_iden = '".$variable['documentoActual']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="estado_activo='S' ";
                                break;
                }
                //echo 'mmm'.$cadena_sql.'<br>';
		return $cadena_sql;

	}
}
?>
