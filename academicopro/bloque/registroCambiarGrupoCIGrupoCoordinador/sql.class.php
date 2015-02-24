<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCambiarGrupoCIGrupoCoordinador extends sql {
    function cadena_sql($configuracion, $opcion,$variable="") {

        switch($opcion) {

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

            case 'datosEstudiante':

                $cadena_sql="SELECT est_cra_cod, est_pen_nro, est_nombre, cra_nombre  ";
                $cadena_sql.="FROM acest ";
                $cadena_sql.="INNER JOIN ACCRA ON acest.est_cra_cod=accra.cra_cod ";
                $cadena_sql.="WHERE est_cod=".$variable;
                $cadena_sql.=" AND est_ind_cred like '%S%'";
//echo $cadena_sql;exit;
                break;

            case 'espacio_grupoInscritos':

                    $cadena_sql="select distinct count(ins_est_cod) from acins ";
                    $cadena_sql.=" where ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.=" and ins_asi_cod=".$variable[0];
                    $cadena_sql.=" and ins_gr=".$variable[1];

                break;

            case 'actualizarCupos':

                    $cadena_sql="update accurso set cur_nro_ins= ".$variable[2];
                    $cadena_sql.=" where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.=" and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                    $cadena_sql.=" and cur_asi_cod=".$variable[0];
                    $cadena_sql.=" and cur_nro=".$variable[1];

                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT HOR_NRO ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND CUR_NRO!=".$variable[3];
                $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%')";
                $cadena_sql.=" ORDER BY 1";

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

            case 'horario_grupos_registrar':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%')";
                $cadena_sql.=" AND HOR_NRO=".$variable[3];
                $cadena_sql.=" ORDER BY 1,2";

                break;

            case 'horario_registrado':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACINS ON ACHORARIO.HOR_ASI_COD=ACINS.INS_ASI_COD AND ACHORARIO.HOR_NRO=ACINS.INS_GR ";
                $cadena_sql.="AND ACHORARIO.HOR_APE_ANO=ACINS.INS_ANO AND ACHORARIO.HOR_APE_PER=ACINS.INS_PER ";
                $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                $cadena_sql.=" AND ACINS.INS_ASI_COD!=".$variable[1];
                $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.=" ORDER BY 1,2";


                break;

            case 'cupo_grupo_ins':

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.="FROM ACINS ";
                $cadena_sql.="WHERE INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND INS_ASI_COD=".$variable[2]." AND INS_GR=".$variable[1];

                break;

            case 'cupo_grupo_cupo':

                $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO ";
                $cadena_sql.="FROM ACCURSO ";
                $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


                break;

            case "actualizarGrupoOracle":

                $cadena_sql="UPDATE ACINS SET INS_GR=".$variable['nroGrupoNue'];
                $cadena_sql.=" WHERE INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%V%')";
                $cadena_sql.=" AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%V%')";
                $cadena_sql.=" AND INS_ASI_COD=".$variable['codEspacio'];
                $cadena_sql.=" AND INS_EST_COD=".$variable['codEstudiante'];
                
                break;

            case "actualizarGrupoMySQL":

                $cadena_sql="UPDATE sga_horario_estudiante SET horario_grupo=".$variable['nroGrupoNue'];
                $cadena_sql.=" WHERE horario_ano=".$variable['anno'];
                $cadena_sql.=" AND horario_periodo=".$variable['periodo'];
                $cadena_sql.=" AND horario_idEspacio=".$variable['codEspacio'];
                $cadena_sql.=" AND horario_codEstudiante=".$variable['codEstudiante'];

                break;

            case "periodoActivo":

                $cadena_sql="SELECT APE_ANO, APE_PER ";
                $cadena_sql.=" FROM ACASPERI WHERE APE_ESTADO LIKE '%V%'";

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

            case 'buscarIDRegistro':

                $cadena_sql="select id_log from ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="where log_usuarioProceso='".$variable[0]."'";
                $cadena_sql.=" and log_evento='".$variable[2]."'";
                $cadena_sql.=" and log_registro='".$variable[4]."'";
                $cadena_sql.=" and log_usuarioAfectado='".$variable[5]."'";

                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>