<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_actualizarDatosEgresado extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

  public $configuracion;


  function __construct($configuracion){

    $this->configuracion=$configuracion;

  }
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                        

            case 'periodoActivo':

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";
                break;


            case 'consultarDatosEstudiante':

                $cadena_sql="SELECT est_cod CODIGO, ";
                $cadena_sql.=" SUBSTR(est_nombre,INSTR(est_nombre,' ',1,2)+1) NOMBRE, ";
                $cadena_sql.=" SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1) APELLIDO, ";
                $cadena_sql.=" coalesce(est_pen_nro,'') PENSUM, ";
                $cadena_sql.=" coalesce(est_ind_cred,'') MODALIDAD, ";
                $cadena_sql.=" estado_cod ESTADO, ";
                $cadena_sql.=" estado_descripcion DESC_ESTADO, ";
                $cadena_sql.=" est_cra_cod CODIGO_CRA, ";
                $cadena_sql.=" cra_dep_cod FACULTAD, ";
                $cadena_sql.=" cra_nombre NOMBRE_CRA, ";
                $cadena_sql.=" coalesce(est_sexo,'') SEXO, ";
                $cadena_sql.=" coalesce(eot_email,'') EMAIL, ";
                $cadena_sql.=" coalesce(est_telefono,'') TELEFONO, ";
                $cadena_sql.=" coalesce(eot_tel_cel,'') CELULAR, ";
                $cadena_sql.=" coalesce(est_direccion,'') DIRECCION, ";
                $cadena_sql.=" coalesce(eot_cod_mun_res,'') POBLACION, ";
                $cadena_sql.=" coalesce(TO_CHAR(eot_fecha_nac, 'yyyymmdd'),'') FEC_NACIMIENTO, ";
                $cadena_sql.=" est_nro_iden IDENTIFICACION, ";
                $cadena_sql.=" coalesce(est_tipo_iden,'') TIPO_IDENTIFICACION, ";
                $cadena_sql.=" coalesce(est_lib_militar,'') LIBRETA, ";
                $cadena_sql.=" coalesce(est_nro_dis_militar,'') DISTRITO_MILITAR, ";
                $cadena_sql.=" coalesce(est_fallecido,'') FALLECIDO ";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" INNER JOIN accra on est_cra_cod= cra_cod ";
                $cadena_sql.=" INNER JOIN acestotr on EOT_COD= EST_COD ";
                $cadena_sql.=" LEFT OUTER JOIN acegresado ON egr_nro_iden=est_nro_iden AND egr_cra_cod=est_cra_cod";
                $cadena_sql.=" LEFT OUTER JOIN acinsgrado ON ing_est_cod=est_cod AND ing_cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_estado like '%A%' ";
                //$cadena_sql.=" AND est_estado_est = 'E'";
                $cadena_sql.=" AND est_estado_est IN ('E','T')";
                $cadena_sql.=" AND est_cod = '".$variable['codEstudiante']."' ";
                break;      
            
            case 'consultarDatosEgresado':

                $cadena_sql="SELECT coalesce(egr_tip_iden,'') TIPO_IDENTIFICACION, ";
                $cadena_sql.=" egr_nro_iden IDENTIFICACION, ";
                $cadena_sql.=" SUBSTR(egr_nombre,INSTR(egr_nombre,' ',1,2)+1) APELLIDO, ";
                $cadena_sql.=" SUBSTR(egr_nombre,1,INSTR(egr_nombre,' ',1,2)-1) NOMBRE, ";
                $cadena_sql.=" coalesce(egr_sexo,'') SEXO, ";
                $cadena_sql.=" coalesce(egr_lug_exp_iden,'') LUGAR_DOCUMENTO, ";
                $cadena_sql.=" coalesce(egr_trabajo_grado,'') NOMBRE_TRABAJO, ";
                $cadena_sql.=" coalesce(egr_director_trabajo,'') DIRECTOR_TRABAJO, ";
                $cadena_sql.=" coalesce(egr_director_trabajo_2,'') DIRECTOR_TRABAJO_2, ";
                $cadena_sql.=" coalesce(egr_acta_sustentacion,'') ACTA_SUST, ";
                $cadena_sql.=" coalesce(to_char(egr_fecha_grado,'YYYY/MM/DD'),'') FECHA_GRADO, ";
                $cadena_sql.=" coalesce(egr_acta_grado,'') ACTA_GRADO, ";
                $cadena_sql.=" coalesce(egr_nota,'') NOTA, ";
                $cadena_sql.=" coalesce(egr_libro,'') LIBRO, ";
                $cadena_sql.=" coalesce(egr_folio,'') FOLIO, ";
                $cadena_sql.=" coalesce(egr_reg_diploma,'') DIPLOMA, ";
                $cadena_sql.=" coalesce(egr_titulo,'') TITULO, ";
                $cadena_sql.=" coalesce(egr_rector,'') RECTOR, ";
                $cadena_sql.=" coalesce(egr_secretario,'') SECRETARIO, ";
                $cadena_sql.=" coalesce(egr_email,'') EMAIL, ";
                $cadena_sql.=" coalesce(egr_telefono_casa,'') TELEFONO, ";
                $cadena_sql.=" coalesce(egr_movil,'') CELULAR, ";
                $cadena_sql.=" coalesce(egr_direccion_casa,'') DIRECCION, ";
                $cadena_sql.=" coalesce(egr_empresa,'') EMPRESA, ";
                $cadena_sql.=" coalesce(egr_direccion_empresa,'') DIRECCION_EMPRESA, ";
                $cadena_sql.=" coalesce(egr_telefono_empresa,'') TELEFONO_EMPRESA, ";
                $cadena_sql.=" coalesce(egr_caracter_nota,'') MENCION ";
                $cadena_sql.=" FROM acegresado";
                $cadena_sql.=" WHERE egr_est_cod = '".$variable['codEstudiante']."' ";
                $cadena_sql.=" AND egr_cra_cod = '".$variable['proyecto']."' ";

                break;      
            
            case 'consultar_codigo_egresado_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                //$cadena_sql.=" AND est_estado_est= 'E'";
		$cadena_sql.=" AND est_estado_est IN ('E','T')";
                $cadena_sql.=" ORDER BY CODIGO desc";
           
                break;
            
            default :
                $cadena_sql='';
                break;
   
        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
