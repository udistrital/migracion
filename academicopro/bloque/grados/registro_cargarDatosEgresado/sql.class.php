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
 * Clase sql_registroCargarDatosEgresado
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque registro_actualizarDatosGraduando
 *
 * @package recibos
 * @subpackage Admin
 */
class sql_registroCargarDatosEgresado extends sql {

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
             
            case 'consultar_estudiante':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod          CODIGO, ";
                $cadena_sql.=" est_cra_cod      COD_PROYECTO, ";
                $cadena_sql.=" est_nombre       NOMBRE, ";
                $cadena_sql.=" SUBSTR(est_nombre,INSTR(est_nombre,' ',1,2)+1) NOMBRE, ";
                $cadena_sql.=" SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1) APELLIDO, ";
                $cadena_sql.=" est_nro_iden     IDENTIFICACION, ";
                $cadena_sql.=" est_tipo_iden    TIPO_IDENTIFICACION, ";
                $cadena_sql.=" est_estado_est   ESTADO, ";
                $cadena_sql.=" est_direccion    DIRECCION, ";
                $cadena_sql.=" est_telefono     TELEFONO, ";
                $cadena_sql.=" est_sexo         SEXO,";
                $cadena_sql.=" eot_email        CORREO,";
                $cadena_sql.=" cra_dep_cod      COD_FACULTAD";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" LEFT OUTER JOIN acestotr ON est_cod=eot_cod";
                $cadena_sql.=" LEFT OUTER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" LEFT OUTER JOIN gedep ON cra_dep_cod=dep_cod";
                $cadena_sql.=" WHERE est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND est_nro_iden=".$variable['documento'];
                break;
            
            case 'datos_egresado':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" egr_est_cod,";
                $cadena_sql.=" egr_nombre,";
                $cadena_sql.=" egr_cra_cod,";
                $cadena_sql.=" egr_nro_iden,";
                $cadena_sql.=" egr_tip_iden,";
                $cadena_sql.=" egr_lug_exp_iden,";
                $cadena_sql.=" egr_sexo,";
                $cadena_sql.=" egr_direccion_casa,";
                $cadena_sql.=" egr_telefono_casa,";
                $cadena_sql.=" egr_movil,";
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
                $cadena_sql.=" FROM acegresado ";
                $cadena_sql.=" WHERE egr_est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND egr_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND egr_nro_iden=".$variable['documento'];
                
            break;

         //Oracle
         case "actualizar_egresado":
                $cadena_sql=" UPDATE acegresado ";
                $cadena_sql.=" SET ".$variable['listaCambios'];
                $cadena_sql.=" WHERE egr_est_cod ='".$variable['codEstudiante']."'";
                break;
            
         case "actualizar_estado":
                $cadena_sql=" UPDATE acest ";
                $cadena_sql.=" SET est_estado_est='".$variable['estado']."' ";
                $cadena_sql.=" WHERE est_cod ='".$variable['codEstudiante']."'";
                $cadena_sql.=" AND est_cra_cod ='".$variable['codProyecto']."'";
                break;		


          case 'existe_registro_egresado':
                $cadena_sql="SELECT count(*) ";
                $cadena_sql.=" FROM acegresado";
                $cadena_sql.=" WHERE egr_est_cod = '".$variable['codEstudiante']."' ";
                $cadena_sql.=" AND egr_cra_cod = '".$variable['codProyecto']."' ";
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
                $cadena_sql.=" ('".$variable['codProyecto']."',";
                $cadena_sql.=" '".$variable['nombreEstudiante']."',";
                $cadena_sql.=" '".(isset($variable['identificacion'])?$variable['identificacion']:'')."',";
                $cadena_sql.=" '".$variable['tipoIdentificacion']."',";
                $cadena_sql.=" '".$variable['lugarExpedicion']."',";
                $cadena_sql.=" '".mb_strtoupper($variable['genero'],'UTF-8')."',";
                $cadena_sql.=" '".(isset($variable['nombreTrabajoGrado'])?$variable['nombreTrabajoGrado']:'')."',";
                $cadena_sql.=" '".(isset($variable['nombreDirector'])?$variable['nombreDirector']:'')."',";
                $cadena_sql.=" '".(isset($variable['nombreDirector2'])?$variable['nombreDirector2']:'')."',";
                $cadena_sql.=" '".(isset($variable['actaSustentacion'])?$variable['actaSustentacion']:'')."',";
                if((isset($variable['fechaGrado'])?$variable['fechaGrado']:'')){
                    $cadena_sql.=" to_date('".$variable['fechaGrado']."','YYYY/MM/DD'),";
                }else{
                     $cadena_sql.=" null,";
                }
                $cadena_sql.=" '".(isset($variable['actaGrado'])?$variable['actaGrado']:'')."',";
                if(is_numeric((isset($variable['nota'])?$variable['nota']:''))){
                    $cadena_sql.=" '".$variable['nota']."',";
                }else{
                    $cadena_sql.=" '',";
                }
                $cadena_sql.=" '".(isset($variable['libro'])?$variable['libro']:'')."',";
                $cadena_sql.=" '".(isset($variable['folio'])?$variable['folio']:'')."',";
                $cadena_sql.=" '".(isset($variable['registroDiploma'])?$variable['registroDiploma']:'')."',";
                $cadena_sql.=" '".(isset($variable['tituloObtenido'])?$variable['tituloObtenido']:'')."',";
                $cadena_sql.=" '".(isset($variable['rector'])?$variable['rector']:'')."',";
                $cadena_sql.=" '".(isset($variable['secretarioAcademico'])?$variable['secretarioAcademico']:'')."',";
                $cadena_sql.=" '".$variable['codEstudiante']."',";
                $cadena_sql.=" '".(isset($variable['direccion'])?$variable['direccion']:'')."',";
                $cadena_sql.=" '".(isset($variable['telefonoFijo'])?$variable['telefonoFijo']:'')."',";
                $cadena_sql.=" '".(isset($variable['correoElectronico'])?$variable['correoElectronico']:'')."',";
                $cadena_sql.=" '".(isset($variable['empresa'])?$variable['empresa']:'')."',";
                $cadena_sql.=" '".(isset($variable['direccionEmpresa'])?$variable['direccionEmpresa']:'')."',";
                $cadena_sql.=" '".(isset($variable['telefonoEmpresa'])?$variable['telefonoEmpresa']:'')."',";
                $cadena_sql.=" '".(isset($variable['telefonoCelular'])?$variable['telefonoCelular']:'')."',";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" '".(isset($variable['mencion'])?$variable['mencion']:'')."',";
                $cadena_sql.=" 'L')";                    
                break;
                
            case "consultarSecretarios":
                $cadena_sql=" SELECT sec_cod,";
                $cadena_sql.=" trim(sec_nombre)||' '||trim(sec_apellido) NOMBRE,";
                $cadena_sql.=" nvl(to_char(sec_fecha_desde,'YYYYMMDD'),'') FECHA_DESDE,";
                $cadena_sql.=" nvl(to_char(sec_fecha_hasta,'YYYYMMDD'),to_char(sysdate,'YYYYMMDD')) FECHA_HASTA";
                $cadena_sql.=" FROM acsecretario";
                $cadena_sql.=" WHERE sec_dep_cod=".$variable;
                $cadena_sql.=" ORDER BY sec_estado,sec_fecha_desde desc";
                break;
            
            case "consultarRectores":
                $cadena_sql=" SELECT rec_cod,";
                $cadena_sql.=" trim(rec_nombre)||' '||trim(rec_apellido) NOMBRE,";
                $cadena_sql.=" nvl(to_char(rec_fecha_desde,'YYYYMMDD'),'') FECHA_DESDE,";
                $cadena_sql.=" nvl(to_char(rec_fecha_hasta,'YYYYMMDD'),to_char(sysdate,'YYYYMMDD')) FECHA_HASTA";
                $cadena_sql.=" FROM acrector";
                $cadena_sql.=" ORDER BY rec_estado,rec_fecha_desde desc";
                break;
            
            case "consultarTituloGrado":
                $cadena_sql=" SELECT tit_cod COD_TITULO,";
                $cadena_sql.=" tit_nombre||' ('||decode(tit_sexo,'M','MASCULINO','F','FEMENINO')||')' NOMBRE_TITULO,";
                $cadena_sql.=" tit_sexo SEXO";
                $cadena_sql.=" from actitulo";
                $cadena_sql.=" WHERE tit_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND tit_sexo='".$variable['sexo']."'";
                break;
            
        }
        return $cadena_sql;
    }
}
?>
