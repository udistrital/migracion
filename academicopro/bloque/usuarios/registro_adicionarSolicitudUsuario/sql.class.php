<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarSolicitudUsuario extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
             case 'buscarUltimoIndiceTablaSolicitudCuenta':
                    $cadena_sql="SELECT MAX( ";
                    $cadena_sql.="sol_id) AS ULTIMO_CODIGO ";
                    $cadena_sql.="FROM ".$this->configuracion['esquema_general']."gesolicitudcuenta";
                            
                break;
            
            case 'adicionar_tabla_datos_usuario':
                    $cadena_sql="INSERT INTO ".$this->configuracion['esquema_general']."geusuwebdatos ";
                    $cadena_sql.="(uwd_codigo,
                                uwd_tipo_iden,
                                uwd_nombres,
                                uwd_apellidos,
                                uwd_correo_electronico,
                                uwd_telefono,
                                uwd_celular,
                                uwd_direccion) ";
                    $cadena_sql.="VALUES ('".$variable['identificacion']."',";
                    $cadena_sql.="'".$variable['tipo_documento']."',";
                    $cadena_sql.="'".$variable['nombre']."',";
                    $cadena_sql.="'".$variable['apellido']."',";
                    $cadena_sql.="'".$variable['correo']."',";
                    $cadena_sql.="'".$variable['telefono']."',";
                    $cadena_sql.="'".$variable['celular']."',";
                    $cadena_sql.="'".$variable['direccion']."')"; 
                
                break;
            
            case 'adicionar_tabla_solicitud_cuenta':
                    $cadena_sql="INSERT INTO ".$this->configuracion['esquema_general']."gesolicitudcuenta ";
                    $cadena_sql.="(sol_id,
                                sol_nro_iden_solicitante,
                                sol_codigo,
                                sol_fecha,
                                sol_usuwebtipo,
                                sol_tipo_vinculacion,
                                sol_fecha_inicio,
                                sol_fecha_fin,
                                sol_estado_solicitud,
                                sol_estado,
                                sol_anexo_soporte) ";
                    $cadena_sql.="VALUES ('".$variable['identificador']."',";
                    $cadena_sql.="'".$variable['solicitante']."',";
                    $cadena_sql.="'".$variable['identificacion']."',";
                    $cadena_sql.="to_date('".$variable['fecha']."','dd/mm/yyyy'),";
                    $cadena_sql.="'".$variable['tipo_cuenta']."',";
                    $cadena_sql.="'".$variable['tipo_contrato']."',";
                    $cadena_sql.="to_date('".$variable['fecha_inicio']."','yyyy/mm/dd'),";
                    $cadena_sql.="to_date('".$variable['fecha_fin']."','yyyy/mm/dd'),";
                    $cadena_sql.="'1',";
                    $cadena_sql.="'A',";
                    $cadena_sql.="'".$variable['anexo']."')"; 
                
                break;

            case 'buscarDatosUsuario':

                    $cadena_sql=" SELECT uwd_codigo,";
                    $cadena_sql.=" uwd_tipo_iden,";
                    $cadena_sql.=" uwd_nombres,";
                    $cadena_sql.=" uwd_apellidos,";
                    $cadena_sql.=" uwd_correo_electronico,";
                    $cadena_sql.=" uwd_telefono,";
                    $cadena_sql.=" uwd_celular,";
                    $cadena_sql.=" uwd_direccion";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."geusuwebdatos ";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" uwd_codigo= ".$variable;
                    break;

            case 'buscarSolicitudYaExiste':

                    $cadena_sql=" SELECT sol_id,";
                    $cadena_sql.=" sol_nro_iden_solicitante,";
                    $cadena_sql.=" sol_codigo,";
                    $cadena_sql.=" sol_fecha,";
                    $cadena_sql.=" sol_usuwebtipo,";
                    $cadena_sql.=" sol_tipo_vinculacion,";
                    $cadena_sql.=" sol_fecha_inicio,";
                    $cadena_sql.=" sol_fecha_fin,";
                    $cadena_sql.=" sol_estado_solicitud,";
                    $cadena_sql.=" sol_estado,";
                    $cadena_sql.=" sol_anexo_soporte";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_general']."gesolicitudcuenta ";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" sol_nro_iden_solicitante= ".$variable['solicitante'];
                    $cadena_sql.=" AND sol_codigo= ".$variable['identificacion'];
                    $cadena_sql.=" AND sol_usuwebtipo= ".$variable['tipo_cuenta'];
//                    $cadena_sql.=" AND TO_CHAR(sol_fecha_inicio,'yyyy/mm/dd')<= '".$variable['fecha_inicio']."'";
//                    $cadena_sql.=" AND TO_CHAR(sol_fecha_fin,'yyyy/mm/dd')>= '".$variable['fecha_fin']."'";
                    $cadena_sql.=" AND sol_estado_solicitud in (1,2)";
                    break;
                
            case 'buscarUsuarioConPerfilYaExiste':

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
                    break;

                    
            case 'inactivar_solicitud':

                    $cadena_sql=" UPDATE ".$this->configuracion['esquema_general']."gesolicitudcuenta ";
                    $cadena_sql.=" SET sol_estado_solicitud= 4";
                    $cadena_sql.=" WHERE sol_id= ".$variable;
                    break;
  
            
            case 'proyectos_coordinador':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cra_cod,";
                    $cadena_sql.=" cra_nombre";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."accra";
                    $cadena_sql.=" WHERE cra_emp_nro_iden=".$variable;
                    break;
           
            case 'dependencia_solicitante':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" emp_dep_cod DEP_COD,";
                    $cadena_sql.=" dep_nombre DEP_NOMBRE";
                    $cadena_sql.=" FROM ".$this->configuracion['esquema_personal']."peemp";
                    $cadena_sql.=" INNER JOIN  ".$this->configuracion['esquema_general']."gedep ON emp_dep_cod=dep_cod";
                    $cadena_sql.=" WHERE emp_nro_iden=".$variable;
                    break;
                
            case 'actualizarDatosBasicosUsuario':

                    $cadena_sql=" UPDATE ".$this->configuracion['esquema_general']."geusuwebdatos ";
                    $cadena_sql.=" SET uwd_nombres= '".$variable['nombre']."',";
                    $cadena_sql.=" uwd_apellidos= '".$variable['apellido']."',";
                    $cadena_sql.=" uwd_tipo_iden= '".$variable['tipo_documento']."',";
                    $cadena_sql.=" uwd_correo_electronico= '".$variable['correo']."',";
                    $cadena_sql.=" uwd_telefono= '".$variable['telefono']."',";
                    $cadena_sql.=" uwd_celular= '".$variable['celular']."',";
                    $cadena_sql.=" uwd_direccion= '".$variable['direccion']."' ";
                    $cadena_sql.=" WHERE uwd_codigo= ".$variable['identificacion'];
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
            
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>