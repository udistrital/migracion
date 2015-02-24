<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarInscripcionEstudiantesInactivos extends sql {

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

            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                $cadena_sql.="WHERE ape_estado like '%A%'";
                break;

            case 'estudiantes_asignaturas':
                $cadena_sql .= "SELECT DISTINCT est_cod, ";
                $cadena_sql .= "                est_nombre, ";
                $cadena_sql .= "                ins_asi_cod, ";
                $cadena_sql .= "                asi_nombre, ";
                $cadena_sql .= "                ins_gr, ";
                $cadena_sql .= "                ins_cra_cod, ";
                $cadena_sql .= "                cra_nombre ";
                $cadena_sql .= "FROM   acins ";
                $cadena_sql .= "       inner join acest ON ins_est_cod = est_cod ";
                $cadena_sql .= "       inner join acasi ON asi_cod = ins_asi_cod ";
                $cadena_sql .= "       inner join accra ON ins_cra_cod = cra_cod ";
                $cadena_sql .= "WHERE  est_estado_est LIKE '%".$variable[0]."%' ";
                $cadena_sql .= "       AND est_ind_cred LIKE '%S%' ";
                $cadena_sql .= "       AND est_cra_cod = '".$variable[1]."' ";
                $cadena_sql .= "       AND ins_estado LIKE '%A%' ";
                $cadena_sql .= "       AND ins_ano = (SELECT ape_ano FROM   acasperi WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND ins_per = (SELECT ape_per FROM   acasperi WHERE  ape_estado LIKE '%A%') " ;
                $cadena_sql .= "       ORDER BY est_cod " ;
                break;

            case 'datos_estudiante':
                $cadena_sql .= "SELECT DISTINCT est_cod, est_nombre ";
                $cadena_sql .= "FROM   acest ";
                $cadena_sql .= "WHERE  est_cod = '".$variable."' ";
                break;

            case 'actualizar_cupo':

                $cadena_sql="UPDATE ACCURSO ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[0]." and ins_gr=".$variable[1]." and ins_ano=".$variable[2]." and ins_per=".$variable[3].")";
                $cadena_sql.=" WHERE CUR_APE_ANO=".$variable[2]." ";
                $cadena_sql.="AND CUR_APE_PER=".$variable[3]." ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[0]." AND CUR_NRO=".$variable[1];


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

            case 'estudiante_inscripciones':
                $cadena_sql .= "SELECT DISTINCT est_cod, ";
                $cadena_sql .= "                est_nombre, ";
                $cadena_sql .= "                ins_asi_cod, ";
                $cadena_sql .= "                asi_nombre, ";
                $cadena_sql .= "                ins_gr, ";
                $cadena_sql .= "                ins_cra_cod, ";
                $cadena_sql .= "                cra_nombre ";
                $cadena_sql .= "FROM   acins ";
                $cadena_sql .= "       inner join acest ON ins_est_cod = est_cod ";
                $cadena_sql .= "       inner join acasi ON asi_cod = ins_asi_cod ";
                $cadena_sql .= "       inner join accra ON ins_cra_cod = cra_cod ";
                $cadena_sql .= "WHERE  est_estado_est LIKE '%".$variable[0]."%' ";
                $cadena_sql .= "       AND est_ind_cred LIKE '%S%' ";
                $cadena_sql .= "       AND est_cod ='".$variable[2]."'";
                $cadena_sql .= "       AND est_cra_cod = '".$variable[1]."' ";
                $cadena_sql .= "       AND ins_estado LIKE '%A%' ";
                $cadena_sql .= "       AND ins_ano = (SELECT ape_ano FROM   acasperi WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND ins_per = (SELECT ape_per FROM   acasperi WHERE  ape_estado LIKE '%A%') " ;
                $cadena_sql .= "       ORDER BY est_cod " ;
                break;

            case 'cancelarEspaciosOracle':
                $cadena_sql .= "DELETE FROM ACINS ";
                $cadena_sql .= " WHERE ins_est_cod ='".$variable[2]."'";
                $cadena_sql .= " AND ins_cra_cod = '".$variable[1]."' ";
                $cadena_sql .= " AND ins_estado LIKE '%A%' ";
                $cadena_sql .= " AND ins_ano = (SELECT ape_ano FROM   acasperi WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= " AND ins_per = (SELECT ape_per FROM   acasperi WHERE  ape_estado LIKE '%A%') " ;
                break;
            
            case 'cancelarEspaciosMySQL':
                $cadena_sql .= "UPDATE ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql .= " SET horario_estado ='3'";
                $cadena_sql .= " WHERE horario_codEstudiante = '".$variable[2]."' ";
                $cadena_sql .= " AND horario_ano = '".$variable[3]."' ";
                $cadena_sql .= " AND horario_periodo = '".$variable[4]."' ";
                break;

            case 'cancelarAsignaturaOracle':
                $cadena_sql .= "DELETE FROM ACINS ";
                $cadena_sql .= " WHERE ins_est_cod ='".$variable[0]."'";
                $cadena_sql .= " AND ins_asi_cod = '".$variable[1]."' ";
                $cadena_sql .= " AND ins_gr = '".$variable[2]."' ";
                $cadena_sql .= " AND ins_estado LIKE '%A%' ";
                $cadena_sql .= " AND ins_ano = '".$variable[3]."' ";
                $cadena_sql .= " AND ins_per = '".$variable[4]."' " ;
                break;

            case 'cancelarAsignaturaMySQL':
                $cadena_sql .= "UPDATE ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql .= " SET horario_estado ='3'";
                $cadena_sql .= " WHERE horario_codEstudiante = '".$variable[0]."' ";
                $cadena_sql .= " AND horario_idEspacio = '".$variable[1]."' ";
                $cadena_sql .= " AND horario_ano = '".$variable[3]."' ";
                $cadena_sql .= " AND horario_periodo = '".$variable[4]."' ";
                break;

            case 'estudiante_inscripcionesCancelar':
                $cadena_sql .= "SELECT DISTINCT est_cod, ";
                $cadena_sql .= "                est_nombre, ";
                $cadena_sql .= "                ins_asi_cod, ";
                $cadena_sql .= "                asi_nombre, ";
                $cadena_sql .= "                ins_gr, ";
                $cadena_sql .= "                ins_cra_cod, ";
                $cadena_sql .= "                cra_nombre ";
                $cadena_sql .= "FROM   acins ";
                $cadena_sql .= "       inner join acest ON ins_est_cod = est_cod ";
                $cadena_sql .= "       inner join acasi ON asi_cod = ins_asi_cod ";
                $cadena_sql .= "       inner join accra ON ins_cra_cod = cra_cod ";
                $cadena_sql .= "WHERE  est_estado_est LIKE '%".$variable[0]."%' ";
                $cadena_sql .= "       AND est_ind_cred LIKE '%S%' ";
                $cadena_sql .= "       AND est_cod ='".$variable[2]."'";
                $cadena_sql .= "       AND est_cra_cod = '".$variable[1]."' ";
                $cadena_sql .= "       AND ins_asi_cod = '".$variable[3]."' ";
                $cadena_sql .= "       AND ins_estado LIKE '%A%' ";
                $cadena_sql .= "       AND ins_ano = (SELECT ape_ano FROM   acasperi WHERE  ape_estado LIKE '%A%') ";
                $cadena_sql .= "       AND ins_per = (SELECT ape_per FROM   acasperi WHERE  ape_estado LIKE '%A%') " ;
                $cadena_sql .= "       ORDER BY est_cod " ;
                break;
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>