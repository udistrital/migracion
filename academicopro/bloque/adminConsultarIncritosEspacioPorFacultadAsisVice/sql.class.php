<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarIncritosEspacioPorFacultadAsisVice extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
           case "listaFacultades":
                $cadena_sql="SELECT ";
                $cadena_sql.="DISTINCT dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join gedep on cra_dep_cod=dep_cod ";
                $cadena_sql.="where ";
                $cadena_sql.="cra_tip_cra in (1,2,3,7,9) ";
                $cadena_sql.="order by 1 ";
                break;

            case "buscarEspacios":
                $cadena_sql="select distinct asi_cod, ";
                $cadena_sql.="asi_nombre ";
                $cadena_sql.="from acasi ";
                $cadena_sql.="inner join acpen on asi_cod= pen_asi_cod ";
                $cadena_sql.="where pen_nro>200 ";
                $cadena_sql.="and asi_cod<20000 ";
                $cadena_sql.="and pen_estado like '%A%' ";
                $cadena_sql.="and (select count(*) ";
                $cadena_sql.="from acpen ";
                $cadena_sql.=" where pen_asi_cod= asi_cod ";
                $cadena_sql.="and pen_estado like '%A%')>2 ";
                $cadena_sql.="order by asi_cod";
                break;

            case "buscarCatedras":
                $cadena_sql="select distinct asi_cod, ";
                $cadena_sql.="asi_nombre ";
                $cadena_sql.="from acasi ";
                $cadena_sql.="where asi_ind_catedra like '%S%'";
                $cadena_sql.="and asi_estado='A'";
                $cadena_sql.="order by asi_cod";
                break;

           case "consultarPeriodoActivo":
                $cadena_sql="SELECT ";
                $cadena_sql.="APE_ANO, ";
                $cadena_sql.="APE_PER ";
                $cadena_sql.="FROM ACASPERI ";
                $cadena_sql.="where ape_estado like '%A%' ";
                break;


           case "consultarInscritosFacultad":
                $cadena_sql=" SELECT INS_CRA_COD cra_estudiante,";
                $cadena_sql.=" INS_EST_COD cod_estudiante,";
                $cadena_sql.=" EST_NOMBRE nombre_estudiante,";
                $cadena_sql.=" EST_ESTADO_EST estado_estudiante,";
                $cadena_sql.=" (SELECT CRA_NOMBRE FROM ACCRA WHERE CRA_COD=INS_CRA_COD) nombre_carrera_est,";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) grupo,";
                $cadena_sql.=" CUR_CRA_COD carrera_grupo,";
                $cadena_sql.=" CRA_NOMBRE nombre_carrera_grupo,";
                $cadena_sql.=" CUR_NRO_CUPO cupo_grupo,";
                $cadena_sql.=" (select count(*) from acins where ins_asi_cod = cur_asi_cod and ins_gr= cur_id and ins_ano=".$variable[2]." and ins_per=".$variable[3].") inscritos,";
                $cadena_sql.=" ins_gr";
                $cadena_sql.=" FROM ACINS";
                $cadena_sql.=" inner join acest on ins_est_cod=EST_COD";
                $cadena_sql.=" INNER JOIN accursos ON ins_asi_cod=cur_asi_cod AND ins_gr= cur_id AND ins_ano= cur_ape_ano AND ins_per= cur_ape_per";
                $cadena_sql.=" inner join gedep on CUR_DEP_COD=DEP_COD";
                $cadena_sql.=" INNER JOIN ACCRA ON CUR_CRA_COD=CRA_COD";
                $cadena_sql.=" where ins_asi_cod=".$variable[1];
                $cadena_sql.=" and INS_ANO =".$variable[2]."";
                $cadena_sql.=" and INS_PER =".$variable[3]."";
                $cadena_sql.=" and CUR_DEP_COD =".$variable[0];
                $cadena_sql.=" and ins_estado = 'A'";
                $cadena_sql.=" ORDER BY CUR_CRA_COD, INS_GR, INS_CRA_COD, INS_EST_COD";               
                break;


            case "consultaDocenteGrupo":
                $cadena_sql=" SELECT distinct DOC_NOMBRE,";
                $cadena_sql.=" DOC_APELLIDO";
                $cadena_sql.=" FROM ACDOCENTE";
                $cadena_sql.=" INNER JOIN ACCARGAS ON DOC_NRO_IDEN=CAR_DOC_NRO";
                $cadena_sql.=" INNER JOIN achorarios ON hor_id=car_hor_id";
                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                $cadena_sql.=" WHERE CUR_APE_ANO = ".$variable[2];
                $cadena_sql.=" AND CUR_APE_PER = ".$variable[3];
                $cadena_sql.=" AND CUR_CRA_COD = ".$variable[4];
                $cadena_sql.=" AND CUR_ASI_COD = ".$variable[1];
                $cadena_sql.=" AND CUR_ID = ".$variable[5];
                $cadena_sql.=" AND cur_estado='A'";
                $cadena_sql.=" AND hor_estado='A'";
                $cadena_sql.=" AND car_estado='A'";                
                break;



        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
