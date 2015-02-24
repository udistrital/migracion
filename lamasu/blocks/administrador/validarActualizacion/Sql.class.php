<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlvalidarActualizacion extends sql {
	
	
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
                               $cadena_sql="SELECT ";
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
                               $cadena_sql.="perf_redireccion, ";
                               $cadena_sql.="perf_id ";
                               $cadena_sql.="FROM ";
                               $cadena_sql.="administracion.admin_usuario ";
                               $cadena_sql.="INNER JOIN administracion.admin_cuenta ON usu_id=cta_usu_id ";
                               $cadena_sql.="INNER JOIN administracion.admin_perfil_usuario ON usu_id=pus_usu_id "; 
                               $cadena_sql.="INNER JOIN administracion.admin_aplicacion ON pus_apl_id=apl_id ";
                               $cadena_sql.="INNER JOIN administracion.admin_perfil ON perf_id=pus_perf_id AND perf_apl_id=apl_id ";
                               $cadena_sql.="WHERE ";
                               $cadena_sql.="cta_estado='1' ";
                               $cadena_sql.="AND ";
                               $cadena_sql.="cta_nombre_usuario='".$variable['usuario_id']."' ";
                               if(isset($variable['tipo']))
                               { 
                                   $cadena_sql.=" AND ";
                                   $cadena_sql.="pus_perf_id='".$variable['tipo']."' ";
                               }	

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
                }
		return $cadena_sql;
	}
}
?>
