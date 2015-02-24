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

class sql_ejecutarPreinscripcion extends sql
{
	function cadena_ejPre_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);

		switch($opcion)
		{
			/*case "parametros":
                                $cadena_sql="INSERT INTO ";
                                $cadena_sql.="sga.".$configuracion["prefijo"]."parametros ";
                                $cadena_sql.="(";
                                $cadena_sql.="parametro_cod_cra, ";
                                $cadena_sql.="parametro_cod_pensum, ";
                                $cadena_sql.="parametro_orden, ";
                                $cadena_sql.="parametro_semestres, ";
                                $cadena_sql.="parametro_anno, ";
                                $cadena_sql.="parametro_periodo";
                                $cadena_sql.=") ";
                                $cadena_sql.="VALUES ";
                                $cadena_sql.="(";
                                $cadena_sql.="'".$variable[0]."', "; //COD_CRA
                                $cadena_sql.="'".$variable[1]."', "; //COD_PENSUM
                                $cadena_sql.="'".$variable[2]."', "; //ORDEN
                                $cadena_sql.="'".$variable[3]."', "; //SEM
                                $cadena_sql.="'".$variable[4]."', "; //ANNO
                                $cadena_sql.="'".$variable[5]."')"; //PER
                                //$cadena_sql.="";
                                //$cadena_sql.="";
                                break;*/

			case "buscarEstudiantes": //accede al registro a consultar en la tabla

                                $cadena_sql="SELECT ";
				$cadena_sql.="EST_COD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACEST ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="EST_CRA_COD = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				//La filas comentadas se activan al momento de ejecutar
                                $cadena_sql.="EST_PEN_NRO = ";
				$cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				//esta fila se desactiva para ejecutar
                                //$cadena_sql.="EST_COD > 20091000000 ";
				$cadena_sql.="EST_IND_CRED ";
				$cadena_sql.="LIKE '%S%' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="EST_ESTADO ";
                                $cadena_sql.="LIKE '%A%' ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="EST_COD ";
				$cadena_sql.=$variable[2];
				//echo $cadena_sql."<br>";
                                break;
                                //exit;

			case "consultarNotaEst": //accede al registro a consultar en la tabla
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="NOT_ASI_COD, ";
				$cadena_sql.="NOT_GR, ";
                                $cadena_sql.="NOT_NOTA, ";
                                $cadena_sql.="PEN_CRE ";
                                $cadena_sql.="FROM ";
                                //$cadena_sql.="(";
                                $cadena_sql.="ACNOT ";
                                $cadena_sql.="INNER JOIN ";
                                $cadena_sql.="ACPEN ";
                                $cadena_sql.="ON ";
                                $cadena_sql.="(";
                                $cadena_sql.="ACNOT.NOT_ASI_COD = ";
                                $cadena_sql.="ACPEN.PEN_ASI_COD) ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="ACNOT.NOT_EST_COD =".$variable." ";
                                $cadena_sql.="AND ACNOT.NOT_NOTA < 30 ";
                                $cadena_sql.="AND ACPEN.PEN_NRO > 200 ";
                                $cadena_sql.="ORDER BY ACNOT.NOT_ASI_COD";
                                break;


                        case "consultarNotaAprobado":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="NOT_NOTA ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.="ACNOT ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="NOT_ASI_COD = ";
                                $cadena_sql.="'".$variable[1]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="NOT_EST_COD = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="NOT_NOTA >= 30";
                                break;



			case "insertarNotaEstudiante":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$configuracion["prefijo"]."nota_reprobados ";
				$cadena_sql.="(";
				$cadena_sql.="nota_codEstudiante, ";
				$cadena_sql.="nota_idEspacio, ";
				$cadena_sql.="nota_sem_anterior, ";
				$cadena_sql.="nota_grupo_sem_anterior, ";
				$cadena_sql.="nota_veces_cursadas, ";
				$cadena_sql.="nota_creditos, ";
				$cadena_sql.="nota_idProyectoCurricular, ";
				$cadena_sql.="nota_idPlanEstudio, ";
				$cadena_sql.="nota_ano, ";
				$cadena_sql.="nota_periodo";
                                $cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="(";
				$cadena_sql.="'".$variable[0]."', ";//cod est
				$cadena_sql.="'".$variable[1]."', ";//cod EA
				$cadena_sql.="'".$variable[2]."', ";//nota sem ant
				$cadena_sql.="'".$variable[3]."', ";//grupo sem ant
				$cadena_sql.="'".$variable[4]."', ";// veces
                                $cadena_sql.="'".$variable[5]."', ";//creditos
				$cadena_sql.="'".$variable[6]."', ";//cra
				$cadena_sql.="'".$variable[7]."', ";//planest
				$cadena_sql.="'".$variable[8]."', ";//ano
				$cadena_sql.="'".$variable[9]."')";//periodo
				//$cadena_sql.="";
                                //echo $cadena_sql."<br>";
                                //exit;
                                break;


                        case "actualizaCupoIns":
                                $cadena_sql="UPDATE ";
                                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="cupos_despues = ";
                                $cadena_sql.="'".$variable[7]."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="cupos_idEspacio = ";
                                $cadena_sql.="'".$variable[1]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="cupos_grupo = ";
                                $cadena_sql.="'".$variable[3]."' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="cupos_idProyectoCurricular= ";
                                $cadena_sql.="'".$variable[8]."' ";//proyecto
				$cadena_sql.="AND ";
				$cadena_sql.="cupos_idPlanestudio= ";
                                $cadena_sql.="'".$variable[9]."' ";//planest
				$cadena_sql.="AND ";
				$cadena_sql.="cupos_ano= ";
                                $cadena_sql.="'".$variable[10]."' ";//año
				$cadena_sql.="AND ";
				$cadena_sql.="cupos_periodo= ";
                                $cadena_sql.="'".$variable[11]."'";//periodo
				//$cadena_sql.=")";

                                break;





                        case "actualizarCupos":
                        //ORACLE
                                $cadena_sql="UPDATE ";
                                $cadena_sql.="ACCURSO ";
                                $cadena_sql.="SET ";
                                $cadena_sql.="CUR_NRO_INS = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="CUR_APE_ANO = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_APE_PER = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_ASI_COD = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_NRO = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="CUR_CRA_COD = ";
                                $cadena_sql.="'".$variable[0]."'";
                                //$cadena_sql.=" ";
                                break;



                        case "buscarEA":
                                $cadena_sql="SELECT DISTINCT ";
                                $cadena_sql.="nota_idEspacio, ";
                                $cadena_sql.="nota_grupo_sem_anterior, ";
                                $cadena_sql.="nota_creditos, ";
                                $cadena_sql.="nota_codEstudiante ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."nota_reprobados ";//tabla
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="nota_codEstudiante = ";
                                $cadena_sql.="'".$variable[0]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="nota_ano = ";
                                $cadena_sql.="'".$variable[1]."' ";
                                $cadena_sql.="AND ";
                                $cadena_sql.="nota_periodo = ";
                                $cadena_sql.="'".$variable[2]."' ";
                                $cadena_sql.="ORDER BY nota_codEstudiante ";
                                //$cadena_sql.=" ";
                                break;


                        case "nombreCraPlanest":
				//En ORACLE
				$cadena_sql="SELECT DISTINCT ";
				$cadena_sql.="CRA_NOMBRE ";
                                $cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.="ACPEN ";
				$cadena_sql.="ON (";
				$cadena_sql.="ACCRA.CRA_COD = ";
                                $cadena_sql.="ACPEN.PEN_CRA_COD) ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACPEN.PEN_NRO = ".$variable[1]." ";
				$cadena_sql.="AND ";
                                $cadena_sql.="ACPEN.PEN_CRA_COD = ".$variable[0]." ";
                                //$cadena_sql.="";
                                break;

                        case "numEst":
				$cadena_sql="SELECT ";
				$cadena_sql.="count";
				$cadena_sql.="(distinct nota_codEstudiante) ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."nota_reprobados ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="nota_idProyectoCurricular = ";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nota_idPlanEstudio = ";
				$cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nota_ano = ";
				$cadena_sql.="'".$variable[4]."' " ;
				$cadena_sql.="AND ";
				$cadena_sql.="nota_periodo = ";
				$cadena_sql.="'".$variable[5]."'";
				//$cadena_sql.=" ";

                                break;

                                case "rescatarEstudiantes":
				$cadena_sql="SELECT ";
				$cadena_sql.="count";
				$cadena_sql.="(distinct nota_codEstudiante) ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."nota_reprobados ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="nota_idProyectoCurricular = ";
				$cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nota_idPlanEstudio = ";
				$cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nota_ano = ";
				$cadena_sql.="'".$variable["anno"]."' " ;
				$cadena_sql.="AND ";
				$cadena_sql.="nota_periodo = ";
				$cadena_sql.="'".$variable["periodo"]."'";
				//$cadena_sql.=" ";
                                break;

                                case "rescatarEstudiantesConEA":
				$cadena_sql="SELECT ";
				$cadena_sql.="count";
				$cadena_sql.="(distinct horario_codEstudiante) ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="horario_idProyectoCurricular = ";
				$cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_idPlanEstudio = ";
				$cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
				$cadena_sql.="'".$variable["anno"]."' " ;
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
				$cadena_sql.="'".$variable["periodo"]."'";
				//$cadena_sql.=" ";

                                break;

                        case "buscarNumEspacios":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="count(distinct horario_idEspacio) ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="horario_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_idPlanEstudio = ";
                                $cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_ano = ";
                                $cadena_sql.="'".$variable["anno"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="horario_periodo = ";
                                $cadena_sql.="'".$variable["periodo"]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="horario_estado = '2'";

                                //$cadena_sql.=" ";
                                break;

                        case "buscarErrores":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="errores_codEstudiante, ";
                                $cadena_sql.="errores_idEspacio, ";
                                $cadena_sql.="errores_grupo, ";
                                $cadena_sql.="errores_observaciones ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."errores_preinscripcion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="errores_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="errores_idPlanEstudio = ";
                                $cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_ano = ";
                                $cadena_sql.="'".$variable["anno"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_periodo = ";
                                $cadena_sql.="'".$variable["periodo"]."' ";
				$cadena_sql.="ORDER BY ";
                                $cadena_sql.="errores_codEstudiante  ";
                                break;

                        case "buscarErroresEspacios":


                                $cadena_sql="SELECT distinct id_espacio, espacio_nombre ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."errores_preinscripcion ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"]."espacio_academico ON ".$configuracion["prefijo"]."errores_preinscripcion.errores_idEspacio= ";
                                $cadena_sql.=$configuracion["prefijo"]."espacio_academico.id_espacio ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="errores_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="errores_idPlanEstudio = ";
                                $cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_ano = ";
                                $cadena_sql.="'".$variable["anno"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_periodo = ";
                                $cadena_sql.="'".$variable["periodo"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_observaciones not like '%1%' ";
                                $cadena_sql.="ORDER BY ";
                                $cadena_sql.="errores_idEspacio ";

                                //echo $cadena_sql;exit;
                                break;


                        case "buscarErroresConteo":


                                $cadena_sql="SELECT count( * ), id_espacio, espacio_nombre ";
                                $cadena_sql.="FROM ";
                                $cadena_sql.=$configuracion["prefijo"]."errores_preinscripcion ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"]."espacio_academico ON ".$configuracion["prefijo"]."errores_preinscripcion.errores_idEspacio= ";
                                $cadena_sql.=$configuracion["prefijo"]."espacio_academico.id_espacio ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="errores_idProyectoCurricular = ";
                                $cadena_sql.="'".$variable["proyectoCurricular"]."' ";
				$cadena_sql.="AND ";
                                $cadena_sql.="errores_idPlanEstudio = ";
                                $cadena_sql.="'".$variable["planEstudios"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_ano = ";
                                $cadena_sql.="'".$variable["anno"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_periodo = ";
                                $cadena_sql.="'".$variable["periodo"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_idEspacio = ";
                                $cadena_sql.="'".$variable["idEspacio"]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="errores_observaciones not like '%1%' ";
                                $cadena_sql.="ORDER BY ";
                                $cadena_sql.="errores_idEspacio ";

                                //echo $cadena_sql;exit;
                                break;

                        case "buscarNombreEA":
                        //ORACLE
				$cadena_sql="SELECT ";
				//$cadena_sql.="ASI_COD, ";
				$cadena_sql.="ASI_NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACASI ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ASI_COD = ";
				$cadena_sql.="'".$variable."'";//observaciones
				//$cadena_sql.=" ";
                                break;


			

                        case "consultarCupoMysql":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="count (*) ";
                                $cadena_sql.="from ";
                                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="cupos_idEspacio = ".$variable[1];
                                $cadena_sql.="and cupos_idProyectoCurricular = ".$variable[6];
                                $cadena_sql.="and cupos_idPlanestudio = ".$variable[7];
                                $cadena_sql.="and cupos_ano = ".$variable[8];
                                $cadena_sql.="and cupos_periodo = ".$variable[9];
                                break;


                        case "buscarCuposDisponibles":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="cupos_idEspacio, cupos_grupo, cupos_antes, cupos_despues ";
                                $cadena_sql.="from ";
                                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="cupos_idProyectoCurricular = ".$variable["proyectoCurricular"];
                                $cadena_sql.=" and cupos_idPlanestudio = ".$variable["planEstudios"];
                                $cadena_sql.=" and cupos_ano = ".$variable["anno"];
                                $cadena_sql.=" and cupos_periodo = ".$variable["periodo"];
                                $cadena_sql.=" ORDER BY ";
                                $cadena_sql.="1, 2";
                                break;


                        case "buscarEquivalente":
                                $cadena_sql="SELECT ";
                                $cadena_sql.="equivalente_idEspacioEquivalente ";
                                $cadena_sql.="from ";
                                $cadena_sql.=$configuracion["prefijo"]."espacio_equivalente ";
                                $cadena_sql.="WHERE ";
                                $cadena_sql.="equivalente_idEspacio = ".$variable;
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
		//echo $cadena_sql."<hr>";
		return $cadena_sql;
	}


}
?>