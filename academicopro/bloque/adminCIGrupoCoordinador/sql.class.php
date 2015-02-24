<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminCIGrupoCoordinador extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
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

            case 'espacios_activos':

                $cadena_sql="select distinct cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_nro_ins, pen_sem from accurso ";
                $cadena_sql.="inner join acpen on accurso.cur_asi_cod=acpen.pen_asi_cod ";
                $cadena_sql.="inner join accra on accurso.cur_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.=" and pen_nro=".$variable[0];
                $cadena_sql.=" and cur_cra_cod=".$variable[1];
                $cadena_sql.=" order by 6,1,2";


            break;
       
            case 'espacios_seleccionadoCodigo':

                $cadena_sql="select distinct cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_nro_ins, pen_sem from accurso ";
                $cadena_sql.="inner join acpen on accurso.cur_asi_cod=acpen.pen_asi_cod ";
                $cadena_sql.="inner join accra on accurso.cur_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="and pen_nro=".$variable[0];
                $cadena_sql.=" and cur_asi_cod=".$variable[1];
                $cadena_sql.=" and cur_cra_cod=".$variable[2];
                $cadena_sql.=" order by 6,1,2";
            break;

            case 'espacios_seleccionadoPalabra':

                $cadena_sql="select distinct cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_nro_ins, pen_sem from accurso ";
                $cadena_sql.="inner join acpen on accurso.cur_asi_cod=acpen.pen_asi_cod ";
                $cadena_sql.="inner join accra on accurso.cur_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="inner join acasi on accurso.cur_asi_cod=acasi.asi_cod ";
                $cadena_sql.="where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="and pen_nro=".$variable[0];
                $cadena_sql.=" and asi_nombre like '%".$variable[1]."%'";
                $cadena_sql.=" and cur_cra_cod=".$variable[2];
                $cadena_sql.=" order by 6,1,2";
            break;

            case 'espacio_grupoInscritos':

                    $cadena_sql="select distinct count(ins_est_cod) from acins ";
                    $cadena_sql.="where ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.="and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.="and ins_asi_cod=".$variable[0];
                    $cadena_sql.="and ins_gr=".$variable[1];

                break;

            case 'datosCoordinador':

                $cadena_sql="SELECT DISTINCT ";
                $cadena_sql.="PEN_NRO, ";
                $cadena_sql.="CRA_COD ";
                $cadena_sql.="FROM ACCRA ";
                $cadena_sql.="INNER JOIN GEUSUCRA ";
                $cadena_sql.="ON ACCRA.CRA_COD = ";
                $cadena_sql.="GEUSUCRA.USUCRA_CRA_COD ";
                $cadena_sql.="INNER JOIN ACPEN ";
                $cadena_sql.="ON ACCRA.CRA_COD = ";
                $cadena_sql.="ACPEN.PEN_CRA_COD ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="GEUSUCRA.USUCRA_NRO_IDEN = ";
                $cadena_sql.=$variable." ";
                //$cadena_sql.="'".$variable."' ";
                $cadena_sql.="AND PEN_NRO > 200 ";

                //echo "cadena".$this->cadena_sql;
                //exit;

                break;

            case 'datos_espacio':

                $cadena_sql="SELECT distinct id_nivel, PEE.`id_espacio` , `espacio_nombre` , `espacio_nroCreditos` , `espacio_horasDirecto` , `espacio_horasCooperativo` , `espacio_horasAutonomo` ";
                $cadena_sql.=" FROM `sga_espacio_academico` EA ";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                $cadena_sql.=" WHERE EA.`id_espacio` = ".$variable;

                break;

            case 'horario_grupos':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%')";
                $cadena_sql.=" AND HOR_NRO=".$variable[3];
                $cadena_sql.=" ORDER BY 1,2,3";

                break;

            case 'año_periodo':
                $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%'";

                break;

        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
