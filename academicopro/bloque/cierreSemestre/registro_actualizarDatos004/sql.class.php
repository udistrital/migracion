<?php
/**
 * SQL sql_registro
 *
 * Descripción
 *
 * @package academicopro
 * @subpackage cierreSemestre
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 02/09/2015
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
 * Clase sql_registro
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class sql_registro extends sql
{
  public $configuracion;


  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }

    /**
     * Funcion que arma la cadena sql
     * 
     * @param string $tipo Nombre de la cadena sql
     * @param type $variable contiene pasan los parámetros que se pasan a la cadena sql
     * @return string retorna la cadena sql
     */
    function cadena_sql($tipo,$variable=""){

        switch($tipo)
        {

             //Oracle
             case 'consultarEstudiantesReglamento':
                /*$cadena_sql="SELECT";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" coalesce(reg_promedio,0) PROMEDIO,";
                $cadena_sql.=" est_acuerdo ACUERDO,";
                $cadena_sql.=" reg_asi_3 HISTORICO,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" reg_reglamento REGLAMENTO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" reglamento,acest ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" reg_est_cod = est_cod";
                $cadena_sql.=" AND reg_estado = 'A'";
                $cadena_sql.=" AND reg_cra_cod = '".$variable['proyecto']."'";
                $cadena_sql.=" AND reg_ano = '".$variable['anio']."'";
                $cadena_sql.=" AND reg_per = '".$variable['periodo']."'";
                $cadena_sql.=" AND est_acuerdo=2011004";*/
                

                $cadena_sql=" SELECT reg_cra_cod COD_PROYECTO,";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" reg_ano ANO,";
                $cadena_sql.=" reg_per PERIODO,";
                $cadena_sql.=" est_acuerdo ACUERDO";
                $cadena_sql.=" FROM reglamento B";
                $cadena_sql.=" INNER JOIN acest ON est_cod=reg_est_cod";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra";
                $cadena_sql.=" WHERE CONCAT(B.reg_ano,B.reg_per)";
                $cadena_sql.=" IN (SELECT MAX(CONCAT(C.reg_ano,C.reg_per))";
                $cadena_sql.=" FROM reglamento C";
                $cadena_sql.=" WHERE C.reg_est_cod = B.reg_est_cod";
                $cadena_sql.=" AND reg_estado='A')";
                $cadena_sql.=" AND (B.REG_RENOVACIONES_004=0 or B.REG_RENOVACIONES_004 is null)";
                $cadena_sql.=" AND reg_porcentaje_plan=0";
                $cadena_sql.=" AND est_acuerdo='2011004'";
                $cadena_sql.=" AND tra_nivel='PREGRADO'";
                $cadena_sql.=" AND reg_estado='A'";
                $cadena_sql.=" AND est_estado_est not in ('E')";
                $cadena_sql.=" AND CONCAT(B.reg_ano,B.reg_per)::integer>20121";
                $cadena_sql.=" ORDER BY reg_est_cod";
                break;
            
             case 'consultarAcuerdo004':
                $cadena_sql=" SELECT reg_cra_cod COD_PROYECTO,";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" reg_ano ANO,";
                $cadena_sql.=" reg_per PERIODO,";
                $cadena_sql.=" est_acuerdo ACUERDO";
                $cadena_sql.=" FROM reglamento B";
                $cadena_sql.=" INNER JOIN acest ON est_cod=reg_est_cod";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra";
                $cadena_sql.=" WHERE CONCAT(B.reg_ano,B.reg_per)";
                $cadena_sql.=" IN (SELECT MAX(CONCAT(C.reg_ano,C.reg_per))";
                $cadena_sql.=" FROM reglamento C";
                $cadena_sql.=" WHERE C.reg_est_cod = B.reg_est_cod";
                $cadena_sql.=" AND reg_estado='A')";
                $cadena_sql.=" AND (B.REG_RENOVACIONES_004=0 or B.REG_RENOVACIONES_004 is null)";
                $cadena_sql.=" AND reg_porcentaje_plan=0";
                $cadena_sql.=" AND est_cod = ".$variable;
                $cadena_sql.=" AND est_acuerdo='2011004'";
                $cadena_sql.=" AND tra_nivel='PREGRADO'";
                $cadena_sql.=" AND reg_estado='A'";
                $cadena_sql.=" AND est_estado_est not in ('E')";
                $cadena_sql.=" AND CONCAT(B.reg_ano,B.reg_per)::integer>20121";
                break;

                case 'consultarEstudiantesReporte':
                $cadena_sql=" SELECT reg_cra_cod COD_PROYECTO,";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" reg_ano ANO,";
                $cadena_sql.=" reg_per PERIODO,";
                $cadena_sql.=" est_acuerdo ACUERDO,";
                $cadena_sql.=" coalesce(cast(reg_porcentaje_plan as text),'N/D') PORCENTAJE_PLAN,";
                $cadena_sql.=" coalesce(cast(reg_porcentaje_004 as text),'N/D') PORCENTAJE_004,";
                $cadena_sql.=" coalesce(cast(reg_num_matriculas as text),'N/D') MATRICULAS,";
                $cadena_sql.=" coalesce(cast(reg_matriculas_004 as text),'N/D') MATRICULAS_004,";
                $cadena_sql.=" coalesce(cast(reg_renovaciones_004 as text),'N/D') RENOVACIONES_004";
                $cadena_sql.=" FROM reglamento B";
                $cadena_sql.=" INNER JOIN acest ON est_cod=reg_est_cod";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra";
                $cadena_sql.=" WHERE CONCAT(B.reg_ano,B.reg_per)";
                $cadena_sql.=" IN (SELECT MAX(CONCAT(C.reg_ano,C.reg_per))";
                $cadena_sql.=" FROM reglamento C";
                $cadena_sql.=" WHERE C.reg_est_cod = B.reg_est_cod";
                $cadena_sql.=" AND reg_estado='A')";
                $cadena_sql.=" AND est_acuerdo='2011004'";
                $cadena_sql.=" AND tra_nivel='PREGRADO'";
                $cadena_sql.=" AND reg_estado='A'";
                $cadena_sql.=" AND est_estado_est not in ('E')";
                $cadena_sql.=" AND CONCAT(B.reg_ano,B.reg_per)::integer>20121";
                $cadena_sql.=" ORDER BY reg_est_cod";
                break;

             case 'consultarEstudianteRecalcularReglamento':
                $cadena_sql="SELECT";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" fa_promedio_nota(".$variable['codEstudiante'].")*100 PROMEDIO ,";
                $cadena_sql.=" est_acuerdo ACUERDO,";
                $cadena_sql.=" reg_asi_3 HISTORICO,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" reg_reglamento REGLAMENTO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" reglamento,acest ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" reg_est_cod = est_cod";
                $cadena_sql.=" AND reg_estado = 'A'";
                $cadena_sql.=" AND reg_cra_cod = '".$variable['proyecto']."'";
                $cadena_sql.=" AND reg_ano = '".$variable['anio']."'";
                $cadena_sql.=" AND reg_per = '".$variable['periodo']."'";
                $cadena_sql.=" AND reg_est_cod=".$variable['codEstudiante'];
                break;

            case 'consultarEstudiantesReglamentoenPrueba':
                $cadena_sql="SELECT";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" coalesce(reg_promedio,0) PROMEDIO,";
                $cadena_sql.=" reg_motivo MOTIVO,";
                $cadena_sql.=" reg_ano::text||reg_per::text ANIOPERIODO,";
                $cadena_sql.=" reg_asi_3 HISTORICO,";
                $cadena_sql.=" reg_reglamento REGLAMENTO";                
                $cadena_sql.=" FROM";
                $cadena_sql.=" reglamento ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" reg_estado = 'A'";
                $cadena_sql.=" AND reg_cra_cod = '".$variable['proyecto']."'";
                $cadena_sql.=" AND reg_per in (1,3)";
                break;
			   
            case 'consultarPruebas007':
                $cadena_sql="SELECT";
                $cadena_sql.=" reg_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" reg_motivo MOTIVO,";
                $cadena_sql.=" reg_ano::text||reg_per::text ANIOPERIODO,";
                $cadena_sql.=" reg_asi_3 HISTORICO,";
                $cadena_sql.=" reg_reglamento REGLAMENTO";                
                $cadena_sql.=" FROM";
                $cadena_sql.=" reglamento ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" reg_estado = 'A'";
                $cadena_sql.=" AND reg_est_cod = '".$variable."'";
                $cadena_sql.=" AND reg_per in (1,3)";
                $cadena_sql.=" ORDER BY ANIOPERIODO";
                break;
			   
            case 'consultarNotas':
                $cadena_sql="SELECT";
                $cadena_sql.=" not_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" not_asi_cod COD_ASIGNATURA,";
                $cadena_sql.=" not_nota NOTA,";
                $cadena_sql.=" not_ano::text||not_per::text ANIOPERIODO,";
                $cadena_sql.=" not_ano NOTA_ANIO,";
                $cadena_sql.=" not_per NOTA_PERIODO ";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acnot ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" not_est_cod in ";
                $cadena_sql.=" (SELECT reg_est_cod FROM reglamento WHERE ";
                $cadena_sql.=" reg_estado = 'A'";
                $cadena_sql.=" AND reg_cra_cod = '".$variable['proyecto']."'";
                $cadena_sql.=" AND reg_ano = '".$variable['anio']."'";
                $cadena_sql.=" AND reg_per = '".$variable['periodo']."') ";
                $cadena_sql.=" AND not_est_reg='A'";
                $cadena_sql.=" AND not_nota<(select cra_nota_aprob from accra where cra_cod=".$variable['proyecto'].")";
                $cadena_sql.=" AND not_obs not in (10,11)";
                break;

            case 'consultarNotasEstudiante':
                $cadena_sql="SELECT";
                $cadena_sql.=" not_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" not_asi_cod COD_ASIGNATURA,";
                $cadena_sql.=" not_nota NOTA,";
                $cadena_sql.=" not_ano::text||not_per::text ANIOPERIODO,";
                $cadena_sql.=" not_ano NOTA_ANIO,";
                $cadena_sql.=" not_per NOTA_PERIODO ";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acnot ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" not_est_cod = ".$variable['codEstudiante'];
                $cadena_sql.=" AND not_est_reg='A'";
                $cadena_sql.=" AND not_nota<(select cra_nota_aprob from accra where cra_cod=".$variable['proyecto'].")";
                $cadena_sql.=" AND not_obs not in (10,11)";
                break;

            case 'actualizarReglamento':
                $cadena_sql="UPDATE";
                $cadena_sql.=" reglamento";
                $cadena_sql.=" SET reg_porcentaje_plan=".$variable['porcentaje'].",";
                $cadena_sql.=" reg_num_matriculas=".$variable['matriculas'].",";
                $cadena_sql.=" reg_renovaciones_004=".$variable['renovaciones004'].",";
                $cadena_sql.=" reg_porcentaje_004=".$variable['porcentaje004'].",";
                $cadena_sql.=" reg_matriculas_004=".$variable['matriculas004']."";
                $cadena_sql.=" WHERE reg_ano='".$variable['anio']."'";
                $cadena_sql.=" AND reg_per='".$variable['periodo']."'";
                $cadena_sql.=" AND reg_est_cod='".$variable['estudiante']."'";
                $cadena_sql.=" AND reg_cra_cod='".$variable['proyecto']."'";
                break;

            case 'actualizarEstadoEstudiante':
                $cadena_sql="UPDATE";
                $cadena_sql.=" acest";
                $cadena_sql.=" SET est_estado_est='".$variable['estado']."'";
                $cadena_sql.=" WHERE est_cra_cod='".$variable['proyecto']."'";
                $cadena_sql.=" AND est_cod='".$variable['estudiante']."'";
                break;

            case 'insertarInicioEvento':
                $cadena_sql=" INSERT";
                $cadena_sql.=" INTO ACCALEVENTOS";
                $cadena_sql.=" (";
                $cadena_sql.=" ACE_COD_EVENTO,";
                $cadena_sql.=" ACE_CRA_COD,";
                $cadena_sql.=" ACE_FEC_INI,";
                $cadena_sql.=" ACE_TIP_CRA,";
                $cadena_sql.=" ACE_DEP_COD,";
                $cadena_sql.=" ACE_ANIO,";
                $cadena_sql.=" ACE_PERIODO,";
                $cadena_sql.=" ACE_ESTADO,";
                $cadena_sql.=" ACE_HABILITAR_EX";
                $cadena_sql.=" )";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.=" ".$variable['evento'].",";
                $cadena_sql.=" ".$variable['proyecto'].",";
                $cadena_sql.=" CURRENT_TIMESTAMP,";
                $cadena_sql.=" ".$variable['tipo_proyecto'].",";
                $cadena_sql.=" (SELECT cra_dep_cod FROM accra WHERE cra_cod=".$variable['proyecto']."),";
                $cadena_sql.=" ".$variable['anio'].",";
                $cadena_sql.=" ".$variable['periodo'].",";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" 'N'";
                $cadena_sql.=" )";
                break;
        
            case 'insertarFinEvento':
                $cadena_sql=" UPDATE ACCALEVENTOS";
                $cadena_sql.=" SET ACE_FEC_FIN=CURRENT_TIMESTAMP";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ACE_COD_EVENTO=".$variable['evento'];
                $cadena_sql.=" AND ACE_CRA_COD=".$variable['proyecto'];
                $cadena_sql.=" AND ACE_ANIO=".$variable['anio'];
                $cadena_sql.=" AND ACE_PERIODO=".$variable['periodo'];
                break;			
            
            case 'consultarProyectosCierre':
                $cadena_sql="select distinct est_cra_cod PROYECTO";
                $cadena_sql.=" from acesthis";
                $cadena_sql.=" where est_ano=".$variable['anio'];
                $cadena_sql.=" and est_per=".$variable['periodo'];
                break;
            
            case 'consultarPorcentajePlan':
                $cadena_sql=" SELECT DISTINCT est_cra_cod PROYECTO,";
                $cadena_sql.=" est_pen_nro PLAN,";
                $cadena_sql.=" est_ind_cred TIPO_ESTUDIANTE,";
                $cadena_sql.=" plan_creditos CREDITOS_PLAN,";
                $cadena_sql.=" coalesce(sum(not_cred),0) CREDITOS_ESTUDIANTE,";
                $cadena_sql.=" coalesce(count(not_asi_cod),0) ESPACIOS_ESTUDIANTE";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" INNER JOIN acest ON est_cod=not_est_cod";
                $cadena_sql.=" AND est_cra_cod=not_cra_cod";
                $cadena_sql.=" LEFT OUTER JOIN acplanestudio ON est_cra_cod=plan_cra_cod";
                $cadena_sql.=" AND est_pen_nro=plan_pen_nro";
                $cadena_sql.=" WHERE not_est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND trim(not_est_reg)='A'";
                $cadena_sql.=" AND (not_nota>=(SELECT cra_nota_aprob FROM accra WHERE cra_cod=not_cra_cod)";
                $cadena_sql.=" OR not_obs IN (19,22,24))";
                $cadena_sql.=" AND not_ano::text||not_per::text<'20113'";
                $cadena_sql.=" GROUP BY est_cra_cod,est_pen_nro,est_ind_cred,plan_creditos";
                break;
            
            case 'consultarCreditosPlan':
                $cadena_sql=" SELECT DISTINCT est_cra_cod PROYECTO,";
                $cadena_sql.=" est_pen_nro PLAN,";
                $cadena_sql.=" est_ind_cred TIPO_ESTUDIANTE,";
                $cadena_sql.=" plan_creditos CREDITOS_PLAN";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" LEFT OUTER JOIN acplanestudio ON est_cra_cod=plan_cra_cod";
                $cadena_sql.=" AND est_pen_nro=plan_pen_nro";
                $cadena_sql.=" WHERE est_cod=".$variable['codEstudiante'];
                break;

            case 'consultarPorcentajeTotalPlan':
                $cadena_sql=" SELECT DISTINCT est_cra_cod PROYECTO,";
                $cadena_sql.=" est_pen_nro PLAN,";
                $cadena_sql.=" est_ind_cred TIPO_ESTUDIANTE,";
                $cadena_sql.=" plan_creditos CREDITOS_PLAN,";
                $cadena_sql.=" coalesce(sum(not_cred),0) CREDITOS_ESTUDIANTE,";
                $cadena_sql.=" coalesce(count(not_asi_cod),0) ESPACIOS_ESTUDIANTE";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" INNER JOIN acest ON est_cod=not_est_cod";
                $cadena_sql.=" AND est_cra_cod=not_cra_cod";
                $cadena_sql.=" LEFT OUTER JOIN acplanestudio ON est_cra_cod=plan_cra_cod";
                $cadena_sql.=" AND est_pen_nro=plan_pen_nro";
              	$cadena_sql.=" WHERE not_est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND trim(not_est_reg)='A'";
                $cadena_sql.=" AND (not_nota>=(SELECT cra_nota_aprob FROM accra WHERE cra_cod=not_cra_cod)";
                $cadena_sql.=" OR not_obs IN (19,22,24))";
                $cadena_sql.=" GROUP BY est_cra_cod,est_pen_nro,est_ind_cred,plan_creditos";	 
                break;

            case 'consultarMatriculasEstudiante':
                $cadena_sql=" SELECT DISTINCT not_ano,not_per";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" WHERE not_est_cod=$variable";
                $cadena_sql.=" AND trim(not_est_reg)='A'";
                $cadena_sql.=" AND not_per IN (1,3)";
                $cadena_sql.=" ORDER BY not_ano, not_per";
                break;
            
             case 'consultarTipoProyecto':
                 $cadena_sql="SELECT cra_tip_cra TIPO,";
                 $cadena_sql.=" tra_nivel NIVEL";
                 $cadena_sql.=" FROM accra";
                 $cadena_sql.=" INNER JOIN actipcra on cra_tip_cra=tra_cod";
                 $cadena_sql.=" WHERE cra_cod=".$variable;
                 break;

             case 'consultarTablaPermanencia':
                $cadena_sql=" SELECT per_num_renovaciones RENOVACIONES";
                $cadena_sql.=" FROM acpermanencia";
                $cadena_sql.=" WHERE per_porcentaje_min<=".$variable['porcentaje'];
                $cadena_sql.=" AND per_porcentaje_max>".$variable['porcentaje'];
                $cadena_sql.=" AND per_tipo_carrera=".$variable['tipo_proyecto'];
                $cadena_sql.=" AND per_acuerdo=".$variable['acuerdo'];
                $cadena_sql.=" AND trim(per_estado)='A'";
                $cadena_sql.=" ORDER BY per_num_renovaciones";
                 break;
             
             case 'periodo_activo':
                            //El periodo se establece a P para los cierres de los posgrados posterior a la fecha del pregrado 26/12/2013
                            //El período se cambia de P a A para el proceso de cierre de pregrado
                            //Este período se utiliza para recalcular el reglamento de los estudiantes. Se registra en MySQL.
               $cadena_sql="SELECT";
               $cadena_sql.=" sga_ano ANO,";
               $cadena_sql.=" sga_periodo PERIODO";
               $cadena_sql.=" FROM";
               $cadena_sql.=" sga_periodo_recalcular ";
               $cadena_sql.=" WHERE";
               $cadena_sql.=" sga_estado='A'";
               break;
           
               case 'consultarDatosEstudiante':
                $cadena_sql=" SELECT est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_pen_nro PLAN_ESTUDIANTE,";
                $cadena_sql.=" est_ind_cred INDICA_CREDITOS";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod=".$variable;
                break;
            
            case 'consultarDatosReglamento':
                $cadena_sql=" SELECT A.est_cod CODIGO,";
                $cadena_sql.=" A.est_nombre NOMBRE_ESTUDIANTE,";
                $cadena_sql.=" coalesce(H.est_estado,'-') ESTADO_ANTERIOR,";
                $cadena_sql.=" coalesce(A.est_estado_est,'-') ESTADO_ACTUAL,";
                $cadena_sql.=" coalesce(A.est_acuerdo,'0') ACUERDO,";
                $cadena_sql.=" coalesce(reg_motivo,0) MOTIVO_PRUEBA,";
                $cadena_sql.=" coalesce(reg_asi_3,0) ESP_ACAD_REPRO,";
                $cadena_sql.=" coalesce(reg_veces,0) MAX_VECES_REPROBADO,";
                $cadena_sql.=" coalesce(reg_promedio,0) PROMEDIO,";
                $cadena_sql.=" coalesce(reg_causal_exclusion,0) CAUSAL_EXCLUSION,";
                $cadena_sql.=" coalesce(reg_porcentaje_plan,0) PORCENTAJE_PLAN,";
                $cadena_sql.=" coalesce(reg_espacio_veces,'0') ESPACIOS_REPROBADOS";
                $cadena_sql.=" FROM acest A";
                $cadena_sql.=" INNER JOIN reglamento ON A.est_cod=reg_est_cod AND reg_cra_cod=A.est_cra_cod";
                $cadena_sql.=" INNER JOIN acesthis H ON A.est_cod=H.est_cod AND H.est_ano=reg_ano AND H.est_per=reg_per";
                $cadena_sql.=" INNER JOIN acestado EA ON A.est_estado_est=EA.estado_cod";
                $cadena_sql.=" INNER JOIN acestado EH ON H.est_estado=EH.estado_cod";
                $cadena_sql.=" WHERE A.est_cod in (".$variable['estudiantes'].") ";
                $cadena_sql.=" AND reg_ano=".$variable['anio'];
                $cadena_sql.=" AND reg_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY reg_reglamento,A.est_cod";                                
                break;

       }
	return $cadena_sql;
   }
}
?>
