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

class sql_registroActualizarPlanesClasificacion extends sql
{
	function cadena_sql($configuracion,$opcion,$variable="")
	{

		switch($opcion)
		{

                    case 'espaciosCargados':
                        $cadena_sql=" SELECT CLP_ASI_COD, CLP_PEN_NRO ";
                        $cadena_sql.=" FROM acclasificacpen ";
                        $cadena_sql.=" WHERE CLP_ESTADO LIKE '%A%' ";
                        $cadena_sql.=" ORDER BY clp_pen_nro, clp_asi_cod";
                        break;

                    case 'clasificacionEspacio':

                        $cadena_sql=" SELECT id_clasificacion ";
                        $cadena_sql.=" FROM sga_planEstudio_espacio ";
                        $cadena_sql.=" WHERE id_planEstudio ='".$variable[1]."' ";
                        $cadena_sql.=" AND id_espacio ='".$variable[0]."'";
                        break;

                    case 'actualizarClasificacion':
                        $cadena_sql=" UPDATE acclasificacpen ";
                        $cadena_sql.=" SET CLP_CEA_COD='".$variable[2]."' ";
                        $cadena_sql.=" WHERE CLP_ASI_COD ='".$variable[0]."'";
                        $cadena_sql.=" AND CLP_PEN_NRO='".$variable[1]."'";
                        $cadena_sql.=" AND CLP_ASI_COD NOT IN (8100, 8101, 8102, 8103, 8104)";
                        break;


                      case 'espaciosOracle':
                        $cadena_sql="SELECT PEN_CRA_COD, PEN_ASI_COD, PEN_NRO";
                        $cadena_sql.=" FROM ACPEN";
                        $cadena_sql.=" WHERE PEN_NRO>200";
                        $cadena_sql.=" AND PEN_NRO!=295";
                        //$cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";
                        $cadena_sql.=" AND PEN_ASI_COD NOT IN (8100, 8101, 8102, 8103, 8104)";
                        $cadena_sql.=" ORDER BY 1, 2, 3";
                        break;

                    case 'planEstudio':
                        $cadena_sql = "SELECT DISTINCT id_planEstudio, "; //0
                        $cadena_sql .= "                PEE.id_espacio, ";//1
                        $cadena_sql .= "                espacio_nombre, ";//2
                        $cadena_sql .= "                id_nivel, ";      //3
                        $cadena_sql .= "                PEE.id_clasificacion, ";//4
                        $cadena_sql .= "                clasificacion_abrev, ";//5
                        $cadena_sql .= "                espacio_nroCreditos, ";//6
                        $cadena_sql .= "                PEE.id_estado, ";//7
                        $cadena_sql .= "                PEE.id_aprobado, ";//8
                        $cadena_sql .= "                PEE.id_cargado, ";//9
                        $cadena_sql .= "                horasDirecto, ";//10
                        $cadena_sql .= "                horasCooperativo, ";//11
                        $cadena_sql .= "                espacio_horasAutonomo, ";//12
                        $cadena_sql .= "                semanas, ";//13
                        $cadena_sql .= "                PEE.ofrecido_portafolio ";//14
                        $cadena_sql .= "FROM   ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                        $cadena_sql .= "       INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON PEE.id_espacio = EA.id_espacio ";
                        $cadena_sql .= "       INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion = EC.id_clasificacion ";
                        $cadena_sql .= "WHERE  id_planEstudio = '".$variable."' ";
                        $cadena_sql .= "       AND PEE.id_estado = '1' ";
                        $cadena_sql .= "       AND PEE.id_aprobado = '1' ";
                        $cadena_sql .= "       AND PEE.id_clasificacion != '4' ";
                        $cadena_sql .= "ORDER  BY id_nivel, ";
                        $cadena_sql .= "          PEE.id_espacio ASC ";
                        break;

                    case 'buscarEspacio':
                      $cadena_sql="SELECT *";
                      $cadena_sql.=" FROM ACCLASIFICACPEN";
                      $cadena_sql.=" WHERE CLP_ASI_COD=".$variable[1];
                      $cadena_sql.=" AND CLP_CRA_COD=".$variable[0];
                      $cadena_sql.=" AND CLP_PEN_NRO=".$variable[2];
                      break;

                    case 'crearClasificacion':
                      $cadena_sql="INSERT INTO ACCLASIFICACPEN ";
                      $cadena_sql.="(CLP_CRA_COD, CLP_ASI_COD, CLP_PEN_NRO, CLP_CEA_COD, CLP_ESTADO)";
                      $cadena_sql.=" VALUES (".$variable[0].",";
                      $cadena_sql.=" ".$variable[1].",";
                      $cadena_sql.=" ".$variable[2].",";
                      $cadena_sql.=" ".$variable[3].",";
                      $cadena_sql.=" 'A')";
                      break;

//                    case 'actualizarClasificacion':
//                        $cadena_sql ="UPDATE ".$configuracion['prefijo']."planEstudio_espacio";
//                        $cadena_sql .=" SET id_clasificacion='".$variable[2]."', ofrecido_portafolio='1'";
//                        $cadena_sql .=" WHERE id_planEstudio='".$variable[0]."'";
//                        $cadena_sql .=" AND id_espacio='".$variable[1]."'";
//                        break;

                    case 'buscarElectivo':
                        $cadena_sql=" SELECT pec_id_espacio, pec_id_planEstudio, pec_id_estado ";
                        $cadena_sql.=" FROM sga_planEstudio_clasificacion ";
                        $cadena_sql.=" WHERE pec_id_planEstudio=".$variable[0];
                        $cadena_sql.=" AND pec_id_espacio=".$variable[1];
                        $cadena_sql.=" AND pec_id_clasificacion='4'";
                        break;
                    
                    case 'actualizarElectivo':
                        $cadena_sql=" UPDATE sga_planEstudio_clasificacion SET pec_id_estado='1' ";
                        $cadena_sql.=" WHERE pec_id_planEstudio=".$variable[0];
                        $cadena_sql.=" AND pec_id_espacio=".$variable[1];
                        $cadena_sql.=" AND pec_id_clasificacion='4'";
                        break;
                    
                    case 'crearElectivo':
                        $cadena_sql=" INSERT INTO sga_planEstudio_clasificacion VALUES";
                        $cadena_sql.=" ('".$variable[2]."',";
                        $cadena_sql.=" '".$variable[1]."',";
                        $cadena_sql.=" '".$variable[0]."',";
                        $cadena_sql.=" '4',";
                        $cadena_sql.=" '1')";
                        break;

                    case 'actualizarPortafolio':
                        $cadena_sql=" UPDATE sga_planEstudio_clasificacion SET pec_id_estado='1' ";
                        $cadena_sql.=" WHERE pec_id_planEstudio=".$variable[0];
                        $cadena_sql.=" AND pec_id_espacio=".$variable[1];
                        $cadena_sql.=" AND pec_id_clasificacion='4'";
                        break;

                    case 'registroLogEvento':

                         $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                         $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                         $cadena_sql.="'".$variable[1]."', ";
                         $cadena_sql.="'".$variable[2]."', ";
                         $cadena_sql.="'".$variable[3]."', ";
                         $cadena_sql.="'".$variable[4]."', ";
                         $cadena_sql.="'".$variable[5]."')";

                         break;

                      case "periodo":
                         $cadena_sql="select ape_ano, ape_per " ;
                         $cadena_sql.="from acasperi ";
                         $cadena_sql.="where ape_estado like '%A%'";
                         break;

                      case "estudiantesCreditos":
                         $cadena_sql="SELECT EST_COD " ;
                         $cadena_sql.="FROM ACEST ";
                         $cadena_sql.="WHERE EST_IND_CRED LIKE '%S%' ";
                         $cadena_sql.="AND EST_CRA_COD = ";
                         $cadena_sql.="ORDER BY EST_COD";
                         break;
                     
                      case "inscripcionesEstudiante":
                         $cadena_sql="SELECT INS_ASI_COD, INS_CRA_COD " ;
                         $cadena_sql.="FROM ACINS ";
                         $cadena_sql.="WHERE INS_EST_COD=".$variable;
                         break;

                      case "buscarInfoEspacio":
                         $cadena_sql="SELECT PEN_NRO_HT, PEN_NRO_HP, PEN_NRO_AUT, PEN_CRE, CLP_CEA_COD " ;
                         $cadena_sql.="FROM ACPEN ";
                         $cadena_sql.="INNER JOIN ACCLASIFICACPEN ON CLP_CRA_COD= PEN_CRA_COD AND CLP_PEN_NRO= PEN_NRO AND CLP_ASI_COD= PEN_ASI_COD ";
                         $cadena_sql.="WHERE PEN_ASI_COD=".$variable[0]." AND PEN_CRA_COD=".$variable[1];
                         break;
                     
                      case "buscarInfoEspacioElectivo":
                         $cadena_sql="SELECT PEN_NRO_HT, PEN_NRO_HP, PEN_NRO_AUT, PEN_CRE, CLP_CEA_COD " ;
                         $cadena_sql.="FROM ACPEN ";
                         $cadena_sql.="INNER JOIN ACCLASIFICACPEN ON CLP_CRA_COD= PEN_CRA_COD AND CLP_PEN_NRO= PEN_NRO AND CLP_ASI_COD= PEN_ASI_COD ";
                         $cadena_sql.="WHERE PEN_ASI_COD=".$variable[0];
                         $cadena_sql.=" AND CLP_CEA_COD=4";
                         $cadena_sql.=" AND CLP_ESTADO LIKE '%A%'";
                         break;

                      case "actualizarInscripcionEst":
                         $cadena_sql="update acins " ;
                         $cadena_sql.=" set INS_NRO_HT='".$variable[0]."'";
                         $cadena_sql.=", INS_NRO_HP='".$variable[1]."'";
                         $cadena_sql.=", INS_NRO_AUT='".$variable[2]."'";
                         $cadena_sql.=", INS_CRED='".$variable[3]."'";
                         $cadena_sql.=", INS_CEA_COD='".$variable[4]."'";
                         $cadena_sql.=" WHERE INS_ASI_COD='".$variable[5]."'";
                         $cadena_sql.=" AND INS_CRA_COD='".$variable[6]."'";
                         $cadena_sql.=" AND INS_EST_COD='".$variable[7]."'";
                         break;

                      case "notasEstudiante":
                         $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD " ;
                         $cadena_sql.="FROM ACNOT ";
                         $cadena_sql.="WHERE NOT_EST_COD=".$variable;
                         break;

                      case "notasEstudianteElectivas":
                         $cadena_sql="SELECT NOT_ASI_COD, NOT_CRA_COD " ;
                         $cadena_sql.="FROM ACNOT ";
                         $cadena_sql.="WHERE NOT_EST_COD=".$variable;
                         $cadena_sql.=" AND NOT_CEA_COD IS NULL";
                         break;

                     case "actualizarNotasEst":
                         $cadena_sql="UPDATE MNTAC.ACNOT " ;
                         $cadena_sql.=" SET ";
//                         $cadena_sql.=" NOT_NRO_HT='".$variable[0]."'";
//                         $cadena_sql.=", NOT_NRO_HP='".$variable[1]."'";
//                         $cadena_sql.=", NOT_NRO_AUT='".$variable[2]."'";
//                         $cadena_sql.=", NOT_CRED='".$variable[3]."'";
                         $cadena_sql.=" NOT_CEA_COD='".$variable[4]."'";
                         $cadena_sql.=" WHERE NOT_ASI_COD='".$variable[5]."'";
                         $cadena_sql.=" AND NOT_CRA_COD='".$variable[6]."'";
                         $cadena_sql.=" AND NOT_EST_COD='".$variable[7]."'";
                         break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>
