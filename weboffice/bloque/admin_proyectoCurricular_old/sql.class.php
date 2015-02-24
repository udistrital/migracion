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

class sql_adminProyectoCurricular extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
	//	$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{	
			case "carreraCoordinador":
				$cadena_sql="SELECT ";
				$cadena_sql.="cra_cod, ";
				$cadena_sql.="cra_abrev ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA_EMP_NRO_IDEN=".$variable;
				break;

			case "carrera":
				//en ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="CRA.CRA_COD, ";
				$cadena_sql.="CRA.CRA_NOMBRE, ";
				$cadena_sql.="CRA.CRA_ABREV, ";
				$cadena_sql.="CRA.CRA_COD_ICFES, ";
				$cadena_sql.="to_char(CRA.CRA_FECHA_APROB_ICFES, 'dd/mm/yyyy'), ";
				//$cadena_sql.="CRA.CRA_FECHA_APROB_ICFES, ";
				$cadena_sql.="CRA.CRA_RESOL_SUP, ";
				$cadena_sql.="DEP.DEP_NOMBRE, ";
				$cadena_sql.="TIP.TRA_NIVEL, ";
				$cadena_sql.="TIP.TRA_COD_NIVEL, ";
				$cadena_sql.="TIP.TRA_NOMBRE, ";
				$cadena_sql.="CRA.CRA_ESTADO, ";
				$cadena_sql.="CRA.CRA_JUST_COD, ";
				$cadena_sql.="TIP.TRA_CICLO, ";
				$cadena_sql.="TIP.TRA_DURACION, ";
				$cadena_sql.="CRA.CRA_JORNADA, ";
				$cadena_sql.="COMP.COM_COD_MET, ";
				$cadena_sql.="COMP.COM_CREDITOS, ";
				$cadena_sql.="COMP.COM_TITULO_CRA, ";
				$cadena_sql.="COMP.COM_URL_CRA, ";
				$cadena_sql.="COMP.COM_URL_PERFIL_ASP, ";
				$cadena_sql.="COMP.COM_URL_PERFIL_PRO, ";
				$cadena_sql.="COMP.COM_PROPE_CRA, ";
				$cadena_sql.="COMP.COM_PROPE_CRA_COD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.ACCRA CRA ";
				$cadena_sql.=" INNER JOIN ";
				$cadena_sql.="MNTAC.ACTIPCRA TIP ";
				$cadena_sql.="ON CRA.CRA_TIP_CRA = TIP.TRA_COD ";
				$cadena_sql.=" INNER JOIN ";
				$cadena_sql.="MNTGE.GEDEP DEP ";
				$cadena_sql.="ON CRA.CRA_DEP_COD = DEP.DEP_COD ";
				$cadena_sql.=" INNER JOIN ";
				$cadena_sql.="MNTAC.COMPLECRA COMP ";
				$cadena_sql.="ON CRA.CRA_COD = COMP.COM_COD_CRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="CRA.CRA_COD=";
				$cadena_sql.=$variable;
				//echo $cadena_sql;
				break;		
				
		case "complemento":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT (*) COM ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.COMPLECRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="com_cod_cra= ";
				$cadena_sql.="'".$variable."'";
				//echo $cadena_sql;
				break;			
				
		case "inserta_complemento":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="MNTAC.COMPLECRA ";
				$cadena_sql.="(";
				$cadena_sql.="COM_COD_CRA, ";
				$cadena_sql.="COM_COD_MET, ";
				$cadena_sql.="COM_TITULO_CRA, ";
				$cadena_sql.="COM_PROPE_CRA, ";
				$cadena_sql.="COM_PROPE_CRA_COD, ";
				$cadena_sql.="COM_CREDITOS, ";
				$cadena_sql.="COM_URL_CRA, ";
				$cadena_sql.="COM_URL_PERFIL_ASP, ";
				$cadena_sql.="COM_URL_PERFIL_PRO ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.="'".$variable."', ";
				$cadena_sql.="'1', ";
				$cadena_sql.="'',";
				$cadena_sql.="'N',";
				$cadena_sql.="'0',";
				$cadena_sql.="'',";
				$cadena_sql.="'',";
				$cadena_sql.="'',";
				$cadena_sql.="''";
				$cadena_sql.=")";
				break;	

		case "actualiza_complemento":
				$cadena_sql="UPDATE ";
				$cadena_sql.="MNTAC.COMPLECRA ";
				$cadena_sql.="SET ";
				$cadena_sql.="COM_COD_MET=";
				$cadena_sql.="'".$variable[1]."', ";
				$cadena_sql.="COM_TITULO_CRA=";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="COM_PROPE_CRA=";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="COM_PROPE_CRA_COD=";
				$cadena_sql.="'".$variable[4]."', ";
				$cadena_sql.="COM_CREDITOS=";
				$cadena_sql.="'".$variable[5]."', ";
				$cadena_sql.="COM_URL_CRA=";
				$cadena_sql.="'".$variable[6]."', ";
				$cadena_sql.="COM_URL_PERFIL_ASP=";
				$cadena_sql.="'".$variable[7]."', ";
				$cadena_sql.="COM_URL_PERFIL_PRO=";
				$cadena_sql.="'".$variable[8]."'";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="COM_COD_CRA=";
				$cadena_sql.="'".$variable[0]."'";

				break;					
				
			case "nbc":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="cra.nbc_cod, ";
				$cadena_sql.="nbc.nbc_cod_area, ";
				$cadena_sql.="cra.nbc_cod_esp, ";
				$cadena_sql.="cra.nbc_nivel ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntac.nbccra cra ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.="mntac.nbc nbc ";
				$cadena_sql.="ON cra.nbc_cod=nbc.nbc_cod ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra.nbc_cod_cra= ";
				$cadena_sql.="'".$variable."'";
				//echo $cadena_sql;
				break;	
			
			case "acreditacion":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="cra.acrecra_cod, ";
				$cadena_sql.="cra.acre_cod_tipo, ";
				$cadena_sql.="cra.acre_resolucion, ";
				$cadena_sql.="to_char(cra.acre_fecha, 'dd/mm/yyyy'), ";
				$cadena_sql.="cra.acre_duracion, ";
				$cadena_sql.="cra.acre_entidad ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntac.acrecra cra ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.="mntac.acretipo tip ";
				$cadena_sql.="ON cra.acre_cod_tipo=tip.acre_cod_tipo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="cra.acre_cod_cra=";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="tip.acre_nivel=";
				$cadena_sql.="'".$variable[1]."'";
				$cadena_sql.=" AND cra.acre_fecha= ";
				$cadena_sql.="(SELECT MAX (acre_fecha) FROM mntac.acrecra WHERE cra.acre_cod_cra='".$variable[0]."')";

				//echo $cadena_sql;
				break;	
			
					
		case "actualizabasico":
				$cadena_sql="UPDATE ";
				$cadena_sql.="MNTAC.ACCRA "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="CRA_FECHA_APROB_ICFES=";
				$cadena_sql.= $variable[1].", ";
				$cadena_sql.="CRA_RESOL_SUP=";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="CRA_ESTADO= ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="CRA_JUST_COD=";
				$cadena_sql.="'".$variable[4]."', ";
				$cadena_sql.="CRA_JORNADA=";
				$cadena_sql.="'".$variable[5]."' ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.=" CRA_COD=";
				$cadena_sql.="'".$variable[0]."'";
				break;
		
		case "actualiza_tipo":
				$cadena_sql="UPDATE ";
				$cadena_sql.="MNTAC.ACTIPCRA "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="TRA_CICLO=";
				$cadena_sql.="'".$variable[1]."', ";
				$cadena_sql.="TRA_DURACION=";
				$cadena_sql.="'".$variable[2]."' ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.=" TRA_COD=";
				$cadena_sql.="'".$variable[0]."'";
				break;		

		case "nucleo":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT (*) NBC ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.NBCCRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="NBC_COD_CRA= ";
				$cadena_sql.="'".$variable[0]."'";
				$cadena_sql.=" AND ";
				$cadena_sql.="NBC_NIVEL= ";
				$cadena_sql.="'".$variable[1]."'";
				//echo $cadena_sql;
				break;			
				
		case "inserta_nucleo":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="MNTAC.NBCCRA  ";
				$cadena_sql.="(";
				$cadena_sql.="NBC_COD, ";
				$cadena_sql.="NBC_COD_CRA, ";
				$cadena_sql.="NBC_COD_ESP, ";
				$cadena_sql.="NBC_NIVEL ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.="'".$variable[0]."', ";
				$cadena_sql.="'".$variable[1]."', ";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="'".$variable[3]."' ";
				$cadena_sql.=")";
				break;	
				
		case "actualiza_nucleo":
				$cadena_sql="UPDATE ";
				$cadena_sql.="MNTAC.NBCCRA  "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="NBC_COD= ";
				$cadena_sql.= $variable[0].", ";
				$cadena_sql.="NBC_COD_ESP= ";
				$cadena_sql.= $variable[2]." ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.=" NBC_COD_CRA=";
				$cadena_sql.="'".$variable[1]."'";
				$cadena_sql.=" AND ";
				$cadena_sql.="NBC_NIVEL=";
				$cadena_sql.="'".$variable[3]."'";
				break;	
			
		case "acred":
				//ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT (*) ACR ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.ACRECRA ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACRE_COD_CRA= ";
				$cadena_sql.="'".$variable[1]."'";
				//echo $cadena_sql;
				break;			
				
		case "inserta_acred":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="MNTAC.ACRECRA ";
				$cadena_sql.="(";
				$cadena_sql.="ACRECRA_COD, ";
				$cadena_sql.="ACRE_COD_CRA, ";
				$cadena_sql.="ACRE_COD_TIPO, ";
				$cadena_sql.="ACRE_FECHA, ";
				$cadena_sql.="ACRE_RESOLUCION, ";
				$cadena_sql.="ACRE_DURACION, ";
				$cadena_sql.="ACRE_ENTIDAD ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES (";
				$cadena_sql.="'".$variable[0]."', ";
				$cadena_sql.="'".$variable[1]."', ";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="'', ";
				$cadena_sql.="'', ";
				$cadena_sql.="'', ";
				$cadena_sql.="'' ";
				$cadena_sql.=")";
				break;	
				
		case "actualiza_acred":
				$cadena_sql="UPDATE ";
				$cadena_sql.="MNTAC.ACRECRA "; 
				$cadena_sql.="SET "; 
				$cadena_sql.="ACRE_COD_TIPO=";
				$cadena_sql.="'".$variable[2]."', ";
				$cadena_sql.="ACRE_FECHA=";
				$cadena_sql.="".$variable[3].", ";
				$cadena_sql.="ACRE_RESOLUCION=";
				$cadena_sql.=" '".$variable[4]."', ";
				$cadena_sql.="ACRE_DURACION=";
				$cadena_sql.="'".$variable[5]."', ";
				$cadena_sql.="ACRE_ENTIDAD=";
				$cadena_sql.="'".$variable[6]."' ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="ACRECRA_COD=";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.=" AND ";
				$cadena_sql.="ACRE_COD_CRA=";
				$cadena_sql.="'".$variable[1]."'";
				break;
					
			case "listar_acred":
				$cadena_sql="SELECT ";
				$cadena_sql.="TACRE.ACRE_NOMBRE, ";
				$cadena_sql.="ACRE.ACRE_RESOLUCION, ";
				$cadena_sql.="ACRE.ACRE_FECHA, ";
				$cadena_sql.="ACRE.ACRE_DURACION ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.ACRECRA ACRE ";
				$cadena_sql.="INNER JOIN MNTAC.ACRETIPO TACRE ";
				$cadena_sql.="ON ACRE.ACRE_COD_TIPO=TACRE.ACRE_COD_TIPO ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACRE.ACRE_COD_CRA=";
				$cadena_sql.="'".$variable[0]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="TACRE.ACRE_NIVEL= ";
				$cadena_sql.="'".$variable[1]."' ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="ACRE.ACRE_FECHA DESC ";
				break;		
		
			case "buscar_max":
				$cadena_sql="SELECT ";
				$cadena_sql.="(ACRECRA_COD) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="MNTAC.ACRECRA ";
				break;	
				
			case "verificaCalendario":
				$cadena_sql="SELECT ";
				$cadena_sql.="fua_fecha_recibo(".$variable.")";
				$cadena_sql.="FROM ";
				$cadena_sql.="DUAL ";
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
