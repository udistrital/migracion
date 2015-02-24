<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarSolicitudesUsuario extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

        case 'solicitudes_cuentas':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" sol_id,";
                $cadena_sql.=" sol_nro_iden_solicitante,";
                $cadena_sql.=" emp_nombre SOLICITANTE,";
                $cadena_sql.=" sol_codigo,";
                $cadena_sql.=" UWD_APELLIDOS||' '||UWD_NOMBRES  NOMBRE_USUARIO,";
                $cadena_sql.=" TO_CHAR(sol_fecha,'dd/mm/YYYY') SOL_FECHA,";
                $cadena_sql.=" TO_CHAR(sol_fecha_inicio,'dd/mm/YYYY') SOL_FECHA_INICIO,";
                $cadena_sql.=" TO_CHAR(sol_fecha_fin,'dd/mm/YYYY') SOL_FECHA_FIN,";
                $cadena_sql.=" sol_estado_solicitud,";
                $cadena_sql.=" ecta_nombre ESTADO_SOLICITUD,";
                $cadena_sql.=" sol_estado,";
                $cadena_sql.=" sol_anexo_soporte";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."gesolicitudcuenta";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."geestadoscuenta ON sol_estado_solicitud=ecta_id";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_general']."geusuwebdatos ON uwd_codigo=sol_codigo";
                $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_personal']."peemp ON emp_nro_iden=sol_nro_iden_solicitante";
                $cadena_sql.=" WHERE sol_estado='A'";
                break;

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
            
        
        case 'datos_facultad':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" dep_cod,";
                $cadena_sql.=" dep_nombre";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."gedep";
                $cadena_sql.=" WHERE dep_cod=".$variable;
                break;
            
        case 'datos_proyecto_curricular':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" cra_cod,";
                $cadena_sql.=" cra_nombre";
                 $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."accra";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

        case "consultarTipoCuenta":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" usutipo_cod,";
                $cadena_sql.=" usutipo_tipo||' ('||usutipo_cod||')' usutipo_tipo";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geusutipo";
                $cadena_sql.=" WHERE usutipo_cod!=0";
                $cadena_sql.=" AND (usutipo_cod>=110";
                $cadena_sql.=" AND usutipo_cod<=120";
                $cadena_sql.=" AND usutipo_cod!=113)";
                $cadena_sql.=" OR usutipo_cod=68";
                $cadena_sql.=" UNION SELECT 0, '----' FROM ".$this->configuracion['esquema_general']."geusutipo ";
                $cadena_sql.=" ORDER BY usutipo_tipo ";
                break;


        case "consultarTipoDocumento":
                $cadena_sql = " SELECT ";
                $cadena_sql.=" tdo_codigo,";
                $cadena_sql.=" tdo_nombre";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."getipdocu";
                $cadena_sql.=" UNION SELECT 0, '----' FROM ".$this->configuracion['esquema_general']."getipdocu ";
                break;
        
        case "proyectos_relacionados_usuario":
                $cadena_sql=" SELECT ";
                $cadena_sql.=" usuweb_codigo,";
                $cadena_sql.=" usuweb_tipo,";
                $cadena_sql.=" usutipo_tipo,";
                $cadena_sql.=" usuweb_estado,";
                $cadena_sql.=" usuweb_codigo_dep COD_PROYECTO,";
                $cadena_sql.=" (CASE WHEN usuweb_tipo=110 OR usuweb_tipo=114 THEN cra_nombre ELSE dep_nombre END) PROYECTO ";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geusuweb";
                $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_academico']."accra ON cra_cod=usuweb_codigo_dep";
                $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_general']."gedep ON dep_cod=usuweb_codigo_dep ";
                $cadena_sql.=" LEFT OUTER JOIN ".$this->configuracion['esquema_general']."geusutipo ON usuweb_tipo=usutipo_cod";
                $cadena_sql.=" WHERE  usuweb_codigo=".$variable;
                $cadena_sql.=" AND  usuweb_estado='A'";
                break;

            default :
                $cadena_sql="";
                break;
    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>