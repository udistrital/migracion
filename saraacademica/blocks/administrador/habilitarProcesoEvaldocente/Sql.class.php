<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlhabilitarProcesoEvaldocente extends sql {
	
	
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
                        case "consultarAnioPeriodoPG": //PG: PSTGRESQL
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
                            
			case "consultarAnioPeriodo":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano||'#'||ape_per, ";
                                $cadena_sql.="ape_ano||' - '||ape_per ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ape_estado IN ('A','X')";    
				break;
                            
                        case "buscarPeriodo": 
				$cadena_sql="SELECT ";
				$cadena_sql.="acasperiev_anio, ";
                                $cadena_sql.="acasperiev_periodo, ";
                                $cadena_sql.="acasperiev_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="acasperiev_estado IN ('A','I') ";
                                break;
                            
                        case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
				$cadena_sql.="( ";
				//$cadena_sql.="instructivo_id, ";
				$cadena_sql.="acasperiev_anio, ";
				$cadena_sql.="acasperiev_periodo, ";
				$cadena_sql.="acasperiev_estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				//$cadena_sql.="'' , ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="'".$variable['per']."', ";
				$cadena_sql.="'A' ";
				$cadena_sql.=")";
				break;
                        
                          case "actualizaEstados":
				$cadena_sql="UPDATE ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
				$cadena_sql.="SET ";
				$cadena_sql.="acasperiev_estado = 'I' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="acasperiev_estado ='A' ";
				break;
                            
                          case "actualizaEstado":
				$cadena_sql="UPDATE ";
				$cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
				$cadena_sql.="SET ";
				$cadena_sql.="acasperiev_estado = '".$variable['estadoFinal']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="acasperiev_estado ='".$variable['estadoInicial']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_anio =".$variable['anio']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="acasperiev_periodo =".$variable['per']." ";
				break;
			
                         case "consultarEventos":
				$cadena_sql="SELECT ";
                                $cadena_sql.="acd_cod_evento, ";
                                $cadena_sql.="acd_cod_evento||' '||acd_descripcion ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdeseventos ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="acd_cod_evento in (11,53,54,36,37) ";
                                break;
                            
        		case "consultarEventosCarrera":
				$cadena_sql="SELECT ";
                                $cadena_sql.="ace_cod_evento, ";
                                $cadena_sql.="acd_descripcion, ";
				$cadena_sql.="ace_cra_cod, ";
                                $cadena_sql.="cra_nombre, ";
                                $cadena_sql.="TO_CHAR(ACE_FEC_INI, 'dd/mm/yyyy'), ";
                                $cadena_sql.="TO_CHAR(ACE_FEC_FIN, 'dd/mm/yyyy'), ";
                                $cadena_sql.="ace_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="mntac.accaleventos ";
                                $cadena_sql.="INNER JOIN mntac.acdeseventos ON ace_cod_evento=acd_cod_evento ";
                                $cadena_sql.="INNER JOIN mntac.accra ON cra_cod=ace_cra_cod ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ace_estado='A' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_anio= ".$variable['anio']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_periodo= ".$variable['periodo']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_cod_evento IN (11,53,54,36,37) ";
                               	break;
                         
                          case "insertaEventos":
                                $cadena_sql="INSERT into "; 
                                $cadena_sql.="accaleventos ";
                                $cadena_sql.="SELECT ace_cod_evento, ";
                                $cadena_sql.="ACE_CRA_COD, ";
                                $cadena_sql.="to_date('".$variable['fechaIni']."', 'mm/dd/yyyy'), ";
                                $cadena_sql.="to_date('".$variable['fechaFin']."', 'mm/dd/yyyy'), ";
                                $cadena_sql.="ace_tip_cra, ";
                                $cadena_sql.="ace_dep_cod, ";
                                $cadena_sql.="".$variable['anio'].", ";
                                $cadena_sql.="".$variable['periodo'].", ";
                                $cadena_sql.="ace_estado, ";
                                $cadena_sql.="ace_habilitar_ex ";
                                $cadena_sql.="FROM  accaleventos "; 
                                $cadena_sql.="WHERE "; 
                                $cadena_sql.="ace_cod_evento = ".$variable['evento']." "; 
                                $cadena_sql.="AND ace_anio IN (select ape_ano from acasperi where ape_estado='P') "; 
                                $cadena_sql.="AND ace_periodo IN (select ape_per from acasperi where ape_estado='P')";
                                break;
                        
                        case "actualizaEventos":
                                $cadena_sql="UPDATE accaleventos ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="ace_fec_ini=(TO_DATE('".$variable['fechaIni']."', 'mm/dd/yyyy')), ";
                                $cadena_sql.="ace_fec_fin=(TO_DATE('".$variable['fechaFin']."', 'mm/dd/yyyy')) ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ace_anio='".$variable['anio']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_periodo='".$variable['periodo']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_cod_evento IN (".$variable['evento'].") ";
                                break;
                        case "actualizaEventosCarrera":
                                $cadena_sql="UPDATE accaleventos ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="ace_fec_ini=(TO_DATE('".$variable['fechaIni']."', 'mm/dd/yyyy')), ";
                                $cadena_sql.="ace_fec_fin=(TO_DATE('".$variable['fechaFin']."', 'mm/dd/yyyy')) ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ace_anio='".$variable['anio']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_periodo='".$variable['periodo']."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_cod_evento IN (".$variable['evento'].") ";
                                 $cadena_sql.="AND ";
                                $cadena_sql.="ace_cra_cod = ".$variable['carrera']." ";
                                break;
                            
                        case "consultarEventosAnteriores":
				$cadena_sql="SELECT ";
                                $cadena_sql.="ace_cod_evento, ";
                                $cadena_sql.="acd_descripcion, ";
				$cadena_sql.="ace_cra_cod, ";
                                $cadena_sql.="cra_nombre, ";
                                $cadena_sql.="ace_fec_ini, ";
                                $cadena_sql.="ace_fec_fin, ";
                                $cadena_sql.="ace_estado ";
                               	$cadena_sql.="FROM ";
				$cadena_sql.="mntac.accaleventos ";
                                $cadena_sql.="INNER JOIN mntac.acdeseventos ON ace_cod_evento=acd_cod_evento ";
                                $cadena_sql.="INNER JOIN mntac.accra ON cra_cod=ace_cra_cod ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ace_estado='A' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_anio= ".$variable['anio']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_periodo= ".$variable['periodo']." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="ace_cod_evento= ".$variable['evento']." ";
                                break;    
                        
                }
                //echo $cadena_sql."<br>";
		return $cadena_sql;

	}
}
?>
