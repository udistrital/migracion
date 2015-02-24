<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroaprobarSolicitudUsuario extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
            case 'detalle_solicitud_cuenta':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" sol_id,";
                    $cadena_sql.=" sol_nro_iden_solicitante,";
                    $cadena_sql.=" emp_nombre       SOLICITANTE,";
                    $cadena_sql.=" car_nombre       CARGO,";
                    $cadena_sql.=" sol_codigo,";
                    $cadena_sql.=" (UWD_APELLIDOS||' '||UWD_NOMBRES)  NOMBRE_USUARIO,";
                    $cadena_sql.=" uwd_tipo_iden ,";
                    $cadena_sql.=" tdo_nombre    TIPO_IDENTIFICACION,";
                    $cadena_sql.=" uwd_correo_electronico CORREO_ELECTRONICO ,";
                    $cadena_sql.=" uwd_telefono     TELEFONO ,";
                    $cadena_sql.=" uwd_celular      CELULAR ,";
                    $cadena_sql.=" uwd_direccion    DIRECCION ,";
                    $cadena_sql.=" TO_CHAR(sol_fecha,'dd/mm/YYYY')          SOL_FECHA,";
                    $cadena_sql.=" TO_CHAR(sol_fecha_inicio,'dd/mm/YYYY')   SOL_FECHA_INICIO,";
                    $cadena_sql.=" TO_CHAR(sol_fecha_fin,'dd/mm/YYYY')      SOL_FECHA_FIN,";
                    $cadena_sql.=" sol_usuwebtipo ,";
                    $cadena_sql.=" usutipo_tipo     TIPO_USUARIO,";
                    $cadena_sql.=" sol_tipo_vinculacion,";
                    $cadena_sql.=" tvin_nombre      TIPO_VINCULACION,";
                    $cadena_sql.=" sol_estado_solicitud,";
                    $cadena_sql.=" ecta_nombre      ESTADO_SOLICITUD,";
                    $cadena_sql.=" sol_estado,";
                    $cadena_sql.=" sol_anexo_soporte";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."gesolicitudcuenta";
                    $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."geestadoscuenta ON sol_estado_solicitud=ecta_id";
                    $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."geusuwebdatos ON uwd_codigo=sol_codigo";
                    $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_personal']."peemp ON emp_nro_iden=sol_nro_iden_solicitante";
                    $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_personal']."pecargo ON emp_car_cod=car_cod";
                    $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."geusutipo ON usutipo_cod=sol_usuwebtipo";
                    $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."getipovinculacion ON tvin_cod=sol_tipo_vinculacion";
                    $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."getipdocu ON tdo_codigo=uwd_tipo_iden";
                    $cadena_sql.=" WHERE sol_estado='A'";
                    $cadena_sql.=" AND sol_id=".$variable;
                    break;


            case 'consultar_usuario':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cla_codigo,";
                    $cadena_sql.=" cla_clave";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geclaves";
                    $cadena_sql.=" WHERE cla_codigo=".$variable;
                    break;
                
            case 'consultar_usuarioweb':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" usuweb_codigo,";
                    $cadena_sql.=" usuweb_tipo,";
                    $cadena_sql.=" usuweb_secuencia,";
                    $cadena_sql.=" usuweb_codigo_dep,";
                    $cadena_sql.=" usuweb_tipo_vinculacion,";
                    $cadena_sql.=" usuweb_fecha_inicio,";
                    $cadena_sql.=" usuweb_fecha_fin,";
                    $cadena_sql.=" usuweb_estado";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geusuweb";
                    $cadena_sql.=" WHERE usuweb_codigo=".$variable['identificacion'];
                    $cadena_sql.=" AND usuweb_tipo=".$variable['tipo_cuenta'];
                    $cadena_sql.=" AND usuweb_codigo_dep=".$variable['dependencia'];
                    break;

            case 'proyectos_coordinador':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cra_cod,";
                    $cadena_sql.=" cra_nombre";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."accra";
                    $cadena_sql.=" WHERE cra_emp_nro_iden=".$variable;
                    break;
            
            case 'facultad_secretario':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" sec_dep_cod DEP_COD,";
                    $cadena_sql.=" dep_nombre DEP_NOMBRE";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_personal']."peemp";
                    $cadena_sql.=" INNER JOIN  acsecretario ON sec_cod=emp_cod AND sec_estado='A'";
                    $cadena_sql.=" INNER JOIN  ".$this->configuracion['esquema_general']."gedep ON sec_dep_cod=dep_cod";
                    $cadena_sql.=" WHERE emp_nro_iden=".$variable;
                    break;
            
            case 'facultad_decano':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" dec_dep_cod DEP_COD,";
                    $cadena_sql.=" dep_nombre DEP_NOMBRE";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_personal']."peemp";
                    $cadena_sql.=" INNER JOIN  acdecanos ON dec_cod=emp_cod AND dec_estado='A'";
                    $cadena_sql.=" INNER JOIN  ".$this->configuracion['esquema_general']."gedep ON dec_dep_cod=dep_cod";
                    $cadena_sql.=" WHERE emp_nro_iden=".$variable;
                    break;
            
            case 'dependencia_solicitante':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" emp_dep_cod DEP_COD,";
                    $cadena_sql.=" dep_nombre DEP_NOMBRE";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_personal']."peemp";
                    $cadena_sql.=" INNER JOIN  ".$this->configuracion['esquema_general']."gedep ON emp_dep_cod=dep_cod";
                    $cadena_sql.=" WHERE emp_nro_iden=".$variable;
                    break;

            case 'adicionar_cuenta_usuario':
                    $cadena_sql="INSERT INTO ".$this->configuracion['esquema_general']."geclaves ";
                    $cadena_sql.="(cla_codigo,
                                cla_clave,
                                cla_tipo_usu,
                                cla_estado) ";
                    $cadena_sql.="VALUES ('".$variable['identificacion']."',";
                    $cadena_sql.="'".$variable['clave']."',";
                    $cadena_sql.="'".$variable['tipo_cuenta']."',";
                    $cadena_sql.="'A')";

                break;
            
            case 'adicionar_cuenta_usuario_mysql':
                    $cadena_sql="INSERT INTO geclaves ";
                    $cadena_sql.="(cla_codigo,
                                cla_clave,
                                cla_tipo_usu,
                                cla_estado,
                                cla_facultad,
                                cla_proyecto) ";
                    $cadena_sql.="VALUES ('".$variable['identificacion']."',";
                    $cadena_sql.="'".$variable['clave']."',";
                    $cadena_sql.="'".$variable['tipo_cuenta']."',";
                    $cadena_sql.="'A',";
                    $cadena_sql.="'0',";
                    $cadena_sql.="'0')";
                break;
            
            case "modificaClaveMySQL":
				$cadena_sql="UPDATE ";
				$cadena_sql.="geclaves ";
				$cadena_sql.="SET ";
				$cadena_sql.="cla_clave='".$variable[4]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cla_codigo = '".$variable[5]."' ";
				break;
            
            case 'adicionar_cuenta_usuarioweb':
                    $cadena_sql="INSERT INTO ".$this->configuracion['esquema_general']."geusuweb ";
                    $cadena_sql.="( usuweb_codigo,
                                    usuweb_tipo,
                                    usuweb_secuencia,
                                    usuweb_codigo_dep,
                                    usuweb_tipo_vinculacion,
                                    usuweb_fecha_inicio,
                                    usuweb_fecha_fin,
                                    usuweb_estado) ";
                    $cadena_sql.="VALUES ('".$variable['identificacion']."',";
                    $cadena_sql.="'".$variable['tipo_cuenta']."',";
                    $cadena_sql.="'".$variable['secuencia']."',";
                    $cadena_sql.="'".$variable['dependencia']."',";
                    $cadena_sql.="'".$variable['tipo_contrato']."',";
                    $cadena_sql.="to_date('".$variable['fecha_inicio']."','dd/mm/yyyy'),";
                    $cadena_sql.="to_date('".$variable['fecha_fin']."','dd/mm/yyyy'),";
                    $cadena_sql.="'A')";

                break;

            case 'actualizar_estado_solicitud':
                    $cadena_sql="UPDATE ";
                    $cadena_sql.=" ".$this->configuracion['esquema_general']."gesolicitudcuenta";
                    $cadena_sql.=" SET sol_estado_solicitud=".$variable['estado'];
                    $cadena_sql.=" WHERE sol_id='".$variable['idSolicitud']."'";
                break;
            
            case 'actualizar_usuario':
                    $cadena_sql="UPDATE ";
                    $cadena_sql.=" ".$this->configuracion['esquema_general']."geclaves";
                    $cadena_sql.=" SET cla_estado='".$variable['estado_usuario']."'";
                    $cadena_sql.=" WHERE cla_codigo='".$variable['identificacion']."'";
                    $cadena_sql.=" AND cla_tipo_usu='".$variable['tipo_cuenta']."'";
                break;
            
            case 'actualizar_usuarioweb':
                    $cadena_sql="UPDATE ";
                    $cadena_sql.=" ".$this->configuracion['esquema_general']."geusuweb";
                    $cadena_sql.=" SET usuweb_fecha_inicio=to_date('".$variable['fecha_inicio']."','dd/mm/yyyy'),";
                    $cadena_sql.=" usuweb_fecha_fin=to_date('".$variable['fecha_fin']."','dd/mm/yyyy'),";
                    $cadena_sql.=" usuweb_estado='".$variable['estado_usuario']."'";
                    $cadena_sql.=" WHERE usuweb_codigo='".$variable['identificacion']."'";
                    $cadena_sql.=" AND usuweb_tipo='".$variable['tipo_cuenta']."'";
                    $cadena_sql.=" AND usuweb_codigo_dep='".$variable['dependencia']."'";
                break;
            
            case 'buscarUsuarioYaExiste':

                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cla_codigo,";
                    $cadena_sql.=" cla_tipo_usu";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geclaves";
                    $cadena_sql.=" WHERE cla_codigo=".$variable['identificacion'];
                    $cadena_sql.=" AND cla_tipo_usu=".$variable['tipo_cuenta'];
                    break;

            case 'actualizar_usuario_mysql':
                    $cadena_sql="UPDATE geclaves ";
                    $cadena_sql.="SET cla_estado='A'";
                    $cadena_sql.=" WHERE  cla_codigo='".$variable['identificacion']."' ";
                    $cadena_sql.=" AND cla_tipo_usu='".$variable['tipo_cuenta']."'";
                break;
            
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>