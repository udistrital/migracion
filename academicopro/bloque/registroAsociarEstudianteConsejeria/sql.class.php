<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAsociarEstudianteConsejeria extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios

            case 'datos_coordinador':
                $cadena_sql="select distinct usucra_cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from geusucra ";
                $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                $cadena_sql.="INNER JOIN ACPEN ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.=" where usucra_nro_iden=".$variable;
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                $cadena_sql.=" and pen_nro>200";

                break;

            case 'estudiantes_asociados':
                $cadena_sql="select ECO_EST_COD,ECO_DOC_NRO_IDENT, ";
                $cadena_sql.=" est_cod, est_nombre , estado_cod, estado_descripcion ";
                $cadena_sql.="from ACESTUDIANTECONSEJERO ";
                $cadena_sql.=" INNER JOIN ACEST ON EST_COD=ECO_EST_COD";
                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod";
                $cadena_sql.=" where ECO_DOC_NRO_IDENT='".$variable[3]."' and ECO_ESTADO='A'";
                $cadena_sql.=" ORDER BY ECO_EST_COD";

                break;


            case 'registroEvento':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0,'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."')";

                break;


            case 'consultarDatosEstudiante':
                $cadena_sql="select est_cod, est_nombre , estado_cod, estado_descripcion";
                $cadena_sql.=" from acest ";
                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" where est_estado like '%A%' ";
                $cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" and est_cod = '".$variable[5]."' ";
                //$cadena_sql.=" and est_ind_cred like '%S%' ";
                $cadena_sql.=" ORDER BY 1 ";

                break;

            case 'periodoActual':

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";

                break;

            case 'registrarRelacion':

                $cadena_sql="insert into ACESTUDIANTECONSEJERO ";
                $cadena_sql.="VALUES('".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="TO_DATE('".$variable[3]."','YYYY-MM-DD'),";
                $cadena_sql.="'".$variable[4]."')";

                break;
            
            case 'actualizarEstadoRelacion':

                $cadena_sql="delete from ACESTUDIANTECONSEJERO ";
                $cadena_sql.=" where ECO_EST_COD='".$variable[0]."'";
                $cadena_sql.=" and ECO_DOC_NRO_IDENT='".$variable[1]."'";
                
                break;
            
            case 'buscarEstudianteAconsejado':

                $cadena_sql="select ECO_EST_COD, ECO_DOC_NRO_IDENT FROM ACESTUDIANTECONSEJERO ";
                $cadena_sql.=" where ECO_EST_COD='".$variable[0]."'";
                $cadena_sql.=" and ECO_DOC_NRO_IDENT='".$variable[1]."'";

                break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
