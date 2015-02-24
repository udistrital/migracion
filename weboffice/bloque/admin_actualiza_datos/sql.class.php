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

class sql_registroActualizaDatos extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$valor="")
	{
		$valor=$conexion->verificar_variables($valor);
		//echo "<br>OPCION=".$opcion;	
	
		switch($opcion)
		{
			case "registroCompleto":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_COD, ";  //0
				$cadena_sql.="TO_CHAR(EOT_FECHA_NAC,'DD/MM/YYYY'), ";  //1
				$cadena_sql.="EOT_COD_LUG_NAC, ";  //2
				$cadena_sql.="EOT_SEXO, ";  //3
				$cadena_sql.="EOT_ESTADO_CIVIL, ";  //4
				$cadena_sql.="EOT_LUG_COD_PROVIENE, ";  //5
				$cadena_sql.="EOT_VIVE_CON, ";  //6
				$cadena_sql.="EOT_TIPO_VIVIENDA, ";  //7
				$cadena_sql.="EOT_NRO_SNP, ";  //8
				$cadena_sql.="EOT_PUNTOS_SNP, ";  //9
				$cadena_sql.="EOT_TIPO_BTO, ";  //10
				$cadena_sql.="EOT_TIPO_COLEGIO, ";  //11
				$cadena_sql.="EOT_TRABAJA, ";  //12
				$cadena_sql.="EOT_VIVE_PADRE, ";  //13
				$cadena_sql.="EOT_ED_PADRE, ";  //14
				$cadena_sql.="EOT_TRABAJA_PADRE, ";  //15
				$cadena_sql.="EOT_VIVE_MADRE, ";  //16
				$cadena_sql.="EOT_ED_MADRE, ";  //17
				$cadena_sql.="EOT_TRABAJA_MADRED, ";  //18
				$cadena_sql.="EOT_VIVE_CONYUGE, ";  //19
				$cadena_sql.="EOT_ED_CONYUGE, ";  //20
				$cadena_sql.="EOT_TRABAJA_CONYUGE, ";  //21
				$cadena_sql.="EOT_ASP_CRED, ";  //22
				$cadena_sql.="EOT_FORMA_INGRESO, ";  //23
				$cadena_sql.="EOT_JORNADA_COLEGIO, ";  //24
				$cadena_sql.="EOT_VALOR_MATRICULA_COLEGIO, ";  //25
				$cadena_sql.="EOT_COSTEA_ESTUDIOS, ";  //26
				$cadena_sql.="EOT_INGRESOS_COSTEA, ";  //27
				$cadena_sql.="EOT_GRUPO, ";  //28
				$cadena_sql.="EOT_SEXO_COLEGIO, ";  //29
				$cadena_sql.="EOT_ESTRATO_SOCIAL, ";  //30
				$cadena_sql.="EOT_APE_ANO, ";  //31
				$cadena_sql.="EOT_APE_PER, ";  //32
				$cadena_sql.="EOT_ESTADO, ";  //33
				$cadena_sql.="EOT_ARURAL, ";  //34
				$cadena_sql.="EOT_EMAIL, ";  //35
				$cadena_sql.="EOT_TIPOSANGRE, ";  //36
				$cadena_sql.="EOT_RH, ";  //37
				$cadena_sql.="EOT_EMAIL_INS, ";  //38
				$cadena_sql.="EOT_GRUPO_VULNERA, ";  //39
				$cadena_sql.="EOT_GRUPO_ETNICO, ";  //40
				$cadena_sql.="EOT_REGUARDO, ";	//41
				$cadena_sql.="EOT_VICTIMA, ";	//42
				$cadena_sql.="EOT_LUGAR_EXPUL, ";	//43
				$cadena_sql.="EOT_PROV_SEC_PRIV, ";	//44
				$cadena_sql.="EOT_ESPECIALES, ";	//45
				$cadena_sql.="EOT_TIPO_DISCAP, ";	//46
				$cadena_sql.="EOT_CAPAC_CODE, ";	//47
				$cadena_sql.="EOT_CATEG_SISBEN, ";	//48
				$cadena_sql.="EOT_RAZON_PRESENTA, ";	//49
				$cadena_sql.="EOT_COD_CRA_DESEA, ";	//50
				$cadena_sql.="EOT_METOD_BTO, ";	//51
				$cadena_sql.="EOT_IDIO_BTO, ";	//52
				$cadena_sql.="EOT_VALIDA_BTO, ";	//53	
				$cadena_sql.="EOT_NUM_GRUP_FAM, ";	//54
				$cadena_sql.="EOT_NUM_PER_APOR, ";	//55
				$cadena_sql.="EOT_ING_FAM, ";	//56
				$cadena_sql.="EOT_DEU_VIV, ";	//57
				$cadena_sql.="EOT_OCUP_PADRE, ";	//58
				$cadena_sql.="EOT_NUM_HERMANOS, ";	//59
				$cadena_sql.="EOT_POS_HERMANOS, ";	//60
				$cadena_sql.="EOT_NUM_HERMANOS_EST_SUP, ";	//61
				$cadena_sql.="EOT_COD_MUN_NAC, ";		//62
				$cadena_sql.="EOT_COD_DEP_NAC, ";		//63
				$cadena_sql.="EOT_COD_COLEGIO, ";		//64
				$cadena_sql.="EOT_RAZON_INS_PRESENTA, ";	//65
				$cadena_sql.="EOT_OCUP_MADRE, ";	//66
				$cadena_sql.="EST_NOMBRE, ";	//67
				$cadena_sql.="EST_NRO_IDEN, ";	//68
				$cadena_sql.="EST_TIPO_IDEN, ";	//69
				$cadena_sql.="EST_DIRECCION, ";	//70
				$cadena_sql.="EST_TELEFONO, ";	//71
				$cadena_sql.="EST_ZONA_POSTAL, ";	//72
				$cadena_sql.="CRA_NOMBRE, ";	//73
				$cadena_sql.="EOT_COD_MUN_PROV, ";	//74
				$cadena_sql.="EOT_COD_INS_DESEA, ";	//75
				$cadena_sql.="EOT_COD_MUN_EXPUL, "; //76
				$cadena_sql.="EOT_TEL_CEL, "; //77
				$cadena_sql.="EOT_COD_MUN_EXP, "; //78				
				$cadena_sql.="EOT_COD_MUN_RES, "; //79				
				$cadena_sql.="EOT_BAR_COD_RES, "; //80
				$cadena_sql.="EOT_MODO_TRANSPORTE, "; //81
				$cadena_sql.="TO_CHAR(EOT_FECHA_GRADO_SECUNDARIA,'DD/MM/YYYY') ";  //82
 				$cadena_sql.="FROM acestotr,acest,accra ";
				$cadena_sql.="WHERE est_cod= eot_cod ";
				$cadena_sql.="AND cra_cod=est_cra_cod ";
				$cadena_sql.="AND est_cod=".$valor;                                //echo $cadena_sql; exit;
			break;
			case "Pension":
				$cadena_sql="SELECT * ";
 				$cadena_sql.="FROM acestadm ";
				$cadena_sql.="WHERE ead_cod=".$valor;
			break;
			case "Colegio":
				$cadena_sql="SELECT ";
				$cadena_sql.="NOMBREOFICIAL, ";
				$cadena_sql.="JORNADA, ";
				$cadena_sql.="CALENDARIO, ";
				$cadena_sql.="CARACTER, ";
				$cadena_sql.="EOT_COD_COLEGIO ";
 				$cadena_sql.="FROM gecolegio,acestotr ";
				$cadena_sql.="WHERE codigocolegio=eot_cod_colegio ";
				$cadena_sql.="AND eot_cod=".$valor;
			break;
			case "guardarLugarNacimiento":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_COD_MUN_NAC=".$valor[1]." ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];	
			break;
			case "guardarLugarExpulsion":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_COD_MUN_EXPUL=".$valor[1]." ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];	
			break;
			case "guardarLugarProcedencia":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_COD_MUN_PROV=".$valor[1]." ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];	
			break;	
			case "guardarDatos1":
				$cadena_sql="UPDATE acestotr SET ";
				//$cadena_sql.="EOT_TIPO_BTO='".$valor[6]."',";
				//$cadena_sql.="EOT_TIPO_COLEGIO='".$valor[7]."',";
				$cadena_sql.="EOT_VIVE_PADRE='".$valor[9]."',";
				//$cadena_sql.="EOT_VIVE_MADRE='".$valor[12]."',";
				//$cadena_sql.="EOT_FORMA_INGRESO='".$valor[18]."',";
				//$cadena_sql.="EOT_GRUPO=".$valor[22].",";
				//$cadena_sql.="EOT_SEXO_COLEGIO='".$valor[23]."',";
				//$cadena_sql.="EOT_ESTADO='".$valor[25]."',";
				$cadena_sql.="EOT_GRUPO_VULNERA='".$valor[31]."',";
				//$cadena_sql.="EOT_PROV_SEC_PRIV='".$valor[35]."',";
				//$cadena_sql.="EOT_ESPECIALES='".$valor[36]."',";
				//$cadena_sql.="EOT_DEU_VIV='".$valor[48]."',";
				$cadena_sql.="EOT_COD_COLEGIO=TO_NUMBER('".$valor[57]."') ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];				

			break;		
			case "actDatosBasicosEST":
				$cadena_sql="UPDATE acest SET ";
				$cadena_sql.="EST_DIRECCION='".$valor['direccion']."',";
				$cadena_sql.="EST_TELEFONO='".$valor['telefono']."',";
				$cadena_sql.="EST_ZONA_POSTAL='".$valor['zonapostal']."' ";
				$cadena_sql.="WHERE EST_COD=".$valor['registro'];																									
			break;	
			case "actDatosBasicosEOT":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_EMAIL='".$valor['email']."',";
				$cadena_sql.="EOT_ESTADO_CIVIL=TO_NUMBER('".$valor['estadocivil']."'),";
				$cadena_sql.="EOT_TIPOSANGRE='".$valor['tiposangre']."',";
				$cadena_sql.="EOT_RH='".$valor['rh']."',";
				$cadena_sql.="EOT_FECHA_NAC=TO_DATE('".$valor['fechanacimiento']."','DD/MM/YYYY'),";
				$cadena_sql.="EOT_FECHA_GRADO_SECUNDARIA=TO_DATE('".$valor['gradoColegio']."','DD/MM/YYYY'),";
				$cadena_sql.="EOT_SEXO='".$valor['sexo']."', ";
				$cadena_sql.="EOT_COD_MUN_PROV='".$valor['munprocedencia']."', ";
				$cadena_sql.="EOT_BAR_COD_RES='".$valor['barviv']."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor['registro']; //echo $cadena_sql;exit;																									
			break;	
			case "actInfFamiliar":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_TRABAJA='".$valor['trabaja']."',";
				$cadena_sql.="EOT_TRABAJA_PADRE='".$valor['trabajapadre']."',";
				$cadena_sql.="EOT_TRABAJA_MADRED='".$valor['trabajamadre']."',";
				$cadena_sql.="EOT_VIVE_CONYUGE='".$valor['viveconyugue']."',";
				$cadena_sql.="EOT_TRABAJA_CONYUGE='".$valor['trabajaconyugue']."',";
				$cadena_sql.="EOT_VIVE_CON=TO_NUMBER('".$valor['vivecon']."'),";
				$cadena_sql.="EOT_ED_CONYUGE='".$valor['niveleduconyugue']."',";
				$cadena_sql.="EOT_NUM_GRUP_FAM='".$valor['numgrupfam']."',";
				$cadena_sql.="EOT_ED_PADRE='".$valor['niveledupadre']."',";
				$cadena_sql.="EOT_ED_MADRE='".$valor['niveledumadre']."',";
				$cadena_sql.="EOT_OCUP_PADRE='".$valor['ocupadre']."',";
				$cadena_sql.="EOT_OCUP_MADRE='".$valor['ocumadre']."',";
				$cadena_sql.="EOT_NUM_HERMANOS='".$valor['numhermanos']."',";
				$cadena_sql.="EOT_POS_HERMANOS='".$valor['poshermanos']."',";
				$cadena_sql.="EOT_NUM_HERMANOS_EST_SUP='".$valor['numedusuphermanos']."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor['registro'];																									
			break;	
			case "actInfAcademica":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_METOD_BTO='".$valor['metbachillerato']."',";
				$cadena_sql.="EOT_IDIO_BTO='".$valor['idiomabachillerato']."',";
				$cadena_sql.="EOT_VALIDA_BTO='".$valor['validobachill']."',";
				$cadena_sql.="EOT_COD_CRA_DESEA=TO_NUMBER('".$valor['cradeseada']."'),";
				$cadena_sql.="EOT_RAZON_PRESENTA='".$valor['razoncarrera']."',";
				$cadena_sql.="EOT_COD_INS_DESEA='".$valor['insdeseada']."',";
				$cadena_sql.="EOT_RAZON_INS_PRESENTA='".$valor['razoninstitucion']."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor['registro'];																									
			break;
			case "actInfSocio":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_COSTEA_ESTUDIOS=TO_NUMBER('".$valor['costeaestudio']."'),";
				$cadena_sql.="EOT_INGRESOS_COSTEA=TO_NUMBER('".$valor['ingcosteaestudios']."'),";
				$cadena_sql.="EOT_CATEG_SISBEN='".$valor['sisben']."',";
				$cadena_sql.="EOT_ESTRATO_SOCIAL=TO_NUMBER('".$valor['estrato']."'),";
				$cadena_sql.="EOT_VALOR_MATRICULA_COLEGIO=TO_NUMBER('".$valor['matriculacolegio']."'),";
				$cadena_sql.="EOT_NUM_PER_APOR='".$valor['numeroaportantes']."',";
				$cadena_sql.="EOT_ING_FAM='".$valor['ingresosfamiliares']."',";
				$cadena_sql.="EOT_TIPO_VIVIENDA=TO_NUMBER('".$valor['viviendapropia']."') ";
				$cadena_sql.="WHERE EOT_COD=".$valor['registro'];																									
			break;	
			case "actInfAdicional":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.="EOT_GRUPO_ETNICO='".$valor['etnia']."',";
				$cadena_sql.="EOT_REGUARDO='".$valor['resguardo']."',";
				$cadena_sql.="EOT_VICTIMA='".$valor['victima']."',";
				$cadena_sql.="EOT_ARURAL='".$valor['provienearearural']."',";
				$cadena_sql.="EOT_CAPAC_CODE='".$valor['capacidad']."',";
				$cadena_sql.="EOT_TIPO_DISCAP='".$valor['discapacidad']."', ";
				$cadena_sql.="EOT_MODO_TRANSPORTE='".$valor['modotransporte']."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor['registro'];																									
			break;	
			case "rescatarDepartamentos":
				$cadena_sql="SELECT ";
				$cadena_sql.="dep_cod,";
				$cadena_sql.="dep_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedepartamento ";	
				$cadena_sql.="WHERE dep_estado='A' ";				
				$cadena_sql.="AND dep_cod<>0";
			break;
			case "rescatarLocalidades":
				$cadena_sql="SELECT ";
				$cadena_sql.="unique to_number(loc_nro),";
				$cadena_sql.="loc_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aclocalidad ";	
				$cadena_sql.="WHERE loc_estado='A' ";				
				$cadena_sql.="ORDER BY 1";
			break;			
			case "rescatarMunicipios":
				$cadena_sql="SELECT ";
				$cadena_sql.="mun_cod,";
				$cadena_sql.="mun_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gemunicipio ";	
				$cadena_sql.="WHERE mun_estado='A' ";
				$cadena_sql.="AND mun_dep_cod=".$valor;					
				$cadena_sql.="AND mun_cod<>0";
			break;
			case "rescatarMunicipio":
				$cadena_sql="SELECT ";
				$cadena_sql.="mun_cod,";
				$cadena_sql.="mun_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gemunicipio ";	
				$cadena_sql.="WHERE mun_estado='A' ";
				$cadena_sql.="AND mun_cod=".$valor;					
				$cadena_sql.="AND mun_cod<>0";
			break;
			case "rescatarBarrios":
				$cadena_sql="SELECT ";
				$cadena_sql.="bar_cod,";
				$cadena_sql.="bar_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gebarrio ";	
				$cadena_sql.="WHERE bar_estado='A' ";
				$cadena_sql.="AND bar_loc_cod=".$valor;					
			break;
			case "rescatarBarrio":
				$cadena_sql="SELECT ";
				$cadena_sql.="bar_cod,";
				$cadena_sql.="bar_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gebarrio ";	
				$cadena_sql.="WHERE bar_estado='A' ";
				$cadena_sql.="AND bar_cod=".$valor;					
			break;
			case "rescatarEtnias":
				$cadena_sql="SELECT ";
				$cadena_sql.="etnia_code, ";
				$cadena_sql.="etnia_descr ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntge.geetnia ";
				$cadena_sql.="ORDER BY etnia_descr ASC ";																									
			break;	
			case "rescatarResguardos":
				$cadena_sql="SELECT ";
				$cadena_sql.="resguardo_code, ";
				$cadena_sql.="resguardo_desc ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntge.geresguardo ";
				$cadena_sql.="ORDER BY resguardo_desc ASC ";
			break;
			case "rescatarDatosBasicos":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_FECHA_NAC, ";  
				$cadena_sql.="EOT_SEXO, "; 
				$cadena_sql.="EOT_ESTADO_CIVIL, "; 
				$cadena_sql.="EOT_EMAIL, ";  
				$cadena_sql.="EOT_TIPOSANGRE, ";  
				$cadena_sql.="EOT_RH, ";  
				$cadena_sql.="EST_DIRECCION, ";	
				$cadena_sql.="EST_TELEFONO, ";	
				$cadena_sql.="EOT_COD_MUN_PROV, ";
				$cadena_sql.="EOT_BAR_COD_RES "; 
				$cadena_sql.="FROM acestotr,acest ";
				$cadena_sql.="WHERE est_cod= eot_cod ";
				$cadena_sql.="AND est_cod=".$valor;
			break;
			case "rescatarInfFamiliar":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_TRABAJA,";
				$cadena_sql.="EOT_TRABAJA_PADRE,";
				$cadena_sql.="EOT_TRABAJA_MADRED,";
				$cadena_sql.="EOT_VIVE_CON,";
				$cadena_sql.="EOT_NUM_GRUP_FAM,";
				$cadena_sql.="EOT_ED_PADRE,";
				$cadena_sql.="EOT_ED_MADRE,";
				$cadena_sql.="EOT_OCUP_PADRE,";
				$cadena_sql.="EOT_OCUP_MADRE,";
				$cadena_sql.="EOT_NUM_HERMANOS,";
				$cadena_sql.="EOT_POS_HERMANOS ";
 				$cadena_sql.="FROM acestotr ";
				$cadena_sql.="WHERE eot_cod=".$valor;
			break;
			case "rescatarInfAcademica":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_METOD_BTO,";
				$cadena_sql.="EOT_IDIO_BTO,";
				$cadena_sql.="EOT_VALIDA_BTO,";
				$cadena_sql.="EOT_COD_CRA_DESEA,";
				$cadena_sql.="EOT_RAZON_PRESENTA,";
				$cadena_sql.="EOT_COD_INS_DESEA,";
				$cadena_sql.="EOT_RAZON_INS_PRESENTA ";
 				$cadena_sql.="FROM acestotr ";
				$cadena_sql.="WHERE eot_cod=".$valor;
			break;
			case "rescatarInfSocioeco":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_COSTEA_ESTUDIOS,";
				$cadena_sql.="EOT_INGRESOS_COSTEA,";
				$cadena_sql.="EOT_CATEG_SISBEN,";
				$cadena_sql.="EOT_ESTRATO_SOCIAL,";
				$cadena_sql.="EOT_VALOR_MATRICULA_COLEGIO,";
				$cadena_sql.="EOT_NUM_PER_APOR,";
				$cadena_sql.="EOT_ING_FAM,";
				$cadena_sql.="EOT_TIPO_VIVIENDA ";
 				$cadena_sql.="FROM acestotr ";
				$cadena_sql.="WHERE eot_cod=".$valor;
			break;
			case "rescatarInfAdicional":
				$cadena_sql="SELECT "; 
				$cadena_sql.="EOT_GRUPO_ETNICO,";
				$cadena_sql.="EOT_REGUARDO,";
				$cadena_sql.="EOT_VICTIMA,";
				$cadena_sql.="EOT_ARURAL,";
				$cadena_sql.="EOT_CAPAC_CODE,";
				$cadena_sql.="EOT_TIPO_DISCAP, ";
				$cadena_sql.="EOT_MODO_TRANSPORTE "; 
 				$cadena_sql.="FROM acestotr ";
				$cadena_sql.="WHERE eot_cod=".$valor;
			break;
			case "rescatarCapacidades":
				$cadena_sql="SELECT ";
				$cadena_sql.="cap_code, ";
				$cadena_sql.="cap_descr ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntge.gecapacidad ";
				$cadena_sql.="WHERE cap_code<>0";
				$cadena_sql.="ORDER BY cap_descr ASC ";
			break;	
			case "rescatarParientes":
				$cadena_sql="SELECT ";
				$cadena_sql.="tpa_codigo,";
				$cadena_sql.="tpa_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="mntge.getiparen ";
				$cadena_sql.="ORDER BY tpa_nombre ASC ";
			break;			
			case "actBandera":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.=$valor[1]."='".$valor[2]."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];	
			break;
                    	case "actBandera":
				$cadena_sql="UPDATE acestotr SET ";
				$cadena_sql.=$valor[1]."='".$valor[2]."' ";
				$cadena_sql.="WHERE EOT_COD=".$valor[0];	
			break;
			default:
				$cadena_sql="";
			break;
		}
	//	echo "<br><br>".$cadena_sql;
		return $cadena_sql;
	}
	
	
}
?>
