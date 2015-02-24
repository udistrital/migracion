<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroConsejeria extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case 'consultarDocentesPlanta':
                $cadena_sql=" select distinct cur_cra_cod,";
                $cadena_sql.=" car_doc_nro,";
                $cadena_sql.=" car_tip_vin,";
                $cadena_sql.=" tvi_nombre,";
                $cadena_sql.=" trim(doc_apellido),";
                $cadena_sql.=" trim (doc_nombre),";
                $cadena_sql.=" doc_email";
                $cadena_sql.=" FROM accargas";
                $cadena_sql.=" INNER JOIN achorarios ON hor_id=car_hor_id";
                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano=ape_ano and cur_ape_per=ape_per";
                $cadena_sql.=" INNER JOIN actipvin ON car_tip_vin=tvi_cod";
                $cadena_sql.=" INNER JOIN acdocente ON doc_nro_iden=car_doc_nro";
                $cadena_sql.=" where cur_cra_cod=".$variable;
                $cadena_sql.=" and hor_estado='A'";
                $cadena_sql.=" and cur_estado='A'";
                $cadena_sql.=" and car_estado='A'";
                $cadena_sql.=" and ape_estado='A'";
                $cadena_sql.=" AND car_tip_vin NOT IN (4, 5)";
                $cadena_sql.=" AND EXISTS (SELECT 1";
                $cadena_sql.=" FROM acdocente";
                $cadena_sql.=" WHERE doc_nro_iden = car_doc_nro)";
                $cadena_sql.=" AND EXISTS (SELECT 1";
                $cadena_sql.=" FROM peemp";
                $cadena_sql.=" WHERE emp_nro_iden = car_doc_nro AND emp_estado_e ='A')";
                $cadena_sql.=" ORDER BY car_tip_vin, trim(doc_apellido) ";

                break;

            case 'consultarDocentesPlantaOtros':
                $cadena_sql=" select distinct cur_cra_cod,";
                $cadena_sql.=" car_doc_nro,";
                $cadena_sql.=" car_tip_vin,";
                $cadena_sql.=" tvi_nombre,";
                $cadena_sql.=" trim(doc_apellido),";
                $cadena_sql.=" trim (doc_nombre),";
                $cadena_sql.=" doc_email,";
                $cadena_sql.=" cra_nombre";
                $cadena_sql.=" FROM accargas";
                $cadena_sql.=" INNER JOIN achorarios ON hor_id=car_hor_id";
                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano=ape_ano and cur_ape_per=ape_per";
                $cadena_sql.=" INNER JOIN actipvin ON car_tip_vin=tvi_cod";
                $cadena_sql.=" INNER JOIN acdocente ON doc_nro_iden=car_doc_nro";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=cur_cra_cod";
                $cadena_sql.=" where cur_cra_cod!=".$variable;
                $cadena_sql.=" and hor_estado='A'";
                $cadena_sql.=" and cur_estado='A'";
                $cadena_sql.=" and car_estado='A'";
                $cadena_sql.=" and ape_estado='A'";
                $cadena_sql.=" AND car_tip_vin NOT IN (4, 5)";
                $cadena_sql.=" AND EXISTS (SELECT 1";
                $cadena_sql.=" FROM acdocente";
                $cadena_sql.=" WHERE doc_nro_iden = car_doc_nro)";
                $cadena_sql.=" AND EXISTS (SELECT 1";
                $cadena_sql.=" FROM peemp";
                $cadena_sql.=" WHERE emp_nro_iden = car_doc_nro AND emp_estado_e ='A')";
                $cadena_sql.=" ORDER BY cur_cra_cod,car_tip_vin, trim(doc_apellido) ";
                break;

            case 'datos_coordinador':
                $cadena_sql="select distinct usucra_cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from geusucra ";
                $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                $cadena_sql.="INNER JOIN ACPEN ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.=" where usucra_nro_iden=".$variable;
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                $cadena_sql.=" and pen_nro>200";

                break;

            case 'registrarDocente':
                $cadena_sql="insert into ACDOCENTECONSEJERO ";
                $cadena_sql.=" values( ";
                $cadena_sql.=" '".$variable[0]."', ";
                $cadena_sql.=" '".$variable[1]."') ";

                break;

            case 'consultarDocentesConsejerosActivos':
                $cadena_sql="select DCO_DOC_NRO_IDENT, DCO_CRA_COD from ACDOCENTECONSEJERO ";
                $cadena_sql.=" where DCO_DOC_NRO_IDENT= '".$variable[0]."'";
                $cadena_sql.=" AND DCO_CRA_COD= '".$variable[1]."'";

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

            case 'consultarDocentesConsejeros':
                $cadena_sql="select DCO_DOC_NRO_IDENT,";
                $cadena_sql.=" DCO_CRA_COD,";
                $cadena_sql.=" DOC_NOMBRE,";
                $cadena_sql.=" DOC_APELLIDO,";
                $cadena_sql.=" DOC_EMAIL";
                $cadena_sql.=" FROM ACDOCENTECONSEJERO";
                $cadena_sql.=" INNER JOIN ACDOCENTE ON DCO_DOC_NRO_IDENT = DOC_NRO_IDEN";
                $cadena_sql.=" WHERE DCO_CRA_COD= '".$variable."'";
                $cadena_sql.=" ORDER BY DOC_APELLIDO";

                break;

            case 'consultarDatosConsejeros':
                $cadena_sql="select doc_nombre, doc_apellido, doc_email";
                $cadena_sql.=" from acdocente";
                $cadena_sql.=" where doc_nro_iden=".$variable;

                break;

            case 'borrarDocenteSeleccionado':
                $cadena_sql="delete from ACDOCENTECONSEJERO ";
                $cadena_sql.=" where DCO_DOC_NRO_IDENT= '".$variable[0]."'";
                $cadena_sql.=" and DCO_CRA_COD= '".$variable[1]."'";

                break;

            case 'estudiantes_asociados':
                $cadena_sql="SELECT COUNT(*) FROM ACESTUDIANTECONSEJERO ";
                $cadena_sql.=" WHERE ECO_DOC_NRO_IDENT='".$variable[0]."'";
                $cadena_sql.=" AND ECO_ESTADO='A'";
                //$cadena_sql.=" AND ECO_CRA_COD='".$variable[1]."'";

                break;

             case 'buscarDocenteActivo':
                $cadena_sql="select DCO_DOC_NRO_IDENT from ACDOCENTECONSEJERO ";
                $cadena_sql.=" where DCO_DOC_NRO_IDENT= '".$variable[0]."'";
                $cadena_sql.=" and DCO_CRA_COD= '".$variable[1]."'";

                break;

        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
