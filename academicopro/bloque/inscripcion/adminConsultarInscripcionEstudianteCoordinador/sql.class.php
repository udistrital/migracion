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

class sql_adminConsultarInscripcionEstudianteCoordinador extends sql
{	//@ Método que crea las sentencias sql para el modulo admin_noticias
   function cadena_sql($configuracion,$tipo,$variable="")
	{

	 switch($tipo)
	 {
                
                #consulta de datos del estudiante
                case "consultaEstudiante":
                    $cadena_sql="SELECT est_cod, ";
                    $cadena_sql.="est_nombre, ";
                    $cadena_sql.="est_pen_nro, ";
                    $cadena_sql.="est_cra_cod, ";
                    $cadena_sql.="cra_nombre ";
                    $cadena_sql.="FROM acest ";
                    $cadena_sql.="INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.="WHERE est_cod=".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

                case "estado_estudiante":

                    $cadena_sql="SELECT estado_cod, estado_nombre FROM acest ";
                    $cadena_sql.="inner join acestado on acest.est_estado_est=acestado.estado_cod ";
                    $cadena_sql.="WHERE est_cod=".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

                case "buscar_adiciones_estudiantes":
                    
                    $cadena_sql="SELECT modulos_estadoEstudiantes ";
                    $cadena_sql.="FROM ".$configuracion['prefijo']."modulosProyecto ";
                    $cadena_sql.="WHERE modulos_idProyectoCurricular = ".$variable[1];
                    $cadena_sql.=" AND modulos_idPlanEstudio = ".$variable[0];
                    $cadena_sql.=" AND modulos_idModulo = 4 ";
                    $cadena_sql.=" AND modulos_idEstado = 1 ";

//                    echo $cadena_sql;
//                    exit;
                break;


                case 'consultaGrupo':

                    $cadena_sql="SELECT DISTINCT  ";//1
                    $cadena_sql.=" ins_asi_cod      CODIGO, ";//0
                    $cadena_sql.=" ins_cra_cod      PROYECTO, ";//1
                    $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO,";
                    $cadena_sql.=" ins_gr           ID_GRUPO, ";    //9
                    $cadena_sql.=" ins_ano          ANIO, ";                 //3
                    $cadena_sql.=" ins_per          PERIODO, ";              //4
                    $cadena_sql.=" asi_nombre       NOMBRE,";              //5
                    $cadena_sql.=" ins_est_cod      COD_ESTUDIANTE, ";              //6
                    $cadena_sql.=" est_pen_nro      PENSUM, ";              //7
                    $cadena_sql.=" est_nombre       ESTUDIANTE, ";    //8
                    $cadena_sql.=" ins_cred         CREDITOS, ";    //9
                    $cadena_sql.=" cea_abr          CLASIFICACION";    //10
                    $cadena_sql.=" FROM acins ";
                    $cadena_sql.=" INNER JOIN acasi on acins.ins_asi_cod=acasi.asi_cod ";
                    $cadena_sql.=" INNER JOIN accursos on acins.ins_gr=accursos.cur_id ";
                    $cadena_sql.=" INNER JOIN acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.=" LEFT JOIN acpen on acins.ins_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.=" LEFT outer join geclasificaespac on cea_cod=ins_cea_cod ";
                    $cadena_sql.=" WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" AND asi_ind_cred like '%S%'";
                    $cadena_sql.=" AND pen_estado LIKE '%A%'";
//                    $cadena_sql.=" and pen_nro>200";
                    $cadena_sql.=" ORDER BY ins_asi_cod ";

//                    echo $cadena_sql;
//                    exit;

                break;


                case 'clasificacionEspacio':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".$configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable;
                    break;

                case 'clasificacion':

                    $cadena_sql="SELECT id_clasificacion,clasificacion_abrev,clasificacion_nombre FROM ".$configuracion['prefijo']."espacio_clasificacion";
                    break;

                case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT  ";
                    $cadena_sql.=" hor.hor_dia_nro      DIA, ";
                    $cadena_sql.=" hor.hor_hora         HORA, ";
                    $cadena_sql.=" hor.hor_alternativa  HOR_ALTERNATIVO, ";
                    $cadena_sql.=" sede.sed_id          SEDE, ";
                    $cadena_sql.=" edif.edi_nombre      EDIFICIO,";
                    $cadena_sql.=" salon.sal_nombre     SALON,";
                    $cadena_sql.=" hor.hor_id           HOR_ID, ";
                    $cadena_sql.=" hor.hor_id_curso     ID_CURSO, ";
                    $cadena_sql.=" hor.hor_estado       ESTADO";
                    $cadena_sql.=" FROM achorarios hor";
                    $cadena_sql.=" INNER JOIN accursos curso ON hor.hor_id_curso=curso.cur_id ";
                    $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON hor.hor_sal_id_espacio = salon.sal_id_espacio AND salon.sal_estado='A' ";
                    $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                    $cadena_sql.=" LEFT OUTER JOIN geedificio edif ON salon.sal_edificio=edif.edi_cod ";
                    $cadena_sql.=" WHERE curso.cur_asi_cod=".$variable[0]['CODIGO']; //codigo del espacio
//                    $cadena_sql.=" AND CUR_CRA_COD=".$variable[0][1];  //codigo del proyecto curricular
                    $cadena_sql.=" AND curso.cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" AND curso.cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cadena_sql.=" AND hor.hor_id_curso=".$variable[0]['ID_GRUPO'];//numero de grupo
                    $cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden
                break;

                case 'consultaCreditosSemestre':

                    $cadena_sql="SELECT semestre_nroCreditosEstudiante ";
                    $cadena_sql.="FROM ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                    $cadena_sql.="WHERE semestre_codEstudiante=".$variable; //codigo del espacio

                    //echo "cadena".$cadena_sql;
                    //exit;

                break;

                case 'consultaRegistroHorario':

                    $cadena_sql="SELECT horario_codEstudiante, horario_idProyectoCurricular, horario_idPlanEstudio, horario_ano, horario_periodo, espacio_nroCreditos ";
                    $cadena_sql.=" FROM ".$configuracion['prefijo']."horario_estudiante HE ";
                    $cadena_sql.="inner join ".$configuracion['prefijo']."espacio_academico EA on HE.horario_idEspacio=EA.id_espacio ";
                    $cadena_sql.="WHERE horario_codEstudiante=".$variable; //codigo del estudiante
                    $cadena_sql.=" AND horario_estado!='3'"; //codigo del estudiante

                    //echo "cadena".$cadena_sql;
                    //exit;

                break;

                case 'grabarCreditosNuevo':

                    $cadena_sql="INSERT INTO ".$configuracion['prefijo']."semestre_creditos_estudiante ";
                    $cadena_sql.=" VALUES( ";
                    $cadena_sql.="'".$variable[0]."',";
                    $cadena_sql.="'".$variable[1]."',";
                    $cadena_sql.="'".$variable[2]."',";
                    $cadena_sql.="'".$variable[3]."',";
                    $cadena_sql.="'".$variable[4]."',";
                    $cadena_sql.="'".$variable[5]."',";
                    $cadena_sql.="'0')";

                    //echo "cadena".$cadena_sql;
                    //exit;

                break;

                case 'periodoActivo':

                    $cadena_sql="SELECT ape_ano, ape_per FROM acasperi ";
                    $cadena_sql.="WHERE ape_estado like '%A%'";
                    break;

                case 'consultaFechas':

                    $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                    $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                    $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable[0];
                    $cadena_sql.=" and CFE.id_cobertura_evento =".$variable[1];
                    $cadena_sql.=" AND fecha_ano=".$variable[2];
                    $cadena_sql.=" AND fecha_periodo=".$variable[3];
                    $cadena_sql.=" and (id_evento=102 OR id_evento=103 OR id_evento=106 OR id_evento=107)";
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
                    $cadena_sql.=" and (id_evento=102 OR id_evento=103 OR id_evento=106 OR id_evento=107)";
                    $cadena_sql.=" and fecha_estado=1";
                    $cadena_sql.=" ORDER BY id_evento";
//echo $cadena_sql;exit;
                    break;



                case 'facultad':

                    $cadena_sql="SELECT id_facultad ";
                    $cadena_sql.="FROM `sga_proyectoCurricular` ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;
                    
                    break;

                case 'buscarPlan':

                    $cadena_sql="SELECT est_cra_cod, est_pen_nro ";
                    $cadena_sql.="FROM acest ";
                    $cadena_sql.="WHERE est_cod=".$variable;

                    break;

                case 'creditosPlan':

                    $cadena_sql="SELECT parametro_creditosPlan, parametros_OB, parametros_OC, parametros_EI, parametros_EE ";
                    $cadena_sql.="FROM sga_parametro_plan_estudio ";
                    $cadena_sql.="WHERE parametro_idPlanEstudio=".$variable;

                    break;

                case 'espaciosAprobados':

                    $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD, NOT_CRED, NOT_CEA_COD ";
                    $cadena_sql.="FROM ACNOT ";
                    $cadena_sql.="INNER JOIN ACEST ON EST_COD=NOT_EST_COD AND NOT_CRA_COD=EST_CRA_COD ";
                    $cadena_sql.="WHERE NOT_EST_COD =".$variable;
                    $cadena_sql.=" AND NOT_NOTA >= '30'";
                    $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";

                    break;

                case 'valorCreditosPlan':

                    $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                    $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                    $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];
                    //echo $cadena_sql;exit;

                    break;

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>