<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarCIGrupoEstudianteCoordinador extends sql {
    function cadena_sql($configuracion,$opcion,$variable="") {

        switch($opcion) {
            case 'periodo':
                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%'";
                
                break;

            case 'verificar_estado':

                $cadena_sql="SELECT horario_estado ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.=" WHERE horario_codEstudiante=".$variable[0];
                $cadena_sql.=" AND ";               
                $cadena_sql.=" horario_ano=".$variable[1];
                $cadena_sql.=" AND ";
                $cadena_sql.=" horario_periodo=".$variable[2];
                $cadena_sql.=" AND ";
                $cadena_sql.=" horario_idEspacio=".$variable[3];
                $cadena_sql.=" AND ";
                $cadena_sql.=" horario_grupo=".$variable[4];
               // $cadena_sql.=" horario_idProyectoCurricular=".$variable[1];
               // $cadena_sql.=" AND ";
               // $cadena_sql.=" AND ";
               // $cadena_sql.=" horario_grupo=".$variable[4];

                break;

            case 'buscar_espacio_oracle':

                $cadena_sql="SELECT * FROM ACINS ";
                //$cadena_sql.="WHERE INS_CRA_COD = ".$variable[1];
                //$cadena_sql.=" AND INS_EST_COD = ".$variable[0];
                $cadena_sql.="WHERE INS_EST_COD = ".$variable[0];
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

            case "estudiantesNoCancelados":

                    $cadena_sql="select est_cod, est_nombre, cra_nombre,est_ind_cred ";
                    $cadena_sql.="from acins ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join accra on acest.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.=" where ins_asi_cod=".$variable[0];
                    $cadena_sql.=" and ins_gr=".$variable[1];
                    $cadena_sql.=" and est_cod=".$variable[2];
                    $cadena_sql.=" ORDER BY 1";

                break;

            case "estudiantesCancelados":

                    $cadena_sql="select est_cod, est_nombre, cra_nombre,est_ind_cred ";
                    $cadena_sql.="from acest ";
                    $cadena_sql.="inner join accra on acest.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.=" where est_cod=".$variable;

                break;
            

            //Hasta aca se utilizan          

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
                $cadena_sql.=" where parametro_idPlanEstudio =".$variable;

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

            case 'actualizar_creditos':

                $cadena_sql="update ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                $cadena_sql.="set semestre_nroCreditosEstudiante= ".$variable[0];
                $cadena_sql.=" where semestre_codEstudiante=".$variable[1];
                $cadena_sql.=" and semestre_ano=".$variable[2];
                $cadena_sql.=" and semestre_periodo=".$variable[3];

                break;
           
            case 'cancelar_espacio_oracle':

                $cadena_sql="DELETE FROM ACINS ";
                //$cadena_sql.="WHERE INS_CRA_COD = ".$variable[1];
                //$cadena_sql.=" AND INS_EST_COD = ".$variable[0];
                $cadena_sql.="WHERE INS_EST_COD = ".$variable[0];
                $cadena_sql.=" AND INS_ASI_COD = ".$variable[5];
                $cadena_sql.=" AND INS_GR = ".$variable[6];
                $cadena_sql.=" AND INS_ANO = ".$variable[2];
                $cadena_sql.=" AND INS_PER = ".$variable[3];


                break;

            case 'cancelar_espacio_mysql':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="SET horario_estado = 3 ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_ano = ".$variable[2];
                $cadena_sql.=" AND horario_periodo = ".$variable[3];
                $cadena_sql.=" AND horario_idEspacio = ".$variable[5];
                $cadena_sql.=" AND horario_grupo = ".$variable[6];
                //$cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[1];


                break;            

            case 'cupo_grupo':

                $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
                $cadena_sql.="FROM ACCURSO ";
                $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[5]." AND CUR_NRO=".$variable[6]." AND CUR_CRA_COD=".$variable[1];


                break;

            case 'actualizar_cupo':

                $cadena_sql="UPDATE ACCURSO ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[5]." and ins_gr=".$variable[6]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%'))";
                $cadena_sql.=" WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[5]." AND CUR_NRO=".$variable[6]." AND CUR_CRA_COD=".$variable[1];


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
                $cadena_sql.="WHERE INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
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

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>
