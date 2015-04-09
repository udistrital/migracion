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

class sql_panelPrincipal extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "periodoActual":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='A'";	
				break;
				
			case "acasperieventos":
				$cadena_sql="SELECT ";
				$cadena_sql.="acn_cra_cod, ";
				$cadena_sql.="acn_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperieventos";
				break;
				
			case "consultarCarreras":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_nombre ";
				$cadena_sql.="FROM accra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_emp_nro_iden=".$variable." ";

				break;
							
			 
			case "consultarObservaciones":
				$cadena_sql="SELECT ";
				$cadena_sql.="nob_cod,nob_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acnotobs ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="nob_estado='A' ";
				break;

			case "consultarClasificacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="cea_cod,cea_nom,cea_abr ";
				$cadena_sql.="FROM ";
				$cadena_sql.="geclasificaespac ";
				break;
				
			case "verificarCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi,accra,accaleventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra_cod=ace_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_ano=ace_anio ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_per=ace_periodo ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 89 ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_estado = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_emp_nro_iden=".$variable[0]." ";
				if($variable[1]<>""){
					$cadena_sql.="AND ";
					$cadena_sql.="cra_cod=".$variable[1]." ";
				}
				$cadena_sql.="AND ";
				//$cadena_sql.="TO_NUMBER(TO_CHAR(SYSDATE,'YYYYMMDD')) not between TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD')) and TO_NUMBER(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD')) ";
                                $cadena_sql.="current_date between ACE_FEC_INI and ACE_FEC_FIN ";
				break;
				
			case "consultarRegistros":
				$cadena_sql="SELECT ";
				$cadena_sql.="NUMERO, "; //aqui deberia ir el rownum
				$cadena_sql.="CODIGO ";
				$cadena_sql.="FROM(";
				$cadena_sql.="	SELECT row_NUMBER() OVER (ORDER BY codigo) AS NUMERO, ";
				$cadena_sql.="		CODIGO ";
				$cadena_sql.="	FROM( ";
				$cadena_sql.="		SELECT DISTINCT";//registros del proyecto de las notas del estudiante
				$cadena_sql.="			NOT_EST_COD CODIGO";
				$cadena_sql.="		FROM accra,acnot,acest ";
				$cadena_sql.="		WHERE cra_cod=not_cra_cod ";
				$cadena_sql.="		AND not_est_cod=est_cod ";
				$cadena_sql.="		AND cra_emp_nro_iden=".$variable['usuario']." ";
				$cadena_sql.=isset($variable['filtroEstudiante'])?" AND not_est_cod::text like '%".$variable['filtroEstudiante']."%'":"";
				$cadena_sql.=isset($variable['filtroNombre'])?" AND est_nombre like UPPER('%".$variable['filtroNombre']."%')":"";
				$cadena_sql.=isset($variable['filtroPlan'])?" AND est_pen_nro like '%".$variable['filtroPlan']."%'":"";
				$cadena_sql.=isset($variable['filtroIdentificacion'])?" AND est_nro_iden like '%".$variable['filtroIdentificacion']."%'":"";
				$cadena_sql.=isset($variable['filtroCarrera'])?" AND cra_nombre like '%".$variable['filtroCarrera']."%'":"";
				$cadena_sql.=isset($variable['filtroEstado'])?" AND est_estado_est like '%".$variable['filtroEstado']."%'":"";
                                if(isset($variable['filtroCodCarrera']) && is_numeric($variable['filtroCodCarrera'])){
                                    $cadena_sql.=" AND cra_cod = '".$variable['filtroCodCarrera']."' ";
                                }
				$cadena_sql.="		UNION ";
				$cadena_sql.="		SELECT ";//registros del actual proyecto del estudiante
				$cadena_sql.="			EST_COD ";
				$cadena_sql.="		FROM accra,acest ";
				$cadena_sql.="		WHERE cra_cod=est_cra_cod ";
				$cadena_sql.="		AND cra_emp_nro_iden=".$variable['usuario']." ";
				$cadena_sql.=isset($variable['filtroEstudiante'])?" AND est_cod::text like '%".$variable['filtroEstudiante']."%'":"";
				$cadena_sql.=isset($variable['filtroNombre'])?" AND est_nombre like UPPER('%".$variable['filtroNombre']."%')":"";
				$cadena_sql.=isset($variable['filtroPlan'])?" AND est_pen_nro like '%".$variable['filtroPlan']."%'":"";
				$cadena_sql.=isset($variable['filtroIdentificacion'])?" AND est_nro_iden like '%".$variable['filtroIdentificacion']."%'":"";
                                if(isset($variable['filtroCodCarrera']) && is_numeric($variable['filtroCodCarrera'])){
                                    $cadena_sql.=" AND cra_cod = '".$variable['filtroCodCarrera']."' ";
                                }
                                $cadena_sql.=isset($variable['filtroCarrera'])?" AND cra_nombre like '%".$variable['filtroCarrera']."%'":"";
				$cadena_sql.=isset($variable['filtroEstado'])?" AND est_estado_est like '%".$variable['filtroEstado']."%'":"";
				
				$cadena_sql.="		ORDER BY CODIGO asc ) as codigo ";
				$cadena_sql.=") AS registro ";
                                
				$cadena_sql.="WHERE 1=1 ";
				//$cadena_sql.="AND NUMERO=".$variable['registroActual'];
				break;

			case "consultarNotas":
				$cadena_sql="SELECT  ";
				$cadena_sql.="not_asi_cod, "; //0
				$cadena_sql.="not_gr, "; //1
				$cadena_sql.="not_sem, "; //2
				$cadena_sql.="asi_nombre, "; //3
				$cadena_sql.="not_ano, "; //4
				$cadena_sql.="not_per, "; //5
				$cadena_sql.="not_nota, "; //6
				$cadena_sql.="not_obs, ";	//7
				$cadena_sql.="not_est_reg, ";//8
				$cadena_sql.="row_NUMBER() OVER () AS NUMERO, ";//9
				$cadena_sql.="est_cra_cod, ";//10
				$cadena_sql.="not_est_cod, ";//11
				$cadena_sql.="est_nombre, ";//12
				$cadena_sql.="trim(est_ind_cred) est_ind_cred, ";//13
				$cadena_sql.="not_cred, ";//14
				$cadena_sql.="not_nro_ht, ";//15
				$cadena_sql.="not_nro_hp, ";//16
				$cadena_sql.="not_nro_aut, ";//17
				$cadena_sql.="not_cea_cod, ";//18
				$cadena_sql.="asi_ind_cred, ";//19
                                $cadena_sql.="tra_nivel, ";//20
                                $cadena_sql.="not_cra_cod ";//21
                                
				$cadena_sql.="FROM ";
				$cadena_sql.="acasi,acest,acnot, accra, actipcra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asi_cod=not_asi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod=not_est_cod ";
				$cadena_sql.="AND not_cra_cod=cra_cod ";
                                $cadena_sql.="AND cra_tip_cra=tra_cod ";
				$cadena_sql.="AND cra_emp_nro_iden=".$variable['usuario']." ";
				$cadena_sql.=isset($variable['estudiante'])?" AND not_est_cod=".$variable['estudiante']:"";
				if(isset($variable['filtroCodCarrera'])  && is_numeric($variable['filtroCodCarrera'])){
                                    $cadena_sql.=" AND not_cra_cod=".$variable['filtroCodCarrera']." ";
                                }else{
                                    $cadena_sql.=" AND not_cra_cod=est_cra_cod ";
                                }
				//$cadena_sql.=isset($variable['filtroCodCarrera'])?" AND est_cra_cod=".$variable['filtroCodCarrera']:"";
				//$cadena_sql.=isset($variable['nota_org'])?" AND not_nota=".$variable['nota_org']:"";
				//$cadena_sql.=isset($variable['obs_org'])?" AND not_obs=".$variable['obs_org']:"";
				//$cadena_sql.=isset($variable['nota_org'])?" AND not_est_reg='".$variable['estado_org']."'":"";
                                $cadena_sql.="ORDER BY not_sem,not_asi_cod,not_ano DESC,not_per DESC";
				
				break;
                            
			case "consultarNotaACambiar":
				$cadena_sql="SELECT  ";
				$cadena_sql.="not_asi_cod, "; //0
				$cadena_sql.="not_gr, "; //1
				$cadena_sql.="not_sem, "; //2
				$cadena_sql.="asi_nombre, "; //3
				$cadena_sql.="not_ano, "; //4
				$cadena_sql.="not_per, "; //5
				$cadena_sql.="not_nota, "; //6
				$cadena_sql.="not_obs, ";	//7
				$cadena_sql.="not_est_reg, ";//8
				$cadena_sql.="row_NUMBER() OVER () AS NUMERO, ";//9
				$cadena_sql.="est_cra_cod, ";//10
				$cadena_sql.="not_est_cod, ";//11
				$cadena_sql.="est_nombre, ";//12
				$cadena_sql.="trim(est_ind_cred) est_ind_cred, ";//13
				$cadena_sql.="not_cred, ";//14
				$cadena_sql.="not_nro_ht, ";//15
				$cadena_sql.="not_nro_hp, ";//16
				$cadena_sql.="not_nro_aut, ";//17
				$cadena_sql.="not_cea_cod, ";//18
				$cadena_sql.="asi_ind_cred, ";//19
                                $cadena_sql.="tra_nivel, ";//20
                                $cadena_sql.="not_cra_cod ";//21
                                
				$cadena_sql.=" FROM ";
				$cadena_sql.=" acasi,acest,acnot, accra, actipcra ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.=" asi_cod=not_asi_cod ";
				$cadena_sql.=" AND ";
				$cadena_sql.=" est_cod=not_est_cod ";
				$cadena_sql.=" AND est_cra_cod=cra_cod ";
                                $cadena_sql.=" AND cra_tip_cra=tra_cod";
				$cadena_sql.=" AND not_est_cod=". (isset($variable['estudiante'])?$variable['estudiante']:"");
				if(isset($variable['filtroCodCarrera'])  && is_numeric($variable['filtroCodCarrera'])){
                                    $cadena_sql.=" AND not_cra_cod=".$variable['filtroCodCarrera']." ";
                                }else{
                                    $cadena_sql.=" AND not_cra_cod=est_cra_cod ";
                                }
                                //$cadena_sql.=" AND est_cra_cod=".(isset($variable['filtroCodCarrera'])?$variable['filtroCodCarrera']:"");
				$cadena_sql.=" AND asi_cod=".(isset($variable['asignatura'])?$variable['asignatura']:"");
				$cadena_sql.=" AND not_ano=".(isset($variable['anio'])?$variable['anio']:"");
				$cadena_sql.=" AND not_per=".(isset($variable['per'])?$variable['per']:"");
				$cadena_sql.=" AND cra_emp_nro_iden=".$variable['usuario']." ";
				$cadena_sql.=" ORDER BY not_sem,not_asi_cod,not_ano DESC,not_per DESC";
				
				break;
				
			case "actualizarRegistroNota":
				$cadena_sql="UPDATE acnot SET ";
                                $cadena_sql.= $variable['cadenaActualizar']." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="not_cra_cod=".$variable['carrera'];
				$cadena_sql.=" AND ";
				$cadena_sql.="not_est_cod=".$variable['estudiante'];
				$cadena_sql.=" AND ";
				$cadena_sql.="not_asi_cod=".$variable['asignatura'];
				$cadena_sql.=" AND ";
				$cadena_sql.="not_ano=".$variable['anio'];
				$cadena_sql.=" AND ";
				$cadena_sql.="not_per=".$variable['per'];
			break;	

			case "insertarRegistroNota":
				$cadena_sql="INSERT INTO acnot(";
				$cadena_sql.="not_cra_cod, ";
				$cadena_sql.="not_est_cod, ";
				$cadena_sql.="not_asi_cod, ";
				$cadena_sql.="not_ano, ";
				$cadena_sql.="not_per, ";
				$cadena_sql.="not_sem, ";
				$cadena_sql.="not_nota, ";
				$cadena_sql.="not_gr, ";
				$cadena_sql.="not_obs, ";
				$cadena_sql.="not_com, ";
				$cadena_sql.="not_fecha, "; //11
				$cadena_sql.="not_cred, ";
				$cadena_sql.="not_nro_ht, ";
				$cadena_sql.="not_nro_hp, ";
				$cadena_sql.="not_nro_aut, ";
				$cadena_sql.="not_cea_cod, "; //16
				$cadena_sql.="not_asi_cod_ins, ";
				$cadena_sql.="not_asi_homologa, ";
				$cadena_sql.="not_est_homologa, ";
				$cadena_sql.="not_est_reg) values( ";
				$cadena_sql.=isset($variable['carrera'])?"'".$variable['carrera']."',":"'',";
				$cadena_sql.=isset($variable['estudiante'])?"'".$variable['estudiante']."',":"'',";
				$cadena_sql.=isset($variable['asignatura'])?"'".$variable['asignatura']."',":"'',";
				$cadena_sql.=isset($variable['anio'])?"'".$variable['anio']."',":"'',";
				$cadena_sql.=isset($variable['per'])?"'".$variable['per']."',":"'',";
				$cadena_sql.=isset($variable['semestre'])?"'".$variable['semestre']."',":"'',";
				$cadena_sql.=isset($variable['nota'])?"'".$variable['nota']."',":"'',";
				$cadena_sql.=isset($variable['grupo'])?"'".$variable['grupo']."',":"'',";
				$cadena_sql.=isset($variable['obs'])?"'".$variable['obs']."',":"'',";
				$cadena_sql.=isset($variable['com'])?"'".$variable['com']."',":"'',";
				$cadena_sql.="SYSDATE,"; //11
				$cadena_sql.=isset($variable['creditos'])?"'".$variable['creditos']."',":"'',";
				$cadena_sql.=isset($variable['hteoricas'])?"'".$variable['hteoricas']."',":"'',";
				$cadena_sql.=isset($variable['hpracticas'])?"'".$variable['hpracticas']."',":"'',";
				$cadena_sql.=isset($variable['hautonomo'])?"'".$variable['hautonomo']."',":"'',"; //16
				$cadena_sql.=isset($variable['ceacod'])?"'".$variable['ceacod']."',":"'',";
				$cadena_sql.=isset($variable['asigins'])?"'".$variable['asigins']."',":"'',";
				$cadena_sql.=isset($variable['asihomologa'])?"'".$variable['asihomologa']."',":"'',";
				$cadena_sql.=isset($variable['asihomologa'])?"'".$variable['asihomologa']."',":"'',";
				$cadena_sql.="'A'";
				$cadena_sql.=")";
			break;					
				

			case "insertarAuditoria":
				$cadena_sql="INSERT INTO logaudit(";
				$cadena_sql.="lau_object, ";
				$cadena_sql.="lau_type, ";
				$cadena_sql.="lau_date, ";
				$cadena_sql.="lau_op, ";
				$cadena_sql.="lau_user, ";
				$cadena_sql.="lau_field, ";
				$cadena_sql.="lau_vlrini, ";
				$cadena_sql.="lau_vlrend, ";
				$cadena_sql.="lau_reg, ";
				$cadena_sql.="lau_terminal) values( ";
				$cadena_sql.="'ACNOT', ";
				$cadena_sql.="'TABLE', ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'".$variable['operacion']."', ";
				$cadena_sql.="'".$variable['usuario']."', ";
				$cadena_sql.="'".(isset($variable['campo'])?$variable['campo']:'')."', ";
				$cadena_sql.="'".(isset($variable['valIni'])?$variable['valIni']:'')."', ";
				$cadena_sql.="'".(isset($variable['valFin'])?$variable['valFin']:'')."', ";
				$cadena_sql.="'".$variable['carrera']."|".$variable['estudiante']."|".$variable['asignatura']."|".$variable['anio']."|".$variable['per']."', ";
				$cadena_sql.="'".$_SERVER["REMOTE_ADDR"]."'";
				$cadena_sql.=")";
			break;
			case "consultarEstudiante":
				$cadena_sql="SELECT  ";
				$cadena_sql.="est_ind_cred, ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_pen_nro, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="tra_nivel ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="accra,acest, actipcra ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=" cra_cod=est_cra_cod ";
                                $cadena_sql.=" AND cra_tip_cra=tra_cod";
				$cadena_sql.=" AND ";
				$cadena_sql.=" cra_emp_nro_iden=".$variable['usuario']." ";
				$cadena_sql.=" AND ";
				$cadena_sql.=" est_cod=".$variable['estudiante']." ";
				
			break;			
			case "consultarAsignatura":
				$cadena_sql="SELECT  ";
				$cadena_sql.="ASI_COD, ";
				$cadena_sql.="ASI_IND_CRED, ";
				$cadena_sql.="ASI_NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asi_cod=".$variable['asignatura'];
			break;	
			case "consultarClasificacionAsignatura":
				$cadena_sql="SELECT  ";
				$cadena_sql.="CLP_CEA_COD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acclasificacpen ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="clp_asi_cod=".$variable['asignatura'];
				$cadena_sql.="AND ";
				$cadena_sql.="clp_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="clp_pen_nro=".$variable['plan']." ";
			break;				
			case "consultarActivayAprobada":
				$cadena_sql="SELECT  ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acnot ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="not_est_cod=".$variable['estudiante']." ";
				$cadena_sql.="AND ";
				$cadena_sql.="not_asi_cod=".$variable['asignatura']." ";
				$cadena_sql.="AND ";
				$cadena_sql.="not_nota>=fua_nota_aprobatoria(".$variable['carrera'].") ";
				$cadena_sql.="AND ";
				$cadena_sql.="not_est_reg='A' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="not_cra_cod=".$variable['carrera']." ";
				
			break;	
			case "consultarAsignaturaPlan":
				$cadena_sql="SELECT  ";
				$cadena_sql.="ASI_COD,  "; 
				$cadena_sql.="ASI_IND_CRED,  "; 
				$cadena_sql.="ASI_NOMBRE,  "; 
				$cadena_sql.="PEN_CRA_COD,  ";  
				$cadena_sql.="PEN_ASI_COD, ";   
				$cadena_sql.="PEN_SEM, "; //5
				$cadena_sql.="PEN_IND_ELE, ";
				$cadena_sql.="PEN_NRO_HT, "; //7 //HTD
				$cadena_sql.="PEN_NRO_HP, "; //8   //HTC
				$cadena_sql.="PEN_ESTADO, "; 
				$cadena_sql.="PEN_CRE, ";  //10  
				$cadena_sql.="PEN_NRO, "; //11  
				$cadena_sql.="PEN_NRO_AUT ";  //12 //HTA
				$cadena_sql.="FROM ";
				$cadena_sql.="acasi,acpen,acest ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asi_cod=pen_asi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="pen_nro=est_pen_nro ";
				$cadena_sql.="AND ";
				$cadena_sql.="pen_cra_cod=est_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="asi_cod=".$variable['asignatura'];
				$cadena_sql.=" AND ";
				$cadena_sql.="est_cod=".$variable['estudiante'];
			break;
			case "consultarAsignaturaDatos":
				$cadena_sql="SELECT  ";
				$cadena_sql.="ASI_COD,  "; 
				$cadena_sql.="ASI_IND_CRED,  "; 
				$cadena_sql.="ASI_NOMBRE,  "; 
				$cadena_sql.="PEN_CRA_COD,  ";  
				$cadena_sql.="PEN_ASI_COD, ";   
				$cadena_sql.="PEN_SEM, "; //5
				$cadena_sql.="PEN_IND_ELE, ";
				$cadena_sql.="PEN_NRO_HT, "; //7
				$cadena_sql.="PEN_NRO_HP, "; //8   
				$cadena_sql.="PEN_ESTADO, "; 
				$cadena_sql.="PEN_CRE, ";  //10  
				$cadena_sql.="PEN_NRO, ";   
				$cadena_sql.="PEN_NRO_AUT ";   
				$cadena_sql.="FROM ";
				$cadena_sql.="acasi,acpen ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="asi_cod=pen_asi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="asi_cod='".$variable."' ";

			break;
			
			case "consultarAsignaturaExtrinseca":
				$cadena_sql="SELECT  ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="geclasificaespac,acclasificacpen ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=" clp_cea_cod=cea_cod ";
                                $cadena_sql.=" AND clp_asi_cod=".$variable['asignatura'];
				$cadena_sql.=" AND cea_abr='EE' ";
			break;			
                    
                        case 'registro_evento':
                                $cadena_sql="insert into sga_log_eventos ";
                                $cadena_sql.="VALUES(0,'".$variable['usuario']."',";
                                $cadena_sql.="'".date('YmdHis')."',";
                                $cadena_sql.="'".$variable['evento']."',";
                                $cadena_sql.="'".utf8_decode($variable['descripcion'])."',";
                                $cadena_sql.="'".utf8_decode($variable['registro'])."',";
                                $cadena_sql.="'".$variable['afectado']."')";
                          break;

			default:
				$cadena_sql="";
				break;
		}
		//echo "<br>$opcion=".$cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>
