<?php
/**
 * SQL adminInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionAutomaticaCoordinador
 *
 * @package InscripcionAutomaticaCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 14/01/2013
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
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
 * Clase sql_adminInscripcionAutomaticaCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionAutomaticaCoordinador
 *
 * @package InscripcionAutomaticaCoordinador
 * @subpackage Admin
 */
class sql_adminInscripcionAutomaticaCoordinador extends sql {

  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    
    /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @param array $variable2 Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($tipo, $variable="", $variable2="") {
        switch ($tipo) {
            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'proyectos_curriculares':

                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN actipcra on cra_tip_cra=tra_cod";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND cra_estado LIKE '%A%'";
                $cadena_sql.=" AND tra_cod_nivel =1";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'datos_coordinador':
                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN actipcra on cra_tip_cra=tra_cod";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND cra_estado LIKE '%A%'";
                $cadena_sql.=" AND tra_cod_nivel =1";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'consultarInscripcionAutomatica':
                $cadena_sql = "SELECT ace_cod_evento EVENTO, ace_cra_cod PROYECTO, ace_fec_ini INICIO, ace_fec_fin FIN";
                $cadena_sql.=" FROM accaleventos";
                $cadena_sql.=" WHERE ace_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=14";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='A'";
                break;

            case 'consultarInscripcionesProyecto':
                $cadena_sql = "SELECT count(*) TOTAL";
                $cadena_sql.=" FROM acinspre";
                $cadena_sql.=" WHERE ins_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                break;
            
            case 'eventoPreinscripcionAutomatica':
                $cadena_sql = "SELECT ace_cod_evento EVENTO, ace_cra_cod PROYECTO, ace_fec_ini INICIO, ace_fec_fin FIN";
                $cadena_sql.=" FROM accaleventos";
                $cadena_sql.=" WHERE ace_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND ace_cod_evento=90";
                $cadena_sql.=" AND ace_anio=".$variable['ano'];
                $cadena_sql.=" AND ace_periodo=".$variable['periodo'];
                $cadena_sql.=" AND ace_estado='A'";
                $cadena_sql.=" AND sysdate>ace_fec_ini";
                $cadena_sql.=" AND sysdate<ace_fec_fin";
                break;

            case 'consultarEstudiantesClasificados':
                $cadena_sql="SELECT cle_id idClasificacion,";
                $cadena_sql.=" cle_codEstudiante codEstudiante,";
                $cadena_sql.=" cle_codProyectoCurricular codProyecto,";
                $cadena_sql.=" cle_clasificacion clasificacion,";
                $cadena_sql.=" cle_tipoEstudiante tipo ";
                $cadena_sql.=" FROM sga_clasificacion_estudiantes";
                $cadena_sql.=" WHERE cle_codProyectoCurricular=".$variable;
                //$cadena_sql.=" AND cle_codEstudiante=20022025044"; 
                //$cadena_sql.=" AND cle_codEstudiante=20102025105"; 
                //$cadena_sql.=" AND cle_codEstudiante=20002005071"; 
                //$cadena_sql.=" AND cle_codEstudiante=8920585"; 
                //$cadena_sql.=" AND cle_codEstudiante=20081005002"; 
                //$cadena_sql.=" AND cle_codEstudiante=20041005116"; 
                $cadena_sql.=" ORDER BY cle_clasificacion, cle_id"; 
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
                
            case 'consultarPreinscripcionesDemandaProyecto':
                $cadena_sql = "SELECT count(*)";
                $cadena_sql.=" FROM acinsdemanda";
                $cadena_sql.=" WHERE insde_ano=" . $variable['ano'];
                $cadena_sql.=" AND insde_per=" . $variable['periodo'];
                $cadena_sql.=" AND insde_cra_cod=" . $variable['codProyecto'];
                $cadena_sql.=" AND insde_estado LIKE '%A%'";
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
