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

class sql_registroCambiarGrupoInscripcionEstudCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

                        case 'datosCoordinador':

                            $cadena_sql="SELECT DISTINCT ";
                            //$cadena_sql.="PEN_NRO, ";
                            $cadena_sql.="CRA_COD, ";
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
                        break;

                        case 'ano_periodo':
                            $cadena_sql="SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";
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
                                    $cadena_sql.=" AND cur_ape_ano=".$variable[4];
                                    $cadena_sql.=" AND cur_ape_per=".$variable[5];
                                    $cadena_sql.=" AND hor_id_curso=".$variable[7];
                                    $cadena_sql.=" ORDER BY 1,2,3";

                                break;

                        case 'grupos_proyecto':

                            $cadena_sql="SELECT DISTINCT CUR_ID,(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO ";
                            $cadena_sql.="FROM ACHORARIOS ";
                            $cadena_sql.="INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                            $cadena_sql.="WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND CUR_ID!=".$variable[6];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
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

                        case 'horario_registrado':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.="FROM ACHORARIOS ";
                            $cadena_sql.="INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                            $cadena_sql.="INNER JOIN ACINS ON ACCURSOS.CUR_ID=ACINS.INS_GR ";
                            $cadena_sql.="AND ACCURSOS.CUR_APE_ANO=ACINS.INS_ANO AND ACCURSOS.CUR_APE_PER=ACINS.INS_PER ";
                            $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                            $cadena_sql.=" AND ins_asi_cod != ".$variable[2];
                            $cadena_sql.=" AND INS_ANO=".$variable[4];
                            $cadena_sql.=" AND INS_PER=".$variable[5];
                            $cadena_sql.=" ORDER BY 1,2";
                        break;

                        case 'horario_registradoCruce':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.="FROM ACHORARIOS ";
                            $cadena_sql.="INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                            $cadena_sql.="INNER JOIN ACINS ON ACCURSOS.CUR_ID=ACINS.INS_GR ";
                            $cadena_sql.="AND ACCURSOS.CUR_APE_ANO=ACINS.INS_ANO AND ACCURSOS.CUR_APE_PER=ACINS.INS_PER ";
                            $cadena_sql.="WHERE ACINS.INS_EST_COD=".$variable[0];
                            $cadena_sql.=" AND INS_ANO=".$variable[3];
                            $cadena_sql.=" AND INS_PER=".$variable[4];
                            $cadena_sql.=" AND INS_ASI_COD!= ".$variable[1];
                            $cadena_sql.=" ORDER BY 1,2";
                        break;

                        case 'horario_grupos_registrar':

                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA";
                            $cadena_sql.=" FROM ACHORARIOS horario";
                            $cadena_sql.=" INNER JOIN ACCURSOS curso ON horario.hor_id_curso=curso.cur_id";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ID=".$variable[7];
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
                            $cadena_sql="SELECT count(*)";
                            $cadena_sql.=" FROM ACINS";
                            $cadena_sql.=" WHERE INS_ANO=".$variable[4];
                            $cadena_sql.=" AND INS_PER=".$variable[5];
                            $cadena_sql.=" AND INS_ASI_COD=".$variable[2]." AND INS_GR=".$variable[1];
                        break;

                        case 'cupo_grupo_cupo':
                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_CRA_COD";
                            $cadena_sql.=" FROM ACCURSOS";
                            $cadena_sql.=" WHERE CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                        break;

                        case 'cupo_grupoAnterior':
                            $cadena_sql="SELECT DISTINCT CUR_NRO_CUPO, CUR_NRO_INS ";
                            $cadena_sql.="FROM ACCURSOS ";
                            $cadena_sql.="WHERE CUR_APE_ANO=".$variable[4];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[5];
                            $cadena_sql.=" AND CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[3];
                        break;

                        case 'actualizar_cupo':
                            $cadena_sql="UPDATE ACCURSOS ";
                            $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2];
                            $cadena_sql.=" and ins_gr=".$variable[1]." and ins_ano=".$variable[4]." and ins_per=".$variable[5].")";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[1];
                        break;

                        case 'actualizar_cupoAnterior':
                            $cadena_sql="UPDATE ACCURSOS ";
                            $cadena_sql.="SET CUR_NRO_INS= (select count(*) from acins where ins_asi_cod = ".$variable[2];
                            $cadena_sql.=" and ins_gr=".$variable[3]." and ins_ano=".$variable[4]." and ins_per=".$variable[5].")";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[2]." AND CUR_ID=".$variable[3];
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
                            $cadena_sql.="VALUES(0,'".$variable[0]."',";
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
                            $cadena_sql=" SELECT DISTINCT ACE_CRA_COD";
                            $cadena_sql.=" FROM ACCALEVENTOS";
                            $cadena_sql.=" WHERE ACE_ANIO=".$variable[3];
                            $cadena_sql.=" AND ACE_PERIODO=".$variable[4];
                            $cadena_sql.=" AND (to_char(ACE_FEC_INI, 'yyyymmdd')<=to_char(current_timestamp, 'yyyymmdd')";
                            $cadena_sql.=" AND to_char(ACE_FEC_FIN, 'yyyymmdd')>=to_char(current_timestamp, 'yyyymmdd')";
                            $cadena_sql.=" AND ACE_COD_EVENTO=15";
                            $cadena_sql.=" AND ACE_ESTADO LIKE '%A%'";
                            $cadena_sql.=" AND ACE_CRA_COD !=".$variable[1].")";
                            $cadena_sql.=" OR (to_char(ACE_FEC_INI, 'yyyymmdd')<=to_char(current_timestamp, 'yyyymmdd')";
                            $cadena_sql.=" AND to_char(ACE_FEC_FIN, 'yyyymmdd')>=to_char(current_timestamp, 'yyyymmdd')";
                            $cadena_sql.=" AND ACE_COD_EVENTO=8";
                            $cadena_sql.=" AND ACE_ESTADO LIKE '%A%'";
                            $cadena_sql.=" AND ACE_CRA_COD=105)";
                            $cadena_sql.=" ORDER BY 1";                        
                        break;

                        case 'otros_gruposproyecto':
                            $cadena_sql="SELECT DISTINCT CUR_ID,(lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo),CRA_NOMBRE";
                            $cadena_sql.=" FROM ACCURSOS curso";
                            $cadena_sql.=" INNER JOIN ACHORARIOS horario ON curso.CUR_ID=horario.HOR_ID_CURSO";
                            $cadena_sql.=" INNER JOIN ACCRA cra ON curso.CUR_CRA_COD=cra.CRA_COD";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_ID!=".$variable[6];
                            $cadena_sql.=" AND CUR_CRA_COD=".$variable[5];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                            $cadena_sql.=" ORDER BY 1";
                        break;

                        case 'horario_otrosgrupos_registrar':
                            $cadena_sql="SELECT DISTINCT HOR_DIA_NRO, HOR_HORA ";
                            $cadena_sql.=" FROM ACHORARIOS horario ";
                            $cadena_sql.=" INNER JOIN ACCURSOS curso ON horario.hor_id_curso=curso.cur_id";
                            $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                            $cadena_sql.=" AND CUR_CRA_COD!=".$variable[1];
                            $cadena_sql.=" AND CUR_APE_ANO=".$variable[3];
                            $cadena_sql.=" AND CUR_APE_PER=".$variable[4];
                            $cadena_sql.=" AND CUR_ID=".$variable[5];
                            $cadena_sql.=" ORDER BY 1,2";
                            break;

		}
		return $cadena_sql;
	}


}
?>