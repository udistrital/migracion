<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroBloqueEstudiantes extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

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

                        case 'bloques_registrados':
                            //se comentan algunas lineas para que busque el bloque sin tener en cuenta si tiene registrados estudiantes
                            $cadena_sql="SELECT distinct RBPE.bloque_idBloque,RBPE.bloque_idPlanEstudio,RBPE.bloque_idProyecto,RBPE.bloque_estado";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."registroBloquePlanEstudio RBPE";
                            //$cadena_sql.=" inner join sga_registroBloqueEstudiantes RBE on RBE.bloque_idBloque=RBPE.bloque_idBloque ";
                            //$cadena_sql.=" and RBE.bloque_idPlanEstudio=RBPE.bloque_idPlanEstudio and RBE.bloque_idProyecto=RBPE.bloque_idProyecto ";
                            $cadena_sql.=" WHERE RBPE.bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND RBPE.bloque_idProyecto=".$variable[0];
                            //$cadena_sql.=" and RBE.bloque_codEstudiante like '".$variable[3].$variable[4]."%'";
                            $cadena_sql.=" and RBPE.bloque_ano = ".$variable[3];
                            $cadena_sql.=" and RBPE.bloque_periodo = ".$variable[5];
                            $cadena_sql.=" order by 1";
                        break;

                        case 'estudiantes_bloquesRegistrados':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_idBloque= ".$variable[3];
                            $cadena_sql.=" AND bloque_ano= ".$variable[5];
                            $cadena_sql.=" AND bloque_periodo= ".$variable[6];
                        break;

                        case 'espaciosRegistrados':

                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.=" WHERE horario_idBloque =".$variable[3];
                            $cadena_sql.=" AND horario_idProyectoCurricular= ".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio= ".$variable[2];
                            $cadena_sql.=" AND horario_ano= ".$variable[5];
                            $cadena_sql.=" AND horario_periodo= ".$variable[6];
                        break;


                        case 'estudiantes_bloques':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[1];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_codEstudiante= ".$variable[2];
                        break;

                        case 'estudiantes_carrera':
                            $cadena_sql="select est_cod, est_nombre, est_cra_cod  from acest ";
                            $cadena_sql.=" where est_cra_cod= ".$variable[0];
                            $cadena_sql.=" and est_pen_nro= ".$variable[1];
                            $cadena_sql.=" and est_ind_cred like '%S%' and est_estado like '%A%'";
                            $cadena_sql.=" AND cast(EST_COD as text) LIKE '".$variable[2]."%'";
                        break;

                        case 'verificar_bloque':

                            $cadena_sql="SELECT distinct RBPE.bloque_idBloque,RBPE.bloque_idPlanEstudio,RBPE.bloque_idProyecto,RBPE.bloque_estado";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."registroBloquePlanEstudio RBPE";
                            $cadena_sql.=" inner join sga_registroBloqueEstudiantes RBE on RBE.bloque_idBloque=RBPE.bloque_idBloque ";
                            $cadena_sql.=" and RBE.bloque_idPlanEstudio=RBPE.bloque_idPlanEstudio and RBE.bloque_idProyecto=RBPE.bloque_idProyecto ";
                            $cadena_sql.=" WHERE RBE.bloque_idPlanEstudio=".$variable[1];
                            $cadena_sql.=" AND RBE.bloque_idProyecto=".$variable[0];
                            $cadena_sql.=" AND RBE.bloque_idBloque=".$variable[2];
                            $cadena_sql.=" and RBE.bloque_codEstudiante like '".$variable[3].$variable[4]."%'";
                            $cadena_sql.=" and RBE.bloque_ano =".$variable[3];
                            $cadena_sql.=" and RBE.bloque_periodo =".$variable[5];
                        break;

                        case 'verificar_bloque_plan':

                            $cadena_sql="SELECT distinct RBPE.bloque_idBloque,RBPE.bloque_idPlanEstudio,RBPE.bloque_idProyecto,RBPE.bloque_estado";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."registroBloquePlanEstudio RBPE";
                            $cadena_sql.=" WHERE RBPE.bloque_idPlanEstudio=".$variable[1];
                            $cadena_sql.=" AND RBPE.bloque_idProyecto=".$variable[0];
                            $cadena_sql.=" AND RBPE.bloque_idBloque=".$variable[2];
                            $cadena_sql.=" and RBPE.bloque_ano =".$variable[3];
                            $cadena_sql.=" and RBPE.bloque_periodo =".$variable[5];
                        break;

                        case 'borrar_bloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[1];
                            $cadena_sql.=" AND bloque_ano=".$variable[3];
                            $cadena_sql.=" AND bloque_periodo=".$variable[4];
                        break;

                        case 'borrar_estudiantesBloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[1];
                            $cadena_sql.=" AND bloque_ano=".$variable[3];
                            $cadena_sql.=" AND bloque_periodo=".$variable[4];
                        break;

                        case 'borrar_horarioBloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="WHERE horario_idBloque=".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];
                            $cadena_sql.=" AND horario_ano=".$variable[3];
                            $cadena_sql.=" AND horario_periodo=".$variable[4];
                        break;

                        case 'guardar_bloque':
                            $cadena_sql="insert into ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.=" values(".$variable[2].",".$variable[1].",".$variable[0].",'0',".$variable[3].",".$variable[5].")";
                        break;

                        case 'guardar_estudiantes':
                            $cadena_sql="insert into ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.=" values(".$variable[2].",".$variable[1].",".$variable[0].",".$variable[3].",".$variable[4].",".$variable[5].")";
                        break;

                        case 'espacio_grupoBloque':

                            $cadena_sql="SELECT horario_idEspacio   ID_ESPACIO, ";
                            $cadena_sql.=" horario_grupo            ID_GRUPO, ";
                            $cadena_sql.=" espacio_nombre           ESPACIO";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico ";
                            $cadena_sql.="ON ".$configuracion['prefijo']."horarioBloque.horario_idEspacio= ".$configuracion['prefijo']."espacio_academico.id_espacio ";
                            $cadena_sql.=" WHERE horario_idBloque=".$variable[0]; //codigo del bloque
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1]; //codigo del proyecto
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2]; //codigo del planEstudio
                            $cadena_sql.=" AND horario_ano=".$variable[3]; //codigo del planEstudio
                            $cadena_sql.=" AND horario_periodo=".$variable[4]; //codigo del planEstudio
                            $cadena_sql.=" order by 1";
                        break;

                        case 'horario_grupos_registrados':
                            $cadena_sql="SELECT DISTINCT";
                            $cadena_sql.=" horario.hor_dia_nro          DIA,";
                            $cadena_sql.=" horario.hor_hora             HORA,";
                            $cadena_sql.=" sede.sed_id                  SEDE,";
                            $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                            $cadena_sql.=" salon.sal_nombre             SALON,";
                            $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                            $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                            $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                            $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)  GRUPO,";
                            $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                            $cadena_sql.=" FROM achorarios horario";
                            $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                            $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                            $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                            $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                            $cadena_sql.=" WHERE cur_asi_cod=".$variable[0]; //codigo del espacio
                            $cadena_sql.=" AND cur_ape_ano=" . $variable[2];
                            $cadena_sql.=" AND cur_ape_per=" . $variable[3];
                            $cadena_sql.=" AND hor_id_curso=" . $variable[1]; //numero de grupo
                            $cadena_sql.=" ORDER BY 1,2,3";
                        break;

                        case 'espacios_plan_estudio':

                            $cadena_sql="SELECT DISTINCT pen_asi_cod, asi_nombre ";
                            $cadena_sql.="from acpen ";
                            $cadena_sql.="INNER JOIN ACASI ON ACPEN.PEN_ASI_COD=ACASI.ASI_COD  ";
                            $cadena_sql.="WHERE pen_nro=".$variable[1];
                            $cadena_sql.=" AND pen_sem=1 AND pen_estado like '%A%'";
                        break;


                        case 'cupo_grupo':

                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
                            $cadena_sql.="FROM ACCURSO ";
                            $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                            $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                            $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                        break;

                        case 'ano_periodo':
                            $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";
                        break;


                        case 'seleccionar_estudiantes':

                            $cadena_sql="SELECT distinct RBE.bloque_codEstudiante";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."registroBloqueEstudiantes RBE";
                            $cadena_sql.=" WHERE RBE.bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND RBE.bloque_idProyecto=".$variable[1];
                            $cadena_sql.=" AND RBE.bloque_idBloque=".$variable[0];
                            $cadena_sql.=" and RBE.bloque_codEstudiante like '".$variable[3].$variable[4]."%'";
                        break;

                        case 'seleccionar_espaciosInscritos':
                            $cadena_sql="SELECT horario_idEspacio,";
                            $cadena_sql.=" horario_grupo,";
                            $cadena_sql.=" espacio_nroCreditos,";
                            $cadena_sql.=" PEE.id_clasificacion,";
                            $cadena_sql.=" PEE.horasDirecto,";
                            $cadena_sql.=" PEE.horasCooperativo,";
                            $cadena_sql.=" espacio_horasAutonomo,";
                            $cadena_sql.=" PEE.id_nivel ";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."horarioBloque HB ";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON HB.horario_idEspacio=EA.id_espacio ";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON horario_idEspacio=PEE.id_espacio ";
                            $cadena_sql.=" AND horario_idPlanEstudio=PEE.id_planEstudio ";
                            $cadena_sql.=" WHERE horario_idBloque=".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];
                            $cadena_sql.=" AND horario_ano=".$variable[3];
                            $cadena_sql.=" AND horario_periodo=".$variable[5];
                        break;


                        case 'actualizar_cupo':

                            $cadena_sql="UPDATE ACCURSOS ";
                            $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = cur_asi_cod and ins_gr=cur_id and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')) ";
                            $cadena_sql.=" WHERE CUR_ASI_COD='".$variable[1]."' AND CUR_ID='".$variable[2]."'";
                        break;

                        case 'registroEvento':

                            $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                            $cadena_sql.="VALUES(0,'".$variable[0]."',";
                            $cadena_sql.="'".$variable[1]."',";
                            $cadena_sql.="'".$variable[2]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[5]."')";
                        break;

                        case 'inscribir_espaciosMysql':
                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."horario_estudiante ";
                            $cadena_sql.="VALUES('".$variable[0]."',";
                            $cadena_sql.="'".$variable[6]."',";
                            $cadena_sql.="'".$variable[7]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[1]."',";
                            $cadena_sql.="'".$variable[2]."',";
                            $cadena_sql.="'4')";
                        break;

                        case 'inscribir_espaciosOracle':
                            $cadena_sql="INSERT INTO ACINS ";
                            $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_ESTADO, INS_ANO, INS_PER, INS_OBS, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS, INS_SEM, INS_HOR_ALTERNATIVO)";
                            $cadena_sql.=" VALUES('".$variable[6]."',";//Carrera
                            $cadena_sql.="'".$variable[0]."',";//codEstudiante
                            $cadena_sql.="'".$variable[1]."',";//Espacio
                            $cadena_sql.="'".$variable[2]."',";//Grupo
                            $cadena_sql.="'A',";               //Estado
                            $cadena_sql.="'".$variable[3]."',";//Año
                            $cadena_sql.="'".$variable[4]."',";//Periodo
                            $cadena_sql.="'0',";//Observaciones
                            $cadena_sql.="'".$variable[9]."',";               //Cred
                            $cadena_sql.="'".$variable[11]."',";               //HT
                            $cadena_sql.="'".$variable[12]."',";               //HP
                            $cadena_sql.="'".$variable[13]."',";               //AUT
                            $cadena_sql.="'".$variable[10]."',";               //cea_cod
                            $cadena_sql.="'0',";               //Fallas
                            $cadena_sql.="'".$variable[14]."',";               //cea_cod
                            $cadena_sql.="'0')";               //cea_cod
                        break;

                        case 'estado_bloque':
                            $cadena_sql="UPDATE ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.="SET bloque_estado=1";
                            $cadena_sql.=" WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio =".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto =".$variable[1];
                            $cadena_sql.=" AND bloque_ano =".$variable[3];
                            $cadena_sql.=" AND bloque_periodo =".$variable[5];
                        break;

                        case 'periodoActivo':

                            $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                            $cadena_sql.="WHERE ape_estado like '%A%'";
                        break;

                        case "planEstudio":

                            $cadena_sql = "SELECT planEstudio_nombre ";
                            $cadena_sql.="FROM " . $configuracion["prefijo"];
                            $cadena_sql.="planEstudio ";
                            $cadena_sql.="WHERE id_planEstudio=".$variable;
                        break;

                        case 'facultad':

                            $cadena_sql="SELECT id_facultad ";
                            $cadena_sql.="FROM `sga_proyectoCurricular` ";
                            $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;
                        break;

                        case 'consultaFechas':

                            $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                            $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                            $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                            $cadena_sql.="WHERE `id_usuario_afectado` =".$variable[0];
                            $cadena_sql.=" and CFE.id_cobertura_evento =".$variable[1];
                            $cadena_sql.=" AND fecha_ano=".$variable[2];
                            $cadena_sql.=" AND fecha_periodo=".$variable[3];
                            $cadena_sql.=" and id_evento=108";
                            $cadena_sql.=" and fecha_estado=1";
                            $cadena_sql.=" ORDER BY id_evento";
                            break;

                        case 'consultaFechasGeneral':

                            $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                            $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                            $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                            $cadena_sql.=" WHERE CFE.id_cobertura_evento =".$variable[1];
                            $cadena_sql.=" AND fecha_ano=".$variable[2];
                            $cadena_sql.=" AND fecha_periodo=".$variable[3];
                            $cadena_sql.=" and id_evento =108";
                            $cadena_sql.=" and fecha_estado=1";
                            $cadena_sql.=" ORDER BY id_evento";
                        break;
		}
		return $cadena_sql;
	}


}
?>