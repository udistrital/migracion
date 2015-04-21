<?php
/**
 * SQL registro_actualizarDatosGraduando
 *
 * Esta clase se encarga de crear las sentencias sql del bloque registro_actualizarDatosGraduando
 *
* @package recibos
 * @subpackage registro_actualizarDatosGraduando
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 13/03/2014
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
 * Clase sql_registroActualizarIntensidadHorariaEgresado
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque registro_actualizarDatosGraduando
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_registroActualizarIntensidadHorariaEgresado extends sql {

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
             
            case 'datos_egresado':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" egr_est_cod,";
                $cadena_sql.=" est_nombre,";
                $cadena_sql.=" egr_nombre,";
                $cadena_sql.=" est_cra_cod,";
                $cadena_sql.=" egr_cra_cod,";
                $cadena_sql.=" est_nro_iden,";
                $cadena_sql.=" egr_nro_iden,";
                $cadena_sql.=" est_tipo_iden,";
                $cadena_sql.=" egr_tip_iden,";
                $cadena_sql.=" egr_lug_exp_iden,";
                $cadena_sql.=" est_nro_dis_militar,";
                $cadena_sql.=" est_lib_militar,";
                $cadena_sql.=" est_sexo,";
                $cadena_sql.=" egr_sexo,";
                $cadena_sql.=" est_estado_est,";
                $cadena_sql.=" est_direccion,";
                $cadena_sql.=" egr_direccion_casa,";
                $cadena_sql.=" eot_cod_mun_res,";
                $cadena_sql.=" est_telefono,";
                $cadena_sql.=" egr_telefono_casa,";
                $cadena_sql.=" eot_tel_cel,";
                $cadena_sql.=" egr_movil,";
                $cadena_sql.=" eot_email,";
                $cadena_sql.=" egr_email,";
                $cadena_sql.=" egr_empresa,";
                $cadena_sql.=" egr_direccion_empresa,";
                $cadena_sql.=" egr_telefono_empresa,";
                $cadena_sql.=" egr_trabajo_grado,";
                $cadena_sql.=" egr_director_trabajo,";
                $cadena_sql.=" egr_director_trabajo_2,";
                $cadena_sql.=" egr_acta_sustentacion,";
                $cadena_sql.=" egr_nota,";
                $cadena_sql.=" egr_acta_grado,";
                $cadena_sql.=" egr_caracter_nota,";
                $cadena_sql.=" to_char(egr_fecha_grado,'YYYY/mm/dd') egr_fecha_grado,";
                $cadena_sql.=" egr_libro,";
                $cadena_sql.=" egr_folio,";
                $cadena_sql.=" egr_reg_diploma,";
                $cadena_sql.=" egr_titulo,";
                $cadena_sql.=" egr_rector,";
                $cadena_sql.=" egr_secretario";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" LEFT OUTER JOIN acegresado ON est_cod=egr_est_cod";
                $cadena_sql.=" LEFT OUTER JOIN acestotr ON est_cod=eot_cod";
                $cadena_sql.=" WHERE est_cod=".$variable;                                              
            break;

         //Oracle
         case "actualizar_egresado":
                $cadena_sql=" UPDATE acegresado ";
                $cadena_sql.=" SET ".$variable['listaCambios'];
                $cadena_sql.=" WHERE egr_est_cod ='".$variable['codEstudiante']."'";             
                break;

         case "actualizar_estudiante":
                $cadena_sql=" UPDATE acest ";
                $cadena_sql.=" SET ".$variable['listaCambios'];
                $cadena_sql.=" WHERE est_cod ='".$variable['codEstudiante']."'";

                break;		

    
         case "actualizar_estudianteOtros":
                $cadena_sql=" UPDATE acestotr ";
                $cadena_sql.=" SET ".$variable['listaCambios'];
                $cadena_sql.=" WHERE eot_cod ='".$variable['codEstudiante']."'";

                break;		

    
        }
        return $cadena_sql;
    }
}
?>
