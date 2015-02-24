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

class sql_registroHorarioBloqueCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

                        case 'borrar_horarioBloque'://no se usa
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="WHERE horario_idBloque=".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];

                        break;

			
                        case 'espacio_grupoBloque':

                            $cadena_sql="SELECT horario_idEspacio ID_ESPACIO, horario_grupo ID_GRUPO,espacio_nombre ESPACIO, espacio_nroCreditos CREDITOS ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ";
                            $cadena_sql.="ON ".$configuracion['prefijo']."horarioBloque.horario_idEspacio= ".$configuracion['prefijo']."espacio_academico.id_espacio ";
                            $cadena_sql.=" WHERE horario_idBloque=".$variable[0]; //codigo del bloque
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1]; //codigo del proyecto
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2]; //codigo del planEstudio
                            $cadena_sql.=" AND horario_ano=".$variable[3]; //ano
                            $cadena_sql.=" AND horario_periodo=".$variable[4]; //periodo

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
                            $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)  GRUPO,";
                            $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                            $cadena_sql.=" FROM achorarios horario";
                            $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                            $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                            $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                            $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                            $cadena_sql.=" WHERE cur_asi_cod=".$variable[0]; //codigo del espacio
//                            $cadena_sql.=" AND cur_cra_cod=".$variable[1];
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
                            $cadena_sql.=" AND pen_sem=".$variable[3]." AND pen_estado like '%A%'";

//                            echo $cadena_sql;
//                            exit;
                        break;

                        case 'horario_bloqueRegistrado':

                            $cadena_sql="SELECT horario_idEspacio ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.=" WHERE horario_idBloque=".$variable[2]; //codigo del bloque
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[0]; //codigo del proyecto
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[1]; //codigo del planEstudio
                            $cadena_sql.=" AND horario_idEspacio=".$variable[3]; //codigo del espacio
                            $cadena_sql.=" AND horario_ano=".$variable[4]; //ano
                            $cadena_sql.=" AND horario_periodo=".$variable[5]; //periodo

                        break;


                        case 'nombre_espacio':

                            $cadena_sql="SELECT DISTINCT espacio_nombre, espacio_nroCreditos, clasificacion_abrev ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_academico EA ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion ";
                            $cadena_sql.="WHERE EA.id_espacio=".$variable;

                        break;

                        case 'grupos_proyecto':

                            $cadena_sql=" SELECT DISTINCT CUR_ID                    ID_GRUPO, ";
                            $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)  GRUPO";
                            $cadena_sql.=" FROM ACHORARIOS HORARIO";
                            $cadena_sql.=" INNER JOIN ACCURSOS CURSO ON HORARIO.HOR_ID_CURSO = CURSO.CUR_ID ";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                            $cadena_sql.=" ORDER BY 1";

                        break;

                        case 'horario_grupos':
                            
                            $cadena_sql="SELECT DISTINCT";
                            $cadena_sql.=" horario.hor_dia_nro          DIA,";
                            $cadena_sql.=" horario.hor_hora             HORA,";
                            $cadena_sql.=" sede.sed_id                  SEDE,";
                            $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                            $cadena_sql.=" salon.sal_nombre             SALON,";
                            $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                            $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                            $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                            $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                            $cadena_sql.=" FROM achorarios horario";
                            $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                            $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                            $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                            $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                            $cadena_sql.=" WHERE cur_asi_cod=".$variable[0]; //codigo del espacio
                            $cadena_sql.=" AND cur_cra_cod=".$variable[1];
                            $cadena_sql.=" AND cur_ape_ano=" . $variable[4];
                            $cadena_sql.=" AND cur_ape_per=" . $variable[5];
                            $cadena_sql.=" AND hor_id_curso=" . $variable[3]; //numero de grupo
                            $cadena_sql.=" ORDER BY 1,2,3";

                        break;

                        case 'cupo_grupo_ins':

                            $cadena_sql="SELECT count(*) ";
                            $cadena_sql.="FROM ACINS ";
                            $cadena_sql.="WHERE INS_ANO=".$variable[3]." ";
                            $cadena_sql.="AND INS_PER=".$variable[4]." ";
                            $cadena_sql.="AND INS_ASI_COD=".$variable[2]." AND INS_GR=".$variable[1];

                            break;

                        case 'cupo_grupo_cupo':

                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO ";
                            $cadena_sql.="FROM ACCURSOS ";
                            $cadena_sql.="WHERE CUR_APE_ANO=".$variable[3]." ";
                            $cadena_sql.="AND CUR_APE_PER=".$variable[4]." ";
                            $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                            break;

                        case 'cupos_bloque':
                            $cadena_sql="SELECT count(*) ";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."registroBloqueEstudiantes bloque ";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."horarioBloque ON (bloque.bloque_idBloque=horario_idBloque"; //codigo del bloque
                            $cadena_sql.=" AND bloque.bloque_idPlanEstudio=horario_idPlanEstudio";
                            $cadena_sql.=" AND bloque.bloque_idProyecto=horario_idProyectoCurricular";
                            $cadena_sql.=" AND bloque.bloque_ano=horario_ano";
                            $cadena_sql.=" AND bloque.bloque_periodo=horario_periodo)";
                            $cadena_sql.=" INNER JOIN sga_registroBloquePlanEstudio horario ON (bloque.bloque_idBloque=horario.bloque_idBloque";
                            $cadena_sql.=" AND bloque.bloque_idPlanEstudio=horario.bloque_idPlanEstudio";
                            $cadena_sql.=" AND bloque.bloque_idProyecto=horario.bloque_idProyecto";
                            $cadena_sql.=" AND bloque.bloque_ano=horario.bloque_ano";
                            $cadena_sql.=" AND bloque.bloque_periodo=horario.bloque_periodo)";
                            $cadena_sql.=" WHERE horario_idEspacio=".$variable[2]; //codigo del espacio
                            $cadena_sql.=" AND horario_grupo=".$variable[1]; //codigo del grupo
                            $cadena_sql.=" AND bloque_estado=0";//estado del bloque
                            $cadena_sql.=" AND bloque.bloque_idBloque!=".$variable[0];//estado del bloque
                            $cadena_sql.=" AND bloque.bloque_ano=".$variable[3];//estado del bloque
                            $cadena_sql.=" AND bloque.bloque_periodo=".$variable[4];//estado del bloque

                        break;


                        case 'ano_periodo':

                            $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

                       break;

                        case 'adicionar_creditos':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="VALUES('".$variable[0]."',";
                            $cadena_sql.="'".$variable[2]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'".$variable[1]."',";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[5]."',";
                            $cadena_sql.="'".$variable[6]."')";

                       break;

                       case 'cambiarGrupoBloque':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="SET horario_grupo='".$variable[4]."'";
                            $cadena_sql.=" where horario_idBloque='".$variable[0]."'";
                            $cadena_sql.=" and horario_idProyectoCurricular='".$variable[2]."'";
                            $cadena_sql.=" and horario_idPlanEstudio='".$variable[3]."'";
                            $cadena_sql.=" and horario_idEspacio='".$variable[1]."'";
                            $cadena_sql.=" and horario_ano='".$variable[5]."'";
                            $cadena_sql.=" and horario_periodo='".$variable[6]."'";

                       break;

                       case 'borrarEABloque':

                            $cadena_sql="delete from ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="where horario_grupo='".$variable[4]."'";
                            $cadena_sql.=" and horario_idBloque='".$variable[0]."'";
                            $cadena_sql.=" and horario_idProyectoCurricular='".$variable[2]."'";
                            $cadena_sql.=" and horario_idPlanEstudio='".$variable[3]."'";
                            $cadena_sql.=" and horario_idEspacio='".$variable[1]."'";
                            $cadena_sql.=" and horario_ano='".$variable[5]."'";
                            $cadena_sql.=" and horario_periodo='".$variable[6]."'";

                       break;

                        case 'seleccionar_estudiantes'://no se usa

                            $cadena_sql="SELECT bloque_codEstudiante ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[1];

                       break;

                        case 'seleccionar_espaciosInscritos'://no se usa

                            $cadena_sql="SELECT horario_idEspacio, horario_grupo, espacio_nroCreditos ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque HB ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON HB.horario_idEspacio=EA.id_espacio  ";
                            $cadena_sql.="WHERE horario_idBloque=".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];

                       break;

                        

                       case 'periodoActivo':

                            $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                            $cadena_sql.="WHERE ape_estado like '%A%'";
                       break;

                        case 'facultad':

                            $cadena_sql="SELECT id_facultad ";
                            $cadena_sql.="FROM `sga_proyectoCurricular` ";
                            $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;

                        break;

                        case 'horario_grupos_registrar':

                            $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.=" FROM ACHORARIOS HORARIO ";
                            $cadena_sql.=" INNER JOIN ACCURSOS CURSO ON HORARIO.HOR_ID_CURSO = CURSO.CUR_ID ";
                            $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                            $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ID=".$variable[3];
                            $cadena_sql.=" ORDER BY 1,2";

                        break;

                        case 'horario_registrado':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.="FROM ACHORARIOS HORARIO ";
                            $cadena_sql.=" INNER JOIN ACCURSOS CURSO ON HORARIO.HOR_ID_CURSO = CURSO.CUR_ID ";
                            $cadena_sql.=" WHERE CURSO.CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CURSO.CUR_ID=".$variable[1];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[2];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[3];
                            $cadena_sql.=" ORDER BY 1,2";

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

                        case 'bloque_publicado':

                            $cadena_sql="SELECT bloque_estado FROM ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.="WHERE bloque_idPlanEstudio='".$variable[0]."'";
                            $cadena_sql.=" AND bloque_idProyecto='".$variable[1]."'";
                            $cadena_sql.=" and bloque_idBloque='".$variable[2]."'";
                            $cadena_sql.=" and bloque_ano='".$variable[3]."'";
                            $cadena_sql.=" and bloque_periodo='".$variable[4]."'";

                            break;
                        case 'proyecto_propedeutico':

                            $cadena_sql="SELECT PE.planEstudio_propedeutico FROM ".$configuracion['prefijo']."planEstudio PE";
                            $cadena_sql.=" INNER JOIN sga_proyectoCurricular PC ON PE.id_proyectoCurricular=PC.id_proyectoCurricular";
                            $cadena_sql.=" WHERE PC.id_proyectoAcademica='".$variable[0]."'";
                            $cadena_sql.=" AND  PE.id_planEstudio='".$variable[1]."'";
                            
                            break;
                        case 'espacio_adicionado':

                            $cadena_sql="SELECT horario_idBloque FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.=" WHERE horario_idProyectoCurricular='".$variable[2]."'";
                            $cadena_sql.=" AND  horario_idPlanEstudio='".$variable[3]."'";
                            $cadena_sql.=" AND  horario_idEspacio='".$variable[1]."'";
                            $cadena_sql.=" AND  horario_idBloque='".$variable[0]."'";
                            $cadena_sql.=" AND  horario_ano='".$variable[5]."'";
                            $cadena_sql.=" AND  horario_periodo='".$variable[6]."'";
                            
                            break;
                 }
//		echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>