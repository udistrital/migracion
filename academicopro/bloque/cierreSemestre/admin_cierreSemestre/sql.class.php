<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminCierreSemestre extends sql
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
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN='".$variable."'";
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
                        
                        case "periodo":
                            //El periodo se establece a P para los cierres de los posgrados posterior a la fecha del pregrado 26/12/2013
                            //El período se cambia de P a A para el proceso de cierre de pregrado

//                                $cadena_sql=" SELECT DISTINCT ape_ano ANIO, ";
//                                $cadena_sql.=" ape_per PERIODO, ";
//                                $cadena_sql.=" ape_estado ESTADO ";
//                                $cadena_sql.="  FROM acasperi ";
//                                $cadena_sql.=" WHERE ape_estado IN ('P')";
//                                $cadena_sql.=" ORDER BY ape_estado  ASC";
                                $cadena_sql=" SELECT DISTINCT pec_ano ANIO, ";
                                $cadena_sql.=" pec_periodo PERIODO, ";
                                $cadena_sql.=" pec_estado ESTADO ";
                                $cadena_sql.="  FROM acperiodocierre ";
                                $cadena_sql.=" WHERE pec_estado IN ('A')";
                                $cadena_sql.=" AND pec_cra_cod= ".$variable;
                                $cadena_sql.=" ORDER BY pec_estado  ASC";
                        break;

     
                            case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(SYSDATE, 'YYYYmmddhh24mmss') FECHA  ";
				$cadena_sql.="FROM ";
				$cadena_sql.="dual";
				break;
                            
                            case "valida_fecha":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="Ace_Anio, ";//TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')), ";
                                $cadena_sql.="Ace_Cod_Evento, ";
                                $cadena_sql.="Ace_Cra_Cod, ";
                                $cadena_sql.="Ace_Dep_Cod, ";
                                $cadena_sql.="Ace_Estado, ";
                                $cadena_sql.="to_char(Ace_Fec_Fin,'YYYYMMDDHH24MISS') ACE_FEC_FIN, ";
                                $cadena_sql.="to_char(Ace_Fec_Ini,'YYYYMMDDHH24MISS') ACE_FEC_INI, ";
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
                                //$cadena_sql.=" AND ";
                                //$cadena_sql.="'".$variable['fecha']."' BETWEEN TO_CHAR(ACE_FEC_INI, 'YYYYmmddhh24mmss') AND TO_CHAR(ACE_FEC_FIN, 'YYYYmmddhh24mmss') ";
                            break;
                        
                            case 'insertarDatosEventos':
                                $cadena_sql="INSERT INTO ACCALEVENTOS ";
                                $cadena_sql.="(Ace_Cod_Evento, Ace_Cra_Cod,Ace_Tip_Cra, Ace_Dep_Cod, Ace_Anio, Ace_Periodo, Ace_Estado) ";
                                $cadena_sql.="VALUES ('".$variable['evento']."',";
                                $cadena_sql.="'".$variable['carrera']."',";
                                $cadena_sql.="(select cra_tip_cra from accra where cra_cod='".$variable['carrera']."'),";
                                $cadena_sql.="(select cra_dep_cod from accra where cra_cod='".$variable['carrera']."'),";
                                $cadena_sql.="'".$variable['periodo'][0]['ANIO']."',";
                                $cadena_sql.="'".$variable['periodo'][0]['PERIODO']."',";
                                $cadena_sql.="'A')";
                                break;
                            
                            case 'consultarDatosEstudiantesCierre':
                                $cadena_sql=" select A.est_cod CODIGO, coalesce(A.est_estado_est,'-') ESTADO, coalesce(A.est_acuerdo,0) ACUERDO";
                                $cadena_sql.=" from acest A";
                                $cadena_sql.=" inner join reglamento on A.est_cod=reg_est_cod and reg_cra_cod=A.est_cra_cod";
                                $cadena_sql.=" inner join acesthis H on A.est_cod=H.est_cod and H.est_ano=reg_ano and H.est_per=reg_per";
                                $cadena_sql.=" where A.est_cra_cod=".$variable['codProyecto'];
                                $cadena_sql.=" and reg_ano=".$variable['anio'];
                                $cadena_sql.=" and reg_per=".$variable['periodo'];
                                $cadena_sql.=" and H.est_estado in ('A','B','H','O')";
                                //la siguiente linea se comenta para que pueda presentar el detalle de todos los estudiantes a los que se les hizo cierre durante el siguiente período
                                //Descomentar para el proceso de cierre
                                //$cadena_sql.=" and A.est_estado_est in ('U','Z','J','T','V','D','K','Q')";
                                $cadena_sql.=" order by A.est_estado_est,A.est_acuerdo";
                                break;

                            case 'consultarEstudiantesMejoresPromedios':
                                $cadena_sql=" select A.est_cod CODIGO,A.est_nombre NOMBRE, A.est_estado_est ESTADO,A.est_acuerdo ACUERDO,reg_promedio PROMEDIO";
                                $cadena_sql.=" from acest A";
                                $cadena_sql.=" inner join reglamento on A.est_cod=reg_est_cod and reg_cra_cod=A.est_cra_cod";
                                $cadena_sql.=" where A.est_cra_cod=".$variable['codProyecto'];
                                $cadena_sql.=" and reg_ano=".$variable['anio'];
                                $cadena_sql.=" and reg_per=".$variable['periodo'];
                                $cadena_sql.=" and reg_promedio is not null";
                                $cadena_sql.=" and est_estado_est in ('V')";
                                $cadena_sql.=" and reg_reglamento in ('N')";
                                $cadena_sql.=" order by reg_promedio desc,est_estado_est,est_acuerdo";
                                break;

                            
                            case 'consultarDatosReglamento':
                               $cadena_sql=" SELECT A.est_cod CODIGO,";
                               $cadena_sql.=" A.est_nombre NOMBRE_ESTUDIANTE,";
                               $cadena_sql.=" coalesce(H.est_estado,'-') ESTADO_ANTERIOR,";
                               $cadena_sql.=" coalesce(A.est_estado_est,'-') ESTADO_ACTUAL,";
                               $cadena_sql.=" coalesce(A.est_acuerdo,0) ACUERDO,";
                               $cadena_sql.=" coalesce(reg_motivo,0) MOTIVO_PRUEBA,";
                               $cadena_sql.=" coalesce(reg_asi_3,0) ESP_ACAD_REPRO,";
                               $cadena_sql.=" coalesce(reg_veces,0) MAX_VECES_REPROBADO,";
                               $cadena_sql.=" coalesce(reg_promedio,0) PROMEDIO,";
                               $cadena_sql.=" coalesce(reg_causal_exclusion,0) CAUSAL_EXCLUSION,";
                               $cadena_sql.=" coalesce(reg_porcentaje_plan,0) PORCENTAJE_PLAN,";
                               $cadena_sql.=" coalesce(reg_espacio_veces,0) ESPACIOS_REPROBADOS";
                               $cadena_sql.=" FROM acest A";
                               $cadena_sql.=" INNER JOIN reglamento ON A.est_cod=reg_est_cod AND reg_cra_cod=A.est_cra_cod";
                               $cadena_sql.=" INNER JOIN acesthis H ON A.est_cod=H.est_cod AND H.est_ano=reg_ano AND H.est_per=reg_per";
                               $cadena_sql.=" INNER JOIN acestado EA ON A.est_estado_est=EA.estado_cod";
                               $cadena_sql.=" INNER JOIN acestado EH ON H.est_estado=EH.estado_cod";
                               $cadena_sql.=" WHERE A.est_cra_cod=".$variable['codProyecto'];
                               $cadena_sql.=" AND reg_ano=".$variable['anio'];
                               $cadena_sql.=" AND reg_per=".$variable['periodo'];
                               $cadena_sql.=" ORDER BY reg_reglamento,A.est_cod";
                               break;
			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
