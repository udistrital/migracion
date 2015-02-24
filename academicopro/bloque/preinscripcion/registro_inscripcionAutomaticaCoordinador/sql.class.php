<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroInscripcionAutomaticaCoordinador extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;
            
            case 'consultarProyectosCoordinador':
                $cadena_sql=" SELECT A.cra_cod CRA_COD FROM accra A";
                $cadena_sql.=" WHERE A.cra_emp_nro_iden=";
                $cadena_sql.=" (SELECT cra_emp_nro_iden FROM accra B";
                $cadena_sql.=" WHERE B.cra_cod=".$variable.")";
                $cadena_sql.=" AND cra_estado='A'";
                break;

            case 'consultarEstudiantesClasificados':
                $cadena_sql="SELECT cle_id idClasificacion,";
                $cadena_sql.=" cle_codEstudiante codEstudiante,";
                $cadena_sql.=" cle_codProyectoCurricular codProyecto,";
                $cadena_sql.=" cle_clasificacion clasificacion,";
                $cadena_sql.=" cle_tipoEstudiante tipo ";
                $cadena_sql.=" FROM sga_clasificacion_estudiantes";
                $cadena_sql.=" WHERE cle_codProyectoCurricular=".$variable;
                //$cadena_sql.=" AND cle_codEstudiante=20121025068"; 
                //$cadena_sql.=" AND cle_codEstudiante=20112025073"; 
                //$cadena_sql.=" AND cle_codEstudiante=20002005071"; 
                //$cadena_sql.=" AND cle_codEstudiante=8920585"; 
                //$cadena_sql.=" AND cle_codEstudiante=20081005002"; 
                //$cadena_sql.=" AND cle_codEstudiante=20041005116"; 
                $cadena_sql.=" ORDER BY cle_clasificacion, cle_id"; 
                break;
            
            case 'buscarHorarios':
                      
                $cadena_sql="SELECT DISTINCT hor_id_curso ID,";
                $cadena_sql.=" hor_facultad SEDE,";
                $cadena_sql.=" hor_sede_dif SEDE_DIF,";
                $cadena_sql.=" hor_alternativa ALTERNATIVA,";
                $cadena_sql.=" hor_lunes LUNES,";
                $cadena_sql.=" hor_martes MARTES,";
                $cadena_sql.=" hor_miercoles MIERCOLES,";
                $cadena_sql.=" hor_jueves JUEVES,";
                $cadena_sql.=" hor_viernes VIERNES,";
                $cadena_sql.=" hor_sabado SABADO,";
                $cadena_sql.=" hor_domingo DOMINGO";
                $cadena_sql.=" FROM sga_horarios_binarios";
                $cadena_sql.=" WHERE hor_carrera in (".$variable['proyectos'].")";
                break;
            
             case 'buscarGrupos': 
                      
                $cadena_sql="SELECT DISTINCT cur_id ID,";
                $cadena_sql.=" cur_nro_ins INSCRITOS,";
                $cadena_sql.=" cur_nro_cupo CUPOS,";
                $cadena_sql.=" cur_grupo GRUPO,";
                $cadena_sql.=" cur_asi_cod ASIGNATURA,";
                $cadena_sql.=" cur_cra_cod PROYECTO";
                $cadena_sql.=" FROM accursos";
                $cadena_sql.=" WHERE cur_ape_ano=".$variable['ano']; 
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo']; 
                $cadena_sql.=" AND cur_cra_cod in "; 
                $cadena_sql.="(SELECT A.cra_cod FROM accra A";
                $cadena_sql.=" WHERE A.cra_emp_nro_iden=";
                $cadena_sql.=" (SELECT cra_emp_nro_iden FROM accra B";
                $cadena_sql.=" WHERE B.cra_cod=".$variable['codCarrera'].")";
                $cadena_sql.=" AND cra_estado='A')";                
                $cadena_sql.=" AND cur_estado LIKE '%A%'";
                break;
            
             case 'buscarInscritas':
                      
                $cadena_sql="SELECT ins_asi_cod ASIGNATURA,";
                $cadena_sql.=" ins_gr GRUPO,";
                $cadena_sql.=" ins_Cred CREDITOS,";
                $cadena_sql.=" ins_nro_ht HT,";
                $cadena_sql.=" ins_nro_hp HP,";
                $cadena_sql.=" ins_nro_aut HAUT,";
                $cadena_sql.=" ins_cea_cod CLASIFICACION,";
                $cadena_sql.=" ins_sem NIVEL";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_ano=".$variable['ano']; 
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_cra_cod=".$variable['codCarrera']; 
                $cadena_sql.=" AND ins_est_cod=".$variable['codEstudiante']; 
                $cadena_sql.=" AND ins_estado LIKE '%A%'"; 
                break;
            
             case 'buscarPreinscritas':
                      
                $cadena_sql="SELECT insde_ano ANO,";
                $cadena_sql.=" insde_asi_cod ASIGNATURA,";
                $cadena_sql.=" insde_cra_cod CARRERA,";
                $cadena_sql.=" insde_est_cod CODIGO,";
                $cadena_sql.=" insde_perdido REPROBADOS,";
                $cadena_sql.=" insde_cred CREDITOS,";
                $cadena_sql.=" insde_htd HT,";
                $cadena_sql.=" insde_htc HP,";
                $cadena_sql.=" insde_hta HAUT,";
                $cadena_sql.=" insde_cea_cod CLASIFICACION,";
                $cadena_sql.=" insde_sem NIVEL";
                $cadena_sql.=" FROM acinsdemanda";
                $cadena_sql.=" WHERE insde_ano=".$variable['ano']; 
                $cadena_sql.=" AND insde_per=".$variable['periodo'];
                $cadena_sql.=" AND insde_cra_cod=".$variable['codCarrera']; 
                $cadena_sql.=" AND insde_est_cod=".$variable['codEstudiante']; 
                $cadena_sql.=" AND insde_estado LIKE '%A%'"; 
                break;
            
            case 'buscarRanking':
                      
                $cadena_sql="SELECT";
                $cadena_sql.=" rank_codEspacio ASIGNATURA,";
                $cadena_sql.=" rank_nombreEspacio NOMBRE,";
                $cadena_sql.=" rank_posicion POSICION";
                $cadena_sql.=" FROM sga_rankingPreinsDemanda";
                $cadena_sql.=" WHERE rank_codProyecto=".$variable['codCarrera']; 
                break;
            
                    case 'adicionar_inscripcion':
                        $cadena_sql="INSERT INTO ACINSPRE ";
                        $cadena_sql.="( INS_ANO, INS_PER,INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_SEM, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_HOR_ALTERNATIVO) ";
                        $cadena_sql.="VALUES (";
                        $cadena_sql.="'".$variable['INS_ANO']."',";
                        $cadena_sql.="'".$variable['INS_PER']."',";
                        $cadena_sql.="'".$variable['INS_CRA_COD']."',";
                        $cadena_sql.="'".$variable['INS_EST_COD']."',";
                        $cadena_sql.="'".$variable['INS_ASI_COD']."',";
                        $cadena_sql.="'".$variable['INS_GR']."',";
                        $cadena_sql.="'".$variable['INS_SEM']."',";
                        $cadena_sql.="'".$variable['INS_ESTADO']."',";
                        $cadena_sql.="'".$variable['INS_CRED']."',";
                        $cadena_sql.="'".$variable['INS_NRO_HT']."',";
                        $cadena_sql.="'".$variable['INS_NRO_HP']."',";
                        $cadena_sql.="'".$variable['INS_NRO_AUT']."',";
                        $cadena_sql.="'".$variable['INS_CEA_COD']."',";
                        $cadena_sql.="'0')";
                        break;
                    
                    case 'actualizar_cupo':
                        $cadena_sql="UPDATE ACCURSOS ";
                        $cadena_sql.="SET  ";
                        $cadena_sql.="CUR_NRO_INS = '".$variable['numeroInscritos']."' ";
                        $cadena_sql.=" WHERE ";
                        $cadena_sql.="CUR_APE_ANO = '".$variable['ano']."' ";
                        $cadena_sql.="AND CUR_APE_PER = '".$variable['periodo']."' ";
                        $cadena_sql.="AND CUR_ASI_COD = '".$variable['codEspacio']."' ";
                        $cadena_sql.="AND CUR_ID = '".$variable['codGrupo']."' ";
                        //$cadena_sql.="AND CUR_CRA_COD = '".$variable['codProyecto']."' ";
                        break;
                    
                    case 'borrar_inscripcion':
                        $cadena_sql=" delete from acinspre";
                        $cadena_sql.=" where ins_ano=".$variable['ano'];
                        $cadena_sql.=" and ins_per=".$variable['periodo'];
                        $cadena_sql.=" and ins_est_cod=".$variable['codEstudiante'];
                        $cadena_sql.=" and ins_cra_cod=".$variable['codProyecto'];
                        $cadena_sql.=" and ins_asi_cod=".$variable['codEspacio'];                        
                        break;

                    case 'consultarEquivalentes':
                        $cadena_sql="SELECT DISTINCT hom_asi_cod_ppal HOM_ASI_COD_PPAL,";
                        $cadena_sql.=" hom_asi_cod_hom HOM_ASI_COD_HOM ";
                        $cadena_sql.=" FROM actablahomologacion";
                        $cadena_sql.=" WHERE hom_cra_cod_ppal=".$variable; 
                        $cadena_sql.=" AND hom_tipo_hom=0"; 
                        break;

                    case "vaciarTablaHorariosBinarios":                

                            $cadena_sql=" DELETE from sga_horarios_binarios where hor_carrera in (".$variable.")";
                        break;          
                    
            case 'publicarInscripcionesProyecto':
                $cadena_sql=" INSERT INTO acins";
                $cadena_sql.=" (ins_cra_cod,ins_est_cod,ins_asi_cod,ins_gr,ins_estado,ins_ano,ins_per,ins_cred,ins_nro_ht,ins_nro_hp,ins_nro_aut,ins_cea_cod,ins_sem,ins_tot_fallas,ins_hor_alternativo)";
                $cadena_sql.=" SELECT ins_cra_cod,ins_est_cod,ins_asi_cod,ins_gr,ins_estado,ins_ano,ins_per,ins_cred,ins_nro_ht,ins_nro_hp,ins_nro_aut,ins_cea_cod,ins_sem,'0','0'";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_cra_cod=".$variable['codProyecto'];
                        break;
                    
            case 'consultarNumeroInscripcionesProyecto':
                $cadena_sql = "SELECT count(*) TOTAL";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                break;

            case 'consultarPreinscripcionesProyecto':
                $cadena_sql="SELECT DISTINCT ins_asi_cod ASIGNATURA,";
                $cadena_sql.=" ins_est_cod ESTUDIANTE,";
                $cadena_sql.=" ins_gr GRUPO,";
                $cadena_sql.=" ins_Cred CREDITOS,";
                $cadena_sql.=" ins_nro_ht HT,";
                $cadena_sql.=" ins_nro_hp HP,";
                $cadena_sql.=" ins_nro_aut HAUT,";
                $cadena_sql.=" ins_cea_cod CLASIFICACION,";
                $cadena_sql.=" ins_sem NIVEL";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND ins_estado LIKE '%A%'";
                break;

            case 'consultarPreinscripcionesDemandaProyecto':
                $cadena_sql = "SELECT DISTINCT insde_ano ANO,";
                $cadena_sql.=" insde_asi_cod ASIGNATURA,";
                $cadena_sql.=" insde_cra_cod CARRERA,";
                $cadena_sql.=" insde_est_cod ESTUDIANTE,";
                $cadena_sql.=" insde_perdido REPROBADOS,";
                $cadena_sql.=" insde_cred CREDITOS,";
                $cadena_sql.=" insde_htd HT,";
                $cadena_sql.=" insde_htc HP,";
                $cadena_sql.=" insde_hta HAUT,";
                $cadena_sql.=" insde_cea_cod CLASIFICACION,";
                $cadena_sql.=" insde_sem NIVEL";
                $cadena_sql.=" FROM acinsdemanda";
                $cadena_sql.=" WHERE insde_ano=" . $variable['ano'];
                $cadena_sql.=" AND insde_per=" . $variable['periodo'];
                $cadena_sql.=" AND insde_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND insde_estado LIKE '%A%'";
                break;

            case 'consultarDatosProyecto':
                $cadena_sql = "SELECT cra_cod CARRERA,";
                $cadena_sql.= " cra_dep_cod FACULTAD,";
                $cadena_sql.= " cra_tip_cra TIPO";
                $cadena_sql.="  FROM accra";
                $cadena_sql.="  WHERE cra_cod=".$variable['codProyecto'];
                break;

            case 'consultarEventosCalendario':
                $cadena_sql=" SELECT cal_cod_evento COD_EVENTO,";
                $cadena_sql.=" to_char(cal_fec_ini, 'dd/mm/yyyy') INICIO,";
                $cadena_sql.=" to_char(cal_fec_fin, 'dd/mm/yyyy') FIN,";
                $cadena_sql.=" cal_acdes_evento EVENTO";
                $cadena_sql.=" FROM accalendario";
                $cadena_sql.=" WHERE cal_anio=".$variable['ano'];
                $cadena_sql.=" AND cal_periodo=".$variable['periodo'];
                $cadena_sql.=" AND cal_cod_evento in (11,12,13,14)";
                break;

            case 'insertarEventoInscripcionAutomatica':
                $cadena_sql="INSERT INTO accaleventos ";
                $cadena_sql.="(ACE_COD_EVENTO,  ACE_CRA_COD, ACE_FEC_INI, ACE_TIP_CRA, ACE_DEP_COD, ACE_ANIO, ACE_PERIODO, ACE_ESTADO) ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'14',";
                $cadena_sql.="'".$variable['codProyecto']."',";
                $cadena_sql.="sysdate,";
                $cadena_sql.="'".$variable['tipo']."',";
                $cadena_sql.="'".$variable['facultad']."',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'A')";
                break;

            case 'insertarEventoInscripciones':
                $cadena_sql="INSERT INTO accaleventos ";
                $cadena_sql.="(ACE_COD_EVENTO, ACE_CRA_COD, ACE_FEC_INI, ACE_FEC_FIN, ACE_TIP_CRA, ACE_DEP_COD, ACE_ANIO, ACE_PERIODO, ACE_ESTADO) ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'".$variable['evento']."',";
                $cadena_sql.="'".$variable['codProyecto']."',";
                $cadena_sql.="to_date('".$variable['inicio']."','dd/mm/yyyy'),";
                $cadena_sql.="to_date('".$variable['fin']."','dd/mm/yyyy'),";
                $cadena_sql.="'".$variable['tipo']."',";
                $cadena_sql.="'".$variable['facultad']."',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'A')";
                break;
            
            case 'borrarPreinscripcionesProyecto':
                $cadena_sql="delete from acinspre";
                $cadena_sql.=" where ins_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" and ins_ano=".$variable['ano'];
                $cadena_sql.=" and ins_per=".$variable['periodo'];           
                break;

            case 'actualizarGruposProyecto':
                $cadena_sql="update accursos";
                $cadena_sql.=" set cur_nro_ins=(select count (*) from acins where ins_gr=cur_id and cur_ape_ano=ins_ano and cur_ape_per=ins_per and cur_asi_cod=ins_asi_cod)";
                $cadena_sql.=" +(select count (*) from acinspre where ins_gr=cur_id and cur_ape_ano=ins_ano and cur_ape_per=ins_per and cur_asi_cod=ins_asi_cod)";
                $cadena_sql.=" where cur_cra_cod in "; 
                $cadena_sql.="(SELECT A.cra_cod FROM accra A";
                $cadena_sql.=" WHERE A.cra_emp_nro_iden=";
                $cadena_sql.=" (SELECT cra_emp_nro_iden FROM accra B";
                $cadena_sql.=" WHERE B.cra_cod=".$variable['codProyecto'].")";
                $cadena_sql.=" AND cra_estado='A')";                
                $cadena_sql.=" and cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" and cur_ape_per=".$variable['periodo'];
                break;

            case 'consultarInscripcionAutomatica':
                $cadena_sql= "SELECT ace_cod_evento EVENTO, ace_cra_cod PROYECTO, ace_fec_ini INICIO, ace_fec_fin FIN";
                $cadena_sql.=" FROM accaleventos";
                $cadena_sql.=" WHERE ace_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=14";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='A'";
                break;
            
            case 'actualizarEstadoEventoPreins':
                $cadena_sql="UPDATE accaleventos";
                $cadena_sql.=" SET ace_estado='I'";
                $cadena_sql.=" WHERE ace_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=14";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='A'";
                break;

            case 'actualizarEstadoEventoInscripcionAutomatica':
                $cadena_sql="UPDATE accaleventos";
                $cadena_sql.=" SET ace_estado='A'";
                $cadena_sql.=" WHERE ace_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=14";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='I'";
                break;

            case 'actualizarEventoFinPreins':
                $cadena_sql="UPDATE accaleventos";
                $cadena_sql.=" SET ace_fec_fin=sysdate";
                $cadena_sql.=" WHERE ace_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=14";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='A'";
                break;

            case 'consultarPreinscripciones':
                $cadena_sql=" SELECT insde_est_cod CODIGO, count(insde_asi_cod) TOTAL";
                $cadena_sql.=" FROM acinsdemanda";
                $cadena_sql.=" WHERE insde_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND insde_ano=".$variable['ano'];
                $cadena_sql.=" AND insde_per=".$variable['periodo'];
                $cadena_sql.=" AND insde_estado LIKE '%A%'";
                $cadena_sql.=" GROUP BY insde_est_cod";
                break;
                
            case 'consultarRegistrosProceso':
                $cadena_sql=" SELECT count(*) TOTAL";
                $cadena_sql.=" FROM ".$variable['tabla'];
                $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_est_cod=".$variable['codEstudiante'];
                break;

            case 'consultarInscripcionesProyecto':
                $cadena_sql = "SELECT count(*) TOTAL";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                break;
            
            case 'insertarEventoEjecutar':
                $cadena_sql=" INSERT INTO sga_ejecuta_inscripcion_auto";
                $cadena_sql.=" (`ins_ano`, `ins_per`, `ins_cra_cod`, `ins_estado`)";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" ('".$variable['ano']."',";
                $cadena_sql.=" '".$variable['periodo']."',";
                $cadena_sql.=" '".$variable['codProyecto']."',";
                $cadena_sql.=" 'A')";
                break;
            
            case 'borrarEventoEjecutar':
                $cadena_sql=" DELETE FROM sga_ejecuta_inscripcion_auto";
                $cadena_sql.=" WHERE ins_ano = ".$variable['ano'];
                $cadena_sql.=" AND ins_per = ".$variable['periodo'];
                $cadena_sql.=" AND ins_cra_cod = ".$variable['codProyecto'];
                break;
            
            case 'consultarEjecucionInscripcion':
                $cadena_sql=" SELECT ins_ano,";
                $cadena_sql.=" ins_per,";
                $cadena_sql.=" ins_cra_cod,";
                $cadena_sql.=" ins_estado";
                $cadena_sql.=" FROM sga_ejecuta_inscripcion_auto";
                $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_cra_cod=".$variable['codProyecto'];
                break;
            
        }
        return $cadena_sql;
    }


}
?>
