<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarInscripcionEstudiantesInactivos extends sql {

    /**
     * Genera la cadena sql, recibe los parametros como array o una variable unica
     * 
     * @param <array> $configuracion
     * @param <String> $opcion
     * @param <array> $variable
     * @return <String> $cadena_sql
     */
    function cadena_sql($configuracion, $opcion,$variable="") {

        switch($opcion) {

              case 'periodo_activo':

                $cadena_sql="SELECT";
                $cadena_sql.=" ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" ACASPERI ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";

                break;

            case 'proyectos_curriculares':

                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE, PEN_NRO ";
                $cadena_sql.="FROM ACCRA ";
                $cadena_sql.="INNER JOIN ACPEN ON ACCRA.CRA_COD=ACPEN.PEN_CRA_COD ";
                //$cadena_sql.="INNER JOIN GEUSUCRA ON ACCRA.CRA_COD=GEUSUCRA.USUCRA_CRA_COD ";
                $cadena_sql.="WHERE PEN_NRO>200 ";
                $cadena_sql.=" AND CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";
                $cadena_sql.=" ORDER BY 3";


            break;

            case 'datos_coordinador':
                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE, PEN_NRO";
                $cadena_sql.=" FROM ACCRA";
                //$cadena_sql.=" INNER JOIN ACCRA ON GEUSUCRA.USUCRA_CRA_COD=ACCRA.CRA_COD";
                $cadena_sql.=" INNER JOIN ACPEN ON ACCRA.CRA_COD=ACPEN.PEN_CRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND PEN_NRO>200";
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";

            break;

            case 'consultar_estudiantes':

                $cadena_sql="select distinct estado_cod, estado_descripcion, ";
                $cadena_sql.="(SELECT COUNT (*) FROM ACEST ";
                $cadena_sql.="WHERE est_ind_cred like '%S%' ";
                $cadena_sql.="AND est_estado_est LIKE estado_cod ";
                $cadena_sql.="and est_cra_cod=".$variable[0]." ";
                $cadena_sql.="AND (SELECT COUNT(*) ";
                $cadena_sql.="FROM ACINS WHERE INS_EST_COD=EST_COD and ins_estado like '%A%' ";
                $cadena_sql.="and ins_ano=".$variable[1]." ";
                $cadena_sql.="and ins_per=".$variable[2].")>0) ESTUDIANTES, ";
                $cadena_sql.="(SELECT COUNT (*) FROM ACINS ";
                $cadena_sql.="INNER JOIN ACEST ON INS_EST_COD = EST_COD ";
                $cadena_sql.="WHERE est_estado_est LIKE estado_cod ";
                $cadena_sql.="and est_ind_cred like '%S%' ";
                $cadena_sql.="and ins_estado like '%A%' ";
                $cadena_sql.="and est_cra_cod=".$variable[0]." ";
                $cadena_sql.="and ins_ano=".$variable[1]." ";
                $cadena_sql.="and ins_per=".$variable[2].") INSCRITOS ";
                $cadena_sql.="from acestado ";
                $cadena_sql.="inner join acest on estado_cod=est_estado_est ";
                $cadena_sql.="inner join acins on est_cod=ins_est_cod ";
                $cadena_sql.="where est_estado_est not in ('A', 'B') ";
                $cadena_sql.="and est_pen_nro>200 ";
                $cadena_sql.="and est_cra_cod=".$variable[0]." ";
                $cadena_sql.="and est_ind_cred like '%S%' ";
                $cadena_sql.="and ins_estado like '%A%' ";
                $cadena_sql.="and ins_ano=".$variable[1]." ";
                $cadena_sql.="and ins_per=".$variable[2]." ";
                $cadena_sql.="order by estado_cod";
                break;

            case "buscar_id":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_planEstudio,";
                $cadena_sql.="planEstudio_ano, ";
                $cadena_sql.="planEstudio_periodo,";
                $cadena_sql.="planEstudio_descripcion,";
                $cadena_sql.="planEstudio_autor, ";
                $cadena_sql.="planEstudio_niveles, ";
                $cadena_sql.="planEstudio_fechaCreacion,";
                $cadena_sql.="PROYECTO.proyecto_nombre,";
                $cadena_sql.="planEstudio_nombre, ";
                $cadena_sql.="planEstudio_observaciones FROM ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="planEstudio AS PLAN ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="proyectoCurricular AS PROYECTO ";
                $cadena_sql.="ON PLAN.id_proyectoCurricular=";
                $cadena_sql.="PROYECTO.id_proyectoCurricular ";
                $cadena_sql.="WHERE PLAN.id_planEstudio=".$variable;
                break;

            case 'seleccionEstados':

                $cadena_sql = "SELECT DISTINCT estado_cod, ";
                $cadena_sql .= "                estado_nombre, ";
                $cadena_sql .= "                (SELECT COUNT (*) ";
                $cadena_sql .= "                 FROM   acest ";
                $cadena_sql .= "                 WHERE  est_ind_cred LIKE '%S%' ";
                $cadena_sql .= "                        AND est_cra_cod = '".$variable."' ";
                $cadena_sql .= "                        AND est_estado_est LIKE estado_cod";
                $cadena_sql .= "                        and est_cod in (select ins_est_cod from acins where ins_estado like '%A%'";
                $cadena_sql .= "                        and ins_est_cod= est_cod";
                $cadena_sql .= "                        and ins_ano = ( SELECT APE_ANO FROM ACASPERI WHERE ape_estado LIKE '%A%')";
                $cadena_sql .= "                        and ins_per = ( SELECT APE_PER FROM ACASPERI WHERE ape_estado LIKE '%A%'))) ";
                $cadena_sql .= "                estudiantes, ";
                $cadena_sql .= "                (SELECT COUNT (*) ";
                $cadena_sql .= "                 FROM   acins ";
                $cadena_sql .= "                        inner join acest ";
                $cadena_sql .= "                          ON ins_est_cod = est_cod ";
                $cadena_sql .= "                 WHERE  est_estado_est LIKE estado_cod ";
                $cadena_sql .= "                        AND est_ind_cred LIKE '%S%' ";
                $cadena_sql .= "                        AND est_cra_cod = '".$variable."' ";
                $cadena_sql .= "                        AND ins_estado LIKE '%A%' ";
                $cadena_sql .= "                        AND ins_ano = (SELECT ape_ano ";
                $cadena_sql .= "                                       FROM   acasperi ";
                $cadena_sql .= "                                       WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "                        AND ins_per = (SELECT ape_per ";
                $cadena_sql .= "                                       FROM   acasperi ";
                $cadena_sql .= "                                       WHERE  ape_estado LIKE '%A%')) inscritos ";
                $cadena_sql .= "FROM   acestado ";
                $cadena_sql .= "WHERE  estado_cod NOT IN ( 'A', 'B' ) " ;


                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>