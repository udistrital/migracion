<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_inscripcionGraduando extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

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
                $cadena_sql.=" SUBSTR(est_nombre::text,INSTR(est_nombre,' ',1,2)+1) NOMBRE, ";
                $cadena_sql.=" SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1) APELLIDO, ";
                $cadena_sql.=" coalesce(est_pen_nro,null) PENSUM, ";
                $cadena_sql.=" coalesce(est_ind_cred,'') MODALIDAD, ";
                $cadena_sql.=" estado_cod ESTADO, ";
                $cadena_sql.=" estado_descripcion DESC_ESTADO, ";
                $cadena_sql.=" est_cra_cod CODIGO_CRA, ";
                $cadena_sql.=" cra_dep_cod FACULTAD, ";
                $cadena_sql.=" cra_nombre NOMBRE_CRA, ";
                $cadena_sql.=" coalesce(est_sexo,'') SEXO, ";
                $cadena_sql.=" coalesce(eot_email,'') EMAIL, ";
                $cadena_sql.=" coalesce(est_telefono,null) TELEFONO, ";
                $cadena_sql.=" coalesce(eot_tel_cel,null) CELULAR, ";
                $cadena_sql.=" coalesce(est_direccion,'') DIRECCION, ";
                $cadena_sql.=" coalesce(eot_cod_mun_res,null) POBLACION, ";
                $cadena_sql.=" coalesce(TO_CHAR(eot_fecha_nac, 'yyyymmdd'),'') FEC_NACIMIENTO, ";
                $cadena_sql.=" est_nro_iden IDENTIFICACION, ";
                $cadena_sql.=" coalesce(est_tipo_iden,'') TIPO_IDENTIFICACION, ";
                $cadena_sql.=" coalesce(est_lib_militar,null) LIBRETA, ";
                $cadena_sql.=" coalesce(est_nro_dis_militar,null) DISTRITO_MILITAR, ";
                $cadena_sql.=" coalesce(est_fallecido,'') FALLECIDO ";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" INNER JOIN accra on est_cra_cod= cra_cod ";
                $cadena_sql.=" INNER JOIN acestotr on EOT_COD= EST_COD ";
                $cadena_sql.=" LEFT OUTER JOIN acegresado ON egr_nro_iden=est_nro_iden AND egr_cra_cod=est_cra_cod";
                $cadena_sql.=" LEFT OUTER JOIN acinsgrado ON ing_est_cod=est_cod AND ing_cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_estado like '%A%' ";
                //$cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" AND est_cod = '".$variable['codEstudiante']."' ";
                break;      
            
            case 'consultarDatosEgresado':

                $cadena_sql="SELECT coalesce(egr_tip_iden,'') TIPO_IDENTIFICACION, ";
                $cadena_sql.=" egr_nro_iden IDENTIFICACION, ";
                $cadena_sql.=" SUBSTR(egr_nombre::text,INSTR(egr_nombre,' ',1,2)+1) APELLIDO, ";
                $cadena_sql.=" SUBSTR(egr_nombre,1,INSTR(egr_nombre,' ',1,2)-1) NOMBRE, ";
                $cadena_sql.=" coalesce(egr_sexo,'') SEXO, ";
                $cadena_sql.=" coalesce(egr_lug_exp_iden,null) LUGAR_DOCUMENTO, ";
                $cadena_sql.=" coalesce(egr_trabajo_grado,'') NOMBRE_TRABAJO, ";
                $cadena_sql.=" coalesce(egr_director_trabajo,'') DIRECTOR_TRABAJO, ";
                $cadena_sql.=" coalesce(egr_director_trabajo_2,null) DIRECTOR_TRABAJO_2, ";
                $cadena_sql.=" coalesce(egr_acta_sustentacion,'') ACTA_SUST, ";
                $cadena_sql.=" coalesce(to_char(egr_fecha_grado,'YYYY/MM/DD'),'') FECHA_GRADO, ";
                $cadena_sql.=" coalesce(egr_acta_grado,'') ACTA_GRADO, ";
                $cadena_sql.=" coalesce(egr_nota,null) NOTA, ";
                $cadena_sql.=" coalesce(egr_libro,'') LIBRO, ";
                $cadena_sql.=" coalesce(egr_folio,'') FOLIO, ";
                $cadena_sql.=" coalesce(egr_reg_diploma,'') DIPLOMA, ";
                $cadena_sql.=" coalesce(egr_titulo,null) TITULO, ";
                $cadena_sql.=" coalesce(egr_rector,null) RECTOR, ";
                $cadena_sql.=" coalesce(egr_secretario,null) SECRETARIO, ";
                $cadena_sql.=" coalesce(egr_email,'') EMAIL, ";
                $cadena_sql.=" coalesce(egr_telefono_casa,'') TELEFONO, ";
                $cadena_sql.=" coalesce(egr_movil,'') CELULAR, ";
                $cadena_sql.=" coalesce(egr_direccion_casa,'') DIRECCION, ";
                $cadena_sql.=" coalesce(egr_empresa,'') EMPRESA, ";
                $cadena_sql.=" coalesce(egr_direccion_empresa,'') DIRECCION_EMPRESA, ";
                $cadena_sql.=" coalesce(egr_telefono_empresa,'') TELEFONO_EMPRESA, ";
                $cadena_sql.=" coalesce(egr_caracter_nota,null) MENCION ";
                $cadena_sql.=" FROM acegresado";                
                $cadena_sql.=" WHERE egr_est_cod = '".$variable['codEstudiante']."' ";
                $cadena_sql.=" AND egr_cra_cod = '".$variable['proyecto']."' ";      
                break;      
            
            case 'consultarDatosGraduando':

                $cadena_sql="SELECT ing_est_cod CODIGO, ";
                $cadena_sql.=" coalesce(ing_nom_trabajo,'') NOMBRE_TRABAJO, ";
                $cadena_sql.=" coalesce(ing_director,null) DOC_DIRECTOR, ";
                $cadena_sql.=" coalesce(ing_tipo_trabajo,'') TIPO_TRABAJO, ";
                $cadena_sql.=" coalesce(ing_acta,'') ACTA_SUST ";
                $cadena_sql.=" FROM acinsgrado";
                $cadena_sql.=" WHERE ing_est_cod = '".$variable['codEstudiante']."' ";
                $cadena_sql.=" AND ing_cra_cod = '".$variable['proyecto']."' ";     
                break;      

            case 'consultarTiposDocumentos':
                $cadena_sql=" SELECT tdo_codvar,";
                $cadena_sql.=" tdo_nombre";
                $cadena_sql.=" FROM getipdocu";
                $cadena_sql.=" ORDER BY tdo_codigo";
                break;

            case 'consultarMunicipios':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" mun_cod mun_cod,";
                $cadena_sql.=" mun_nombre mun_nombre";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" gemunicipio ";
                $cadena_sql.=" WHERE mun_estado='A' ";
                $cadena_sql.=" ORDER BY mun_nombre ";
                break;
            
            case "consultarDirectorGrado":
                $cadena_sql="SELECT ";
                $cadena_sql.="dir_nro_iden, ";
                $cadena_sql.="dir_nombre ||' '|| dir_apellido as NOMBRE ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acdirectorgrado ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="dir_estado='A' ";
                $cadena_sql.="ORDER BY dir_nombre";
                break;

            case "consultarTitulosGrado":
                $cadena_sql=" SELECT tit_cod COD_TITULO,";
                //$cadena_sql.=" tit_nombre||' ('||decode(tit_sexo,'M','MASCULINO','F','FEMENINO')||')' NOMBRE_TITULO,";
                $cadena_sql.=" tit_nombre||' ('||(CASE WHEN tit_sexo::text='M' THEN 'MASCULINO' WHEN tit_sexo::text='F' THEN 'FEMENINO' END)||')' NOMBRE_TITULO,";
                $cadena_sql.=" tit_sexo SEXO";
                $cadena_sql.=" from actitulo";
                $cadena_sql.=" WHERE tit_cra_cod=".$variable;   
                break;
            
            case "consultarSecretarios":
                $cadena_sql=" SELECT sec_cod,";
                $cadena_sql.=" trim(sec_nombre)||' '||trim(sec_apellido)||' ('||coalesce(to_char(sec_fecha_desde,'DD-MON-YYYY'),'')||' '||CASE WHEN coalesce(sec_fecha_hasta::text,'')='' then '' ELSE 'hasta '|| to_char(sec_fecha_hasta,'DD-MON-YYYY') end||')' NOMBRE_FECHA,";
                $cadena_sql.=" coalesce(to_char(sec_fecha_desde,'YYYYMMDD'),'') SEC_DESDE,";
                $cadena_sql.=" coalesce(to_char(sec_fecha_hasta,'YYYYMMDD'),to_char(current_timestamp,'YYYYMMDD')) SEC_HASTA";
                $cadena_sql.=" FROM acsecretario";
                $cadena_sql.=" WHERE sec_dep_cod=".$variable;
                $cadena_sql.=" ORDER BY sec_estado,sec_fecha_desde desc"; 
                break;
            
            case "consultarRectores":
                $cadena_sql=" SELECT rec_cod,";
                $cadena_sql.=" trim(rec_nombre)||' '||trim(rec_apellido)||' ('||coalesce(to_char(rec_fecha_desde,'DD-MON-YYYY'),'')||' '||CASE WHEN coalesce(rec_fecha_hasta::text,'')='' then '' ELSE 'hasta '|| to_char(rec_fecha_hasta,'DD-MON-YYYY') end||')' NOMBRE_FECHA,";
                $cadena_sql.=" coalesce(to_char(rec_fecha_desde,'YYYYMMDD'),'') RECTOR_DESDE,";
                $cadena_sql.=" coalesce(to_char(rec_fecha_hasta,'YYYYMMDD'),to_char(current_timestamp,'YYYYMMDD')) RECTOR_HASTA";
                $cadena_sql.=" FROM acrector";
                $cadena_sql.=" ORDER BY rec_estado,rec_fecha_desde desc";  
                break;
            
            case 'consultar_codigo_estudiante_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" ORDER BY CODIGO desc";
           
                break;
            
            case 'consultar_codigo_estudiante_por_nombre':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_nombre NOMBRE";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nombre like ".$variable." ";
                $cadena_sql.=" ORDER BY CODIGO desc";
                break;

        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
