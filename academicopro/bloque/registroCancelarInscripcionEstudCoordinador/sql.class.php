<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarInscripcionEstudCoordinador extends sql {
    function cadena_sql($configuracion,$opcion,$variable="") {

        switch($opcion)
        {
        case 'periodo':
        $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

        break;

        case 'verificar_estado':

        $cadena_sql="SELECT horario_estado ";
        $cadena_sql.="from ".$configuracion['prefijo']."horario_estudiante ";
        $cadena_sql.=" where horario_codEstudiante=".$variable[0];
        $cadena_sql.=" AND ";
        $cadena_sql.=" horario_idProyectoCurricular=".$variable[1];
        $cadena_sql.=" AND ";
        $cadena_sql.=" horario_ano=".$variable[2];
        $cadena_sql.=" AND ";
        $cadena_sql.=" horario_periodo=".$variable[3];
        $cadena_sql.=" AND ";
        $cadena_sql.=" horario_idEspacio=".$variable[4];
        $cadena_sql.=" AND ";
        $cadena_sql.=" horario_grupo=".$variable[5];


        break;


        case 'verificar_creditos':

        $cadena_sql="SELECT semestre_nroCreditosEstudiante ";
        $cadena_sql.="from ".$configuracion['prefijo']."semestre_creditos_estudiante ";
        $cadena_sql.=" where semestre_codEstudiante=".$variable[0];
        $cadena_sql.=" AND ";
        $cadena_sql.=" semestre_idProyectoCurricular=".$variable[1];
        $cadena_sql.=" AND ";
        $cadena_sql.=" semestre_ano=".$variable[2];
        $cadena_sql.=" AND ";
        $cadena_sql.=" semestre_periodo=".$variable[3];

        break;

        case 'minimo_creditos':

        $cadena_sql="SELECT parametro_minCreditosNivel ";
        $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
        //$cadena_sql.=" where parametro_idPlanEstudio =".$variable;

        break;

        case 'creditos_espacio':
        $cadena_sql="SELECT espacio_nroCreditos ";
        $cadena_sql.="from ".$configuracion['prefijo']."espacio_academico ";
        $cadena_sql.=" where id_espacio=".$variable;

        break;

        case 'numero_creditos':

        $cadena_sql="SELECT semestre_nroCreditosEstudiante ";
        $cadena_sql.="from ".$configuracion['prefijo']."semestre_creditos_estudiante ";
        $cadena_sql.=" where semestre_codEstudiante=".$variable[0];
        $cadena_sql.=" and semestre_ano=".$variable[4];
        $cadena_sql.=" and semestre_periodo=".$variable[5];

        break;

        case 'buscar_espacio_oracle':

        $cadena_sql="SELECT * FROM ACINS ";
        $cadena_sql.="WHERE INS_CRA_COD = ".$variable[1];        
        $cadena_sql.=" AND INS_EST_COD = ".$variable[0];
        $cadena_sql.=" AND INS_ASI_COD = ".$variable[5];
        $cadena_sql.=" AND INS_GR = ".$variable[6];
        $cadena_sql.=" AND INS_ANO = ".$variable[2];
        $cadena_sql.=" AND INS_PER = ".$variable[3];
        break;

        case 'buscar_espacio_mysql':
            
        $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."horario_estudiante ";
        $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
        $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[1];
        $cadena_sql.=" AND horario_ano = ".$variable[2];
        $cadena_sql.=" AND horario_periodo = ".$variable[3];
        $cadena_sql.=" AND horario_idEspacio = ".$variable[5];
        $cadena_sql.=" AND horario_grupo = ".$variable[6];
        break;

        case 'cancelar_espacio_oracle':

        $cadena_sql="DELETE FROM ACINS ";
        $cadena_sql.="WHERE INS_CRA_COD = ".$variable[1];
        $cadena_sql.=" AND INS_EST_COD = ".$variable[0];
        $cadena_sql.=" AND INS_ASI_COD = ".$variable[5];
        $cadena_sql.=" AND INS_GR = ".$variable[6];
        $cadena_sql.=" AND INS_ANO = ".$variable[2];
        $cadena_sql.=" AND INS_PER = ".$variable[3];


        break;

        case 'cancelar_espacio_mysql':

        $cadena_sql="UPDATE ".$configuracion['prefijo']."horario_estudiante ";
        $cadena_sql.="SET horario_estado = 3 ";
        $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
        $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[1];
        $cadena_sql.=" AND horario_ano = ".$variable[2];
        $cadena_sql.=" AND horario_periodo = ".$variable[3];
        $cadena_sql.=" AND horario_idEspacio = ".$variable[5];
        $cadena_sql.=" AND horario_grupo = ".$variable[6];
        break;

        case 'registrar_cancelar_espacio_mysql':

        $cadena_sql="INSERT INTO ".$configuracion['prefijo']."horario_estudiante ";
        $cadena_sql.="VALUES ";
        $cadena_sql.="(".$variable[0].",".$variable[1].",".$variable[4].",".$variable[2].",".$variable[3].",".$variable[5].",".$variable[6].",3)";
        break;

        case 'actualizar_creditos':

        $cadena_sql="update ".$configuracion['prefijo']."semestre_creditos_estudiante ";
        $cadena_sql.="set semestre_nroCreditosEstudiante= ".$variable[9];
        $cadena_sql.=" where semestre_codEstudiante=".$variable[0];
        $cadena_sql.=" and semestre_ano=".$variable[2];
        $cadena_sql.=" and semestre_periodo=".$variable[3];

        break;

        case 'cupo_grupo':

        $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
        $cadena_sql.="FROM ACCURSO ";
        $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
        $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
        $cadena_sql.="AND CUR_ASI_COD=".$variable[5]." AND CUR_NRO=".$variable[6]." AND CUR_CRA_COD=".$variable[1];


        break;

        case 'actualizar_cupo':

        $cadena_sql="UPDATE ACCURSO ";
        $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[5]." and ins_gr=".$variable[6]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
        $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[5]." AND CUR_NRO=".$variable[6];


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

        case 'cupo_grupo_ins':

        $cadena_sql="SELECT count(*) ";
        $cadena_sql.="FROM ACINS ";
        $cadena_sql.="WHERE INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
        $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
        $cadena_sql.="AND INS_ASI_COD=".$variable[5]." AND INS_GR=".$variable[6];

        break;

        case 'buscarIDRegistro':

            $cadena_sql="select id_log from ".$configuracion['prefijo']."log_eventos ";
            $cadena_sql.="where log_usuarioProceso='".$variable[0]."'";
            $cadena_sql.=" and log_evento='".$variable[2]."'";
            $cadena_sql.=" and log_registro='".$variable[4]."'";
            $cadena_sql.=" and log_usuarioAfectado='".$variable[5]."'";
            $cadena_sql.=" ORDER BY id_log DESC";

        break;

        case 'asignaturas_inscritas':
            
            $cadena_sql="select distinct ins_asi_cod, pen_cre from acins ";
            $cadena_sql.=" inner join acpen on pen_asi_cod= ins_asi_cod ";
            $cadena_sql.=" where ins_est_cod='".$variable[0]."'";
            $cadena_sql.=" and ins_ano=".$variable[2];
            $cadena_sql.=" and ins_per=".$variable[3];


        break;

        case 'espacio_reprobado':

            $cadena_sql="select distinct not_asi_cod from acnot ";
            $cadena_sql.=" where not_est_cod='".$variable[0]."'";
            $cadena_sql.=" and not_asi_cod='".$variable[5]."'";
            $cadena_sql.=" and not_nota<'30'";
            //$cadena_sql.=" and NOT_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%P%') ";
            //$cadena_sql.=" AND NOT_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%P%') ";

        break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>