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

class sql_realizarPreinscripcion extends sql
{
	function cadena_preins_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);

		switch($opcion)
		{
			case "planEstudio":

                                //obtiene la fecha del sistema en formato timestamp
                                $fecha = date ( time ());
                                //$fecha = time();

                                $cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="CRA_COD, ";
				$cadena_sql.="PEN_NRO, ";
				$cadena_sql.="CRA_NOMBRE ";
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
				$cadena_sql.="ORDER BY ";
                                $cadena_sql.="CRA_NOMBRE";
                                //echo $cadena_sql;
                                //exit;
                                break;

                        case "parametros":
                                $fecha = date ( time ());
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.=$configuracion["prefijo"]."parametros_preins ";
                                $cadena_sql.="(";
                                $cadena_sql.="parametro_idProyectoCurricular, ";
                                $cadena_sql.="parametro_idPlanEstudio, ";
                                $cadena_sql.="parametro_orden, ";
                                $cadena_sql.="parametro_semestres, ";
                                $cadena_sql.="parametro_ano, ";
                                $cadena_sql.="parametro_periodo, ";
                                $cadena_sql.="parametro_fecha, ";
                                $cadena_sql.="parametro_usuario, ";
                                $cadena_sql.="parametro_estado";
                                $cadena_sql.=") ";
                                $cadena_sql.="VALUES ";
                                $cadena_sql.="(";
                                $cadena_sql.="'".$variable[0]."', "; //PROYECTO
                                $cadena_sql.="'".$variable[1]."', "; //PLANEST
                                $cadena_sql.="'".$variable[2]."', "; //ORDEN
                                $cadena_sql.="'".$variable[3]."', "; //SEM
                                $cadena_sql.="'".$variable[4]."', "; //ANNO
                                $cadena_sql.="'".$variable[5]."', "; //PER
                                $cadena_sql.="'".$fecha."', "; //FECHA
                                $cadena_sql.="'".$variable[6]."', "; //USUARIO
                                $cadena_sql.="'0')"; //ESTADO
                                //$cadena_sql.="";
                                //$cadena_sql.="";
                                break;


/*			case "consultarNotaEst": //accede al registro a consultar en la tabla
				$cadena_sql="SELECT DISTINCT";
				$cadena_sql.="NOT_ASI_COD, ";
				$cadena_sql.="NOT_GR, ";
                                $cadena_sql.="NOT_NOTA, ";
                                $cadena_sql.="FROM ";
                                //$cadena_sql.="(";
                                $cadena_sql.="ACNOT ";
                                //$cadena_sql.="as ars ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="NOT_EST_COD =".$variable." ";
                                $cadena_sql.="AND NOT_NOTA < 30";
                                $cadena_sql.="ORDER BY NOT_ASI_COD";
//                                echo $cadena_sql."<br>";
//                                exit;
                                break;
*/

                        case "consultarActual":
                                $cadena_sql="select ";
                                $cadena_sql.="max(actual_fecha) ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="sga_sol_certif_ofi_actualiza_fecha ";
				$cadena_sql.="WHERE ";
                                $cadena_sql.="actual_id_solicitud =".$variable;
                                break;


                        case "buscarDatosPreinscripcion":

				$cadena_sql="SELECT * ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."parametros_preins ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="parametro_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_ano = ";
				$cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_periodo = ";
				$cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_estado ".$variable[10];
				//$cadena_sql;
                                break;


                        case "borrarDatosParametros":

                                $cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."parametros_preins ";
				$cadena_sql.="SET ";
				$cadena_sql.="parametro_estado = '2' ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="parametro_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_ano = ";
				$cadena_sql.="'".$variable[4]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_periodo = ";
				$cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_estado != '2'";
				//$cadena_sql.=" ";
                                break;


                        case "guardarDatosParametros":

                                $cadena_sql="UPDATE ";
				$cadena_sql.=$configuracion["prefijo"]."parametros_preins ";
				$cadena_sql.="SET ";
				$cadena_sql.="parametro_estado = '1' ";
                                $cadena_sql.="WHERE ";
				$cadena_sql.="parametro_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_ano = ";
				$cadena_sql.="'".$variable[4]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_periodo = ";
				$cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="parametro_estado = 0";
				//$cadena_sql.=" ";
                                break;


                        case "borrarDatos":

                                $cadena_sql="DELETE FROM ";
				$cadena_sql.=$configuracion["prefijo"]."".$variable[2]." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="".$variable[3]."_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_ano = ";
				$cadena_sql.="'".$variable[4]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_periodo = ";
				$cadena_sql.="'".$variable[5]."'  ";
                                //echo $cadena_sql;
                                //exit;
				//$cadena_sql.=" ";
                                break;


                        case "borrarDatosEstudiante":

                                $cadena_sql="DELETE FROM ";
				$cadena_sql.=$configuracion["prefijo"]."".$variable[2]." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="".$variable[3]."_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_ano = ";
				$cadena_sql.="'".$variable[4]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_periodo = ";
				$cadena_sql.="'".$variable[5]."'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="".$variable[3]."_codEstudiante = ";
				$cadena_sql.="'".$variable[6]."'  ";
                                //echo $cadena_sql;
                                //exit;
				//$cadena_sql.=" ";
                                break;


                        case "borrarOracle":

                                $cadena_sql="DELETE FROM ";
				$cadena_sql.="ACINS ";
				$cadena_sql.="WHERE ";
				/*descomentar para ejecutar en produccion*/
                                $cadena_sql.="INS_EST_COD IN ";
				$cadena_sql.="(SELECT EST_COD ";
				$cadena_sql.="FROM ACEST ";
				$cadena_sql.="WHERE EST_PEN_NRO = ";
				$cadena_sql.="'".$variable[1]."') ";
				$cadena_sql.="AND ";
                                /**/
				$cadena_sql.="INS_CRA_COD = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND INS_ANO = ";
				$cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND INS_PER = ";
				$cadena_sql.="'".$variable[5]."'";
				//$cadena_sql.=" ";
                                break;



                        case "borrarEstudiantesOracle":

                                $cadena_sql="DELETE FROM ";
				$cadena_sql.="ACINS ";
				$cadena_sql.="WHERE ";
				/*descomentar para ejecutar en produccion*/
                                $cadena_sql.="INS_EST_COD = ";
                                $cadena_sql.="'".$variable[2]."' ";
				$cadena_sql.="AND INS_ASI_COD = ";
				$cadena_sql.="'".$variable[3]."' ";
				$cadena_sql.="AND INS_CRA_COD = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND INS_ANO = ";
				$cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND INS_PER = ";
				$cadena_sql.="'".$variable[5]."'";
				//$cadena_sql.=" ";
                                break;



                        case "guardarDatos":
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante ";
                                $cadena_sql.="SELECT ";
                                $cadena_sql.="* ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_estado = '2' ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="horario_codEstudiante";
                                break;

                        case "guardarOracle":
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.="ACINS ";
                                $cadena_sql.=$variable;
//                                echo $cadena_sql;
//                                exit;
                                break;


                        case "guardarOracleCupos":
                                $cadena_sql="UPDATE ";
                                $cadena_sql.="ACCURSO ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="CUR_NRO_INS = ";
                                $cadena_sql.="'".$variable[3]."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="CUR_APE_ANO = ";
                                $cadena_sql.="'".$variable[8]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_APE_PER = ";
                                $cadena_sql.="'".$variable[9]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_ASI_COD = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_NRO = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_CRA_COD = ";
                                $cadena_sql.="'".$variable[6]."' ";
//                                echo $cadena_sql;
//                                exit;
                                break;

                        case "buscarOracleCupos":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="CUR_NRO_INS ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="ACCURSO ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="CUR_APE_ANO = ";
                                $cadena_sql.="'".$variable[8]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_APE_PER = ";
                                $cadena_sql.="'".$variable[9]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_CRA_COD = ";
                                $cadena_sql.="'".$variable[6]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_ASI_COD = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_NRO = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                break;

                        case "borrarOracleCupos":
                                $cadena_sql="UPDATE ";
                                $cadena_sql.="ACCURSO ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="CUR_NRO_INS = ";
                                $cadena_sql.="'".$variable[3]."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="CUR_APE_ANO = ";
                                $cadena_sql.="'".$variable[8]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_APE_PER = ";
                                $cadena_sql.="'".$variable[9]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_ASI_COD = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_NRO = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_CRA_COD = ";
                                $cadena_sql.="'".$variable[6]."'";
                                break;


                        case "buscarRegistros":

                                $cadena_sql.="SELECT ";
                                $cadena_sql.="horario_codEstudiante, ";
                                $cadena_sql.="horario_idEspacio ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_estado = '2' ";
                                $cadena_sql.="ORDER BY ";
				$cadena_sql.="horario_codEstudiante";
                                break;


                        case "buscarRegistrosProvisionales":

                                $cadena_sql.="SELECT ";
                                $cadena_sql.="horario_codEstudiante, ";
                                $cadena_sql.="horario_idEspacio ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_estado = '2' ";
                                $cadena_sql.="ORDER BY ";
				$cadena_sql.="horario_codEstudiante";
                                break;


                        case "buscarRegistrosHorario":

                                $cadena_sql.="SELECT ";
                                $cadena_sql.="* ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_estado = '2' ";
                                $cadena_sql.="ORDER BY ";
				$cadena_sql.="horario_codEstudiante";
                                break;


                        case "buscarRegistrosCupos":

                                $cadena_sql.="SELECT ";
                                $cadena_sql.="* ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="cupos_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_idPlanestudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_periodo  = ";
                                $cadena_sql.="'".$variable[5]."' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="cupos_estado = '1' ";
                                $cadena_sql.="ORDER BY cupos_idEspacio";
				break;

                        case "actualizarRegistrosCupos":

                                $cadena_sql.="UPDATE ";
                                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="cupos_estado = '1' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="cupos_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_idPlanestudio = ";
                                $cadena_sql.="'".$variable[1]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_ano = ";
                                $cadena_sql.="'".$variable[4]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_periodo  = ";
                                $cadena_sql.="'".$variable[5]."'";
                                break;


                        case 'registroEvento':

                                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                                $cadena_sql.="VALUES ('','".$variable[0]."',";
                                $cadena_sql.="'".$variable[1]."',";
                                $cadena_sql.="'".$variable[2]."',";
                                $cadena_sql.="'".$variable[3]."',";
                                $cadena_sql.="'".$variable[4]."',";
                                $cadena_sql.="'".$variable[5]."')";

                               break;





                        case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="USU_NRO_IDEN, ";
                                $cadena_sql.="USU_NOMBRE, ";
				$cadena_sql.="USU_APELLIDO, ";
                                $cadena_sql.="USU_DEP_COD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="GEUSUARIO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="USU_NRO_IDEN=".$variable." ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="USU_ESTADO LIKE '%A%'";
				break;

			case "datosUsuarios":
				$cadena_sql="SELECT ";
				$cadena_sql.="clo_cod, ";
				$cadena_sql.="clo_nom ";
				$cadena_sql.="FROM ";
				$cadena_sql.="claobj";
				break;

			case "codigoUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="codigo ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="idInscripcion=".$variable." ";
				$cadena_sql.="LIMIT 1";
				break;

			case "usuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="".$configuracion["prefijo"]."inscripcionGrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="codigo='".$variable."' ";
				$cadena_sql.="LIMIT 1";
				break;


			case  "rescatarUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`apellido`, ";
				$cadena_sql.="`correo`, ";
				$cadena_sql.="`telefono`, ";
				$cadena_sql.="`usuario`, ";
				$cadena_sql.="`clave` ";
				$cadena_sql.="FROM ";
				$cadena_sql.="".$configuracion["prefijo"]."registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario='".$variable."' ";
				$cadena_sql.="LIMIT 1";
				break;


			case "datosEstudiante":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_nombre, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="est_diferido, ";
				$cadena_sql.="est_estado_est, ";
				$cadena_sql.="est_tipo_iden, ";
				$cadena_sql.="est_sexo, ";
				$cadena_sql.="emb_valor_matricula vr_mat, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="V_ACESTMATBRUTO, ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="est_cod =".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="emb_est_cod = est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="cra_cod = est_cra_cod";
				break;

			case "identificacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="identificacion='".$variable."'";
				break;

			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>
