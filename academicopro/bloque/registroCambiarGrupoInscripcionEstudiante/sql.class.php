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

class sql_registroCambiarGrupoInscripcionEstudiante extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

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

//			case 'plan_estudio':
//                            $cadena_sql="SELECT DISTINCT est_pen_nro, est_cra_cod FROM acest";
//                            $cadena_sql.=" WHERE est_cod=".$variable;
//
//                        break;
//
//			case 'parametros_plan':
//
//                            $cadena_sql="SELECT * ";
//                            $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio  ";
//                            $cadena_sql.=" where parametro_idPlanEstudio=".$variable;
//
//                        break;
//
//                         case 'buscar_carrerasAbiertas':
//
//                            $cadena_sql="SELECT modulos_idProyectoCurricular ";
//                            $cadena_sql.=" FROM ".$configuracion['prefijo']."modulosProyecto ";
//                            $cadena_sql.=" WHERE modulos_idModulo=4 and modulos_idEstado=1 ";
//                            $cadena_sql.=" AND modulos_idProyectoCurricular!=".$variable[1];
//
//                        break;
//
                        case 'ano_periodo':
                            $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";

                        break;

//                        case 'espacios_plan_estudio':
//
//                            $cadena_sql="SELECT DISTINCT pen_asi_cod, asi_nombre ";
//                            $cadena_sql.="from acpen ";
//                            $cadena_sql.="INNER JOIN ACASI ON ACPEN.PEN_ASI_COD=ACASI.ASI_COD  ";
//                            $cadena_sql.="WHERE pen_nro=".$variable[0];
//                            $cadena_sql.=" and pen_asi_cod not in";
//                            $cadena_sql.=" (SELECT DISTINCT INS_ASI_COD FROM ACINS";
//                            $cadena_sql.=" WHERE INS_EST_COD=".$variable[1];
//                            $cadena_sql.=" AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
//                            $cadena_sql.=" AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'))";
//                            $cadena_sql.=" and pen_asi_cod not in";
//                            $cadena_sql.=" (select not_asi_cod from acnot";
//                            $cadena_sql.=" WHERE NOT_EST_COD=".$variable[1];
//                            $cadena_sql.=" and not_nota>=30)";
//
//                        break;
//
//                        case 'nombre_espacio':
//
//                            $cadena_sql="SELECT DISTINCT espacio_nombre, espacio_nroCreditos, clasificacion_abrev ";
//                            $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_academico EA ";
//                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
//                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion ";
//                            $cadena_sql.="WHERE EA.id_espacio=".$variable;
//
//                        break;
//
                        case 'horario_grupos':

                            $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                            $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND HOR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND HOR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND HOR_NRO=".$variable[6];
                            $cadena_sql.=" ORDER BY 1,2,3";

                        break;

                    case 'grupos_proyecto':

                            $cadena_sql="SELECT DISTINCT HOR_NRO ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND HOR_NRO!=".$variable[3];
                            $cadena_sql.=" AND HOR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND HOR_APE_PER=".$variable[5];
                            $cadena_sql.=" ORDER BY 1";
                        break;

                    case 'otros_grupos':

                        $cadena_sql="SELECT DISTINCT CUR_NRO, CRA_NOMBRE ";
                        $cadena_sql.="FROM ACCURSO ";
                        $cadena_sql.="INNER JOIN ACHORARIO ON ACCURSO.CUR_ASI_COD=ACHORARIO.HOR_ASI_COD AND ACCURSO.CUR_NRO=ACHORARIO.HOR_NRO ";
                        $cadena_sql.="INNER JOIN ACCRA ON ACCURSO.CUR_CRA_COD=ACCRA.CRA_COD  ";
                        $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                        $cadena_sql.=" AND CUR_NRO!=".$variable[6];
                        $cadena_sql.=" AND CUR_CRA_COD=".$variable[5];
                        $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                        $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                        $cadena_sql.=" ORDER BY 1";
                        break;

                    case 'horario_otros_grupos':

                            $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                            $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND HOR_APE_ANO=".$variable[3];
                            $cadena_sql.=" AND HOR_APE_PER=".$variable[4];
                            $cadena_sql.=" AND HOR_NRO=".$variable[5];
                            $cadena_sql.=" ORDER BY 1,2,3";

                        break;

                    case 'horario_registrado':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="INNER JOIN ACINS ON ACHORARIO.HOR_ASI_COD=ACINS.INS_ASI_COD AND ACHORARIO.HOR_NRO=ACINS.INS_GR ";
                            $cadena_sql.="AND ACHORARIO.HOR_APE_ANO=ACINS.INS_ANO AND ACHORARIO.HOR_APE_PER=ACINS.INS_PER ";
                            $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                            $cadena_sql.=" AND ins_asi_cod != ".$variable[2];
                            $cadena_sql.=" AND INS_ANO=".$variable[4];
                            $cadena_sql.=" AND INS_PER=".$variable[5];
                            $cadena_sql.=" ORDER BY 1,2";


                        break;

                    case 'horario_registradoCruce':

                        $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                        $cadena_sql.="FROM ACHORARIO ";
                        $cadena_sql.="INNER JOIN ACINS ON ACHORARIO.HOR_ASI_COD=ACINS.INS_ASI_COD AND ACHORARIO.HOR_NRO=ACINS.INS_GR ";
                        $cadena_sql.="AND ACHORARIO.HOR_APE_ANO=ACINS.INS_ANO AND ACHORARIO.HOR_APE_PER=ACINS.INS_PER ";
                        $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                        $cadena_sql.=" AND INS_ANO=".$variable[3];
                        $cadena_sql.=" AND INS_PER=".$variable[4];
                        $cadena_sql.=" AND INS_ASI_COD!= ".$variable[1];
                        $cadena_sql.=" ORDER BY 1,2";


                        break;

                    case 'horario_grupos_registrar':

                        $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA ";
                        $cadena_sql.="FROM ACHORARIO ";
                        $cadena_sql.="INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
                        $cadena_sql.="INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
                        $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                        $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                        $cadena_sql.=" AND HOR_APE_ANO=".$variable[4];
                        $cadena_sql.=" AND HOR_APE_PER=".$variable[5];
                        $cadena_sql.=" AND HOR_NRO=".$variable[6];
                        $cadena_sql.=" ORDER BY 1,2";

                        break;

                    case 'horario_otrosGrupos_registrar':

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

                    case 'horario_grupo_nuevo':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.="FROM ACHORARIO ";
                            $cadena_sql.="WHERE HOR_ASI_COD=".$variable[2]." AND HOR_NRO=".$variable[1];
                            $cadena_sql.=" AND HOR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND HOR_APE_PER=".$variable[5];
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

                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO ";
                            $cadena_sql.="FROM ACCURSO ";
                            $cadena_sql.="WHERE CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


                        break;

                    case 'cupo_grupoAnterior':

                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
                            $cadena_sql.="FROM ACCURSO ";
                            $cadena_sql.="WHERE CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[3];


                       break;

                    case 'actualizar_cupo':

                            $cadena_sql="UPDATE ACCURSO ";
                            $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2];
                            $cadena_sql.=" and ins_gr=".$variable[1]." and ins_ano=".$variable[4]." and ins_per=".$variable[5].")";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[1];


                        break;

                    case 'actualizar_cupoAnterior':

                            $cadena_sql="UPDATE ACCURSO ";
                            $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2];
                            $cadena_sql.=" and ins_gr=".$variable[3]." and ins_ano=".$variable[4]." and ins_per=".$variable[5].")";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_NRO=".$variable[3];


                       break;

                    case 'actualizar_grupo_espacio_oracle':

                            $cadena_sql="UPDATE ACINS ";
                            $cadena_sql.="SET INS_GR= ".$variable[1];
                            $cadena_sql.=" WHERE INS_ANO=".$variable[4];
                            $cadena_sql.=" AND INS_PER=".$variable[5];
                            $cadena_sql.=" AND INS_EST_COD=".$variable[0]." AND INS_ASI_COD=".$variable[2];


                       break;

                    case 'actualizar_grupo_espacio_mysql':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."horario_estudiante ";
                            $cadena_sql.="SET horario_grupo= ".$variable[1];
                            $cadena_sql.=" WHERE horario_ano= ".$variable[4];
                            $cadena_sql.=" AND horario_periodo= ".$variable[5];
                            $cadena_sql.=" AND horario_idEspacio=".$variable[2]." AND horario_codEstudiante=".$variable[0];
                            $cadena_sql.=" AND horario_estado!='3'";

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


		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>