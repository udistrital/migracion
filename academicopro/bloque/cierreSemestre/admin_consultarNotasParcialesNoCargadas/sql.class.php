<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          sql.class.php 
* @author        Milton Parra
* @revision      Última revisión 08 de septiembre de 2014
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		cierreSemestre
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Milton Parra
* @author		Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar cargar notas parciales que no fueron procesadas al momento del cierre
*
/*--------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarNotasParcialesNoCargadas extends sql
{
  public $configuracion;


  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }
    
    
	function cadena_sql($opcion="",$variable="")
	{            
        	switch($opcion)
		{	
			case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
				$cadena_sql.=" AND tra_nivel!='PREGRADO'";
				break;
                            
			case "proyecto_curricular":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod CODIGO, ";
				$cadena_sql.="cra_abrev NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
                                $cadena_sql.="CRA_COD='".$variable."'";
				break;                            
                        
                        case "periodos":
                                $cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
                                $cadena_sql.=" ape_per PERIODO, ";
                                $cadena_sql.=" ape_estado ESTADO ";
                                $cadena_sql.=" FROM acasperi";
                                $cadena_sql.=" WHERE ape_estado IN ('A','P','I')";
                                $cadena_sql.=" ORDER BY ape_ano desc,ape_per desc";
                                $cadena_sql.=" LIMIT 4";
                        break;

                        case "consultarNotasNoCargadas":
                                $cadena_sql=" SELECT";
                                //$cadena_sql.=" ins_cra_cod PROYECTO,";
                                $cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
                                $cadena_sql.=" est_nombre ESTUDIANTE,";
                                $cadena_sql.=" est_estado_est ESTADO,";
                                $cadena_sql.=" ins_asi_cod COD_ESPACIO,";
                                $cadena_sql.=" asi_nombre ESPACIO,";
                                //$cadena_sql.=" ins_ano ANO,";
                                //$cadena_sql.=" ins_per PERIODO,";
                                $cadena_sql.=" ins_nota NOTA,";
                                $cadena_sql.=" (SELECT CUR_GRUPO FROM ACCURSOS WHERE CUR_ASI_COD=INS_ASI_COD AND CUR_APE_ANO=INS_ANO AND CUR_APE_PER=INS_PER AND CUR_ID=INS_GR) GRUPO";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN acest ON ins_est_cod=est_cod";
                                $cadena_sql.=" INNER JOIN acasi ON ins_asi_cod=asi_cod";
                                $cadena_sql.=" WHERE INS_CRA_COD = ".$variable['codProyecto'];
                                $cadena_sql.=" AND INS_ANO=".$variable['anio'];
                                $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                                $cadena_sql.=" and INS_NOTA is not null";
                                $cadena_sql.=" and ins_est_cod::text||ins_cra_cod::text||ins_asi_cod::text||ins_ano::text||ins_per::text";
                                $cadena_sql.=" not in (";
                                $cadena_sql.=" select not_est_cod::text||not_cra_cod::text||not_asi_cod::text||not_ano::text||not_per::text from acnot";
                                $cadena_sql.=" where not_ano=ins_ano";
                                $cadena_sql.=" and not_per=ins_per";
                                $cadena_sql.=" and not_cra_cod=ins_cra_cod)";
                                //$cadena_sql.=" and rownum<=100";
                                $cadena_sql.=" order by ins_est_cod,ins_asi_cod";
                            break;
                        
                            case "cargarNotasEstudiante":
                                $cadena_sql=" INSERT INTO ACNOT";
                                $cadena_sql.=" SELECT";
                                $cadena_sql.=" INS_CRA_COD,";
                                $cadena_sql.=" INS_EST_COD,";
                                $cadena_sql.=" INS_ASI_COD,";
                                $cadena_sql.=" INS_ANO,";
                                $cadena_sql.=" INS_PER,";
                                $cadena_sql.=" INS_SEM,";
                                $cadena_sql.=" INS_NOTA,";
                                $cadena_sql.=" (SELECT CUR_GRUPO FROM ACCURSOS WHERE CUR_ASI_COD=INS_ASI_COD AND CUR_APE_ANO=INS_ANO AND CUR_APE_PER=INS_PER AND CUR_ID=INS_GR),";
                                $cadena_sql.=" INS_OBS,";
                                $cadena_sql.=" '',";
                                $cadena_sql.=" current_timestamp,";
                                $cadena_sql.=" 'A',";
                                $cadena_sql.=" INS_CRED,";
                                $cadena_sql.=" INS_NRO_HT,";
                                $cadena_sql.=" INS_NRO_HP,";
                                $cadena_sql.=" INS_NRO_AUT,";
                                $cadena_sql.=" INS_CEA_COD,";
                                $cadena_sql.=" INS_ASI_COD,";
                                $cadena_sql.=" null,";
                                $cadena_sql.=" null";
                                $cadena_sql.=" FROM ACINS";
                                $cadena_sql.=" WHERE INS_CRA_COD =".$variable['codProyecto'];
                                $cadena_sql.=" AND INS_ANO=".$variable['anio'];
                                $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                                $cadena_sql.=" and INS_est_cod=".$variable['codEstudiante'];
                                $cadena_sql.=" and ins_asi_cod in (".$variable['espacios'].")";
                                break;
                        
                            case "valida_fecha":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="Ace_Anio, ";
                                $cadena_sql.="Ace_Cod_Evento, ";
                                $cadena_sql.="Ace_Cra_Cod, ";
                                $cadena_sql.="Ace_Dep_Cod, ";
                                $cadena_sql.="Ace_Estado, ";
                                $cadena_sql.="to_char(Ace_Fec_Fin,'YYYYMMDD') ACE_FEC_FIN, ";
                                $cadena_sql.="to_char(Ace_Fec_Ini,'YYYYMMDD') ACE_FEC_INI, ";
                                $cadena_sql.="Ace_Periodo, ";
                                $cadena_sql.="Ace_Tip_Cra ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="accaleventos ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ACE_ANIO =".$variable['anio'];
                                $cadena_sql.=" AND ";
                                $cadena_sql.="ACE_PERIODO =".$variable['periodo'];
                                $cadena_sql.=" AND ";
                                $cadena_sql.="ACE_CRA_COD =".$variable['codProyecto'];
                                $cadena_sql.=" AND ";
                                $cadena_sql.="ACE_COD_EVENTO =".$variable['evento'];
                            break;
                        

			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
