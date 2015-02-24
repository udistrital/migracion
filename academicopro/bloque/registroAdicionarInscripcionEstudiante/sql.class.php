<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarInscripcionEstudiante extends sql {
    function cadena_sql($configuracion,$opcion,$variable="") {

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

            case 'plan_estudio':
                $cadena_sql="SELECT DISTINCT EST_PEN_NRO, EST_CRA_COD, ESTADO_DESCRIPCION FROM acest";
                $cadena_sql.=" INNER JOIN ACESTADO ON ACEST.EST_ESTADO_EST=ACESTADO.ESTADO_COD";
                $cadena_sql.=" WHERE EST_COD=".$variable;

                break;

            case 'espacio_cancelado':
                $cadena_sql="SELECT DISTINCT horario_estado FROM ".$configuracion['prefijo']."horario_estudiante";
                $cadena_sql.=" WHERE horario_codEstudiante=".$variable[0];
                $cadena_sql.=" AND horario_idEspacio=".$variable[1];
                $cadena_sql.=" AND horario_ano=".$variable[2];
                $cadena_sql.=" AND horario_periodo=".$variable[3];


                break;

            case 'parametros_plan':

                $cadena_sql="select * ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.="where parametro_idPlanEstudio = ".$variable;

                break;

            case 'ano_periodo':
                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

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
                    $cadena_sql.=" AND not_est_reg LIKE '%A%'";
                break;

            case 'espacios_plan_estudio':

                $cadena_sql="SELECT DISTINCT pen_asi_cod, asi_nombre, pen_sem, clp_cea_cod ";
                $cadena_sql.="from acpen ";
                $cadena_sql.="INNER JOIN ACASI ON ACPEN.PEN_ASI_COD=ACASI.ASI_COD ";
                $cadena_sql.="inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_cra_cod= pen_cra_cod and clp_pen_nro=pen_nro ";
                $cadena_sql.="WHERE pen_nro=".$variable[0];
                $cadena_sql.=" and pen_estado like '%A%'";
                $cadena_sql.=" and pen_asi_cod not in";
                $cadena_sql.=" (select not_asi_cod from acnot";
                $cadena_sql.=" WHERE NOT_EST_COD=".$variable[1];
                $cadena_sql.=" and not_nota>=30 and not_est_reg like '%A%') and pen_asi_cod not in";
                $cadena_sql.=" (select ins_asi_cod from acins ";
                $cadena_sql.=" where ins_est_cod=".$variable[1];
                $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')) ";
                $cadena_sql.=" and clp_cea_cod!=4";
                $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod ";
                //echo $cadena_sql;exit;
                break;

            case 'espacios_plan_estudio_prueba':

                $cadena_sql="SELECT DISTINCT pen_asi_cod, asi_nombre, pen_sem ";
                $cadena_sql.="from acpen ";
                $cadena_sql.="INNER JOIN ACASI ON ACPEN.PEN_ASI_COD=ACASI.ASI_COD  ";
                $cadena_sql.="WHERE pen_nro=".$variable[0];
                $cadena_sql.=" and pen_estado like '%A%'";
                $cadena_sql.=" and pen_asi_cod in";
                $cadena_sql.=" (select distinct not_asi_cod from acnot";
                $cadena_sql.=" WHERE NOT_EST_COD=".$variable[1];
                $cadena_sql.=" and not_nota<30 and not_est_reg like '%A%' and not_asi_cod not in (select not_asi_cod from acnot";
                $cadena_sql.=" WHERE NOT_EST_COD=".$variable[1]." and not_nota>=30 and not_est_reg like '%A%')) and pen_asi_cod not in";
                $cadena_sql.=" (select ins_asi_cod from acins ";
                $cadena_sql.=" where ins_est_cod=".$variable[1];
                $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')) ";
                $cadena_sql.=" ORDER BY pen_sem, pen_asi_cod ";
                //echo $cadena_sql;exit;
                break;

            case 'nombre_espacio':

                $cadena_sql="SELECT DISTINCT espacio_nombre, espacio_nroCreditos ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_academico EA ";
                $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                //$cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion ";
                $cadena_sql.="WHERE EA.id_espacio=".$variable;

                break;

            case 'infoEspacio':

                $cadena_sql="SELECT CLP_CEA_COD, CEA_ABR ";
                $cadena_sql.="FROM GECLASIFICAESPAC ";
                $cadena_sql.="INNER JOIN ACCLASIFICACPEN ON CLP_CEA_COD=CEA_COD " ;
                $cadena_sql.="WHERE CLP_ASI_COD=".$variable[1];
                //$cadena_sql.=" AND CLP_CRA_COD=".$variable[0];
                $cadena_sql.=" AND CLP_PEN_NRO=".$variable[2];
                $cadena_sql.=" ORDER BY 1 ASC";

                break;

            case 'horario_grupos':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                $cadena_sql.=" AND HOR_NRO=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2,3";

                break;

            case 'horario_grupos_registrar':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                $cadena_sql.=" AND HOR_NRO=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2";

                break;

            case 'buscar_carrerasAbiertas':

                $cadena_sql="SELECT DISTINCT ACE_CRA_COD FROM ACCALEVENTOS ";
                $cadena_sql.="WHERE ACE_ANIO=".$variable[3];
                $cadena_sql.=" AND ACE_PERIODO=".$variable[4];
                $cadena_sql.=" AND to_char(ACE_FEC_INI, 'yyyymmdd')<=to_char(sysdate, 'yyyymmdd')";
                $cadena_sql.=" AND to_char(ACE_FEC_FIN, 'yyyymmdd')>=to_char(sysdate, 'yyyymmdd')";
                $cadena_sql.=" AND ACE_COD_EVENTO=15 ";
                $cadena_sql.=" AND ACE_ESTADO LIKE '%A%' ";
                $cadena_sql.=" AND ACE_CRA_COD !=".$variable[1];
                $cadena_sql.=" ORDER BY 1";
//                $cadena_sql="SELECT modulos_idProyectoCurricular ";
//                $cadena_sql.=" FROM ".$configuracion['prefijo']."modulosProyecto ";
//                $cadena_sql.=" WHERE 	modulos_idModulo=4 and modulos_idEstado=1 ";
//                $cadena_sql.=" AND modulos_idProyectoCurricular!=".$variable[1];
                
                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT HOR_NRO ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                $cadena_sql.=" ORDER BY 1";

                break;

            case 'otros_grupos':

                $cadena_sql="SELECT DISTINCT CUR_NRO, CRA_NOMBRE ";
                $cadena_sql.="FROM ACCURSO ";
                $cadena_sql.="INNER JOIN ACHORARIO ON ACCURSO.CUR_ASI_COD=ACHORARIO.HOR_ASI_COD AND ACCURSO.CUR_NRO=ACHORARIO.HOR_NRO ";
                $cadena_sql.="INNER JOIN ACCRA ON ACCURSO.CUR_CRA_COD=ACCRA.CRA_COD  ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[5];
                $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                $cadena_sql.=" ORDER BY 1";

                break;

            case 'horario_otros_grupos':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD, CRA_NOMBRE ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="INNER JOIN ACCRA ON ACCURSO.CUR_CRA_COD=ACCRA.CRA_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                $cadena_sql.=" AND HOR_NRO=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2,3";

                break;

            case 'horario_otrosgrupos_registrar':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD!=".$variable[1];
                $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                $cadena_sql.=" AND HOR_NRO=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2";

                break;

            case 'horario_registrado':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIO ";
                $cadena_sql.="INNER JOIN ACINS ON ACHORARIO.HOR_ASI_COD=ACINS.INS_ASI_COD AND ACHORARIO.HOR_NRO=ACINS.INS_GR ";
                $cadena_sql.="AND ACHORARIO.HOR_APE_ANO=ACINS.INS_ANO AND ACHORARIO.HOR_APE_PER=ACINS.INS_PER ";
                $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                $cadena_sql.=" AND INS_ANO=".$variable[4];
                $cadena_sql.=" AND INS_PER=".$variable[5];
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
                $cadena_sql.="WHERE INS_ANO=".$variable[4];
                $cadena_sql.=" AND INS_PER=".$variable[5];
                $cadena_sql.=" AND INS_ASI_COD=".$variable[2]." AND INS_GR=".$variable[1];

                break;

            case 'cupo_grupo_cupo':

                $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO CUPO,";
                $cadena_sql.=" CUR_CRA_COD CARRERA";
                $cadena_sql.=" FROM ACCURSO";
                $cadena_sql.=" WHERE CUR_APE_ANO=".$variable[4];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


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

            case 'numero_creditos':

                $cadena_sql="SELECT semestre_nroCreditosEstudiante ";
                $cadena_sql.="from ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                $cadena_sql.=" where semestre_codEstudiante=".$variable[0];
                $cadena_sql.=" and semestre_ano=".$variable[4];
                $cadena_sql.=" and semestre_periodo=".$variable[5];

                break;

            case 'crear_creditos':

                $cadena_sql="INSERT INTO ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                $cadena_sql.="VALUES ('".$variable[0]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'')";

                break;

            case 'actualizar_creditos':

                $cadena_sql="update ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                $cadena_sql.="set semestre_nroCreditosEstudiante= ".$variable[6];
                $cadena_sql.=" where semestre_codEstudiante=".$variable[0];
                $cadena_sql.=" and semestre_ano=".$variable[4];
                $cadena_sql.=" and semestre_periodo=".$variable[5];

                break;

            case 'actualizar_cupo':

                $cadena_sql="UPDATE ACCURSO ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2]." and ins_gr=".$variable[1]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


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

            case 'requisitos':

                $cadena_sql="SELECT requisitos_previoAprobado, requisitos_idEspacioPrevio, requisitos_idEspacioPosterior, requisitos_idPlanEstudio ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                $cadena_sql.="WHERE requisitos_idPlanEstudio='".$variable[0]."'AND requisitos_idEspacioPosterior='".$variable[1]."'";

                break;

            case 'curso_aprobado':

                $cadena_sql="SELECT NOT_NOTA FROM ACNOT WHERE NOT_ASI_COD = '";
                $cadena_sql.=$variable[0]."' AND NOT_EST_COD ='".$variable[1]."'";
                $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";
                $cadena_sql.=" order by not_nota desc ";
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

            //$variables=array($codEstudiante,/*$proyecto,*/$ano,$periodo,$planEstudio,$espacio,$grupo,/*$nombre*/,$creditos);
            //$Existe_Espacio=array($_REQUEST['codEstudiante'], - ,$resultado_periodo[0][0],$resultado_periodo[0][1], $_REQUEST['planEstudio'],$_REQUEST['espacio'],$_REQUEST['grupo'],-.$creditos=$_REQUEST['creditos']);
            case 'buscar_espacio_oracle':

                $cadena_sql="SELECT * FROM ACINS ";
                $cadena_sql.="WHERE INS_CRA_COD = ".$variable[3];
                $cadena_sql.=" AND INS_EST_COD = ".$variable[0];
                $cadena_sql.=" AND INS_ASI_COD = ".$variable[2];
                $cadena_sql.=" AND INS_ANO = ".$variable[4];
                $cadena_sql.=" AND INS_PER = ".$variable[5];
                
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

            case 'borrar_datos_mysql_no_conexion':

                $cadena_sql="DELETE FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_estado = 4";
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable[3];
                $cadena_sql.=" AND horario_ano = ".$variable[4];
                $cadena_sql.=" AND horario_periodo = ".$variable[5];
                $cadena_sql.=" AND horario_idEspacio = ".$variable[2];

                break;

            case 'estudianteCancelo':

                $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_estado = 3";
                $cadena_sql.=" AND horario_ano = ".$variable[2];
                $cadena_sql.=" AND horario_periodo = ".$variable[3];
                $cadena_sql.=" AND horario_idEspacio = ".$variable[1];

                break;

            //funcion parametros

            case 'creditosPlan':

                    $cadena_sql="SELECT parametro_creditosPlan, parametros_OB, parametros_OC, parametros_EI, parametros_EE ";
                    $cadena_sql.="FROM sga_parametro_plan_estudio ";
                    $cadena_sql.="WHERE parametro_idPlanEstudio=".$variable;

                    break;

                case 'buscarPlan':

                    $cadena_sql="SELECT est_cra_cod, est_pen_nro ";
                    $cadena_sql.="FROM acest ";
                    $cadena_sql.="WHERE est_cod=".$variable;
                   // echo $cadena_sql;exit;

                    break;

                case 'espaciosAprobados':

                    $cadena_sql="SELECT not_asi_cod, not_cra_cod ";
                    $cadena_sql.="FROM acnot ";
                    $cadena_sql.="WHERE not_est_cod =".$variable;
                    $cadena_sql.=" AND not_nota >= '30'";
                    $cadena_sql.=" AND not_est_reg LIKE '%A%'";

                    break;

                case 'valorCreditosPlan':

                    $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                    $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                    $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];

                    break;

                case 'valorCreditos':

                    $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                    $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                    $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0];

                    break;

                case 'espaciosInscritos':

                    $cadena_sql="SELECT ins_asi_cod, ins_cra_cod ";
                    $cadena_sql.="FROM acins ";
                    $cadena_sql.="WHERE ins_est_cod =".$variable;
                    $cadena_sql.=" AND ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cadena_sql.=" AND ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";

                    break;

                case 'espacio_planEstudio':
                    $cadena_sql="select distinct pen_cre, pen_nro_ht, pen_nro_hp, pen_nro_aut, clp_cea_cod";
                    $cadena_sql.=" from acpen";
                    $cadena_sql.=" inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_pen_nro= pen_nro";
                    $cadena_sql.=" where pen_asi_cod='".$variable[0]."' and pen_nro='".$variable[1]."'";
                break;

                case 'carrera_estudiante':
                    $cadena_sql="SELECT est_cra_cod CARRERA_ESTUDIANTE";
                    $cadena_sql.=" FROM acest";
                    $cadena_sql.=" WHERE est_cod=".$variable[0];
                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>