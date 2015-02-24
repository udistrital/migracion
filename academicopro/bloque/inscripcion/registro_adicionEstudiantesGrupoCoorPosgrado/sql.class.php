<?php
/**
 * SQL adminInscripcionGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionGrupoCoordinadorPosgrado
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
/**
 * Clase sql_adminInscripcionGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class sql_registroAdicionEstudiantesGrupoCoorPosgrado extends sql {

  public $configuracion;

  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }

  /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {


              //Oracle
              case 'periodo_activo':

                $cadena_sql="SELECT";
                $cadena_sql.=" ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" ACASPERI ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";

                break;

            case 'buscarDatosEstudiantes':

                $cadena_sql="SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod PROYECTO,";
                $cadena_sql.=" est_pen_nro PLANESTUDIOS,";
                $cadena_sql.=" est_estado_est ESTADO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acest";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" est_cod=".$variable['codEstudiante'];
            break;

            //Oracle
              case 'buscarEspaciosAcademicos':
                  $cadena_sql=" SELECT";
                  $cadena_sql.=" pen_asi_cod CODIGO,";
                  $cadena_sql.=" asi_nombre NOMBRE,";
                  $cadena_sql.=" pen_cre CREDITOS,";
                  $cadena_sql.=" pen_nro_ht HTD,";
                  $cadena_sql.=" pen_nro_hp HTC,";
                  $cadena_sql.=" pen_nro_aut HTA,";
                  $cadena_sql.=" pen_ind_ele ELECTIVO";
                  $cadena_sql.=" FROM acpen";
                  $cadena_sql.=" INNER JOIN acasi ON pen_asi_cod= asi_cod";
                  $cadena_sql.=" WHERE pen_cra_cod=".$variable['codProyecto'];
                  $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                  $cadena_sql.=" AND pen_asi_cod=".$variable['codEspacio'];

                break;


        }
        return $cadena_sql;
    }
}
?>
