<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_consejeriasEstudiante extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios

            case 'datos_docente':
                $cadena_sql="select doc_nombre, doc_apellido, doc_email";
                $cadena_sql.=" from acdocente";
                $cadena_sql.=" where doc_nro_iden=".$variable;

                break;

            case 'docente_asociado':
                $cadena_sql="select ECO_EST_COD,ECO_DOC_NRO_IDENT ";
                $cadena_sql.="from ACESTUDIANTECONSEJERO ";
                $cadena_sql.=" where ECO_EST_COD='".$variable."' and ECO_ESTADO='A'";
                $cadena_sql.=" ORDER BY ECO_EST_COD";
//                $cadena_sql="select docenteEstudiante_codEstudiante,docenteEstudiante_docDocente from ".$configuracion["prefijo"]."docenteEstudiante ";
//                $cadena_sql.=" where docenteEstudiante_codEstudiante='".$variable."' and docenteEstudiante_estado='1'";

                break;

            case 'datosEstudiante':
                $cadena_sql="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, est_ind_cred ";
                $cadena_sql.=" from acest ";
                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" inner join accra on est_cra_cod= cra_cod";
                $cadena_sql.=" where est_estado like '%A%' ";
                //$cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" and est_cod = '".$variable."' ";
                //$cadena_sql.=" and est_ind_cred like '%S%' ";
                $cadena_sql.=" ORDER BY 1 ";
                //echo $cadena_sql;exit;
                break;

            case 'mensajes':
                $cadena_sql="select ACC_COD, ACC_COD_EMISOR, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                $cadena_sql.="INNER JOIN ACTIPCOMMENT ";
                $cadena_sql.="ON TCM_COD=ACC_TIP_COMMENT ";
                $cadena_sql.="WHERE";
                $cadena_sql.=" ((ACC_COD_RECEPTOR = ".$variable[0]." AND ACC_TIP_RECEPTOR=30 AND ACC_COD_EMISOR = ".$variable[1]." )";
                $cadena_sql.=" OR (ACC_COD_RECEPTOR = ".$variable[1]." AND ACC_COD_EMISOR =".$variable[0]." AND ACC_TIP_EMISOR=30))";
                //$cadena_sql.=" AND ACC_ESTADO LIKE '%L%'";
                $cadena_sql.=" AND ACC_COD NOT IN (SELECT ACC_COD FROM ACCOMMENT WHERE ACC_COD_RECEPTOR = ".$variable[1]." AND ACC_COD_EMISOR = ".$variable[0]." AND ACC_TIP_EMISOR=30 AND ACC_ESTADO LIKE '%P%')";
                $cadena_sql.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" ORDER BY 1 DESC";
                break;


            case 'cuenta_nuevos':
                $cadena_sql="select COUNT (*) from ACCOMMENT ";
                $cadena_sql.="WHERE";
                $cadena_sql.=" ACC_COD_RECEPTOR = ".$variable[1];
                $cadena_sql.=" AND ACC_COD_EMISOR = ".$variable[0];
                $cadena_sql.=" AND ACC_TIP_EMISOR = 30";
                $cadena_sql.=" AND ACC_ESTADO LIKE '%P%'";
                $cadena_sql.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                break;

            case 'mensajes_nuevos':
                $cadena_sql="select ACC_COD, to_char(ACC_FECHA,'dd/mon/yyyy'), to_char(ACC_FECHA,'hh12:mi:ss am'), ACC_TIP_COMMENT, ACC_COMMENT, ACC_ESTADO, ACC_TIP_EMISOR, TCM_DES from ACCOMMENT ";
                $cadena_sql.="INNER JOIN ACTIPCOMMENT ";
                $cadena_sql.="ON TCM_COD=ACC_TIP_COMMENT ";
                $cadena_sql.="WHERE";
                $cadena_sql.=" ACC_COD_RECEPTOR = ".$variable[1];
                $cadena_sql.=" AND ACC_COD_EMISOR = ".$variable[0];
                $cadena_sql.=" AND ACC_TIP_EMISOR = 30";
                $cadena_sql.=" AND ACC_ESTADO LIKE '%P%'";
                $cadena_sql.=" AND ACC_ANO =(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" AND ACC_PER =(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" ORDER BY 1 DESC";
                break;
            
            case 'registroEvento':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES('','".$variable[0]."',";
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
                //$cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" and est_cod = '".$variable."' ";
                $cadena_sql.=" and est_ind_cred like '%S%' ";
                $cadena_sql.=" ORDER BY 1 ";

                break;

            case 'periodoActual':

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";

                break;

//            case 'registrarRelacion':
//
//                $cadena_sql="insert into ".$configuracion['prefijo']."docenteEstudiante ";
//                $cadena_sql.="VALUES('".$variable[0]."',";
//                $cadena_sql.="'".$variable[1]."',";
//                $cadena_sql.="'".$variable[2]."',";
//                $cadena_sql.="'".$variable[3]."',";
//                $cadena_sql.="'".$variable[4]."',";
//                $cadena_sql.="'".$variable[5]."')";
//
//                break;
//
//            case 'actualizarEstadoRelacion':
//
//                $cadena_sql="update ".$configuracion['prefijo']."docenteEstudiante ";
//                $cadena_sql.="set docenteEstudiante_estado='".$variable[5]."'";
//                $cadena_sql.=" where docenteEstudiante_codEstudiante='".$variable[0]."'";
//                $cadena_sql.=" and docenteEstudiante_docDocente='".$variable[1]."'";
//
//                break;
//
            case 'consultarMensajes':

                $cadena_sql="SELECT count(*)  ";
                $cadena_sql.="FROM accomment";
                $cadena_sql.=" WHERE acc_cod_receptor= '".$variable[0]."' ";
                $cadena_sql.=" AND acc_tip_receptor='".$variable[1]."'";
                $cadena_sql.=" AND acc_estado like '%P%'";
                $cadena_sql.=" AND acc_ano=(SELECT ape_ano FROM ACASPERI WHERE ape_estado LIKE '%A%')";
                $cadena_sql.=" AND acc_per=(SELECT ape_per FROM ACASPERI WHERE ape_estado LIKE '%A%')";

                break;

            case 'consultarDatosEst':

                $cadena_sql="select est_cod, est_nombre , estado_cod, estado_descripcion, est_cra_cod, cra_nombre, EOT_EMAIL, est_telefono";
                $cadena_sql.=" from acest ";
                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" inner join accra on est_cra_cod= cra_cod";
                $cadena_sql.=" inner join acestotr on EOT_COD= EST_COD";
                $cadena_sql.=" where est_estado like '%A%' ";
                //$cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" and est_cod = '".$variable."' ";
                //$cadena_sql.=" and est_ind_cred like '%S%' ";
                $cadena_sql.=" ORDER BY 1 ";
                break;

            case 'tipomensaje':
                $cadena_sql="SELECT TCM_COD, TCM_DES FROM ACTIPCOMMENT";
                break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
