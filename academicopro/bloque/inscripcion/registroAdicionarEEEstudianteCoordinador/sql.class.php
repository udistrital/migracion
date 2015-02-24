<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarEEEstudianteCoordinador extends sql {
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

            case 'plan_estudio':
                $cadena_sql="SELECT DISTINCT est_pen_nro, est_cra_cod FROM acest";
                $cadena_sql.=" WHERE est_cod=".$variable;

                break;

            case 'estado':
                $cadena_sql="SELECT ESTADO_DESCRIPCION FROM ACESTADO";
                $cadena_sql.=" WHERE ESTADO_COD LIKE '%".$variable."%'";

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
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio  ";
                $cadena_sql.=" where parametro_idPlanEstudio= '".$variable."'";

                break;

            case 'ano_periodo':
                $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

                break;

            case 'electivas_extrinsecas':
                  $cadena_sql="SELECT DISTINCT CLP_ASI_COD, ";
                  $cadena_sql.="CLP_CEA_COD, ";
                  $cadena_sql.="CEA_ABR, ";
                  $cadena_sql.="CLP_PEN_NRO, ";
                  $cadena_sql.="(SELECT DISTINCT PEN_CRA_COD FROM ACPEN WHERE PEN_ASI_COD=CLP_ASI_COD AND PEN_NRO=CLP_PEN_NRO AND PEN_ESTADO LIKE '%A%' AND ROWNUM=1) PEN_CRA_COD, ";
                  $cadena_sql.="(SELECT CRA_NOMBRE FROM ACCRA INNER JOIN ACPEN ON PEN_CRA_COD=CRA_COD WHERE PEN_ASI_COD=CLP_ASI_COD AND PEN_NRO=CLP_PEN_NRO AND PEN_ESTADO LIKE '%A%' AND rownum=1) CRA_NOMBRE, ";
                  $cadena_sql.="DEP_COD, ";
                  $cadena_sql.="DEP_NOMBRE ";
                  $cadena_sql.="FROM ACCLASIFICACPEN ";
                  $cadena_sql.="INNER JOIN GECLASIFICAESPAC ON CLP_CEA_COD=CEA_COD ";
                  $cadena_sql.="INNER JOIN ACCRA ON CLP_CRA_COD=CRA_COD ";
                  $cadena_sql.="INNER JOIN GEDEP ON CRA_DEP_COD=DEP_COD ";
                  $cadena_sql.="INNER JOIN ACPEN ON CLP_ASI_COD=PEN_ASI_COD AND PEN_CRA_COD=CRA_COD ";
                  $cadena_sql.="INNER JOIN ACCURSOS ON CLP_ASI_COD=CUR_ASI_COD ";
                  $cadena_sql.="WHERE CLP_CEA_COD=4 ";
                  $cadena_sql.="AND CLP_ESTADO LIKE '%A%' ";
                  $cadena_sql.="AND CLP_ASI_COD NOT IN (SELECT CLP_ASI_COD FROM ACCLASIFICACPEN WHERE CLP_CEA_COD!=4 AND CLP_PEN_NRO=".$variable[0].") ";
                  $cadena_sql.="AND PEN_ESTADO LIKE '%A%' ";
                  $cadena_sql.="AND CUR_APE_ANO=".$variable[2]." ";
                  $cadena_sql.="AND CUR_APE_PER=".$variable[3]." ";
                  $cadena_sql.="ORDER BY DEP_COD, PEN_CRA_COD, CLP_ASI_COD ";

//                $cadena_sql="SELECT DISTINCT PEN.pen_asi_cod, PEN.pen_sem, CLA.CLP_CEA_COD, PEN.pen_nro, PEN.pen_cra_cod, clas.cea_abr ";
//                $cadena_sql.="FROM acpen PEN ";
//                $cadena_sql.="INNER JOIN acclasificacpen CLA ON pen.pen_asi_cod = cla.clp_asi_cod and pen.pen_nro= cla.clp_pen_nro and pen.pen_cra_cod= cla.clp_cra_cod ";
//                $cadena_sql.="INNER JOIN GECLASIFICAESPAC CLAS ON clas.cea_cod= cla.clp_cea_cod ";
//                $cadena_sql.="WHERE cla. CLP_CEA_COD='4' and CLA.CLP_ESTADO LIKE '%A%' ";
//                $cadena_sql.="and pen.pen_asi_cod not in ";
//                $cadena_sql.="(select clp_asi_cod from acclasificacpen where clp_cea_cod!=4 and clp_pen_nro= ".$variable[0].") ";
//                $cadena_sql.="ORDER BY pen.pen_nro ASC, PEN.PEN_ASI_COD, pen.pen_cra_cod";
//                //echo $cadena_sql;exit;
                break;

            case 'espacio_canceladoPeriodo':

                $cadena_sql="SELECT * ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="WHERE horario_codEstudiante = ".$variable[0];
                $cadena_sql.=" AND horario_idEspacio=".$variable[1];
                $cadena_sql.=" AND horario_ano=".$variable[2];
                $cadena_sql.=" AND horario_periodo=".$variable[3];
                $cadena_sql.=" AND horario_estado=3";
                //echo $cadena_sql;exit;
                break;

            case 'buscar_cargado':

                $cadena_sql="SELECT PEN_CRA_COD, PEN_NRO ";
                $cadena_sql.="FROM ACPEN ";
                $cadena_sql.="WHERE PEN_ASI_COD =".$variable;
                $cadena_sql.=" AND PEN_NRO =".$variable[1];
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";
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

            case 'carrera_ofrece':

                $cadena_sql="SELECT DISTINCT cra_nombre, cra_dep_cod, dep_nombre ";
                $cadena_sql.="FROM accra ";
                $cadena_sql.="INNER JOIN acpen on ACCRA.cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="inner join gedep on cra_dep_cod= dep_cod ";
                $cadena_sql.="WHERE cra_cod=".$variable[1];
                $cadena_sql.=" and pen_nro=".$variable[2];

                break;

            case 'buscarInscrita':

                $cadena_sql="SELECT DISTINCT ins_asi_cod ";
                $cadena_sql.="FROM acins ";
                $cadena_sql.="WHERE ins_asi_cod=".$variable[0];
                $cadena_sql.=" and ins_ano=".$variable[1];
                $cadena_sql.=" and ins_per=".$variable[2];
                $cadena_sql.=" and ins_est_cod=".$variable[3];

                break;

            case 'asignaturasInscritas':

                $cadena_sql="select ins_asi_cod from acins  ";
                $cadena_sql.=" where ins_est_cod=".$variable[0];
                $cadena_sql.=" and ins_ano= ".$variable[1];
                $cadena_sql.=" and ins_per=".$variable[2];

                break;

            case 'creditosInscritos':

                $cadena_sql="select pen_cre from acpen  ";
                $cadena_sql.=" where pen_asi_cod=".$variable;

                break;

            case 'horario_grupos':

                $cadena_sql="SELECT DISTINCT horario.hor_dia_nro DIA,";
                $cadena_sql.=" horario.hor_hora HORA,";
                $cadena_sql.=" sede.sed_id COD_SEDE,";
                $cadena_sql.=" salon.sal_edificio ID_EDIFICIO,";
                $cadena_sql.=" edi.edi_nombre NOM_EDIFICIO,";
                $cadena_sql.=" horario.hor_sal_id_espacio ID_SALON,";
                $cadena_sql.=" salon.sal_nombre NOM_SALON, ";
                $cadena_sql.=" horario.hor_alternativa HOR_ALTERNATIVA ";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                $cadena_sql.=" INNER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                $cadena_sql.=" INNER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable[0];
                $cadena_sql.=" AND cur_cra_cod=".$variable[1];
                $cadena_sql.=" AND cur_ape_ano=".$variable[3];
                $cadena_sql.=" AND cur_ape_per=".$variable[4];
                $cadena_sql.=" AND hor_id_curso=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2,3";
                break;

            case 'horario_grupos_registrar':
               
                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA";
                $cadena_sql.=" FROM ACHORARIOS horario";
                $cadena_sql.=" INNER JOIN ACCURSOS curso ON horario.hor_id_curso=curso.cur_id";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                $cadena_sql.=" AND CUR_ID=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2";

                break;

            case 'buscar_carrerasAbiertas':

                $cadena_sql="SELECT DISTINCT ACE_CRA_COD FROM ACCALEVENTOS ";
                $cadena_sql.="WHERE ACE_ANIO=".$variable[3];
                $cadena_sql.=" AND ACE_PERIODO=".$variable[4];
                $cadena_sql.=" AND ACE_FEC_INI<=SYSDATE ";
                $cadena_sql.="AND ACE_FEC_FIN>=SYSDATE ";
                $cadena_sql.="AND ACE_COD_EVENTO=15 ";
                $cadena_sql.="AND ACE_ESTADO LIKE '%A%' ";
                $cadena_sql.="AND ACE_CRA_COD !=".$variable[1];
                $cadena_sql.=" ORDER BY 1";

//                $cadena_sql="SELECT modulos_idProyectoCurricular ";
//                $cadena_sql.=" FROM ".$configuracion['prefijo']."modulosProyecto ";
//                $cadena_sql.=" WHERE 	modulos_idModulo=4 and modulos_idEstado=1 ";
//                $cadena_sql.=" AND modulos_idProyectoCurricular!=".$variable[1];
                
                break;

            case 'grupos_proyecto':

                $cadena_sql="SELECT DISTINCT CUR_ID,(lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO ";
                $cadena_sql.=" FROM ACHORARIOS ";
                $cadena_sql.=" INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                $cadena_sql.=" AND cur_ape_ano=".$variable[3];
                $cadena_sql.=" AND cur_ape_per=".$variable[4];
                $cadena_sql.=" ORDER BY 1";

                break;

            case 'otros_grupos':

                $cadena_sql="SELECT DISTINCT CUR_ID,(lpad(cur_cra_cod,3,0)||'-'||cur_grupo),CRA_NOMBRE";
                $cadena_sql.=" FROM ACCURSOS curso";
                $cadena_sql.=" INNER JOIN ACHORARIOS horario ON curso.CUR_ID=horario.HOR_ID_CURSO";
                $cadena_sql.=" INNER JOIN ACCRA cra ON curso.CUR_CRA_COD=cra.CRA_COD";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                //$cadena_sql.=" AND CUR_ID!=".$variable[6];
                $cadena_sql.=" AND CUR_CRA_COD=".$variable[5];
                $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'horario_otros_grupos':

                $cadena_sql = "SELECT DISTINCT horario.hor_dia_nro DIA,";
                $cadena_sql.=" horario.hor_hora HORA,";
                $cadena_sql.=" sede.sed_id COD_SEDE,";
                $cadena_sql.=" salon.sal_edificio ID_EDIFICIO,";
                $cadena_sql.=" edi.edi_nombre NOM_EDIFICIO,";
                $cadena_sql.=" horario.hor_sal_id_espacio ID_SALON,";
                $cadena_sql.=" salon.sal_nombre NOM_SALON, ";
                $cadena_sql.=" horario.hor_alternativa HOR_ALTERNATIVA ";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                $cadena_sql.=" INNER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                $cadena_sql.=" INNER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                $cadena_sql.=" WHERE cur_asi_cod=" . $variable[0];
                $cadena_sql.=" AND cur_ape_ano=" . $variable[3];
                $cadena_sql.=" AND cur_ape_per=" . $variable[4];
                $cadena_sql.=" AND hor_id_curso=" . $variable[5];
                $cadena_sql.=" ORDER BY 1,2,3";
                break;

            case 'horario_otrosgrupos_registrar':

                $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable[0];
                $cadena_sql.=" AND CUR_CRA_COD!=".$variable[1];
                $cadena_sql.=" AND cur_APE_ANO=".$variable[3];
                $cadena_sql.=" AND cur_APE_PER=".$variable[4];
                $cadena_sql.=" AND hor_id_curso=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2";

                break;

            case 'horario_registrado':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.="FROM ACHORARIOS ";
                $cadena_sql.="INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                $cadena_sql.="INNER JOIN ACINS ON ACCURSOS.CUR_ID=ACINS.INS_GR ";
                $cadena_sql.="AND ACCURSOS.CUR_APE_ANO=ACINS.INS_ANO AND ACCURSOS.CUR_APE_PER=ACINS.INS_PER ";
                $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                $cadena_sql.=" AND INS_ANO=".$variable[4];
                $cadena_sql.=" AND INS_PER=".$variable[5];
                $cadena_sql.=" ORDER BY 1,2";
                break;

            case 'horario_grupo_nuevo':

                $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                $cadena_sql.=" FROM ACHORARIOS horario";
                $cadena_sql.=" INNER JOIN ACCURSOS curso ON horario.hor_id_curso=curso.cur_id";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                $cadena_sql.=" AND CUR_APE_ANO=".$variable[4];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
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
                $cadena_sql.=" FROM ACCURSOS";
                $cadena_sql.=" WHERE CUR_APE_ANO=".$variable[4];
                $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                break;

            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO ACINS ";
                $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_OBS, INS_ANO, INS_PER, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS,INS_SEM,INS_HOR_ALTERNATIVO) ";
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
                $cadena_sql.="'0', ";
                $cadena_sql.="'".$variable[12]."', ";
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

                $cadena_sql="UPDATE ACCURSOS ";
                $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2]." and ins_gr=".$variable[1]." and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];


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

            case 'espacio_planEstudio':
                $cadena_sql="select distinct pen_cre, pen_nro_ht, pen_nro_hp, pen_nro_aut, clp_cea_cod";
                $cadena_sql.=" from acpen";
                $cadena_sql.=" inner join acclasificacpen on clp_asi_cod= pen_asi_cod and clp_pen_nro= pen_nro";
                $cadena_sql.=" where pen_asi_cod='".$variable[0]."' ";
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