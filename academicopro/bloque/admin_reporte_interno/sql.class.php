<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
//06/11/2012 Milton Parra: Se ajustan mensajes cuando faltan datos en las notas. Se coloca mensaje cuando no presenta Promedio Acumulado
class sql_reporteInterno extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$conexion,$tipo,$variable="") {

        switch($tipo) {

            case "buscar_estudiante":
                $cadena_sql=" SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_pen_nro PLAN_ESTUDIO,";
                $cadena_sql.=" est_nro_iden DOCUMENTO,";
                $cadena_sql.=" est_ind_cred IND_CRED";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cod=".$variable;
                break;

            case "buscar_carrera":
                $cadena_sql="SELECT cra_nombre NOMBRE,";
                $cadena_sql.=" tra_cod_nivel NIVEL";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN actipcra ON CRA_TIP_CRA=TRA_COD";
                $cadena_sql.=" WHERE cra_cod=".$variable[0]['CARRERA'];
                break;

            case "buscar_promedio":
                $cadena_sql="SELECT FA_PROMEDIO_NOTA(".$variable.") PROMEDIO";
                break;

            case "buscarEspacioPregrado":
                $cadena_sql=" SELECT DISTINCT";
                $cadena_sql.=" not_sem NIVEL,";
                $cadena_sql.=" not_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" not_cred CREDITOS,";
                $cadena_sql.=" cea_abr CLASIFICACION,";
                $cadena_sql.=" not_nro_ht HTD,";
                $cadena_sql.=" not_nro_hp HTC,";
                $cadena_sql.=" not_nro_aut HTA,";
                $cadena_sql.=" not_ano ANO,";
                $cadena_sql.=" not_per PERIODO,";
                $cadena_sql.=" not_nota NOTA,";
                $cadena_sql.=" nob_nombre OBSERVACION";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acnot";
                $cadena_sql.=" INNER JOIN acnotobs";
                $cadena_sql.=" ON acnot.not_obs = acnotobs.nob_cod";
                $cadena_sql.=" INNER JOIN acasi";
                $cadena_sql.=" ON acasi.asi_cod = acnot.not_asi_cod";
                $cadena_sql.=" LEFT OUTER JOIN geclasificaespac";
                $cadena_sql.=" ON not_cea_cod=cea_cod";
                $cadena_sql.=" WHERE not_est_cod = ".$variable[0]['CODIGO'];
                $cadena_sql.=" AND not_cra_cod = ".$variable[0]['CARRERA'];
                $cadena_sql.=" AND not_obs != 19";
                $cadena_sql.=" AND not_obs != 20";
                $cadena_sql.=" AND not_est_reg like '%A'";
                //$cadena_sql.=" AND asi_ind_cred like '%S%'";
                //$cadena_sql.=" AND not_cred is not null";
                $cadena_sql.=" ORDER BY not_sem, not_asi_cod, not_ano desc, not_per desc";

                break;

            case "buscarEspacioPosgrado":
                $cadena_sql=" SELECT DISTINCT";
                $cadena_sql.=" not_sem NIVEL,";
                $cadena_sql.=" not_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" not_cred CREDITOS,";
                $cadena_sql.=" pen_ind_ele CLASIFICACION,";
                $cadena_sql.=" not_nro_ht HTD,";
                $cadena_sql.=" not_nro_hp HTC,";
                $cadena_sql.=" not_nro_aut HTA,";
                $cadena_sql.=" not_ano ANO,";
                $cadena_sql.=" not_per PERIODO,";
                $cadena_sql.=" not_nota NOTA,";
                $cadena_sql.=" nob_nombre OBSERVACION";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acnot";
                $cadena_sql.=" INNER JOIN acnotobs";
                $cadena_sql.=" ON acnot.not_obs = acnotobs.nob_cod";
                $cadena_sql.=" INNER JOIN acasi";
                $cadena_sql.=" ON acnot.not_asi_cod=acasi.asi_cod ";
                $cadena_sql.=" INNER JOIN acpen";
                $cadena_sql.=" ON acnot.not_asi_cod=pen_asi_cod AND acnot.not_cra_cod=pen_cra_cod";
                $cadena_sql.=" WHERE not_est_cod = ".$variable[0]['CODIGO'];
                $cadena_sql.=" AND not_cra_cod = ".$variable[0]['CARRERA'];
                $cadena_sql.=" AND not_est_reg like '%A'";
                //$cadena_sql.=" AND asi_ind_cred like '%S%'";
                $cadena_sql.=" AND not_cred is not null";
                $cadena_sql.=" ORDER BY not_sem, not_asi_cod, not_ano desc, not_per desc";
                break;

            case 'buscar_desc':

                $cadena_sql=" SELECT  ";
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio,  ";
                $cadena_sql.=" ESPACIO.espacio_nombre 'Nombre Espacio',  ";
                $cadena_sql.=" ESPACIO.espacio_nroCreditos 'Creditos',   ";
                $cadena_sql.=" PLAN_ESPACIO.id_clasificacion 'Clasificacion',  ";
                $cadena_sql.=" PLAN_ESPACIO.horasDirecto 'HTD',  ";
                $cadena_sql.=" PLAN_ESPACIO.horasCooperativo 'HTC',  ";
                $cadena_sql.=" ESPACIO.espacio_horasAutonomo 'HTA',  ";
                $cadena_sql.=" CLASF.clasificacion_abrev,  ";
                $cadena_sql.=" CLASF.clasificacion_nombre  ";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."espacio_academico ESPACIO  ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio  ";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion CLASF ON CLASF.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_espacio= ".$variable[1];
                $cadena_sql.=" AND PLAN_ESPACIO.id_planEstudio= ".$variable[0];
                //echo $cadena_sql;exit;

                break;
            
            case 'descrip_clasif':

                $cadena_sql=" SELECT  ";
                $cadena_sql.=" CLASF.clasificacion_abrev,  ";
                $cadena_sql.=" CLASF.clasificacion_nombre  ";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."espacio_clasificacion CLASF  ";


                break;

            case "promedio":

                $cadena_sql=" SELECT DISTINCT ";
                $cadena_sql.="not_nota, ";
                $cadena_sql.="not_sem, ";
                $cadena_sql.="pen_cre creditos, asi_nombre, ";
                $cadena_sql.="not_asi_cod, ";
                $cadena_sql.="concat(not_ano, not_per) sem ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acnot ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="acpen ";
                $cadena_sql.="ON acnot.not_cra_cod = acpen.pen_cra_cod ";
                $cadena_sql.="AND acnot.not_asi_cod = acpen.pen_asi_cod ";
                $cadena_sql.="INNER JOIN acasi ON acnot.not_asi_cod = acasi.asi_cod ";
                $cadena_sql.="WHERE not_est_cod = ".$variable;
//                    $cadena_sql.=" AND not_nota >= 30 ";
                $cadena_sql.=" AND asi_ind_cred like '%S%' AND NOT_OBS != 19 AND NOT_OBS != 20 AND NOT_EST_REG LIKE '%A%' ";
                //$cadena_sql.=" AND pen_nro>200 AND NOT_OBS != 19 AND NOT_OBS != 20 AND NOT_EST_REG='A' ";
                $cadena_sql.=" ORDER BY not_sem, not_asi_cod, sem desc ";
                break;


            case "secretario":

                $cadena_sql=" SELECT DISTINCT ";
                $cadena_sql.="usu_nombre, ";
                $cadena_sql.="usu_apellido ";
                $cadena_sql.="FROM geusuario ";
                $cadena_sql.="INNER JOIN accra ";
                $cadena_sql.="INNER JOIN acnot ";
                $cadena_sql.="ON accra.cra_cod = acnot.not_cra_cod ";
                $cadena_sql.="ON geusuario.usu_dep_cod = accra.cra_dep_cod ";
                $cadena_sql.="WHERE not_est_cod= ".$variable;
                $cadena_sql.=" AND usu_tipo = 8 ";
                $cadena_sql.="AND usu_estado = 'A' ";
                $cadena_sql.=" AND usu_usuario like '%1' ";
                break;

            case "buscar_espacio_creditos":
                $cadena_sql=
                        "
                    SELECT
                            PENSUM.pen_nro ,
                            ASIG.asi_cod ,
                            ASIG.asi_nombre ,
                            NOTAS.not_nota ,
                            ESTUDIANTE.est_cod ,
                            ESTUDIANTE.est_nombre ,
                            OBS.nob_nombre ,
                            NOTAS.not_ano ,
                            NOTAS.not_per 
                            FROM acasi ASIG
                            INNER JOIN acpen PENSUM ON PENSUM.pen_asi_cod= ASIG.asi_cod
                            INNER JOIN ACNOT NOTAS ON  NOTAS.not_asi_cod=ASIG.asi_cod
                            INNER JOIN acest ESTUDIANTE ON ESTUDIANTE.est_cod=NOTAS.not_est_cod
                            INNER JOIN acnotobs OBS ON OBS.nob_cod=NOTAS.not_obs
                            WHERE
                            NOTAS.not_est_cod = ".$variable;

                break;



            /*case "contar":
					$cadena_sql="SELECT ";
					$cadena_sql.="COUNT";
					$cadena_sql.="(ESP.id_espacio) ";
					$cadena_sql.="AS REG ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."espacio_academico AS ESP ";
					if($variable)
						{ $cadena_sql.="INNER JOIN ";
						  $cadena_sql.=$configuracion["prefijo"]."espacio_variable AS VAR ";
						  $cadena_sql.="ON ESP.id_espacio=VAR.id_espacio ";
						  $cadena_sql.="WHERE ";
						  $cadena_sql.="VAR.id_variable= ";
						  $cadena_sql.=$variable[1];
						}
					
					break;	*/		
            /*case "completa":
					$cadena_sql="SELECT ";
					$cadena_sql.="ESP.id_espacio, ";
					$cadena_sql.="ESP.codigo_creditos, ";
					$cadena_sql.="ESP.nombre ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."espacio_academico AS ESP ";
					if($variable)
						{ $cadena_sql.="INNER JOIN ";
						  $cadena_sql.=$configuracion["prefijo"]."espacio_variable AS VAR ";
						  $cadena_sql.="ON ESP.id_espacio=VAR.id_espacio ";
						  $cadena_sql.="WHERE ";
						  $cadena_sql.="VAR.id_variable= ";
						  $cadena_sql.=$variable[1];
						}
					break;*/



            case "editar":
                $cadena_sql="UPDATE ";
                $cadena_sql.=$configuracion["prefijo"]."espacio_academico ";
                $cadena_sql.="SET " ;
                $cadena_sql.="`codigo_academica`='".$variable[1]."', ";
                $cadena_sql.="`codigo_creditos`='".$variable[2]."', ";
                $cadena_sql.="`nombre`='".$variable[3]."', ";
                $cadena_sql.="`nro_creditos`='".$variable[4]."', ";
                $cadena_sql.="`horas_directo`='".$variable[5]."', ";
                $cadena_sql.="`horas_cooperativo`='".$variable[6]."', ";
                $cadena_sql.="`horas_autonomo`='".$variable[7]."', ";
                $cadena_sql.="`id_tipo`='".$variable[11]."', ";
                $cadena_sql.="`id_subtipo`='".$variable[12]."', ";
                $cadena_sql.="`id_naturaleza`='".$variable[13]."' ";
                //$cadena_sql.="`id_areaAcademica`='".$variable[19]."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="`id_espacio`= ";
                $cadena_sql.=$variable[0];
                break;


            #Variable en la posicion 0 contiene el nombre del campo que se esta evaluando
            case "areaAcademica":
                $cadena_sql="SELECT '', ";
                $cadena_sql.="areaA.num_espacioArea, ";
                $cadena_sql.="areaAcademica_nombre,";
                $cadena_sql.="areaA.id_areaAcademica ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="area_academica AS areaA ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="area_academicaFormacion AreaF ";
                $cadena_sql.="ON AreaF.id_areaAcademica=";
                $cadena_sql.="areaA.id_areaAcademica ";
                $cadena_sql.="WHERE ".$variable[0]."=".$variable[1]." ";
                $cadena_sql.="ORDER BY areaAcademica_nombre";

                break;

            /*case "buscar_idCompleta":
				$cadena_sql="SELECT ";
				$cadena_sql.="`id_espacio`, ";
				$cadena_sql.="`codigo_creditos`, ";
                                $cadena_sql.="`codigo_academica`, ";
                              	$cadena_sql.="`nombre`, ";
				$cadena_sql.="`nro_creditos`, ";
                                $cadena_sql.="`horas_directo`, ";
                                $cadena_sql.="`horas_cooperativo`, ";
                                $cadena_sql.="`horas_autonomo`, ";
				$cadena_sql.="`fecha_creacion`, ";
				$cadena_sql.="`aprobado`, ";
                                $cadena_sql.="`id_estado`, ";
				$cadena_sql.="`tipo_nombre`, ";
				$cadena_sql.="`subtipo_nombre`, ";
				$cadena_sql.="`naturaleza_nombre`, ";
				$cadena_sql.="`areaAcademica_nombre` ";
                                $cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"];
                                $cadena_sql.="espacio_academico AS ESPACIO ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
				$cadena_sql.="espacio_tipo USING(id_tipo) ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
				$cadena_sql.="espacio_subtipo USING(id_subtipo) ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
				$cadena_sql.="espacio_naturaleza USING(id_naturaleza) ";
                                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
				$cadena_sql.="area_academica USING(id_areaAcademica) ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`id_espacio`= ";
				$cadena_sql.=$variable;
                        break;*/

            case "areaAcademicaEspacio": #Variable[0]->Contiene si esta sumando o restando
                $cadena_sql="UPDATE ".$configuracion["prefijo"]."area_academica ";
                $cadena_sql.="SET num_espacioArea=num_espacioArea".$variable[0]."1 ";
                $cadena_sql.="WHERE id_areaAcademica=".$variable[1];
                break;

            /*Importante: No borrar, de esto depende los combos agrupados */
            /*case "areasFormacion":
                               $cadena_sql="SELECT id_areaFormacionUd, areaFormacion_nombre FROM ";
 			       $cadena_sql.=$configuracion["prefijo"]."area_formacion_ud ";
                        break;*/

            case "relacionEspacio_areaAcademica":
                $cadena_sql="INSERT INTO ".$configuracion["prefijo"];
                $cadena_sql.="espacio_areaAcademica (id_areaAcademica,id_espacio) ";
                $cadena_sql.="VALUES (".$variable[0].",".$variable[1].")";

                break;

            case "borrar_relacionEspacio_areaAcademica":
                $cadena_sql="DELETE FROM ".$configuracion["prefijo"];
                $cadena_sql.="espacio_areaAcademica WHERE id_areaAcademica=";
                $cadena_sql.=$variable[0]." AND id_espacio=".$variable[1];

                break;

            case "areasFormacion":
                $cadena_sql="SELECT id_areaFormacionUd,  ";
                $cadena_sql.="areaFormacion_nombre FROM ";
                $cadena_sql.=$configuracion["prefijo"]."area_formacion_ud ";
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

                $cadena_sql="SELECT not_asi_cod, not_cra_cod, not_cred, not_cea_cod";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" WHERE not_est_cod =".$variable;
                $cadena_sql.=" AND not_cra_cod= (SELECT est_cra_cod FROM acest WHERE est_cod=".$variable.")";
                $cadena_sql.=" AND not_nota >= '30'";
                $cadena_sql.=" AND not_est_reg like '%A%'";

                break;

            case 'valorCreditosPlan':

                $cadena_sql="SELECT espacio_nroCreditos, id_clasificacion FROM sga_espacio_academico ";
                $cadena_sql.="JOIN sga_planEstudio_espacio ON sga_espacio_academico.id_espacio = sga_planEstudio_espacio.id_espacio ";
                $cadena_sql.="WHERE sga_espacio_academico.id_espacio= ".$variable[0]." AND id_planEstudio=".$variable[1];

                break;

            case "buscar_espacioHoras":

                $cadena_sql=" SELECT DISTINCT ";
                $cadena_sql.="not_asi_cod,";
                $cadena_sql.="asi_nombre,";
                $cadena_sql.="not_nota, ";
                $cadena_sql.="nvl(not_sem,0) not_sem, ";
                $cadena_sql.="not_est_cod, ";
                $cadena_sql.="est_nombre, ";
                $cadena_sql.="est_nro_iden, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="0 intensidad, ";
                $cadena_sql.="not_cred creditos, ";
                $cadena_sql.="not_ano, nob_nombre, not_per,not_nro_ht, not_nro_hp  ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acnot ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="acasi ";
                $cadena_sql.="ON acnot.not_asi_cod = acasi.asi_cod ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="accra ";
                $cadena_sql.="ON acnot.not_cra_cod = accra.cra_cod ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="acest ";
                $cadena_sql.="ON acnot.not_est_cod = acest.est_cod ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="acnotobs ";
                $cadena_sql.="ON acnot.not_obs = acnotobs.nob_cod ";
                $cadena_sql.=" WHERE not_est_cod = ".$variable[0]['CODIGO'];
                $cadena_sql.=" AND not_cra_cod = ".$variable[0]['CARRERA'];
//                $cadena_sql.=" AND NOT_OBS != 19 AND NOT_OBS != 20";
                $cadena_sql.=" AND not_est_reg like '%A' ";
//                $cadena_sql.=" AND pen_asi_cod not in (select pen_asi_cod from acnot ";
//                $cadena_sql.=" inner join acpen on pen_asi_cod= not_asi_cod ";
//                $cadena_sql.=" where pen_ind_ele='S' and not_est_cod=".$variable." and not_nota<'30') ";
                $cadena_sql.=" ORDER BY not_sem, not_asi_cod, not_ano desc, not_per desc ";

                //echo $cadena_sql;exit;

                break;

            case "promedioHoras":

                $cadena_sql=" SELECT DISTINCT ";
                $cadena_sql.="not_nota, ";
                $cadena_sql.="not_sem, ";
                $cadena_sql.="pen_cre creditos, asi_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acnot ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="acpen ";
                $cadena_sql.="ON acnot.not_cra_cod = acpen.pen_cra_cod ";
                $cadena_sql.="AND acnot.not_asi_cod = acpen.pen_asi_cod ";
                $cadena_sql.="INNER JOIN acasi ON acnot.not_asi_cod = acasi.asi_cod ";
                $cadena_sql.="WHERE not_est_cod = ".$variable;
//                    $cadena_sql.=" AND not_nota >= 30 ";
                $cadena_sql.=" AND NOT_OBS != 19 AND NOT_OBS != 20 ";
                $cadena_sql.=" ORDER BY not_sem ";
                break;

            case "promedioAcad":
                $cadena_sql="SELECT FA_PROMEDIO_NOTA(".$variable.") ";
                break;

        }



        return $cadena_sql;

    }
}
?>
