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

class sql_adminConsultarCreditosEstudiante extends sql
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
                    $cadena_sql.="cra_nombre, ";
                    $cadena_sql.="est_acuerdo ";
                    $cadena_sql.="FROM acest ";
                    $cadena_sql.="INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.="WHERE est_cod=".$variable;
//                    echo $cadena_sql;
//                    exit;
                break;

                case "estado_estudiante":

                    $cadena_sql="select estado_cod, estado_nombre from acest ";
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

                    $cadena_sql="SELECT DISTINCT ins_asi_cod, ";//0
                    $cadena_sql.="ins_cra_cod, ";//1
                    $cadena_sql.="ins_gr, ";     //2
                    $cadena_sql.="ins_ano, ";    //3
                    $cadena_sql.="ins_per, ";    //4
                    $cadena_sql.="asi_nombre,";  //5
                    $cadena_sql.="ins_est_cod, ";//6
                    $cadena_sql.="est_pen_nro, ";//7
                    $cadena_sql.="est_nombre, "; //8
                    $cadena_sql.="ins_cred, ";     //9
                    $cadena_sql.="cea_abr ";    //10
                    $cadena_sql.="FROM acins ";
                    $cadena_sql.="LEFT join acasi on acins.ins_asi_cod=acasi.asi_cod ";
                    $cadena_sql.="LEFT join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="LEFT join acpen on acins.ins_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="LEFT outer join geclasificaespac on cea_cod=ins_cea_cod ";
                    $cadena_sql.="WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" AND asi_ind_cred like '%S%'";
                    $cadena_sql.=" AND pen_estado LIKE '%A%'";
                    //$cadena_sql.=" and pen_nro>200";
                    $cadena_sql.=" ORDER BY ins_asi_cod ";
//                    echo $cadena_sql;
//                    exit;

                break;


                case 'clasificacionEspacioPlan':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".$configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable[0]." and id_planEstudio=".$variable[1];
                    break;

                case 'clasificacionEspacio':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".$configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable[0];
                    break;

                case 'clasificacion':

                    $cadena_sql="SELECT id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".$configuracion['prefijo']."espacio_clasificacion";
                    break;

                case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                    $cadena_sql.="FROM ACHORARIO ";
                    $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                    $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                    $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0][0]; //codigo del espacio
//                    $cadena_sql.=" AND CUR_CRA_COD=".$variable[0][1];  //codigo del proyecto curricular
                    $cadena_sql.=" AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.=" AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                    $cadena_sql.=" AND HOR_NRO=".$variable[0][2];//numero de grupo
                    $cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden

                    //echo "cadena".$cadena_sql;
                    //exit;

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

                    $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
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
                    $cadena_sql.=" and id_evento between 102 and 107";
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
                    $cadena_sql.=" and id_evento between 102 and 107";
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

                case 'buscarPlan':

                    $cadena_sql="SELECT est_cra_cod, est_pen_nro ";
                    $cadena_sql.="FROM acest ";
                    $cadena_sql.="WHERE est_cod=".$variable;
                    //echo $cadena_sq."---";exit;

                    break;

                case 'espaciosAprobados':

                    $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD, NOT_CRED, NOT_CEA_COD ";
                    $cadena_sql.="FROM ACNOT ";
                    $cadena_sql.="WHERE NOT_EST_COD =".$variable;
                    $cadena_sql.=" AND NOT_NOTA >= '30'";
                    $cadena_sql.=" AND NOT_EST_REG LIKE '%A%'";

                    break;

                case 'valorCreditosPlan':

                    $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                    $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                    $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];

                    break;
                
                case 'preinscripcion_estudiante':
                    $cadena_sql=" SELECT Fua_Realizo_Preins(".$variable.") FROM DUAL";
                    break;
                

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>