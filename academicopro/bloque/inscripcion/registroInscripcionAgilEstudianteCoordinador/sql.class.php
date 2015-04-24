<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroInscripcionAgilEstudianteCoordinador extends sql {
    function cadena_sql($configuracion, $opcion,$variable="") {

        switch($opcion) {

            
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                $cadena_sql.="WHERE ape_estado like '%A%'";
                break;

            
            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO ACINS ";
                $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_OBS, INS_ANO, INS_PER, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS, INS_SEM, INS_HOR_ALTERNATIVO) ";
                $cadena_sql.="VALUES ('".$variable[3]."',";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'A',";
                $cadena_sql.="'".$variable[7]."',";
                $cadena_sql.="'".$variable[8]."',";
                $cadena_sql.="'".$variable[9]."',";
                $cadena_sql.="'".$variable[10]."',";
                $cadena_sql.="'".$variable[11]."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'".$variable[12]."',";
                $cadena_sql.="'".$variable[13]."')";

                break;

            case 'adicionar_espacio_mysql':

                $cadena_sql="INSERT INTO ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[6]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'4')";

                break;

            case 'actualizar_cupo':

                $cadena_sql="UPDATE ACCURSOS ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2]." and ins_gr=".$variable[1]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
                $cadena_sql.=" WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];


                break;

            case 'registroEvento':

                $cadena_sql="INSERT into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0,'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."')";

                break;

            case 'buscarIDRegistro':

                $cadena_sql="SELECT id_log FROM ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="WHERE log_usuarioProceso='".$variable[0]."'";
                $cadena_sql.=" and log_evento='".$variable[2]."'";
                $cadena_sql.=" and log_registro='".$variable[4]."'";
                $cadena_sql.=" and log_usuarioAfectado='".$variable[5]."'";
                $cadena_sql.=" ORDER BY id_log DESC";

                break;

            case 'borrar_datos_mysql_no_conexion':

                $cadena_sql="DELETE FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_estado = 4";
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[3];
                $cadena_sql.=" AND horario_ano = ".$variable[4];
                $cadena_sql.=" AND horario_periodo = ".$variable[5];
                $cadena_sql.=" AND horario_idEspacio = ".$variable[2];

                break;

            case 'espacio_planEstudio':
                $cadena_sql="SELECT distinct pen_cre, pen_nro_ht, pen_nro_hp, pen_nro_aut, clp_cea_cod, pen_sem";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_pen_nro= pen_nro";
                $cadena_sql.=" where pen_asi_cod='".$variable[0]."' and pen_nro='".$variable[1]."'";
                break;

            case 'espacio_otroPlanEstudio':
                $cadena_sql="SELECT distinct pen_cre, pen_nro_ht, pen_nro_hp, pen_nro_aut, clp_cea_cod, pen_nro, pen_sem";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_pen_nro= pen_nro";
                $cadena_sql.=" where pen_asi_cod='".$variable[0]."' and clp_cea_cod='4'";
                break;

            case 'clasificacionEspacio':
                $cadena_sql=" SELECT clp_cea_cod FROM acclasificacpen";
                $cadena_sql.=" INNER JOIN acest";
                $cadena_sql.=" ON est_cra_cod= clp_cra_cod";
                $cadena_sql.=" AND est_pen_nro= clp_pen_nro ";
                $cadena_sql.=" WHERE clp_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND clp_estado='A'";
                break;
            
            case 'clasificacionExtrinseco':
                $cadena_sql=" SELECT clp_cea_cod FROM acclasificacpen";
                $cadena_sql.=" WHERE clp_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND clp_cea_cod=4";
                $cadena_sql.=" AND clp_estado='A'";
                break;
            
        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>