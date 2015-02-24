<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlinicioRegistroEvaluacion extends sql {
	
	
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
                            
                        case "consultarAnioPeriodo": 
				$cadena_sql="SELECT ";
                                $cadena_sql.="acasperiev_id, ";
				$cadena_sql.="acasperiev_anio||'-'||acasperiev_periodo, ";
                                $cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo, ";
                                $cadena_sql.="acasperiev_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="acasperiev_estado IN ('A') ";
                                $cadena_sql.="ORDER BY acasperiev_id ASC ";
                                //$cadena_sql.="acasperiev_estado NOT IN ('A') ";
                                break;    

                        case "consultarInstructivo":
				$cadena_sql="SELECT ";
                                $cadena_sql.="instructivo_id, ";
                                $cadena_sql.="tipo_id, ";
                                $cadena_sql.="instructivo_texto, ";
                                $cadena_sql.="instructivo_estado ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="autoevaluadoc.evaldocente_insturctivo ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="instructivo_estado='A' ";
                                break;
                            
			case "consultaCarreras":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="distinct (cra_cod), cra_nombre, cra_emp_nro_iden ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="mntac.acdocente ";
                                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";//AND cra_emp_nro_iden=doc_nro_iden";
                                $cadena_sql.=" WHERE ";
                                $cadena_sql.=" cra_emp_nro_iden =".$variable['usuario'] ." ";
                                //$cadena_sql.=" AND ape_estado='A'";
                                $cadena_sql.=" AND ape_ano=".$variable['anio'] ." ";
                                $cadena_sql.=" AND ape_per=".$variable['per'] ." ";
                                $cadena_sql.=" AND car_estado='A'";
                                $cadena_sql.=" AND hor_estado='A'";
                                $cadena_sql.=" AND cur_estado='A'";
                                $cadena_sql.=" AND doc_estado='A'";
                                break;
                            
                        case "consultarCarga":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="DISTINCT(cra_cod),doc_nro_iden, ";
                                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                                $cadena_sql.="cra_nombre,tvi_cod,tvi_nombre,asi_ind_catedra ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="mntac.acdocente ";
                                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                                $cadena_sql.="WHERE ";
                                //$cadena_sql.="ape_estado='A' ";
                                $cadena_sql.=" ape_ano=".$variable['anio'] ." ";
                                $cadena_sql.=" AND ape_per=".$variable['per'] ." ";
                                $cadena_sql.="AND doc_nro_iden = ".$variable['usuario']." ";
                                $cadena_sql.="AND doc_estado = 'A' ";
                                $cadena_sql.="AND car_estado = 'A' ";
                                $cadena_sql.="AND cur_estado = 'A' ";
                                $cadena_sql.="AND hor_estado='A' ";
                                $cadena_sql.="ORDER BY cra_cod ";
                                break;
                            
                        case "consultaAsignaturas":
                                $cadena_sql=" SELECT distinct ape_ano, ";
                                $cadena_sql.=" ape_per, ";
                                $cadena_sql.=" est_cod, ";
                                $cadena_sql.=" asi_cod, ";
                                $cadena_sql.=" asi_nombre, ";
                                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO, ";
                                $cadena_sql.=" doc_nro_iden, ";
                                $cadena_sql.=" (LTRIM(RTRIM(doc_apellido))||' '||LTRIM(RTRIM(doc_nombre))) doc_nombre,";
                                $cadena_sql.=" cra_cod,";
                                $cadena_sql.=" asi_ind_catedra,";
                                $cadena_sql.=" cur_id";
                                $cadena_sql.=" FROM mntac.acest";
                                $cadena_sql.=" inner join mntac.accra on est_cra_cod = cra_cod";
                                $cadena_sql.=" inner join mntac.acins ON est_cod = ins_est_cod";
                                $cadena_sql.=" inner join mntac.acasperi ON ape_ano = ins_ano AND ape_per = ins_per";
                                $cadena_sql.=" inner join mntac.acasi ON asi_cod = ins_asi_cod";
                                $cadena_sql.=" inner join mntac.accursos on cur_id=ins_gr and cur_ape_ano=ins_ano and cur_ape_per=ins_per";
                                $cadena_sql.=" inner join mntac.achorarios on hor_id_curso=cur_id";
                                $cadena_sql.=" inner join mntac.accargas ON car_hor_id=hor_id";
                                $cadena_sql.=" inner join mntac.acdocente ON car_doc_nro = doc_nro_iden ";
                                $cadena_sql.=" WHERE est_cod = ".$variable['usuario']." ";
                                //$cadena_sql.=" AND ape_estado = 'A'";
                                $cadena_sql.=" AND ape_ano=".$variable['anio'] ." ";
                                $cadena_sql.=" AND ape_per=".$variable['per'] ." ";
                                $cadena_sql.=" AND doc_estado = 'A' ";
                                $cadena_sql.=" AND car_estado = 'A' ";
                                $cadena_sql.=" AND cur_estado = 'A'";
                                $cadena_sql.=" AND hor_estado = 'A'";
                                break;
                            
                        case "consultaCoordinadores":
                                $cadena_sql="SELECT dep_nombre,cra_cod, cra_nombre, cra_emp_nro_iden,cra_estado,cra_dep_cod,(doc_nombre||' '||doc_apellido) AS doc";
                                $cadena_sql.=" FROM mntac.accra, mntac.acdocente, mntge.gedep, mntpe.peemp";
                                $cadena_sql.=" WHERE cra_estado = 'A'";
                                $cadena_sql.=" AND doc_nro_iden = cra_emp_nro_iden";
                                $cadena_sql.=" AND cra_dep_cod = (SELECT MAX(dep_cod)";
                                $cadena_sql.="                   FROM mntpe.peemp, mntge.gedep";
                                $cadena_sql.="                   WHERE emp_nro_iden = ".$variable['usuario']." ";
                                $cadena_sql.="                   AND dep_cod IN(23,24,32,33,101) ";
                                $cadena_sql.="                   AND emp_cod = dep_emp_cod)";
                                $cadena_sql.=" AND emp_nro_iden = ".$variable['usuario']." ";
                                $cadena_sql.=" AND emp_cod = dep_emp_cod"; 
                                $cadena_sql.=" ORDER BY cra_cod";
                                break;
                           
                        case "consultarEventos": 
			 	$cadena_sql="SELECT COUNT(ace_cod_evento) "; // --Si calendario vigente..
                                $cadena_sql.="FROM accaleventos ";
                                $cadena_sql.="WHERE ace_cra_cod=".$variable[0]." ";
                                $cadena_sql.="AND ace_cod_evento=".$variable[1]." "; 
                                $cadena_sql.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd')) "; 
                                $cadena_sql.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd')) ";	
                                $cadena_sql.="AND ace_estado = 'A'";
                                break;
				 
                }

		return $cadena_sql;

	}
}
?>
