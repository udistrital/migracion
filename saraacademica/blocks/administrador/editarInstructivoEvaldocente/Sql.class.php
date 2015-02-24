<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqleditarInstructivoEvaldocente extends sql {
	
	
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
			 
			case "buscarInstructivo":
				$cadena_sql="SELECT ";
				$cadena_sql.="instructivo_id, ";
                                $cadena_sql.="tipo_id, ";
                                $cadena_sql.="instructivo_texto, ";
				$cadena_sql.="instructivo_estado ";				 
				$cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_insturctivo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="tipo_id ='".$variable."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="instructivo_estado='A'";
				break;

			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_insturctivo ";
				$cadena_sql.="( ";
				//$cadena_sql.="instructivo_id, ";
				$cadena_sql.="tipo_id, ";
				$cadena_sql.="instructivo_texto, ";
				$cadena_sql.="instructivo_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
				$cadena_sql.="'".$variable['tipoEvaluacion']."', ";
				$cadena_sql.="'".$variable['elm1']."', ";
				$cadena_sql.="'A' ";
				$cadena_sql.=")";
				break;
                        
                          case "actualizarRegistro":
				$cadena_sql="UPDATE ";
				$cadena_sql.="autoevaluadoc.evaldocente_insturctivo ";
				$cadena_sql.="SET ";
				$cadena_sql.="instructivo_texto = '".$variable["elm1"]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="tipo_id =".$variable["tipoEvaluacion"]." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="instructivo_estado ='A' ";
				break;
				 
                }

		return $cadena_sql;

	}
}
?>
