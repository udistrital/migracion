<?php
/**
 * SQL adminInscripcionCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinadorPosgrado
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 10/03/2011
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
 * Clase sql_adminInscripcionCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinadorPosgrado
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class sql_adminInscripcionCoordinadorPosgrado extends sql {
  private $configuracion;

  function  __construct($configuracion) {
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
    function cadena_sql($tipo, $variable="") {
        switch ($tipo) {

            case 'proyectos_curriculares':

                $cadena_sql = "SELECT DISTINCT cra_cod PROYECTO,";
                $cadena_sql.=" cra_abrev NOMBRE,";
                $cadena_sql.=" ctp_pen_nro PLAN,";
                $cadena_sql.=" tra_nivel NIVEL,";
                $cadena_sql.=" coalesce(tra_cod_nivel,0) CODIGONIVEL,";
                $cadena_sql.=" ctp_ind_cred CREDITOS";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=".$variable;
                //$cadena_sql.=" AND (CTP_IND_CRED NOT LIKE '%N%'OR TRA_COD_NIVEL NOT IN (2,3,4)";
                //$cadena_sql.=" AND CTP_IND_CRED LIKE '%S%'";
//                $cadena_sql.=" AND TRA_COD_NIVEL IN (1,2,3,4)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
                //$cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
                $cadena_sql.=" ORDER BY CODIGONIVEL, PROYECTO, PLAN";
                break;
             
            case 'proyectos_curriculares_coordinador':

                $cadena_sql = "SELECT DISTINCT cra_cod PROYECTO,";
                $cadena_sql.=" cra_abrev NOMBRE,";
                $cadena_sql.=" ctp_pen_nro PLAN,";
                $cadena_sql.=" tra_nivel NIVEL,";
                $cadena_sql.=" coalesce(tra_cod_nivel,0) CODIGONIVEL,";
                $cadena_sql.=" ctp_ind_cred CREDITOS";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=".$variable;
                //$cadena_sql.=" AND (CTP_IND_CRED NOT LIKE '%N%'OR TRA_COD_NIVEL NOT IN (2,3,4)";
                //$cadena_sql.=" AND CTP_IND_CRED LIKE '%S%'";
//                $cadena_sql.=" AND TRA_COD_NIVEL IN (1,2,3,4)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
                //$cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
                $cadena_sql.=" ORDER BY CODIGONIVEL, PROYECTO, PLAN";
                break;
             
                case 'proyectos_curriculares_asistente':
                
                	$cadena_sql = "SELECT DISTINCT cra_cod PROYECTO,";
                	$cadena_sql.=" cra_abrev NOMBRE,";
                	$cadena_sql.=" ctp_pen_nro PLAN,";
                	$cadena_sql.=" tra_nivel NIVEL,";
                	$cadena_sql.=" coalesce(tra_cod_nivel,0) CODIGONIVEL,";
                	$cadena_sql.=" ctp_ind_cred CREDITOS";
                	$cadena_sql.=" FROM ACCRA";
                	$cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                	$cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                	$cadena_sql.=" INNER JOIN GEUSUWEB  ON CRA_COD = USUWEB_CODIGO_DEP";
                	$cadena_sql.=" WHERE USUWEB_CODIGO=".$variable;
                	$cadena_sql.="  AND USUWEB_ESTADO = 'A' ";
                        $cadena_sql.="  AND ((usuweb_fecha_fin >= current_timestamp AND usuweb_tipo_vinculacion=9) or usuweb_tipo_vinculacion=10) ";                	//$cadena_sql.=" AND (CTP_IND_CRED NOT LIKE '%N%'OR TRA_COD_NIVEL NOT IN (2,3,4)";
                	//$cadena_sql.=" AND CTP_IND_CRED LIKE '%S%'";
                	//                $cadena_sql.=" AND TRA_COD_NIVEL IN (1,2,3,4)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
                	//$cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
                	$cadena_sql.=" ORDER BY CODIGONIVEL, PROYECTO, PLAN";
                	break;

        }
        return $cadena_sql;
    }
}

?>
