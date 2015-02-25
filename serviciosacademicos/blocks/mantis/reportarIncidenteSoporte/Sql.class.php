<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlreportarIncidenteSoporte extends sql {
	
	
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
			
			case "buscarValorSesion":
				$cadena_sql="SELECT ";
				$cadena_sql.="valor, ";
				$cadena_sql.="sesionid, ";
				$cadena_sql.="variable, ";
				$cadena_sql.="expiracion ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."valor_sesion ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="sesionid ='".$variable["sesionId"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="variable='".$variable["variable"]."' ";
				break;
			 
			case "buscarUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="FECHA_CREACION, ";
				$cadena_sql.="PRIMER_NOMBRE ";				 
				$cadena_sql.="FROM ";
				$cadena_sql.="USUARIOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`PRIMER_NOMBRE` ='".$variable."' ";
				break;


			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."registradoConferencia ";
				$cadena_sql.="( ";
				$cadena_sql.="`idRegistrado`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`apellido`, ";
				$cadena_sql.="`identificacion`, ";
				$cadena_sql.="`codigo`, ";
				$cadena_sql.="`correo`, ";
				$cadena_sql.="`tipo`, ";
				$cadena_sql.="`fecha` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="NULL, ";
				$cadena_sql.="'".$variable['nombre']."', ";
				$cadena_sql.="'".$variable['apellido']."', ";
				$cadena_sql.="'".$variable['identificacion']."', ";
				$cadena_sql.="'".$variable['codigo']."', ";
				$cadena_sql.="'".$variable['correo']."', ";
				$cadena_sql.="'0', ";
				$cadena_sql.="'".time()."' ";
				$cadena_sql.=")";
				break;


			case "actualizarRegistro":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."conductor ";
				$cadena_sql.="SET ";
				$cadena_sql.="`nombre` = '".$variable["nombre"]."', ";
				$cadena_sql.="`apellido` = '".$variable["apellido"]."', ";
				$cadena_sql.="`identificacion` = '".$variable["identificacion"]."', ";
				$cadena_sql.="`telefono` = '".$variable["telefono"]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`idConductor` =".$_REQUEST["registro"]." ";
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
				
			

			case "consultarTipoAtencion":
				$cadena_sql = "SELECT atencion_id , atencion_tipo_usuario FROM mantis_atencion_tipo";
				break;
				
			case "consultarUsuarioPublicador":
				$cadena_sql = " SELECT usuarios_mantis, usuarios_condor, usuarios_password ";
				$cadena_sql.=" FROM mantis_usuarios_login ";
				$cadena_sql.=" WHERE usuarios_tipo = 2 ";
				break;
				
			case "consultarUsuarioMantis":
				$cadena_sql = " SELECT usuarios_mantis, usuarios_condor , tipo_usuario ";
				$cadena_sql.=" FROM mantis_usuarios_login , mantis_usuarios_tipo ";
				$cadena_sql.=" WHERE usuarios_tipo = tipo_id ";
				$cadena_sql.=" AND usuarios_tipo = 1 ";
				$cadena_sql.=" AND usuarios_condor = '".$variable."' ";
				break;
				
			case "consultarWSDL":
				$cadena_sql = " SELECT parametro_nombre, parametro_descripcion FROM mantis_parametros WHERE parametro_nombre='wsdl' ";
				break;
				
			case "consultarURIBusqueda":
				$cadena_sql = " SELECT parametro_nombre, parametro_descripcion FROM mantis_parametros WHERE parametro_nombre='busqueda_mantis' ";
				break;
				
			case "consultarCasosComunes":
				$cadena_sql = 'SELECT casos_id , casos_descripcion FROM mantis_casos_comunes';
				break;
				
			case "resgistroLog":
				$cadena_sql = " INSERT INTO mantis_registro( registro_usuario, registro_numero ) VALUES ('".$variable["usuario"]."','".$variable["mantis"]."')";
				break;

		}
		

		return $cadena_sql;

	}
}
?>
