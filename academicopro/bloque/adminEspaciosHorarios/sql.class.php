<?php
/*
 * --------------------------------------------------------------------------------------------------------------------------
 * @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
 * ---------------------------------------------------------------------------------------------------------------------------
 */
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ($configuracion ["raiz_documento"] . $configuracion ["clases"] . "/sql.class.php");
class sql_adminEspaciosHorarios extends sql {
	function cadena_sql($configuracion, $conexion, $opcion, $variable = "") {
		switch ($opcion) {
			
			case 'datos_coordinador' :
				$cadena_sql = "select distinct cra_cod, pen_nro ";
				$cadena_sql .= "from accra ";
				// $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
				$cadena_sql .= "INNER JOIN ACPEN ON accra.cra_cod=acpen.pen_cra_cod ";
				$cadena_sql .= " where cra_emp_nro_iden=" . $variable;
				$cadena_sql .= " and pen_nro>200";
				$cadena_sql .= " and cra_se_ofrece like 'S'";
				$cadena_sql .= " order by cra_cod";
				
				break;
			
			case 'buscarProyectoCurricular' :
				
				$cadena_sql = "select distinct cra_cod, cra_nombre ";
				$cadena_sql .= "from accra ";
				$cadena_sql .= "where cra_cod=".$variable;
				
				break;
			
			case 'grupos_proyecto' :
				
				
				$cadena_sql = "SELECT DISTINCT CUR_GRUPO, CUR_ASI_COD ";
				$cadena_sql .= " FROM ACCURSOS";
				$cadena_sql .= " WHERE CUR_ASI_COD IN " . $variable [0];
				$cadena_sql .= " AND CUR_CRA_COD=" . $variable [1];
				$cadena_sql .= " AND CUR_APE_ANO=" . $variable [4];
				$cadena_sql .= " AND CUR_APE_PER=" . $variable [5];
				$cadena_sql .= " ORDER BY 2,1";
				
				
				
				/*
				
				$cadena_sql = "SELECT DISTINCT HOR_ID,CUR_ASI_COD, CUR_GRUPO ";
				$cadena_sql .= " FROM ACHORARIOS";
				$cadena_sql .= " INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ASI_COD ";
				$cadena_sql .= " WHERE CUR_ASI_COD IN " . $variable [0];
				$cadena_sql .= " AND CUR_CRA_COD=" . $variable [1];
				$cadena_sql .= " AND CUR_APE_ANO=" . $variable [4];
				$cadena_sql .= " AND CUR_APE_PER=" . $variable [5];
				$cadena_sql .= " ORDER BY 2,1";
				*/
				break;
			
			case 'espacios_carrera' :
				
				$cadena_sql = "select distinct pen_asi_cod, asi_nombre ";
				$cadena_sql .= "from acpen ";
				$cadena_sql .= "inner join acasi on acpen.pen_asi_cod=acasi.asi_cod ";
				$cadena_sql .= "where pen_nro=" . $variable [0];
				$cadena_sql .= " and pen_cra_cod=" . $variable [1];
				$cadena_sql .= " order by 1";
				
				break;
			
			case 'horario_gruposOLD' :
				
				$cadena_sql = "SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
				$cadena_sql .= "FROM ACHORARIO ";
				$cadena_sql .= "INNER JOIN ACCURSO ON ACHORARIO.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND ACHORARIO.HOR_NRO=ACCURSO.CUR_NRO ";
				$cadena_sql .= "INNER JOIN GESEDE ON ACHORARIO.HOR_SED_COD=GESEDE.SED_COD ";
				$cadena_sql .= "WHERE CUR_ASI_COD=" . $variable [0];
				$cadena_sql .= " AND CUR_CRA_COD=" . $variable [1];
				$cadena_sql .= " AND HOR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= " AND HOR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
				$cadena_sql .= " AND HOR_NRO=" . $variable [3];
				$cadena_sql .= " ORDER BY 1,2,3";
				
				break;
			
			case "horario_grupos" :
				
				$cadena_sql="SELECT DISTINCT";
				$cadena_sql.=" horario.hor_dia_nro          DIA,";
				$cadena_sql.=" horario.hor_hora             HORA,";
				$cadena_sql.=" sede.sed_id                  NOM_SEDE,";
				$cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
				$cadena_sql.=" salon.sal_nombre             NOM_SALON,";
				$cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
				$cadena_sql.=" edi.edi_nombre               NOM_EDIFICIO,";
				$cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)   NRO_GRUPO, ";
				$cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
				$cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
				$cadena_sql.=" FROM achorarios horario";
				$cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_asi_cod ";
				$cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
				$cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
				$cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
				$cadena_sql.=" WHERE cur_asi_cod=".$variable[0]; //codigo del espacio
				$cadena_sql.=" AND cur_cra_cod=" . $variable[1];
				$cadena_sql.=" AND cur_grupo=" . $variable[2]; //
				$cadena_sql.=" AND cur_ape_ano=" . $variable[4];
				$cadena_sql.=" AND cur_ape_per=" . $variable[5];
				
				$cadena_sql.=" ORDER BY 1,2,3"; echo $cadena_sql;
				
				/*
				
				
				
				$cadena_sql = "SELECT HOR_G.HOR_DIA_NRO DIA, ";
				$cadena_sql .= " HOR_G.HOR_HORA HORA, ";
				//Datos del Espacio
				$cadena_sql .= " HOR_G.HOR_SED_COD COD_SEDE, ";
				$cadena_sql .= "SEDE.SED_ID NOM_SEDE, ";
				$cadena_sql .= "SALON.SAL_COD SALON_OLD,";
				$cadena_sql .= "SALON.SAL_ID_ESPACIO SALON_NVO, ";
				$cadena_sql .= "SALON.SAL_NOMBRE NOM_SALON , ";
				$cadena_sql .= "SALON.SAL_EDIFICIO ID_EDIFICIO, ";
				$cadena_sql .= "EDIF.EDI_NOMBRE NOM_EDIFICIO ";
				$cadena_sql .= "FROM ACHORARIOS HOR_G ";
				$cadena_sql .= " INNER JOIN accurso CUR ON HOR_G.HOR_ASI_COD=CUR.CUR_ASI_COD AND HOR_G.HOR_NRO=CUR.CUR_NRO AND CUR.CUR_APE_ANO=HOR_G.HOR_APE_ANO AND CUR.CUR_APE_PER=HOR_G.HOR_APE_PER ";
				$cadena_sql .= " INNER JOIN gesede SEDE ON HOR_G.HOR_SED_COD=SEDE.SED_COD";
				$cadena_sql .= " INNER JOIN gesalon_2012 SALON ON HOR_G.HOR_SAL_ID_ESPACIO=SALON.SAL_ID_ESPACIO";
				$cadena_sql .= " INNER JOIN geedificio EDIF ON SALON.SAL_EDIFICIO=EDIF.EDI_COD";
				$cadena_sql .= " WHERE CUR.CUR_ASI_COD=" . $variable [0];
				$cadena_sql .= " AND CUR.CUR_NRO=" . $variable [3];
				$cadena_sql .= " AND CUR.CUR_CRA_COD=" . $variable [1];
				$cadena_sql .= " AND CUR.CUR_APE_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= " AND CUR.CUR_APE_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
				*/
				break;
			
			case 'estudiantes_inscritos' :
				
				$cadena_sql = "select ins_est_cod, est_nombre, cra_nombre ";
				$cadena_sql .= "from acins ";
				$cadena_sql .= "inner join acest on acins.ins_est_cod=acest.est_cod ";
				$cadena_sql .= "inner join accra on acins.ins_cra_cod=accra.cra_cod ";
				$cadena_sql .= " and ins_asi_cod=" . $variable [1];
				$cadena_sql .= " and ins_gr=" . $variable [2];
				$cadena_sql .= " AND INS_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				// $cadena_sql.=" AND INS_ANO=2009 ";
				$cadena_sql .= " AND INS_PER=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
				// $cadena_sql.=" AND INS_PER=3";
				$cadena_sql .= " ORDER BY 1";
				
				break;
			
			case "borrarEstudianteMysql" :
				
				// ELIMINAR REGISTRO EN MYSQL
				// DELETE FROM sga_horario_estudiante WHERE horario_codEstudiante = 20092025006 AND (horario_estado =2 OR horario_estado =4) and horario_idProyectoCurricular =
				// //25 and `horario_ano` = 2010 and `horario_periodo` = 1 and`horario_idEspacio`= 12
				
				$cadena_sql = "DELETE FROM " . $configuracion ["prefijo"] . "horario_estudiante ";
				$cadena_sql .= "WHERE horario_codEstudiante = '" . $variable [0] . "' ";
				$cadena_sql .= "AND (horario_estado =2 OR horario_estado =4) ";
				// $cadena_sql.="AND horario_idProyectoCurricular = '".$variable[1]."' ";
				$cadena_sql .= "AND horario_idEspacio = '" . $variable [2] . "' ";
				$cadena_sql .= "AND horario_grupo = '" . $variable [3] . "' ";
				$cadena_sql .= "AND horario_ano = '2010' ";
				$cadena_sql .= "AND horario_periodo = '1' ";
				
				break;
			
			case "borrarEstudianteOracle" :
				// Consulta BorrarOracleResgistroEstudiante":
				// DELETE from acins where ins_ano=2010 and ins_per=1 and ins_cra_cod=25 and ins_asi_cod = 9 and ins_gr = 22 and ins_est_cod = 20092025006
				$cadena_sql = "DELETE from acins  ";
				$cadena_sql .= "where ins_est_cod = '" . $variable [0] . "' ";
				$cadena_sql .= "and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= "and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				// $cadena_sql.="and ins_cra_cod='".$variable[1]."' ";
				$cadena_sql .= "and ins_asi_cod = '" . $variable [2] . "' ";
				$cadena_sql .= "and ins_gr = '" . $variable [3] . "' ";
				
				break;
			
			case "actualizarCupo":
                $cadena_sql = "UPDATE accursos SET ";
				$cadena_sql .= "cur_nro_ins = (SELECT COUNT(*) from acins ";
				$cadena_sql .= "where ins_ano= (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= "and ins_per= (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= "and ins_cra_cod='" . $variable [1] . "' and ins_asi_cod='" . $variable [2] . "' and ins_gr='" . $variable [3] . "') ";
				$cadena_sql .= "where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= "and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
				$cadena_sql .= "and cur_cra_cod = '" . $variable [1] . "' and cur_asi_cod ='" . $variable [2] . "' ";
				$cadena_sql .= "and cur_nro ='" . $variable [3] . "' ";
				// $cadena_sql.="and cur_nro_cupo >= cur_nro_ins";
				// echo $cadena_sql;exit;
				break;
			
			case 'registroEvento' :
				
				$cadena_sql = "insert into " . $configuracion ['prefijo'] . "log_eventos ";
				$cadena_sql .= "VALUES('','" . $variable [0] . "',";
				$cadena_sql .= "'" . $variable [1] . "',";
				$cadena_sql .= "'" . $variable [2] . "',";
				$cadena_sql .= "'" . $variable [3] . "',";
				$cadena_sql .= "'" . $variable [4] . "',";
				$cadena_sql .= "'" . $variable [5] . "')";
				
				break;
			
			case 'ano_periodo' :
				$cadena_sql = "SELECT APE_ANO,APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%'";
				
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}
}
?>