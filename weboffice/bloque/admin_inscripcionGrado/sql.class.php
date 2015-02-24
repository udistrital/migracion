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

class sql_adminInscripcionGrado extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosUsuarios":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="emp_cod, ";
				$cadena_sql.="emp_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="peemp ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="emp_nro_iden=".$variable."";
				break;
			
			case "verificaEstudiantesSecretario":
				//Oracle
				$cadena_sql="SELECT ROWNUM, ";
				$cadena_sql.="codigo, ";
				$cadena_sql.="nombre, ";
				$cadena_sql.="apellido, ";
				$cadena_sql.="cedula, ";
				$cadena_sql.="tipo, ";
				$cadena_sql.="municipio, ";
				$cadena_sql.="carrera, ";
				$cadena_sql.="trabajo, ";
				$cadena_sql.="director, ";
				$cadena_sql.="acta, ";				
				$cadena_sql.="direccion, ";
				$cadena_sql.="telefono, ";
				$cadena_sql.="celular, ";
				$cadena_sql.="mail, ";
				$cadena_sql.="sexo ";
				$cadena_sql.="FROM ";
				$cadena_sql.="( "; 
				$cadena_sql.="SELECT est_cod codigo, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,INSTR(est_nombre,' ',1,2)+1))) nombre, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1))) apellido, ";
				$cadena_sql.="	est_nro_iden cedula, ";
				$cadena_sql.="	est_tipo_iden tipo, ";
				$cadena_sql.="	(SELECT mun_nombre ";
				$cadena_sql.="	FROM acestotr, gemunicipio ";
				$cadena_sql.="	WHERE acest.est_cod = eot_cod ";
				$cadena_sql.="	AND eot_cod_mun_exp = mun_cod) municipio, ";
				$cadena_sql.="	cra_nombre carrera, ";
				$cadena_sql.="	ing_nom_trabajo trabajo, ";
				$cadena_sql.="  dir_nombre ||' '|| dir_apellido director, ";
				$cadena_sql.="	ing_acta acta, ";
				$cadena_sql.="  est_direccion direccion, ";
				$cadena_sql.="  est_telefono telefono, ";
				$cadena_sql.="  eot_tel_cel celular, ";
				$cadena_sql.="  eot_email mail, ";
				$cadena_sql.="  decode(est_sexo,'M','Masculino','F','Femenino') sexo ";
				$cadena_sql.="FROM acasperi, gedep, accra, acsecretario, peemp, acinsgrado, acest, acdirectorgrado, acestotr ";
				$cadena_sql.="WHERE ape_estado = 'A' ";
				$cadena_sql.="	AND dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND emp_nro_iden =".$variable[0]." ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.=" 	AND cra_cod = ing_cra_cod ";
				$cadena_sql.="	AND ape_ano = ing_ano ";
				$cadena_sql.="	AND ape_per = ing_per ";
				$cadena_sql.="	AND ing_estado = 'A' ";
				$cadena_sql.="	AND ing_cra_cod = est_cra_cod ";
				$cadena_sql.="	AND ing_est_cod = est_cod ";
				$cadena_sql.="  AND dir_nro_iden=ing_director ";
				$cadena_sql.="  AND eot_cod=est_cod "; 
				$cadena_sql.="ORDER BY est_cod ASC ";
				$cadena_sql.=")";
				break;
			
			case "muestraInscripcion":
				$cadena_sql="SELECT ";
				$cadena_sql.="est_cod, ";
				$cadena_sql.="INITCAP(LOWER(est_nombre)), ";
				$cadena_sql.="est_nro_iden, ";
				$cadena_sql.="est_tipo_iden, ";
				$cadena_sql.="est_cra_cod, ";
				$cadena_sql.="cra_nombre, ";
				$cadena_sql.="est_direccion, ";
				$cadena_sql.="est_telefono, ";
				$cadena_sql.="eot_email, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_exp) mun_exp, ";
				$cadena_sql.="(SELECT mun_nombre FROM gemunicipio WHERE mun_cod = acestotr.eot_cod_mun_res) mun_res, ";
				$cadena_sql.="eot_tel_cel, ";
				$cadena_sql.="ing_nom_trabajo, ";
				$cadena_sql.="dir_nombre ||' '|| dir_apellido, ";
				$cadena_sql.="ing_tipo_trabajo, ";
				$cadena_sql.="ing_acta, ";  
				$cadena_sql.="ing_estado, ";
				$cadena_sql.="ing_fecha ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acest, ";
				$cadena_sql.="accra, ";
				$cadena_sql.="acestotr, ";
				$cadena_sql.="acinsgrado, ";
				$cadena_sql.="acdirectorgrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ing_est_cod=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ing_cra_cod=cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="eot_cod=est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="est_cod=ing_est_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dir_nro_iden=ing_director ";
				$cadena_sql.="AND ";
				$cadena_sql.="ing_estado='A'";
				//echo $cadena_sql."<br>";
				break;
				
			case "consultaProyectos":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_nombre "; 
				$cadena_sql.="FROM gedep, accra, acsecretario, peemp ";
				$cadena_sql.="WHERE dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.="	AND cra_cod IN (SELECT ing_cra_cod ";
				$cadena_sql.="	        FROM acasperi, acinsgrado ";
				$cadena_sql.="	    WHERE ape_estado = 'A' ";
				$cadena_sql.="	    AND ape_ano = ing_ano ";
				$cadena_sql.=" 	    AND ape_per = ing_per) ";
				$cadena_sql.="ORDER BY cra_cod, cra_nombre";
				//echo $cadena_sql."<br>";
				break;
			
			case "consultaTotalProyectos":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_nombre "; 
				$cadena_sql.="FROM gedep, accra, acsecretario, peemp ";
				$cadena_sql.="WHERE dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND emp_nro_iden = ".$variable[0]." ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.="ORDER BY cra_cod, cra_nombre";
				//echo $cadena_sql."<br>";
				break;
				
			case "verificaEstudiantesCarrera":
				$cadena_sql="SELECT ROWNUM, ";
				$cadena_sql.="	codigo, ";
				$cadena_sql.="	nombre, ";
				$cadena_sql.="	apellido, ";
				$cadena_sql.="	cedula, ";
				$cadena_sql.="	tipo, ";
				$cadena_sql.="	municipio, ";
				$cadena_sql.="	carrera, ";
				$cadena_sql.="  trabajo, ";
				$cadena_sql.="  director, ";
				$cadena_sql.="  acta, ";
				$cadena_sql.="  direccion, ";
				$cadena_sql.="  telefono, ";
				$cadena_sql.="  celular, ";
				$cadena_sql.="  mail, ";
				$cadena_sql.="  sexo ";
				$cadena_sql.="FROM ";
				$cadena_sql.=" (";
				$cadena_sql.="SELECT est_cod codigo, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,INSTR(est_nombre,' ',1,2)+1))) nombre, ";
				$cadena_sql.="	INITCAP(LOWER(SUBSTR(est_nombre,1,INSTR(est_nombre,' ',1,2)-1))) apellido, ";
				$cadena_sql.="	est_nro_iden cedula, ";
				$cadena_sql.="	est_tipo_iden tipo, ";
				$cadena_sql.="	(SELECT mun_nombre ";
				$cadena_sql.="	FROM acestotr, gemunicipio ";
				$cadena_sql.="	    WHERE acest.est_cod = eot_cod ";
				$cadena_sql.="	        AND eot_cod_mun_exp = mun_cod) municipio, ";
				$cadena_sql.="	cra_nombre carrera, ";
				$cadena_sql.="	ing_nom_trabajo trabajo, ";
				$cadena_sql.="  dir_nombre ||' '|| dir_apellido director, ";
				$cadena_sql.="	ing_acta acta, ";
				$cadena_sql.="  est_direccion direccion, ";
				$cadena_sql.="  est_telefono telefono, ";
				$cadena_sql.="  eot_tel_cel celular, ";
				$cadena_sql.="  eot_email mail, ";
				$cadena_sql.="  decode(est_sexo,'M','Masculino','F','Femenino') sexo ";
				$cadena_sql.="FROM acasperi, gedep, accra, acsecretario, peemp, acinsgrado, acest, acdirectorgrado, acestotr ";
				$cadena_sql.="WHERE ape_estado = 'A' ";
				$cadena_sql.="	AND dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND cra_cod = ".$variable[0]." ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.="	AND cra_cod = ing_cra_cod ";
				$cadena_sql.="	AND ape_ano = ing_ano ";
				$cadena_sql.="	AND ape_per = ing_per ";
				$cadena_sql.="	AND ing_estado = 'A' ";
				$cadena_sql.="	AND ing_cra_cod = est_cra_cod ";
				$cadena_sql.="	AND ing_est_cod = est_cod ";
				$cadena_sql.="  AND dir_nro_iden=ing_director ";
				$cadena_sql.="  AND eot_cod=est_cod "; 
				$cadena_sql.="ORDER BY est_cod ASC ";
				$cadena_sql.=")";
				break;
				
			case "contarRegistros":
				$cadena_sql="SELECT count(est_cod) ";
				$cadena_sql.="FROM acasperi, gedep, accra, acsecretario, peemp, acinsgrado, acest ";
				$cadena_sql.="WHERE ape_estado = 'A' ";
				$cadena_sql.="	AND dep_cod = cra_dep_cod ";
				$cadena_sql.="	AND cra_cod = ".$variable[1]." ";
				$cadena_sql.="	AND dep_cod = sec_dep_cod ";
				$cadena_sql.="	AND sec_cod = emp_cod ";
				$cadena_sql.="	AND sec_estado = 'A' ";
				$cadena_sql.="	AND cra_estado = 'A' ";
				$cadena_sql.="	AND cra_cod = ing_cra_cod ";
				$cadena_sql.="	AND ape_ano = ing_ano ";
				$cadena_sql.="	AND ape_per = ing_per ";
				$cadena_sql.="	AND ing_estado = 'A' ";
				$cadena_sql.="	AND ing_cra_cod = est_cra_cod ";
				$cadena_sql.="	AND ing_est_cod = est_cod ";
				$cadena_sql.="ORDER BY est_cod ASC ";
				break;

			case "promedioEgresados":
				$cadena_sql="SELECT CRA_COD CODIGO_CARRERA, ";
				$cadena_sql.="CRA_NOMBRE CARRERA, ";
				$cadena_sql.="TO_CHAR(EGR_FECHA_GRADO, 'DD/MM/YYYY') FECHA_GRADO, ";
				$cadena_sql.="EST_COD CODIGO, ";
				$cadena_sql.="EST_NOMBRE NOMBRE, ";
				$cadena_sql.="FA_PROMEDIO_NOTA(EST_COD) PROMEDIO ";
				$cadena_sql.="FROM PEEMP, ACSECRETARIO, ACCRA, ACEGRESADO, ACEST ";
				$cadena_sql.="WHERE  EMP_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="	AND cra_cod = ".$variable[1]." ";
				$cadena_sql.="AND EMP_COD = SEC_COD ";
				$cadena_sql.="AND SEC_ESTADO = 'A' ";
				$cadena_sql.="AND SEC_DEP_COD = CRA_DEP_COD ";
				$cadena_sql.="AND TO_CHAR(EGR_FECHA_GRADO, 'DD/MM/YYYY') = '".$variable[2]."' ";
				$cadena_sql.="AND CRA_COD = EGR_CRA_COD ";
				$cadena_sql.="AND EGR_FECHA_GRADO IS NOT NULL ";
				$cadena_sql.="AND EGR_CRA_COD = EST_CRA_COD ";
				$cadena_sql.="AND EGR_EST_COD = EST_COD ";
				$cadena_sql.="order BY CRA_COD asc, ";
				$cadena_sql.="FA_PROMEDIO_NOTA(EST_COD) desc, ";
				$cadena_sql.="EST_COD asc";
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
