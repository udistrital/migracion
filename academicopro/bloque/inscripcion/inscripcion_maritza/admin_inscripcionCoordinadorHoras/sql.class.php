<?php
/**
 * SQL adminInscripcionCoordinadorHoras
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinadorHoras
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 31/05/2011
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
 * Clase sql_adminInscripcionCoordinadorHoras
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinadorHoras
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class sql_adminInscripcionCoordinadorHoras extends sql {
  private $configuracion;

  function __contruct($configuracion) {
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
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" ctp_pen_nro PLAN,";
                $cadena_sql.=" tra_nivel NIVEL,";
                $cadena_sql.=" tra_cod_nivel CODIGONIVEL,";
                $cadena_sql.=" ctp_ind_cred CREDITOS";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=".$variable;
                //$cadena_sql.=" AND CTP_IND_CRED LIKE '%S%'";
                $cadena_sql.=" AND TRA_COD_NIVEL IN (1,2,3,4)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
                //$cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
                $cadena_sql.=" ORDER BY 5, 1, 3";

                break;

        }
        return $cadena_sql;
    }
}

?>
