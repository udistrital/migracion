<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionInscripcionGrupoCoordinador extends sql {
    function cadena_sql($configuracion, $opcion,$variable="") {

        switch($opcion) {

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
                    $cadena_sql.=" where ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" and ins_asi_cod=".$variable[0];
                    $cadena_sql.=" and ins_gr=".$variable[1];

                break;

            case 'actualizarCupos':

                    $cadena_sql="update accurso set cur_nro_ins= ".$variable[2];
                    $cadena_sql.=" where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" and cur_asi_cod=".$variable[0];
                    $cadena_sql.=" and cur_nro=".$variable[1];

                break;

            case 'rangos_proyecto':

                    $cadena_sql="SELECT parametro_creditosPlan,parametros_OB, parametros_OC, parametros_EI, parametros_EE ";
                    $cadena_sql.=" FROM sga_parametro_plan_estudio ";
                    $cadena_sql.=" WHERE parametro_idPlanEstudio =".$variable;

                break;
            
            case 'clasificacion_espacioAdicionar':

                    $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                    $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                    $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[1]." AND id_planEstudio=".$variable[0];

                break;

             case 'espaciosAprobados':

                    $cadena_sql="SELECT not_asi_cod, not_cra_cod ";
                    $cadena_sql.="FROM acnot ";
                    $cadena_sql.="WHERE not_est_cod =".$variable;
                    $cadena_sql.=" AND not_nota >= '30'";

                    break;

            case 'espaciosAprobadosClas':

                  $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD, NOT_CRED, NOT_CEA_COD ";
                  $cadena_sql.="FROM ACNOT ";
                  $cadena_sql.="WHERE NOT_EST_COD =".$variable[0];
                  $cadena_sql.=" AND NOT_NOTA >= '30'";
                  $cadena_sql.=" AND NOT_CEA_COD=".$variable[1];
                  break;

            case 'espaciosInscritosClas':

                $cadena_sql="SELECT INS_CRED  ";
                $cadena_sql.="FROM ACINS ";
                $cadena_sql.="WHERE INS_EST_COD= ".$variable[0];
                $cadena_sql.="AND INS_ANO= ".$variable[2];
                $cadena_sql.="AND INS_PER=".$variable[3];
                $cadena_sql.="AND INS_CEA_COD=".$variable[1];

                break;

            case 'clasificacion':
                $cadena_sql="SELECT CEA_NOM ";
                $cadena_sql.="FROM GECLASIFICAESPAC ";
                $cadena_sql.="WHERE CEA_COD=".$variable;
                break;

            case 'valorCreditosPlan':

                $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];
                //echo $cadena_sql;exit;
                break;

            case 'datosEstudianteHoras':

                $cadena_sql="SELECT est_cra_cod, est_pen_nro, est_nombre, cra_nombre  ";
                $cadena_sql.="FROM acest ";
                $cadena_sql.="INNER JOIN ACCRA ON acest.est_cra_cod=accra.cra_cod ";
                $cadena_sql.="WHERE est_cod=".$variable;
                //echo $cadena_sql;exit;
                break;

            case 'consultaEspaciosEstudiante':
          
                $cadena_sql="select distinct ins_asi_cod, pen_cre ";
                $cadena_sql.="from acins ";
                $cadena_sql.="inner join acpen on acins.ins_asi_cod=acpen.pen_asi_cod ";
                $cadena_sql.="where ins_est_cod= ".$variable['codEstudiante'];
                $cadena_sql.="and ins_ano= (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="and ins_per= (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
//echo $cadena_sql;exit;
                break;

            case 'espacios_planEstudiante':

                $cadena_sql="select pen_asi_cod, pen_nro ";
                $cadena_sql.="from acpen ";
                $cadena_sql.="where pen_asi_cod= ".$variable['codEspacio'];
                $cadena_sql.=" and pen_nro= ".$variable['planEstudioEst'];
//echo $cadena_sql;exit;
                break;

            case 'espacio_electivo':

                $cadena_sql="SELECT CLP_CEA_COD ";
                $cadena_sql.="FROM ACCLASIFICACPEN ";
                $cadena_sql.="WHERE CLP_ASI_COD= ".$variable['codEspacio'];
                $cadena_sql.=" AND CLP_CEA_COD=4";
                $cadena_sql.=" AND CLP_CRA_COD= ".$variable['codProyecto'];
                $cadena_sql.=" AND CLP_ESTADO LIKE '%A%'";//echo $cadena_sql;exit;
                break;

            case 'info_espacioAdicionar':

                $cadena_sql="SELECT espacio_nroCreditos FROM sga_espacio_academico ";
                $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[1];

                break;


            case 'consultaFechas':

                $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                $cadena_sql.="WHERE `id_usuario_afectado` =".$variable[0];
                $cadena_sql.=" and CFE.id_cobertura_evento =".$variable[1];
                $cadena_sql.=" AND fecha_ano=".$variable[2];
                $cadena_sql.=" AND fecha_periodo=".$variable[3];
                $cadena_sql.=" and id_evento between '102' AND '107'";
                $cadena_sql.=" and fecha_estado=1";
                $cadena_sql.=" ORDER BY id_evento";
//echo $cadena_sql;exit;
                break;

            case 'consultaFechasGeneral':

                $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                $cadena_sql.=" WHERE CFE.id_cobertura_evento =".$variable[1];
                $cadena_sql.=" AND fecha_ano=".$variable[2];
                $cadena_sql.=" AND fecha_periodo=".$variable[3];
                $cadena_sql.=" and id_evento between '102' AND '107'";
                $cadena_sql.=" and fecha_estado=1";
                $cadena_sql.=" ORDER BY id_evento";
//echo $cadena_sql;exit;
                break;



            case 'facultad':

                $cadena_sql="SELECT id_facultad ";
                $cadena_sql.="FROM `sga_proyectoCurricular` ";
                $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;

                break;

            case "estado_estudiante":

                    $cadena_sql="select estado_cod, estado_nombre from acest ";
                    $cadena_sql.="inner join acestado on acest.est_estado_est=acestado.estado_cod ";
                    $cadena_sql.="WHERE est_cod=".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

            case "espacio_reprobado":

                    $cadena_sql="select not_asi_cod from acnot ";
                    $cadena_sql.="WHERE not_est_cod=".$variable[0];
                    $cadena_sql.=" AND not_asi_cod=".$variable[1];
                    $cadena_sql.=" AND not_nota<30";
//                    echo $cadena_sql;
//                    exit;
                break;

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                $cadena_sql.="WHERE ape_estado like '%A%'";
                break;

            case 'ano_periodo':
                $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

                break;

            case 'buscar_espacio_oracle':

                $cadena_sql="SELECT * FROM ACINS ";
                $cadena_sql.="WHERE INS_CRA_COD = ".$variable[3];
                $cadena_sql.=" AND INS_EST_COD = ".$variable[0];
                $cadena_sql.=" AND INS_ASI_COD = ".$variable[2];
                $cadena_sql.=" AND INS_ANO = ".$variable[4];
                $cadena_sql.=" AND INS_PER = ".$variable[5];

                break;

            case 'buscar_proyecto_grupo':

                $cadena_sql="select cur_cra_cod, cra_nombre from accurso ";
                $cadena_sql.="inner join accra on accurso.cur_cra_cod=accra.cra_cod ";
                $cadena_sql.=" where cur_nro=".$variable[0];
                $cadena_sql.=" and cur_asi_cod=".$variable[1];
                $cadena_sql.=" AND cur_ape_ano= ".$variable[2];
                $cadena_sql.=" AND cur_ape_per= ".$variable[3];

                break;

            case 'buscar_espacio_mysql':

                $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_estado = 4";
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[3];
                $cadena_sql.=" AND horario_ano = ".$variable[4];
                $cadena_sql.=" AND horario_periodo = ".$variable[5];
                $cadena_sql.=" AND horario_idEspacio = ".$variable[2];

                break;

            case 'horario_registrado':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACINS ON ACHORARIO.HOR_ASI_COD=ACINS.INS_ASI_COD AND ACHORARIO.HOR_NRO=ACINS.INS_GR ";
                $cadena_sql.="AND ACHORARIO.HOR_APE_ANO=ACINS.INS_ANO AND ACHORARIO.HOR_APE_PER=ACINS.INS_PER ";
                $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.=" ORDER BY 1,2";


                break;

            case 'horario_grupo_nuevo':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="WHERE HOR_ASI_COD=".$variable[2]." AND HOR_NRO=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.=" ORDER BY 1,2";


                break;

            case 'cupo_grupo_ins':

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.="FROM ACINS ";
                $cadena_sql.="WHERE INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND INS_ASI_COD=".$variable[2]." AND INS_GR=".$variable[1];

                break;

            case 'cupo_grupo_cupo':

                $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO ";
                $cadena_sql.="FROM ACCURSO ";
                $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


                break;

            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO ACINS ";
                $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_OBS, INS_ANO, INS_PER, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS) ";
                $cadena_sql.="VALUES ('".$variable[3]."',";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'0',";
                $cadena_sql.="(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO= 'A'),";
                $cadena_sql.="(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO= 'A'),";
                $cadena_sql.="'A',";
                $cadena_sql.="'".$variable[7]."',";
                $cadena_sql.="'".$variable[8]."',";
                $cadena_sql.="'".$variable[9]."',";
                $cadena_sql.="'".$variable[10]."',";
                $cadena_sql.="'".$variable[11]."',";
                $cadena_sql.="'0')";

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

                $cadena_sql="UPDATE ACCURSO ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2]." and ins_gr=".$variable[1]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
                $cadena_sql.=" WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


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

            case 'nombre_espacio':

                $cadena_sql="SELECT DISTINCT espacio_nombre, espacio_nroCreditos, clasificacion_abrev ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_academico EA ";
                $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion ";
                $cadena_sql.="WHERE EA.id_espacio=".$variable;

                break;

             case 'requisitos':

                $cadena_sql="SELECT requisitos_previoAprobado, requisitos_idEspacioPrevio, requisitos_idEspacioPosterior, requisitos_idPlanEstudio ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                $cadena_sql.="WHERE requisitos_idPlanEstudio='".$variable[0]."'AND requisitos_idEspacioPosterior='".$variable[1]."'";

                break;

            case 'curso_aprobado':

                $cadena_sql="SELECT NOT_NOTA FROM ACNOT WHERE NOT_ASI_COD = '";
                $cadena_sql.=$variable[0]."' AND NOT_EST_COD ='".$variable[1]."'";

                break;
            
            case 'curso_no_aprobado':

                $cadena_sql="SELECT NOT_NOTA FROM ACNOT WHERE NOT_ASI_COD = '";
                $cadena_sql.=$variable[0]."' AND NOT_EST_COD ='".$variable[1]."'";
                $cadena_sql.=" AND NOT_NOTA <'30'";

                break;

            case 'curso_no_cursado':

                $cadena_sql="SELECT asi_nombre FROM ACASI WHERE ASI_COD = '";
                $cadena_sql.=$variable[0]."'";

                break;

            case 'consultaEspaciosCancelado':

                $cadena_sql="select * ";
                $cadena_sql.="from sga_horario_estudiante ";
                $cadena_sql.=" WHERE horario_estado =3";
                $cadena_sql.=" AND horario_codEstudiante = ".$variable['codEstudiante'];
                $cadena_sql.=" AND horario_ano = ".$variable['anno'];
                $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                $cadena_sql.=" AND horario_grupo = ".$variable['nroGrupo'];
//echo $cadena_sql;exit;
                break;

            case 'otroRequisito':

                $cadena_sql="SELECT COUNT(requisitos_previoAprobado) ";
                //$cadena_sql="SELECT (requisitos_previoAprobado) ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                $cadena_sql.="WHERE requisitos_idPlanEstudio='".$variable[0]."'AND requisitos_idEspacioPosterior='".$variable[1]."' ";

                break;

            case 'requisitoUno':

                $cadena_sql="SELECT (requisitos_previoAprobado) ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                $cadena_sql.="WHERE requisitos_idPlanEstudio='".$variable[0]."'AND requisitos_idEspacioPosterior='".$variable[1]."' ";

                break;

            case "datosProyecto":

                    $cadena_sql="SELECT id_planEstudio , planEstudio_nombre, id_proyectoAcademica ";
                    $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio PE ";
                    $cadena_sql.="inner join ".$configuracion['prefijo']."proyectoCurricular PC on PE.id_proyectoCurricular=PC.id_proyectoCurricular ";
                    $cadena_sql.="WHERE id_planEstudio =".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

            case 'datos_espacio':

                    $cadena_sql="SELECT distinct id_nivel, PEE.`id_espacio` , `espacio_nombre` , `espacio_nroCreditos` , `espacio_horasDirecto` , `espacio_horasCooperativo` , `espacio_horasAutonomo` ";
                    $cadena_sql.=" FROM `sga_espacio_academico` EA ";
                    $cadena_sql.=" INNER JOIN sga_planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                    $cadena_sql.=" WHERE EA.`id_espacio` = ".$variable;

                break;

            case 'espacio_grupo':

                    $cadena_sql="select distinct cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_nro_ins, pen_sem from accurso ";
                    $cadena_sql.="inner join acpen on accurso.cur_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="inner join accra on accurso.cur_cra_cod=acpen.pen_cra_cod ";
                    $cadena_sql.="where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="and cur_asi_cod=".$variable[0];
                    $cadena_sql.="and cur_nro=".$variable[1];
                    $cadena_sql.=" order by 6,1,2";


                break;

            case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                    $cadena_sql.="FROM ACHORARIO ";
                    $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                    $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                    $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                    $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                    $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cadena_sql.=" AND HOR_NRO=".$variable[3];
                    $cadena_sql.=" ORDER BY 1,2,3";

                    break;

                case 'buscar_espacio_aprobado':

                $cadena_sql="SELECT NOT_ASI_COD, NOT_GR, NOT_ANO, NOT_PER, NOT_NOTA FROM ACNOT ";
                $cadena_sql.="WHERE NOT_CRA_COD = ".$variable[3];
                $cadena_sql.=" AND NOT_EST_COD = ".$variable[0];
                $cadena_sql.=" AND NOT_ASI_COD = ".$variable[2];
                $cadena_sql.=" AND NOT_NOTA >=30";

                break;

                case 'buscar_espacio_aprobado_varios':

                $cadena_sql="SELECT NOT_ASI_COD, NOT_GR, NOT_ANO, NOT_PER, NOT_NOTA FROM ACNOT ";
                $cadena_sql.="WHERE NOT_CRA_COD = ".$variable[0];
                $cadena_sql.=" AND NOT_EST_COD = ".$variable[1];
                $cadena_sql.=" AND NOT_ASI_COD = ".$variable[2];
                $cadena_sql.=" AND NOT_NOTA >=30";

                break;
              
                case 'consultarParametrosEstudiante':

                $cadena_sql="SELECT parametro_maxCreditosNivel FROM ".$configuracion['prefijo']."parametro_plan_estudio  ";
                $cadena_sql.="WHERE parametro_idPlanEstudio = ".$variable;

                break;

                case 'espacio_planEstudio':
                $cadena_sql="select distinct pen_cre, pen_nro_ht, pen_nro_hp, pen_nro_aut, clp_cea_cod";
                $cadena_sql.=" from acpen";
                $cadena_sql.=" inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_pen_nro= pen_nro";
                $cadena_sql.=" where pen_asi_cod='".$variable[0]."' and pen_nro='".$variable[1]."'";
                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>