<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqladministrarDeudas extends sql {
	
	
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
				
			case "tablaElementosExiste":
					$cadena_sql="SELECT relname FROM pg_class WHERE relname = '".$variable."elementosNomina'";
					break;
					
			case "tablaFormulasExiste":
					$cadena_sql="SELECT relname FROM pg_class WHERE relname = '".$variable."formulasNomina'";
					break;

			case "obtenerDeudas":
					$cadena_sql='SELECT';
					$cadena_sql.=' "deudasID",';
					$cadena_sql.='"deudasNombre",';
					$cadena_sql.='"deudasEstado"::int';
					$cadena_sql.=' FROM "'.$prefijo.'deudas" ORDER by "deudasID" ASC';
					break;
			
			case "crearDeuda":
					$cadena_sql='INSERT INTO  ACDEUDORES (';
					$cadena_sql.=' "DEU_ID", ';
					$cadena_sql.=' "DEU_EST_COD",';
					$cadena_sql.=' "DEU_CPTO_COD",';
					$cadena_sql.=' "DEU_ANO",';
					$cadena_sql.=' "DEU_PER",';
					$cadena_sql.=' "DEU_ESTADO",';
					$cadena_sql.=' "DEU_MULTA",';
					$cadena_sql.=' "DEU_USUARIO",';
					$cadena_sql.=' "DEU_DEUDOR_NOMBRE",';
					$cadena_sql.=' "DEU_DEUDOR_ID",';
					$cadena_sql.=' "DEU_FECHA",';
					$cadena_sql.=' "DEU_MATERIAL" ';
					$cadena_sql.=' ) VALUES (';
					$cadena_sql.=' '.$variable['id'].', ';
					$cadena_sql.=" '".$variable['codDeudor']."',";
					$cadena_sql.=" ".$variable['Laboratorio']." ,";
					$cadena_sql.=" ".$variable['Ano']." ,";
					$cadena_sql.="'".$variable['Periodo']."',";
					$cadena_sql.=" 1 ,";
					$cadena_sql.=" ".$variable['Multa'].",";
					$cadena_sql.="'".$variable['usuario']."',";
					$cadena_sql.="'".$variable['nombreDeudor']."',";
					$cadena_sql.="'".$variable['identificacionDeudor']."',";
					$cadena_sql.="to_date(sysdate,'DD/MM/YYYY'),";
					$cadena_sql.="'".$variable['Material']."'";
					$cadena_sql.=')';
					break;

			case "consultarActivoDeuda":
					$cadena_sql='SELECT';
					$cadena_sql.='"deudasEstado"::int ';
					$cadena_sql.=' FROM "'.$prefijo.'deudas" ';
					$cadena_sql.="WHERE ";
					$cadena_sql.="\"deudasID\" = '".$variable."' ";
					break;
					
			case "actualizarEstadoDeuda":
					$cadena_sql='UPDATE "';
					$cadena_sql.=$prefijo.'deudas" ';
					$cadena_sql.=' SET "deudasEstado"=\''.$variable['Estado']."'";
					$cadena_sql.=" WHERE ";
					$cadena_sql.="\"deudasID\" = '".$variable['ID']."' ";
					break;

			case "consultarDeudas":
					$cadena_sql='SELECT '; 
					$cadena_sql.=' A."DEU_ID" AS "id",';
					$cadena_sql.=' A."DEU_CPTO_COD" AS "idLaboratorios", ';
					$cadena_sql.=' C."ID_ESTADO_DEUDA" AS "idEstados", ';
					$cadena_sql.=' B."CPTO_NOMBRE" AS "Nombre Laboratorio", ';
					$cadena_sql.=' A."DEU_MATERIAL" AS "Material", ';
					$cadena_sql.=' A."DEU_ANO" AS "Anno", ';
					$cadena_sql.=' A."DEU_PER" AS "Periodo", ';
					$cadena_sql.=' C."ESTADO_VALOR" AS "Estado", ';
					$cadena_sql.=' A."DEU_FECHA" AS "Fecha Creacion", ';
					$cadena_sql.=' A."DEU_MULTA" AS "Multa", ';
					$cadena_sql.=' A."DEU_USUARIO" AS "Usuario Multador", ';
					$cadena_sql.=' A."DEU_FECHA_PAGO" AS "Fecha Pago" ';
					$cadena_sql.=' FROM  ACDEUDORES  A ,  ACCONCEPTO  B ,  ESTADOS_DEUDA C '; 
					$cadena_sql.=' WHERE ';
					$cadena_sql.=' '.$variable['atributo'].' = \''.$variable['valor'].'\' ';
					$cadena_sql.=' AND A."DEU_CPTO_COD"= B."CPTO_COD" ';
					$cadena_sql.=' AND DECODE(A."DEU_ESTADO",\'A\',1,A."DEU_ESTADO")= C."ID_ESTADO_DEUDA" ';
					$cadena_sql.=' UNION ALL ';
					$cadena_sql.=' SELECT -1 AS "id",1 AS "idLaboratorios" , 1 AS "idEstados",\'1\' AS "Nombre Laboratorio",\'1\' AS "Material",1 AS "Anno", ';
					$cadena_sql.=' 1 AS "Periodo",\'1\' AS "Estado", ';
					$cadena_sql.=' TO_DATE(\'01/01/2999\',\'DD/MM/YYYY\') AS "Fecha Creacion",1 AS "Multa",\'1\' AS "Usuario Multador", ';
					$cadena_sql.=' TO_DATE(\'01/01/2999\',\'DD/MM/YYYY\') AS "Fecha Pago"  FROM DUAL ';
					$cadena_sql.=' ORDER BY 1 DESC';
					break;
			
			case "idMayorDeudores":
					$cadena_sql='SELECT ';
					$cadena_sql.=' MAX(A."DEU_ID") ';
					$cadena_sql.=' FROM  ACDEUDORES A ';
					break;
					
			case "consultarListadoLaboratorios":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A."CPTO_COD" AS "codigo", ';
					$cadena_sql.=' A."CPTO_NOMBRE" AS "Nombre" ';
					$cadena_sql.=' FROM  ACCONCEPTO A ';
					break;
			
			case "consultarEstadosDeudas":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A.ID_ESTADO_DEUDA , A.ESTADO_VALOR ';
					$cadena_sql.='  FROM  ESTADOS_DEUDA A';
					break;

			case "consultarEstudiantesCodigo":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A."EST_NOMBRE" AS "Nombre", ';
					$cadena_sql.=' A."EST_NRO_IDEN" AS "Identificacion", ';
					$cadena_sql.=' \'ESTUDIANTE\' AS "Tipo", ';
					$cadena_sql.=' A."EST_COD" AS "Codigo", ';
					$cadena_sql.=' A."EST_CRA_COD" AS "Proyecto Curricular", ';
					$cadena_sql.=' B."ESTADO_NOMBRE" AS "Estado" ';
					$cadena_sql.=' FROM  ACEST A,  ACESTADO B ';
					$cadena_sql.=' WHERE A."EST_ESTADO" = B."ESTADO_COD" ';
					$cadena_sql.=' AND A."EST_COD" = \''.$variable['valor'].'\' ';
					break;
					
			case "consultarEstudiantesIdentificacion":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A."EST_NOMBRE" AS "Nombre", ';
					$cadena_sql.=' A."EST_NRO_IDEN" AS "Identificacion", ';
					$cadena_sql.=' \'ESTUDIANTE\' AS "Tipo", ';
					$cadena_sql.=' A."EST_COD" AS "Codigo", ';
					$cadena_sql.=' A."EST_CRA_COD" AS "Proyecto Curricular", ';
					$cadena_sql.=' B."ESTADO_NOMBRE" AS "Estado" ';
					$cadena_sql.=' FROM  ACEST A,  ACESTADO B ';
					$cadena_sql.=' WHERE A."EST_ESTADO" = B."ESTADO_COD" ';
					$cadena_sql.=' AND A."EST_NRO_IDEN" = \''.$variable['valor'].'\' ';
					break;
					
			case "consultarDocentes":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A."DOC_NOMBRE"||\' \'||A."DOC_APELLIDO" AS "Nombre", ';
					$cadena_sql.=' A."DOC_NRO_IDEN" AS "Identificacion", ';
					$cadena_sql.=' \'DOCENTE\' AS "Tipo", ';
					$cadena_sql.=' A."DOC_ESTADO" AS "Estado" ';
					$cadena_sql.=' FROM  ACDOCENTE A ';
					$cadena_sql.=' WHERE ';
					$cadena_sql.=' A."DOC_NRO_IDEN" = \''.$variable['valor'].'\' ';
					break;
					
		  case "consultarAdministrativos":
					$cadena_sql='SELECT ';
					$cadena_sql.=' A."EMP_NOMBRE" AS "Nombre", ';
					$cadena_sql.=' A."EMP_NRO_IDEN" AS "Identificacion", ';
					$cadena_sql.=' \'ADMINISTRATIVO\' AS "Tipo", ';
					$cadena_sql.=' A."EMP_ESTADO" AS "Estado" ';
					$cadena_sql.=' FROM  PEEMP A ';
					$cadena_sql.=' WHERE ';
					$cadena_sql.=' A."EMP_NRO_IDEN" = \''.$variable['valor'].'\' ';
					break;

		  case "consultarNombres":
		  	$cadena_sql='SELECT  A."EST_NOMBRE" AS "Nombre",  A."EST_NRO_IDEN" AS "Identificacion", ';
		  	$cadena_sql.=' \'ESTUDIANTE\' AS "Tipo",A."EST_COD" AS "Codigo",B."ESTADO_NOMBRE" AS "Estado" ';
		  	$cadena_sql.=' FROM  ACEST A,  ACESTADO B ';
		  	$cadena_sql.=' WHERE A."EST_ESTADO" = B."ESTADO_COD" ';
		  	$cadena_sql.=' AND lower(A."EST_NOMBRE")  like \'%'.$variable.'%\' ';
		  	
		  	$cadena_sql.=' UNION ';
		  	
		  	$cadena_sql.=' SELECT  C."DOC_NOMBRE"||\' \'||C."DOC_APELLIDO" AS "Nombre",  C."DOC_NRO_IDEN" AS "Identificacion", ';
		  	$cadena_sql.='  \'DOCENTE\' AS "Tipo", 0 AS "Codigo", C."DOC_ESTADO" AS "Estado" ';
		  	$cadena_sql.=' FROM  ACDOCENTE C ';
		  	$cadena_sql.=' WHERE  lower(C."DOC_NOMBRE") like \'%'.$variable.'%\' or lower(C."DOC_APELLIDO") like \'%'.$variable.'%\' ';
		  	
		  	$cadena_sql.=' UNION ';
		  	
		  	$cadena_sql.=' SELECT  D."EMP_NOMBRE" AS "Nombre",  D."EMP_NRO_IDEN" AS "Identificacion", ';
		  	$cadena_sql.='  \'ADMINISTRATIVO\' AS "Tipo", 0 AS "Codigo" ,D."EMP_ESTADO" AS "Estado" ';
		  	$cadena_sql.=' FROM  PEEMP D ';
		  	$cadena_sql.=' WHERE  lower(D."EMP_NOMBRE") like \'%'.$variable.'%\' ';
		  			break;
		  	
		  case "consultarNombrePorIdentificacionEstudiante":
		  	$cadena_sql='SELECT A."EST_NOMBRE" AS "Nombre" FROM  ACEST A';
		  	$cadena_sql.=' WHERE A."EST_NRO_IDEN"=\''.$variable['identificacionDeudor'].'\'';
		  	break;
		  
		  case "consultarNombrePorIdentificacionDocente":
		  	$cadena_sql='SELECT C."DOC_NOMBRE"||\' \'||C."DOC_APELLIDO" AS "Nombre" FROM  ACDOCENTE C  ';
		  	$cadena_sql.=' WHERE C."DOC_NRO_IDEN"=\''.$variable['identificacionDeudor'].'\'';
		  	break;
		  		
		  case "consultarNombrePorIdentificacionAdministrativo":
		  	$cadena_sql='SELECT D."EMP_NOMBRE" AS "Nombre" FROM  PEEMP D  ';
		  	$cadena_sql.=' WHERE D."EMP_NRO_IDEN"=\''.$variable['identificacionDeudor'].'\'';
		  	break;
		  	
		  case "consultarCodigoEstudiantePorIdentificacion":
		  	$cadena_sql='SELECT A."EST_COD" AS "Codigo" FROM  ACEST A ';
		  	$cadena_sql.=' WHERE A."EST_NRO_IDEN"=\''.$variable['identificacionDeudor'].'\' ORDER BY 1 DESC';
		  	break;
		  	
		  case "consultaMultaID":
		  	$cadena_sql='SELECT';
		  	$cadena_sql.=' DEU_MULTA FROM  ACDEUDORES WHERE ' ;
		  	$cadena_sql.=' DEU_ID = '.$variable['idDeuda'];
		  	break;
		  	
		  case "consultaEstadoID":
		  	$cadena_sql='SELECT';
		  	$cadena_sql.=' DEU_ESTADO FROM  ACDEUDORES WHERE ' ;
		  	$cadena_sql.=' DEU_ID = '.$variable['idDeuda'];
		  	break;
		  	
		  case "actualizarMulta":
		  	$cadena_sql='UPDATE  ACDEUDORES ';
		  	$cadena_sql.=' SET ';
		  	$cadena_sql.=" DEU_MULTA = '".$variable['Multa']."' ";
		  	$cadena_sql.=' WHERE ';
		  	$cadena_sql.=' DEU_ID = '.$variable['idDeuda'];
		  	break;
		  	
		  case "actualizarEstado":
		  	$cadena_sql='UPDATE  ACDEUDORES ';
		  	$cadena_sql.=' SET ';
		  	$cadena_sql.=" DEU_ESTADO = '".$variable['Estado']."' ";
		  	$cadena_sql.=' WHERE ';
		  	$cadena_sql.=' DEU_ID = '.$variable['idDeuda'];
		  	break;
		  	
		  case "actualizarFechaPago":
		  	$cadena_sql='UPDATE  ACDEUDORES ';
		  	$cadena_sql.=' SET ';
		  	$cadena_sql.=" DEU_FECHA_PAGO = to_date(sysdate,'DD/MM/YYYY') ";
		  	$cadena_sql.=' WHERE ';
		  	$cadena_sql.=' DEU_ID = '.$variable['idDeuda'];
		  	break;
		  	
					
		 case "actualizarDescripccionDeuda":
					$cadena_sql='UPDATE "';
					$cadena_sql.=$prefijo.'deudas" ';
					$cadena_sql.=' SET "deudasDescripccion"=\''.$variable['Descripccion']."'";
					$cadena_sql.=" WHERE ";
					$cadena_sql.="\"deudasID\" = '".$variable['ID']."' ";
					break;
							
		case "idMayorTransacciones":
					$cadena_sql='SELECT ';
					$cadena_sql.=' MAX(ID_DEUDA_TRANSACCION) ';
					$cadena_sql.=' FROM  DEUDA_TRANSACCIONES A ';
					break;

					
		case "creaRegistro":
			$cadena_sql='INSERT INTO  DEUDA_TRANSACCIONES ';
			$cadena_sql.=" (ID_DEUDA_TRANSACCION, ID_USUARIO, DEU_ID, NOMBRE_CAMPO, VALOR_ANTERIOR, VALOR_NUEVO, TIPO_TRANSACCION, FECHA) ";
			$cadena_sql.=" VALUES( ";
			$cadena_sql.=" ".$variable['idDeudaTransaccion'].", ";
			$cadena_sql.=" '".$variable['idUsuario']."', ";
			$cadena_sql.=" ".$variable['deuId'].", ";
			$cadena_sql.=" '".$variable['nombreCampo']."', ";
			$cadena_sql.=" '".$variable['valorAnterior']."', ";
			$cadena_sql.=" '".$variable['valorNuevo']."', ";
			$cadena_sql.=" ".$variable['tipoTransaccion'].", ";
			$cadena_sql.=" sysdate ";
			$cadena_sql.=" )";
			break;
			
		case "consultaValorAnterior":
			$cadena_sql=' SELECT ';
			$cadena_sql.=' '.$variable['nombreCampo'].' ';
			$cadena_sql.=' FROM  ACDEUDORES ';
			$cadena_sql.=' WHERE ';
			$cadena_sql.=' DEU_ID = '.$variable['deuId'].' ';
			break;
			
		case "consultarProyectos";
			$cadena_sql=' SELECT CRA_COD, CRA_NOMBRE FROM ACCRA ';
			$cadena_sql.=" WHERE CRA_ESTADO = 'A' ";
			$cadena_sql.=" ORDER BY 2 ASC ";
		break;
		
		case "consultarFacultades";
			$cadena_sql=' SELECT DEP_COD, DEP_NOMBRE FROM GEDEP ';
			$cadena_sql.=" WHERE DEP_ESTADO = 'A' ";
			$cadena_sql.=" ORDER BY 2 ASC ";
		break;
		
		case "consultarDeudasTodas":
			$cadena_sql=' SELECT A."EST_NOMBRE" AS "Nombre", A."EST_NRO_IDEN" AS "Identificacion", A."EST_COD" AS "Codigo", C."CRA_NOMBRE" AS "Proyecto Curricular", ';
			$cadena_sql.=' D."DEP_NOMBRE" AS "Facultad",\'ESTUDIANTE\' AS "Tipo" ,  E."CPTO_NOMBRE" AS "Nombre Laboratorio" , F."DEU_MULTA" AS "Multa" ';
			$cadena_sql.=' FROM ACDEUDORES F , ACEST A, ACESTADO B,  ACCRA C , GEDEP D , ACCONCEPTO E ';
			$cadena_sql.=' WHERE ';
			$cadena_sql.=' A."EST_COD" = F."DEU_EST_COD" ';
			$cadena_sql.=' AND A."EST_ESTADO" = B."ESTADO_COD" ';
			$cadena_sql.=' AND A."EST_CRA_COD"= C."CRA_COD" ';
			$cadena_sql.=' AND C."CRA_DEP_COD" = D."DEP_COD" ';
			$cadena_sql.=' AND F."DEU_CPTO_COD"= E."CPTO_COD" ';
			$cadena_sql.=' UNION ALL ';
			$cadena_sql.=' SELECT A."DOC_NOMBRE"||\' \'||A."DOC_APELLIDO" AS "Nombre", A."DOC_NRO_IDEN" AS "Identificacion", 0 AS  "Codigo", \'\' AS  "Proyecto Curricular", \'\' AS "Facultad", ';
			$cadena_sql.=' \'DOCENTE\' AS "Tipo" , E."CPTO_NOMBRE" AS "Nombre Laboratorio" , F."DEU_MULTA" AS "Multa" ';
			$cadena_sql.=' FROM ACDEUDORES F , ACDOCENTE A , ACCONCEPTO E ';
			$cadena_sql.=' WHERE A."DOC_NRO_IDEN" = F."DEU_DEUDOR_ID" ';
			$cadena_sql.=' AND F."DEU_CPTO_COD"= E."CPTO_COD" ';
			$cadena_sql.=' UNION ALL ';
			$cadena_sql.=' SELECT A."EMP_NOMBRE" AS "Nombre", A."EMP_NRO_IDEN" AS "Identificacion", 0 AS  "Codigo",  \'\' AS  "Proyecto Curricular", \'\' AS "Facultad", ';
			$cadena_sql.=' \'ADMINISTRATIVO\' AS "Tipo" , E."CPTO_NOMBRE" AS "Nombre Laboratorio" , F."DEU_MULTA" AS "Multa" ';
			$cadena_sql.=' FROM ACDEUDORES F , PEEMP A , ACCONCEPTO E ';
			$cadena_sql.=' WHERE A."EMP_NRO_IDEN" = F."DEU_DEUDOR_ID" ';
			$cadena_sql.=' AND F."DEU_CPTO_COD"= E."CPTO_COD" ';
		
			break;
			
		case "consultaDeudasProyecto":
			$cadena_sql=' SELECT A."EST_NOMBRE" AS "Nombre", A."EST_NRO_IDEN" AS "Identificacion", A."EST_COD" AS "Codigo", C."CRA_NOMBRE" AS "Proyecto Curricular", ';
			$cadena_sql.=' D."DEP_NOMBRE" AS "Facultad",\'ESTUDIANTE\' AS "Tipo" ,  E."CPTO_NOMBRE" AS "Nombre Laboratorio" , F."DEU_MULTA" AS "Multa" ';
			$cadena_sql.=' FROM ACDEUDORES F , ACEST A, ACESTADO B,  ACCRA C , GEDEP D , ACCONCEPTO E ';
			$cadena_sql.=' WHERE ';
			$cadena_sql.=' A."EST_COD" = F."DEU_EST_COD" ';
			$cadena_sql.=' AND A."EST_ESTADO" = B."ESTADO_COD" ';
			$cadena_sql.=' AND A."EST_CRA_COD"= C."CRA_COD" ';
			$cadena_sql.=' AND C."CRA_DEP_COD" = D."DEP_COD" ';
			$cadena_sql.=' AND F."DEU_CPTO_COD"= E."CPTO_COD" ';
			$cadena_sql.=' AND  C."CRA_COD" ='.$variable ;
			break;
			
		case "consultaDeudasFacultad":
			$cadena_sql=' SELECT A."EST_NOMBRE" AS "Nombre", A."EST_NRO_IDEN" AS "Identificacion", A."EST_COD" AS "Codigo", C."CRA_NOMBRE" AS "Proyecto Curricular", ';
			$cadena_sql.=' D."DEP_NOMBRE" AS "Facultad",\'ESTUDIANTE\' AS "Tipo" ,  E."CPTO_NOMBRE" AS "Nombre Laboratorio" , F."DEU_MULTA" AS "Multa" ';
			$cadena_sql.=' FROM ACDEUDORES F , ACEST A, ACESTADO B,  ACCRA C , GEDEP D , ACCONCEPTO E ';
			$cadena_sql.=' WHERE ';
			$cadena_sql.=' A."EST_COD" = F."DEU_EST_COD" ';
			$cadena_sql.=' AND A."EST_ESTADO" = B."ESTADO_COD" ';
			$cadena_sql.=' AND A."EST_CRA_COD"= C."CRA_COD" ';
			$cadena_sql.=' AND C."CRA_DEP_COD" = D."DEP_COD" ';
			$cadena_sql.=' AND F."DEU_CPTO_COD"= E."CPTO_COD" ';
			$cadena_sql.=' AND  C."CRA_DEP_COD" ='.$variable ;
			break;

		}
		

		return $cadena_sql;

	}
}
?>
