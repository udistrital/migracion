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

class sql_registroEstudiantesBloqueCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

                        case 'proyectos_curriculares':

                            $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                            $cadena_sql.="from accra ";
                            $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                            $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                            $cadena_sql.="where pen_nro>200 ";
                            $cadena_sql.=" and USUCRA_NRO_IDEN=".$variable;
                            $cadena_sql.=" order by 3";
                        break;

//			case 'datos_coordinador':
//                            $cadena_sql="select distinct usucra_cra_cod, cra_nombre, pen_nro ";
//                            $cadena_sql.="from geusucra ";
//                            $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
//                            $cadena_sql.="INNER JOIN ACPEN ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
//                            $cadena_sql.=" where usucra_nro_iden=".$variable;
////                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
//                            $cadena_sql.=" and pen_nro>200";

                        break;

			case 'bloques_registrados':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloquePlanEstudio";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];

                        break;

			case 'estudiantes_bloquesRegistrados':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_idBloque= ".$variable[3];
                            $cadena_sql.=" AND bloque_ano= ".$variable[4];
                            $cadena_sql.=" AND bloque_periodo= ".$variable[5];

                        break;

                        case 'espaciosRegistrados':

                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.=" WHERE horario_idBloque =".$variable[3];
                            $cadena_sql.=" AND horario_idProyectoCurricular= ".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio= ".$variable[2];

                        break;

			case 'estudiantes_bloquesRegistradosEst':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_idBloque= ".$variable[3];
                            $cadena_sql.=" AND bloque_codEstudiante= ".$variable[4];

                        break;

			case 'estudiantes_bloques':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[1];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_codEstudiante= ".$variable[2];
                            $cadena_sql.=" AND bloque_ano= ".$variable[4];
                            $cadena_sql.=" AND bloque_periodo= ".$variable[5];

                        break;

			case 'estudiantes_bloquesSeleccionado':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[1];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_codEstudiante= ".$variable[2];
                            $cadena_sql.=" AND bloque_idBloque = ".$variable[3];
                            $cadena_sql.=" AND bloque_ano = ".$variable[4];
                            $cadena_sql.=" AND bloque_periodo = ".$variable[5];

                        break;

			case 'estudiantes_bloqueSeleccionado':
                            $cadena_sql="SELECT * FROM ".$configuracion['prefijo']."registroBloqueEstudiantes";
                            $cadena_sql.=" WHERE bloque_idPlanEstudio= ".$variable[1];
                            $cadena_sql.=" AND bloque_idProyecto= ".$variable[0];
                            $cadena_sql.=" AND bloque_idBloque = ".$variable[2];

                        break;

			case 'estudiantes_carrera':
                            $cadena_sql="select est_cod, est_nombre, est_cra_cod  from acest ";
                            $cadena_sql.=" where est_cra_cod= ".$variable[0];
//                            $cadena_sql.=" where est_pen_nro= ".$variable[1];
                            $cadena_sql.=" and est_pen_nro= ".$variable[1];
                            $cadena_sql.=" and est_ind_cred like '%S%' and est_estado like '%A%'";
                            $cadena_sql.=" AND cast(EST_COD as text) LIKE '".$variable[2].$variable[3]."%'";
                        break;

                        case 'verificar_bloque':
                            $cadena_sql="SELECT * ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[2];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[1];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[0];

                        break;

                        case 'borrar_bloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[1];

                        break;

                        case 'borrar_estudiantesBloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                            $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND bloque_idProyecto=".$variable[1];

                        break;

                        case 'borrar_horarioBloque':
                            $cadena_sql="DELETE ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                            $cadena_sql.="WHERE horario_idBloque=".$variable[0];
                            $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                            $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];

                        break;

			case 'guardar_bloque':
                            $cadena_sql="insert into ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                            $cadena_sql.=" values(".$variable[2].",".$variable[1].",".$variable[0].",'0')";

                        break;

			case 'guardar_estudiantes':
                            $cadena_sql="insert into ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.=" values(".$variable[2].",".$variable[1].",".$variable[0].",".$variable[3].",".$variable[4].",".$variable[5].")";
                        break;

			case 'borrar_estudiantes':
                            $cadena_sql="delete from ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                            $cadena_sql.=" where bloque_idBloque =".$variable[2];
                            $cadena_sql.=" and bloque_idPlanEstudio =".$variable[1];
                            $cadena_sql.=" and bloque_idProyecto  =".$variable[0];
                            $cadena_sql.=" and bloque_codEstudiante   =".$variable[3];

                        break;

                            case 'espacio_grupoBloque':

                                    $cadena_sql="SELECT horario_idEspacio, horario_grupo,espacio_nombre ";
                                    $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque ";
                                    $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ";
                                    $cadena_sql.="ON ".$configuracion['prefijo']."horarioBloque.horario_idEspacio= ".$configuracion['prefijo']."espacio_academico.id_espacio ";
                                    $cadena_sql.=" WHERE horario_idBloque=".$variable[0]; //codigo del bloque
                                    $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1]; //codigo del proyecto
                                    $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2]; //codigo del planEstudio

                            break;

                       case 'espacios_plan_estudio':

                                    $cadena_sql="SELECT DISTINCT pen_asi_cod, asi_nombre ";
                                    $cadena_sql.="from acpen ";
                                    $cadena_sql.="INNER JOIN ACASI ON ACPEN.PEN_ASI_COD=ACASI.ASI_COD  ";
                                    $cadena_sql.="WHERE pen_nro=".$variable[1];
                                    $cadena_sql.=" AND pen_sem=1 AND pen_estado like '%A%'";

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

                            break;



                            case 'nombre_espacio':

                                    $cadena_sql="SELECT DISTINCT espacio_nombre, espacio_nroCreditos, clasificacion_abrev ";
                                    $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_academico EA ";
                                    $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                                    $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion ";
                                    $cadena_sql.="WHERE EA.id_espacio=".$variable;

                                break;

                   
                            case 'cupo_grupo':

                                    $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
                                    $cadena_sql.="FROM ACCURSOS ";
                                    $cadena_sql.="WHERE CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                                    $cadena_sql.="AND CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                                    $cadena_sql.="AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];


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
                                    $cadena_sql.="'".$variable[4]."')";

                               break;

                            case 'seleccionar_estudiantes':
                                    $cadena_sql="SELECT bloque_codEstudiante ";
                                    $cadena_sql.="FROM ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                                    $cadena_sql.="WHERE bloque_idBloque=".$variable[0];
                                    $cadena_sql.=" AND bloque_idPlanEstudio=".$variable[2];
                                    $cadena_sql.=" AND bloque_idProyecto=".$variable[1];

                               break;

                            case 'seleccionar_espaciosInscritos':
                                    $cadena_sql="SELECT horario_idEspacio, horario_grupo, espacio_nroCreditos ";
                                    $cadena_sql.="FROM ".$configuracion['prefijo']."horarioBloque HB ";
                                    $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON HB.horario_idEspacio=EA.id_espacio  ";
                                    $cadena_sql.="WHERE horario_idBloque=".$variable[0];
                                    $cadena_sql.=" AND horario_idPlanEstudio=".$variable[2];
                                    $cadena_sql.=" AND horario_idProyectoCurricular=".$variable[1];

                               break;

                            case 'registro_estudiante_creditos':
                                    $cadena_sql="INSERT INTO ";
                                    $cadena_sql.=$configuracion['prefijo']."semestre_creditos_estudiante ";
                                    $cadena_sql.="VALUES('".$variable[5]."',";
                                    $cadena_sql.="'".$variable[1]."',";
                                    $cadena_sql.="'".$variable[2]."',";
                                    $cadena_sql.="'".$variable[3]."',";
                                    $cadena_sql.="'".$variable[4]."',";
                                    $cadena_sql.="'".$variable[6]."',";
                                    $cadena_sql.="'0')";

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
                                    $cadena_sql="INSERT INTO ACINS(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_ESTADO, INS_ANO, INS_PER, INS_OBS) ";
                                    $cadena_sql.="VALUES('".$variable[6]."',";//Carrera
                                    $cadena_sql.="'".$variable[0]."',";//codEstudiante
                                    $cadena_sql.="'".$variable[1]."',";//Espacio
                                    $cadena_sql.="'".$variable[2]."',";//Grupo
                                    $cadena_sql.="'A',";               //Estado
                                    $cadena_sql.="'".$variable[3]."',";//Año
                                    $cadena_sql.="'".$variable[4]."',";//Periodo
                                    $cadena_sql.="'0')";               //Observaciones

                               break;

                            case 'estado_bloque':
                                    $cadena_sql="UPDATE ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                                    $cadena_sql.="SET bloque_estado=1";
                                    $cadena_sql.=" WHERE bloque_idBloque=".$variable[0];
                                    $cadena_sql.=" AND bloque_idPlanEstudio =".$variable[2];
                                    $cadena_sql.=" AND bloque_idProyecto =".$variable[1];

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
//echo $cadena_sql;exit;
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
//echo $cadena_sql;exit;
                    break;

                case 'bloque_publicado':

                    $cadena_sql="SELECT bloque_estado FROM ".$configuracion['prefijo']."registroBloquePlanEstudio ";
                    $cadena_sql.="WHERE bloque_idPlanEstudio='".$variable[0]."'";
                    $cadena_sql.=" AND bloque_idProyecto='".$variable[1]."'";
                    $cadena_sql.=" and bloque_idBloque='".$variable[2]."'";
                    $cadena_sql.=" and bloque_ano='".$variable[3]."'";
                    $cadena_sql.=" and bloque_periodo='".$variable[4]."'";

                    break;
                
                case 'estudiante_registrado_bloque':
                    $cadena_sql="SELECT bloque_codEstudiante FROM ".$configuracion['prefijo']."registroBloqueEstudiantes ";
                    $cadena_sql.=" WHERE ";
                    $cadena_sql.=" bloque_ano=".$variable[4];
                    $cadena_sql.=" AND bloque_periodo=".$variable[5];
                    $cadena_sql.=" AND bloque_codEstudiante=".$variable[3];
                    
                    break;

                case 'estudiante_proyecto':
                    $cadena_sql="select est_cod  ";
                    $cadena_sql.="from acest ";
                    $cadena_sql.=" where est_cra_cod= ".$variable[0];
                    $cadena_sql.=" and est_pen_nro= ".$variable[1];
                    $cadena_sql.=" and est_ind_cred like '%S%' and est_estado like '%A%'";
                    $cadena_sql.=" AND cast(EST_COD as text) like '".$variable[4].$variable[6]."%'";
                    $cadena_sql.=" AND EST_COD='".$variable[3]."'";
                    break;
		}
//		echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>