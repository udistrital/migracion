<?php
/**
 * SQL registro_registrarDatosGraduando
 *
 * Esta clase se encarga de crear las sentencias sql del bloque registro_registrarDatosGraduando
 *
* @package recibos
 * @subpackage registro_registrarDatosGraduando
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
 * Clase sql_registro_registrarDatosGraduando
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque registro_registrarDatosGraduando
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_registro_registrarDatosGraduando extends sql {

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
                $cadena_sql.=" egr_trabajo_grado,";
                $cadena_sql.=" egr_director_trabajo,";
                $cadena_sql.=" egr_director_trabajo_2,";
                $cadena_sql.=" egr_acta_sustentacion,";
                $cadena_sql.=" egr_nota,";
                $cadena_sql.=" egr_acta_grado,";
                $cadena_sql.=" egr_caracter_nota,";
                $cadena_sql.=" to_char(egr_fecha_grado,'YYYY-mm-dd') egr_fecha_grado,";
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

        case 'consultarDatosEgresado':

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.=" FROM acegresado";
                $cadena_sql.=" WHERE egr_est_cod = '".$variable['codEstudiante']."' ";
                $cadena_sql.=" AND egr_cra_cod = '".$variable['proyecto']."' ";

                break;      

        case 'registrarDatosEgresado':
                $cadena_sql=" INSERT INTO acegresado ";
                $cadena_sql.=" (EGR_CRA_COD,";
                $cadena_sql.=" EGR_NOMBRE,";
                $cadena_sql.=" EGR_NRO_IDEN,";
                $cadena_sql.=" EGR_TIP_IDEN,";
                $cadena_sql.=" EGR_LUG_EXP_IDEN,";
                $cadena_sql.=" EGR_SEXO,";
                $cadena_sql.=" EGR_TRABAJO_GRADO,";
                $cadena_sql.=" EGR_DIRECTOR_TRABAJO,";
                $cadena_sql.=" EGR_DIRECTOR_TRABAJO_2,";
                $cadena_sql.=" EGR_ACTA_SUSTENTACION,";
                $cadena_sql.=" EGR_FECHA_GRADO,";
                $cadena_sql.=" EGR_ACTA_GRADO,";
                $cadena_sql.=" EGR_NOTA,";
                $cadena_sql.=" EGR_LIBRO,";
                $cadena_sql.=" EGR_FOLIO,";
                $cadena_sql.=" EGR_REG_DIPLOMA,";
                $cadena_sql.=" EGR_TITULO,";
                $cadena_sql.=" EGR_RECTOR,";
                $cadena_sql.=" EGR_SECRETARIO,";
                $cadena_sql.=" EGR_EST_COD,";
                $cadena_sql.=" EGR_DIRECCION_CASA,";
                $cadena_sql.=" EGR_TELEFONO_CASA,";
                $cadena_sql.=" EGR_EMAIL,";
                $cadena_sql.=" EGR_EMPRESA,";
                $cadena_sql.=" EGR_DIRECCION_EMPRESA,";
                $cadena_sql.=" EGR_TELEFONO_EMPRESA,";
                $cadena_sql.=" EGR_MOVIL,";
                $cadena_sql.=" EGR_ESTADO,";
                $cadena_sql.=" EGR_CARACTER_NOTA,";
                $cadena_sql.=" EGR_TIPO_CARGA) ";
                $cadena_sql.=" values ";
                $cadena_sql.=" (".$variable['proyectoCurricular'].",";
                $cadena_sql.=" '".$variable['nombreEstudiante']." ".$variable['apellidoEstudiante']."',";
                $cadena_sql.=" ".$variable['identificacion'].",";
                $cadena_sql.=" '".$variable['tipoIdentificacion']."',";
                $cadena_sql.=" ".$variable['lugarExpedicion'].",";
                $cadena_sql.=" '".$variable['genero']."',";
                $cadena_sql.=" '".$variable['nombreTrabajoGrado']."',";
                $cadena_sql.=" '".$variable['nombreDirector']."',";
                $cadena_sql.=" '".$variable['nombreDirector2']."',";
                $cadena_sql.=" '".$variable['actaSustentacion']."',";
                $cadena_sql.=" to_date('".$variable['fechaGrado']."','YYYY/MM/DD'),";    
                $cadena_sql.=" '".$variable['actaGrado']."',";                
                $cadena_sql.=" ".$variable['nota'].",";
                $cadena_sql.=" '".$variable['libro']."',";
                $cadena_sql.=" '".$variable['folio']."',";
                $cadena_sql.=" '".$variable['registroDiploma']."',";
                $cadena_sql.=" ".$variable['tituloObtenido'].",";
                $cadena_sql.=" ".$variable['rector'].",";
                $cadena_sql.=" ".$variable['secretarioAcademico'].",";
                $cadena_sql.=" ".$variable['codEstudiante'].",";
                $cadena_sql.=" '".$variable['direccion']."',";
                $cadena_sql.=" '".$variable['telefonoFijo']."',";
                $cadena_sql.=" '".$variable['correoElectronico']."',";
                $cadena_sql.=" '".$variable['empresa']."',";
                $cadena_sql.=" '".$variable['direccionEmpresa']."',";
                $cadena_sql.=" '".$variable['telefonoEmpresa']."',";
                $cadena_sql.=" '".$variable['telefonoCelular']."',";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" ".$variable['mencion'].",";
                $cadena_sql.=" '')";                  
                break;
        }
        return $cadena_sql;
    }
}
?>
