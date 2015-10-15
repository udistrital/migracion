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

class sql_registro_PlanTrabajo extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "anioper":
				$cadena_sql="SELECT ";
				$cadena_sql.="ape_ano, ape_per ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperi ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_estado='".$variable[10]."'";	
				break;
			
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_CHAR(CURRENT_DATE,'YYYYMMDD') ";
				break;
				
			case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="doc_apellido, ";
				$cadena_sql.="doc_nombre, ";
				$cadena_sql.="doc_nro_iden ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocente ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="doc_nro_iden=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="doc_estado = 'A' ";
				break;
				
			case "dia":
				$cadena_sql="SELECT ";
				$cadena_sql.="dia_cod, ";
				$cadena_sql.="dia_nombre, ";
				$cadena_sql.="dia_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gedia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dia_estado='A'";
				break;
			
			case "hora":
				$cadena_sql="SELECT ";
				$cadena_sql.="hor_cod, ";
				$cadena_sql.="hor_larga, ";
				$cadena_sql.="hor_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="gehora ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="hor_estado='A' ";
				$cadena_sql.="ORDER BY hor_cod ASC";
				break;
				
			case "cargalectiva":
				$cadena_sql="SELECT distinct ";
				$cadena_sql.="cur_ape_ano, ";
				$cadena_sql.="cur_ape_per, ";
				$cadena_sql.="car_doc_nro, ";
				$cadena_sql.="dac_nombre, ";
				$cadena_sql.="substr(asi_nombre,1,30), ";
				$cadena_sql.="asi_nombre, ";
				$cadena_sql.="dia_nombre, ";
				$cadena_sql.="hor_larga, ";
				$cadena_sql.="sed_nombre||' - '||edi_nombre , ";
				$cadena_sql.="sed_id||' - '||edi_nombre, ";
				$cadena_sql.="sal_id_espacio||' - '||sal_nombre , ";
				$cadena_sql.="tvi_nombre, ";
				$cadena_sql.="(CASE WHEN tvi_cod=1 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=6 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=0 THEN 'SD' ";
				$cadena_sql.=" ELSE 'VE' END), ";
				$cadena_sql.=" hor_dia_nro, ";
				$cadena_sql.=" hor_hora ";
                                $cadena_sql.=" FROM accargas";
                                $cadena_sql.=" INNER JOIN achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                                $cadena_sql.=" INNER JOIN gedia on hor_dia_nro=dia_cod";
                                $cadena_sql.=" INNER JOIN gehora ON hor_hora=hor_cod";
                                $cadena_sql.=" INNER JOIN gesalones on hor_sal_id_espacio=sal_id_espacio";
                                $cadena_sql.=" INNER JOIN gesede ON sal_sed_id=sed_id";
                                $cadena_sql.=" INNER JOIN geedificio ON sal_edificio=edi_cod";
                                $cadena_sql.=" INNER JOIN acdocactividad ON dac_cod = 1";
                                $cadena_sql.=" INNER JOIN acasi on asi_cod=cur_asi_cod";
                                $cadena_sql.=" INNER JOIN actipvin ON car_tip_vin=tvi_cod";
				$cadena_sql.=" WHERE car_doc_nro = ".$variable[0]." ";
				$cadena_sql.=" AND cur_ape_ano = ".$variable[1]." ";
				$cadena_sql.=" AND cur_ape_per = ".$variable[2]." ";
				$cadena_sql.=" AND car_estado = 'A'";
				$cadena_sql.=" AND achorarios.hor_estado = 'A'";
				$cadena_sql.=" AND accursos.cur_estado = 'A'";
				$cadena_sql.=" ORDER BY hor_dia_nro, hor_hora ASC"; 
				break;
                            
			case "cargalectivaAnterior":
				$cadena_sql="SELECT distinct ANO,PER,DOC,ACTIVIDAD,ASI_NOMBRE_ABREV,ASI_NOMBRE,DIA,HORA,SEDE,EDIFICIO,SALON,VINC,TIP_VIN,DIA_COD,HORA_COD";
				$cadena_sql.=" FROM ( ";
                                //2008-1 a 2011-3 y 2012-1";
                                $cadena_sql.=" SELECT distinct";
                                $cadena_sql.=" car_ape_ano ANO,";
                                $cadena_sql.=" car_ape_per PER,";
                                $cadena_sql.=" car_doc_nro_iden DOC,";
                                $cadena_sql.=" dac_nombre ACTIVIDAD,";
                                $cadena_sql.=" substr(asi_nombre,1,30) ASI_NOMBRE_ABREV, ";
                                $cadena_sql.=" asi_nombre ASI_NOMBRE,";
                                $cadena_sql.=" dia_nombre DIA,";
                                $cadena_sql.=" hor_larga HORA,";
                                $cadena_sql.=" sed_abrev SEDE,";
                                $cadena_sql.=" sed_nombre EDIFICIO,";
                                $cadena_sql.=" cast(sal_cod as text) SALON,";
                                $cadena_sql.=" tvi_nombre VINC,";
                                $cadena_sql.=" case when tvi_cod in ('1','6') then 'PL'";
                                $cadena_sql.=" when tvi_cod=0 then 'SD'";
                                $cadena_sql.=" else 'VE' end TIP_VIN,";
                                $cadena_sql.=" hor_hora HORA_COD,";
                                $cadena_sql.=" hor_dia_nro DIA_COD";
                                $cadena_sql.=" from achorariohis";
                                $cadena_sql.=" inner join accursohis on cur_ape_ano=hor_ape_ano and cur_ape_per=hor_ape_per and cur_asi_cod=hor_asi_cod and cur_nro=hor_nro";
                                $cadena_sql.=" INNER JOIN ACASI ON ASI_COD=CUR_ASI_COD";
                                $cadena_sql.=" inner join accargahis on car_ape_ano=cur_ape_ano and car_ape_per=cur_ape_per and car_cra_cod=cur_cra_cod and car_cur_asi_cod=cur_asi_cod and car_cur_nro=cur_nro";
                                $cadena_sql.=" inner join gehora on hor_cod=hor_hora";
                                $cadena_sql.=" inner join gedia on dia_cod=hor_dia_nro";
                                $cadena_sql.=" inner join acdoctipvin on CAR_APE_ANO=DTV_APE_ANO and CAR_APE_PER=DTV_APE_PER AND CAR_CRA_COD=DTV_CRA_COD AND CAR_DOC_NRO_IDEN=DTV_DOC_NRO_IDEN";
                                $cadena_sql.=" inner join acdocactividad ON dac_cod = 1";
                                $cadena_sql.=" inner join actipvin on tvi_cod=DTV_TVI_COD";
                                $cadena_sql.=" left outer join gesalon on sal_cod=hor_sal_cod and sal_sed_cod=hor_sed_cod";
                                $cadena_sql.=" left outer join gesede on sed_cod=hor_sed_cod";
                                $cadena_sql.=" where car_estado = 'A'";
                                $cadena_sql.=" and (hor_ape_ano between 2008 and 2011";
                                $cadena_sql.=" OR concat(HOR_APE_ANO,HOR_APE_PER) =cast (20121 as text))";
                                $cadena_sql.=" union";
                                //2012-3 y 2013-1";
                                $cadena_sql.=" SELECT distinct";
                                $cadena_sql.=" car_ape_ano ANO,";
                                $cadena_sql.=" car_ape_per PER,";
                                $cadena_sql.=" car_doc_nro_iden DOC,";
                                $cadena_sql.=" dac_nombre ACTIVIDAD,";
                                $cadena_sql.=" substr(asi_nombre,1,30) ASI_NOMBRE_ABREV, ";
                                $cadena_sql.=" asi_nombre ASI_NOMBRE,";
                                $cadena_sql.=" dia_nombre DIA,";
                                $cadena_sql.=" hor_larga HORA,";
                                $cadena_sql.=" sed_nombre||' - '||edi_nombre SEDE,";
                                $cadena_sql.=" sed_id||' - '||edi_nombre EDIFICIO,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre SALON,";
                                $cadena_sql.=" tvi_nombre VINC,";
                                $cadena_sql.=" case when tvi_cod in ('1','6') then 'PL'";
                                $cadena_sql.=" when tvi_cod=0 then 'SD'";
                                $cadena_sql.=" else 'VE' end TIP_VIN,";
                                $cadena_sql.=" hor_hora HORA_COD,";
                                $cadena_sql.=" hor_dia_nro DIA_COD";
                                $cadena_sql.=" from achorario_2012";
                                $cadena_sql.=" inner join accursohis on cur_ape_ano=hor_ape_ano and cur_ape_per=hor_ape_per and cur_asi_cod=hor_asi_cod and cur_nro=hor_nro";
                                $cadena_sql.=" inner join acasi on asi_cod=cur_asi_cod";
                                $cadena_sql.=" inner join accargahis on car_ape_ano=cur_ape_ano and car_ape_per=cur_ape_per and car_cra_cod=cur_cra_cod and car_cur_asi_cod=cur_asi_cod and car_cur_nro=cur_nro";
                                $cadena_sql.=" inner join gehora on hor_cod=hor_hora";
                                $cadena_sql.=" inner join gedia on dia_cod=hor_dia_nro";
                                $cadena_sql.=" inner join acdoctipvin on CAR_APE_ANO=DTV_APE_ANO and CAR_APE_PER=DTV_APE_PER AND CAR_CRA_COD=DTV_CRA_COD AND CAR_DOC_NRO_IDEN=DTV_DOC_NRO_IDEN";
                                $cadena_sql.=" inner join acdocactividad ON dac_cod = 1";
                                $cadena_sql.=" inner join actipvin on tvi_cod=DTV_TVI_COD";
                                $cadena_sql.=" left outer join gesalon_2012 on HOR_SAL_ID_ESPACIO = SAL_ID_ESPACIO";
                                $cadena_sql.=" left outer join gesede on sed_cod=hor_sed_cod";
                                $cadena_sql.=" left outer join geedificio on SAL_EDIFICIO=EDI_COD";
                                $cadena_sql.=" where car_estado = 'A'";
                                $cadena_sql.=" and cast(concat (hor_ape_ano,hor_ape_per) as numeric) in (20123,20131)";
                                $cadena_sql.=" union";
                                //2013-3 y posterior";
                                $cadena_sql.=" SELECT distinct";
                                $cadena_sql.=" cur_ape_ano ANO,";
                                $cadena_sql.=" cur_ape_per PER,";
                                $cadena_sql.=" car_doc_nro DOC,";
                                $cadena_sql.=" dac_nombre ACTIVIDAD,";
                                $cadena_sql.=" substr(asi_nombre,1,30) ASI_NOMBRE_ABREV,";
                                $cadena_sql.=" asi_nombre ASI_NOMBRE,";
                                $cadena_sql.=" dia_nombre DIA,";
                                $cadena_sql.=" hor_larga HORA,";
                                $cadena_sql.=" sed_nombre||' - '||edi_nombre SEDE,";
                                $cadena_sql.=" sed_id||' - '||edi_nombre EDIFICIO,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre SALON,";
                                $cadena_sql.=" tvi_nombre VINC,";
                                $cadena_sql.=" (CASE WHEN tvi_cod=1 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=6 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=0 THEN 'SD'";
                                $cadena_sql.=" ELSE 'VE' END) TIP_VIN,";
                                $cadena_sql.=" hor_hora HORA_COD,";
                                $cadena_sql.=" hor_dia_nro DIA_COD";
                                $cadena_sql.=" FROM accargas_historia";
                                $cadena_sql.=" INNER JOIN achorarios_historia ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN accursos_historia ON cur_id=hor_id_curso";
                                $cadena_sql.=" INNER JOIN gedia on hor_dia_nro=dia_cod";
                                $cadena_sql.=" INNER JOIN gehora ON hor_hora=hor_cod";
                                $cadena_sql.=" INNER JOIN gesalones on hor_sal_id_espacio=sal_id_espacio";
                                $cadena_sql.=" INNER JOIN gesede ON sal_sed_id=sed_id";
                                $cadena_sql.=" INNER JOIN geedificio ON sal_edificio=edi_cod";
                                $cadena_sql.=" INNER JOIN acdocactividad ON dac_cod = 1";
                                $cadena_sql.=" INNER JOIN acasi on asi_cod=cur_asi_cod";
                                $cadena_sql.=" INNER JOIN actipvin ON car_tip_vin=tvi_cod";
                                $cadena_sql.=" WHERE car_estado = 'A'";
				$cadena_sql.=" AND achorarios_historia.hor_estado = 'A'";
				$cadena_sql.=" AND accursos_historia.cur_estado = 'A'";
                                $cadena_sql.=" and concat(CUR_APE_ANO,CUR_APE_PER) >cast(20131 as text)";
                                $cadena_sql.=" ) CARGA ";
				$cadena_sql.=" WHERE DOC = ".$variable[0]." ";
				$cadena_sql.=" AND ANO = ".$variable[1]." ";
				$cadena_sql.=" AND PER = ".$variable[2]." ";
				$cadena_sql.=" ORDER BY DIA_COD,HORA_COD ASC";
				break;
				

			case "cargaactividades":
				$cadena_sql="SELECT distinct ";
				$cadena_sql.="DPT_APE_ANO, ";
				$cadena_sql.="DPT_APE_PER, ";
				$cadena_sql.="DPT_DOC_NRO_IDEN, ";
				$cadena_sql.="DAC_NOMBRE, ";
				$cadena_sql.="substr(DAC_NOMBRE,1,30), ";
				$cadena_sql.="DIA_ABREV, ";
				$cadena_sql.="HOR_LARGA, ";
				$cadena_sql.="SED_ID||' - '||edi_nombre  , ";
				$cadena_sql.="sed_nombre||' - '||edi_nombre, ";
				$cadena_sql.="sal_id_espacio||' - '||sal_nombre , ";
				$cadena_sql.="sal_id_espacio||' - '||sal_nombre , ";
				$cadena_sql.="DPT_FECHA, ";
				$cadena_sql.="DPT_ESTADO, ";
				$cadena_sql.="DIA_COD, ";
				$cadena_sql.="HOR_COD, ";
				$cadena_sql.="DPT_DAC_COD, ";
				$cadena_sql.="DAC_INTENSIDAD, ";
				$cadena_sql.="tvi_nombre, ";
                                $cadena_sql.="(CASE WHEN tvi_cod=1 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=6 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=0 THEN 'SD' ";
				$cadena_sql.=" ELSE 'VE' END) ";
                                $cadena_sql.="FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalones,actipvin,geedificio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_COD = DPT_DAC_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DIA_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="HOR_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_COD = DPT_SED_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_ID_ESPACIO = DPT_SAL_COD ";
                                $cadena_sql.="AND ";
				$cadena_sql.="sal_edificio= edi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod=dpt_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dia_cod= dpt_dia_nro ";
  				$cadena_sql.="AND ";
  				$cadena_sql.="hor_cod= dpt_hora ";
				$cadena_sql.="ORDER BY 11,12 ";
				break;
                            
			case "cargaactividadesAnterior":
                                $cadena_sql=" SELECT ANO,PER,DOC,ACTIVIDAD,ACTTIVIDAD_ABREV,DIA,HORA,SEDE,EDIFICIO,SALON,COD_SALON,FECHA,ESTADO,DIA_COD,HORA_COD,COD_ACTIVIDAD,INTENSIDAD,VINC,TIP_VIN";
                                $cadena_sql.=" FROM (";
                                $cadena_sql.=" SELECT ";
                                $cadena_sql.=" DPT_APE_ANO ANO, ";
                                $cadena_sql.=" DPT_APE_PER PER, ";
                                $cadena_sql.=" DPT_DOC_NRO_IDEN DOC, ";
                                $cadena_sql.=" DAC_NOMBRE ACTIVIDAD, ";
                                $cadena_sql.=" substr(DAC_NOMBRE,1,30) ACTTIVIDAD_ABREV, ";
                                $cadena_sql.=" DIA_ABREV DIA, ";
                                $cadena_sql.=" HOR_LARGA HORA, ";
                                $cadena_sql.=" SED_ABREV SEDE, ";
                                $cadena_sql.=" cast(sed_nombre as text) EDIFICIO, ";
                                $cadena_sql.=" SAL_DESCRIP SALON, ";
                                $cadena_sql.=" cast(sal_cod as text) COD_SALON, ";
                                $cadena_sql.=" DPT_FECHA FECHA, ";
                                $cadena_sql.=" DPT_ESTADO ESTADO, ";
                                $cadena_sql.=" DIA_COD DIA_COD, ";
                                $cadena_sql.=" HOR_COD HORA_COD, ";
                                $cadena_sql.=" DPT_DAC_COD COD_ACTIVIDAD, ";
                                $cadena_sql.=" DAC_INTENSIDAD INTENSIDAD, ";
                                $cadena_sql.=" tvi_nombre VINC, ";
                                $cadena_sql.=" (CASE WHEN tvi_cod=1 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=6 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=0 THEN 'SD'";
                                $cadena_sql.=" ELSE 'VE' END) TIP_VIN";
                                $cadena_sql.=" FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalon,actipvin ";
                                $cadena_sql.=" WHERE DAC_COD = DPT_DAC_COD ";
                                $cadena_sql.=" AND DAC_ESTADO = 'A' ";
                                $cadena_sql.=" AND DIA_ESTADO = 'A' ";
                                $cadena_sql.=" AND HOR_ESTADO = 'A' ";
                                $cadena_sql.=" AND SED_COD = DPT_SED_COD ";
                                $cadena_sql.=" AND SED_ESTADO = 'A' ";
                                $cadena_sql.=" AND DPT_ESTADO = 'A' ";
                                $cadena_sql.=" AND SAL_SED_COD = DPT_SED_COD ";
                                $cadena_sql.=" AND cast(SAL_COD as text) = DPT_SAL_COD ";
                                $cadena_sql.=" AND SAL_ESTADO = 'A' ";
                                $cadena_sql.=" AND tvi_cod=dpt_tvi_cod ";
                                $cadena_sql.=" AND dia_cod= dpt_dia_nro ";
                                $cadena_sql.=" AND hor_cod= dpt_hora";
                                $cadena_sql.=" UNION";
                                $cadena_sql.=" SELECT ";
                                $cadena_sql.=" DPT_APE_ANO ANO, ";
                                $cadena_sql.=" DPT_APE_PER PER, ";
                                $cadena_sql.=" DPT_DOC_NRO_IDEN DOC, ";
                                $cadena_sql.=" DAC_NOMBRE ACTIVIDAD, ";
                                $cadena_sql.=" substr(DAC_NOMBRE,1,30) ACTTIVIDAD_ABREV, ";
                                $cadena_sql.=" DIA_ABREV DIA, ";
                                $cadena_sql.=" HOR_LARGA HORA, ";
                                $cadena_sql.=" SED_ID||' - '||edi_nombre SEDE,";
                                $cadena_sql.=" sed_nombre||' - '||edi_nombre EDIFICIO,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre SALON,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre COD_SALON,";
                                $cadena_sql.=" DPT_FECHA FECHA, ";
                                $cadena_sql.=" DPT_ESTADO ESTADO, ";
                                $cadena_sql.=" DIA_COD DIA_COD, ";
                                $cadena_sql.=" HOR_COD HORA_COD, ";
                                $cadena_sql.=" DPT_DAC_COD COD_ACTIVIDAD, ";
                                $cadena_sql.=" DAC_INTENSIDAD INTENSIDAD, ";
                                $cadena_sql.=" tvi_nombre VINC, ";
                                $cadena_sql.=" (CASE WHEN tvi_cod=1 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=6 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=0 THEN 'SD'";
                                $cadena_sql.=" ELSE 'VE' END) TIP_VIN";
                                $cadena_sql.=" FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalon_2012,actipvin,geedificio";
                                $cadena_sql.=" WHERE DAC_COD = DPT_DAC_COD ";
                                $cadena_sql.=" AND DAC_ESTADO = 'A' ";
                                $cadena_sql.=" AND DIA_ESTADO = 'A' ";
                                $cadena_sql.=" AND HOR_ESTADO = 'A' ";
                                $cadena_sql.=" AND SED_COD = DPT_SED_COD ";
                                $cadena_sql.=" AND SED_ESTADO = 'A' ";
                                $cadena_sql.=" AND DPT_ESTADO = 'A' ";
                                $cadena_sql.=" AND SAL_ID_ESPACIO = DPT_SAL_COD";
                                $cadena_sql.=" AND sal_edificio= edi_cod";
                                $cadena_sql.=" AND SAL_ESTADO = 'A' ";
                                $cadena_sql.=" AND tvi_cod=dpt_tvi_cod ";
                                $cadena_sql.=" AND dia_cod= dpt_dia_nro ";
                                $cadena_sql.=" AND hor_cod= dpt_hora";
                                $cadena_sql.=" UNION";
                                $cadena_sql.=" SELECT ";
                                $cadena_sql.=" DPT_APE_ANO ANO, ";
                                $cadena_sql.=" DPT_APE_PER PER, ";
                                $cadena_sql.=" DPT_DOC_NRO_IDEN DOC, ";
                                $cadena_sql.=" DAC_NOMBRE ACTIVIDAD, ";
                                $cadena_sql.=" substr(DAC_NOMBRE,1,30) ACTTIVIDAD_ABREV, ";
                                $cadena_sql.=" DIA_ABREV DIA, ";
                                $cadena_sql.=" HOR_LARGA HORA, ";
                                $cadena_sql.=" SED_ID||' - '||edi_nombre SEDE,";
                                $cadena_sql.=" sed_nombre||' - '||edi_nombre EDIFICIO,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre SALON,";
                                $cadena_sql.=" sal_id_espacio||' - '||sal_nombre COD_SALON,";
                                $cadena_sql.=" DPT_FECHA FECHA, ";
                                $cadena_sql.=" DPT_ESTADO ESTADO, ";
                                $cadena_sql.=" DIA_COD DIA_COD, ";
                                $cadena_sql.=" HOR_COD HORA_COD, ";
                                $cadena_sql.=" DPT_DAC_COD COD_ACTIVIDAD, ";
                                $cadena_sql.=" DAC_INTENSIDAD INTENSIDAD, ";
                                $cadena_sql.=" tvi_nombre VINC, ";
                                $cadena_sql.=" (CASE WHEN tvi_cod=1 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=6 THEN 'PL'";
                                $cadena_sql.=" WHEN tvi_cod=0 THEN 'SD'";
                                $cadena_sql.=" ELSE 'VE' END) TIP_VIN";
                                $cadena_sql.=" FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalones,actipvin,geedificio";
                                $cadena_sql.=" WHERE DAC_COD = DPT_DAC_COD ";
                                $cadena_sql.=" AND DAC_ESTADO = 'A' ";
                                $cadena_sql.=" AND DIA_ESTADO = 'A' ";
                                $cadena_sql.=" AND HOR_ESTADO = 'A' ";
                                $cadena_sql.=" AND SED_COD = DPT_SED_COD ";
                                $cadena_sql.=" AND SED_ESTADO = 'A' ";
                                $cadena_sql.=" AND DPT_ESTADO = 'A' ";
                                $cadena_sql.=" AND SAL_ID_ESPACIO = DPT_SAL_COD";
                                $cadena_sql.=" AND sal_edificio= edi_cod";
                                $cadena_sql.=" AND SAL_ESTADO = 'A' ";
                                $cadena_sql.=" AND tvi_cod=dpt_tvi_cod ";
                                $cadena_sql.=" AND dia_cod= dpt_dia_nro ";
                                $cadena_sql.=" AND hor_cod= dpt_hora";
                                $cadena_sql.=" ) PLAN";
				$cadena_sql.=" WHERE";
				$cadena_sql.=" ANO = ".$variable[1];
				$cadena_sql.=" AND PER = ".$variable[2];
				$cadena_sql.=" AND DOC = ".$variable[0];
				$cadena_sql.=" ORDER BY DIA_COD,HORA_COD ";
				break;
                            
			case "cargaactividadesBorrar":
				$cadena_sql="SELECT distinct ";
				$cadena_sql.="DPT_APE_ANO, ";
				$cadena_sql.="DPT_APE_PER, ";
				$cadena_sql.="DPT_DOC_NRO_IDEN, ";
				$cadena_sql.="DAC_NOMBRE, ";
				$cadena_sql.="substr(DAC_NOMBRE,1,30), ";
				$cadena_sql.="DIA_ABREV, ";
				$cadena_sql.="HOR_LARGA, ";
				$cadena_sql.="SED_ID||' - '||edi_nombre  , ";
				$cadena_sql.="sed_nombre||' - '||edi_nombre, ";
				$cadena_sql.="sal_id_espacio||' - '||sal_nombre , ";
				$cadena_sql.="sal_id_espacio||' - '||sal_nombre , ";
				$cadena_sql.="DPT_FECHA, ";
				$cadena_sql.="DPT_ESTADO, ";
				$cadena_sql.="DIA_COD, ";
				$cadena_sql.="HOR_COD, ";
				$cadena_sql.="DPT_DAC_COD, ";
				$cadena_sql.="DAC_INTENSIDAD, ";
				$cadena_sql.="tvi_nombre, ";
                                $cadena_sql.="(CASE WHEN tvi_cod=1 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=6 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=0 THEN 'SD' ";
				$cadena_sql.=" ELSE 'VE' END) ";
				$cadena_sql.="FROM acdocplantrabajo, acdocactividad,gedia,gehora,gesede,gesalones,actipvin,geedificio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_COD = DPT_DAC_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DIA_NRO = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DIA_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_HORA = ".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="HOR_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_COD = DPT_SED_COD ";
				$cadena_sql.="AND ";
				$cadena_sql.="SED_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_ID_ESPACIO = DPT_SAL_COD ";
                                $cadena_sql.="AND ";
				$cadena_sql.="sal_edificio= edi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="SAL_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod=dpt_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="dia_cod= dpt_dia_nro ";
  				$cadena_sql.="AND ";
  				$cadena_sql.="hor_cod= dpt_hora ";
				$cadena_sql.="ORDER BY 11,12 ";
				break; 
				
			case "actividades":
				$cadena_sql="SELECT ";
				$cadena_sql.="DAC_COD, ";
				$cadena_sql.="DAC_NOMBRE, ";
				$cadena_sql.="DAC_INTENSIDAD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="ACDOCACTIVIDAD ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DAC_COD > 1 ";
				$cadena_sql.="AND ";
				$cadena_sql.="DAC_ESTADO = 'A' ";
				$cadena_sql.="ORDER BY 1";
				break;
			
			case "tipoVinculacion";
				$cadena_sql="SELEC ";
				$cadena_sql.="distinct dtv_tvi_cod, ";
				$cadena_sql.="tvi_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdoctipvin,acasperi,actipvin ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ape_ano=dtv_ape_ano ";
				$cadena_sql.="AND ";
				$cadena_sql.="tvi_cod=dtv_tvi_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_per=dtv_ape_per ";
				$cadena_sql.="AND ";
				$cadena_sql.="ape_estado='".$variable[10]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_estado='A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="dtv_doc_nro_iden= ".$variable[0]." ";
				break;
						
			case "cuentaActividad":
				$cadena_sql="SELECT ";
				$cadena_sql.="tvi_nombre, ";
				$cadena_sql.="actividades, ";
				$cadena_sql.="carga, ";
				$cadena_sql.="(CASE WHEN tvi_cod=1 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=6 THEN 'PL' ";
				$cadena_sql.=" WHEN tvi_cod=0 THEN 'SD' ";
				$cadena_sql.=" ELSE 'VE' END), ";
				$cadena_sql.="tvi_cod ";
				$cadena_sql.="FROM ( ";
				$cadena_sql.="SELECT tvi_cod, ";
				$cadena_sql.="tvi_nombre, ";
				$cadena_sql.="(SELECT count(car_hor_id) ";
				$cadena_sql.="FROM accursos ";
				$cadena_sql.="INNER JOIN achorarios ON cur_id=hor_id_curso ";
				$cadena_sql.="INNER JOIN accargas ON hor_id=car_hor_id ";
				$cadena_sql.="WHERE cur_ape_ano= '".$variable[1]."' ";
				$cadena_sql.="AND cur_ape_per = '".$variable[2]."' ";
				$cadena_sql.="AND car_doc_nro = ".$variable[0]." ";
                                $cadena_sql.="AND hor_estado='A' ";
                                $cadena_sql.="AND cur_estado='A'";
				$cadena_sql.="AND car_tip_vin=tvi_cod ";
				$cadena_sql.="AND car_estado = 'A') carga, ";
				$cadena_sql.="(SELECT COUNT(DPT_HORA) numactividades ";
				$cadena_sql.="FROM acdocplantrabajo ";
				$cadena_sql.="WHERE DPT_APE_ANO = '".$variable[1]."' ";
				$cadena_sql.="AND DPT_APE_PER = '".$variable[2]."' ";
				$cadena_sql.="AND actipvin.tvi_cod=dpt_tvi_cod ";//Esta linea estaba comentareada.... verificar.
				$cadena_sql.="AND dpt_estado='A' ";//Esta linea estaba comentareada.... verificar.
				$cadena_sql.="AND DPT_DOC_NRO_IDEN = ".$variable[0].") actividades ";
				$cadena_sql.="FROM actipvin ";
				$cadena_sql.=") act ";
				$cadena_sql.="WHERE (carga+actividades) <> 0 ";
				$cadena_sql.="ORDER BY tvi_cod ASC";
				break;
                                
			case "cuentaActividadAnterior":
                                $cadena_sql=" SELECT tvi_nombre, actividades, carga,";
                                $cadena_sql.=" (CASE WHEN tvi_cod=1 THEN 'PL' WHEN tvi_cod=6 THEN 'PL' WHEN tvi_cod=0 THEN 'SD' ELSE 'VE' END),";
                                $cadena_sql.=" tvi_cod FROM";
                                $cadena_sql.=" ( SELECT tvi_cod,";
                                $cadena_sql.=" tvi_nombre,";
                                $cadena_sql.=" (";
                                $cadena_sql.=" select coalesce(sum(HORAS),0)";
                                $cadena_sql.=" FROM (";
                                $cadena_sql.=" SELECT dtv_ape_ano ANO,dtv_ape_per PER,dtv_doc_nro_iden DOC,dtv_tvi_cod TIPVIN,count(*) HORAS";
                                $cadena_sql.=" FROM acdoctipvin, accargahis, achorariohis ";
                                $cadena_sql.=" WHERE dtv_ape_ano = car_ape_ano ";
                                $cadena_sql.=" AND dtv_ape_per = car_ape_per ";
                                $cadena_sql.=" AND dtv_cra_cod = car_cra_cod ";
                                $cadena_sql.=" AND dtv_doc_nro_iden = car_doc_nro_iden ";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" AND hor_ape_ano=car_ape_ano";
                                $cadena_sql.=" AND hor_ape_per=car_ape_per";
                                $cadena_sql.=" AND hor_asi_cod=car_cur_asi_cod";
                                $cadena_sql.=" AND hor_nro=car_cur_nro";
                                $cadena_sql.=" and cast(concat(dtv_ape_ano,dtv_ape_per) as numeric)<20103";
                                $cadena_sql.=" group by dtv_ape_ano,dtv_ape_per,dtv_doc_nro_iden,dtv_tvi_cod";
                                $cadena_sql.=" union";
                                $cadena_sql.=" SELECT dtv_ape_ano ANO,dtv_ape_per PER,dtv_doc_nro_iden DOC,dtv_tvi_cod TIPVIN,SUM(car_nro_hrs) HORAS";
                                $cadena_sql.=" FROM acdoctipvin, accargahis ";
                                $cadena_sql.=" WHERE dtv_ape_ano = car_ape_ano ";
                                $cadena_sql.=" AND dtv_ape_per = car_ape_per ";
                                $cadena_sql.=" AND dtv_cra_cod = car_cra_cod ";
                                $cadena_sql.=" AND dtv_doc_nro_iden = car_doc_nro_iden ";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" and cast(concat(dtv_ape_ano,dtv_ape_per) as numeric)>=20103";
                                $cadena_sql.=" group by dtv_ape_ano,dtv_ape_per,dtv_doc_nro_iden,dtv_tvi_cod";
                                $cadena_sql.=" union";
                                $cadena_sql.=" SELECT cur_ape_ano ANO,cur_ape_per PER,car_doc_nro DOC,car_tip_vin TIPVIN,count(car_hor_id) ";
                                $cadena_sql.=" FROM accursos_historia ";
                                $cadena_sql.=" INNER JOIN achorarios_historia ON cur_id=hor_id_curso ";
                                $cadena_sql.=" INNER JOIN accargas_historia ON hor_id=car_hor_id ";
                                $cadena_sql.=" WHERE hor_estado='A' ";
                                $cadena_sql.=" AND cur_estado='A'";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" group by cur_ape_ano,cur_ape_per,car_doc_nro,car_tip_vin";
                                $cadena_sql.=" ) CARGA";
                                $cadena_sql.=" WHERE DOC=".$variable[0]." ";
                                $cadena_sql.=" AND ANO='".$variable[1]."' ";
                                $cadena_sql.=" AND PER='".$variable[2]."' ";
                                $cadena_sql.=" AND TIPVIN=tvi_cod) carga, ";
                                $cadena_sql.=" (SELECT COUNT(DPT_HORA) numactividades";
                                $cadena_sql.=" FROM acdocplantrabajo ";
                                $cadena_sql.=" WHERE DPT_APE_ANO = '".$variable[1]."' ";
                                $cadena_sql.=" AND DPT_APE_PER = '".$variable[2]."' ";
                                $cadena_sql.=" AND actipvin.tvi_cod=dpt_tvi_cod ";
                                $cadena_sql.=" AND dpt_estado='A' ";
                                $cadena_sql.=" AND DPT_DOC_NRO_IDEN = ".$variable[0].") actividades ";
                                $cadena_sql.=" FROM actipvin) act";
                                $cadena_sql.=" WHERE (carga+actividades) <> 0";
				$cadena_sql.=" ORDER BY tvi_cod ASC";
				break;
		
			case "totalPorActividad":
				$cadena_sql="SELECT COUNT(DPT_HORA) ";
				$cadena_sql.="FROM acdocplantrabajo ";
				$cadena_sql.="WHERE DPT_APE_ANO= '".$variable[1]."' ";
				$cadena_sql.="AND DPT_APE_PER= '".$variable[2]."' ";
				$cadena_sql.="AND DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND DPT_DAC_COD = ".$variable[5]." ";
				$cadena_sql.="AND DPT_ESTADO = 'A' ";
				$cadena_sql.="AND DPT_TVI_COD = ".$variable[8]."";
				break;
				
			case "intensidadActividad":
				$cadena_sql="SELECT ";
				$cadena_sql.="COUNT(DPT_HORA) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DAC_COD =".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_TVI_COD =".$variable[8]."";
				break;
				
			case "cuentaIntensidad":
				$cadena_sql="SELECT ";
				$cadena_sql.="DAC_INTENSIDAD ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocactividad ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DAC_COD = ".$variable[5]."";
				break;
				
			case "cruceCarga":
				$cadena_sql="SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM "; 
				$cadena_sql.="accargas ";
				$cadena_sql.=" INNER JOIN achorarios ON car_hor_id=hor_id";
				$cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
				$cadena_sql.=" WHERE car_doc_nro = ".$variable[0]." ";
				$cadena_sql.=" AND hor_dia_nro=".$variable[3]." "; 
				$cadena_sql.=" AND hor_hora=".$variable[4]."";
				$cadena_sql.=" AND cur_ape_ano=".$variable[1]." ";
				$cadena_sql.=" AND cur_ape_per=".$variable[2]." ";
				$cadena_sql.=" AND car_estado = 'A'";
				break;
			
			case "cruceActividad";
				$cadena_sql = "SELECT ";
				$cadena_sql.="'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPT_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_DIA_NRO = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPT_HORA =".$variable[4]."";
				break;
			
			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="(";
				$cadena_sql.="DPT_APE_ANO, ";
				$cadena_sql.="DPT_APE_PER, ";
				$cadena_sql.="DPT_DOC_NRO_IDEN, ";
				$cadena_sql.="DPT_DAC_COD, ";
				$cadena_sql.="DPT_DIA_NRO, ";
				$cadena_sql.="DPT_HORA, ";
				$cadena_sql.="DPT_SED_COD, ";
				$cadena_sql.="DPT_SAL_COD, ";
				$cadena_sql.="DPT_FECHA, ";
				$cadena_sql.="DPT_ESTADO, ";
				$cadena_sql.="DPT_TVI_COD ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="$variable[1], ";
				$cadena_sql.="$variable[2], ";
				$cadena_sql.="$variable[0], ";
				$cadena_sql.="$variable[5], ";
				$cadena_sql.="$variable[3], ";
				$cadena_sql.="$variable[4], ";
				$cadena_sql.="$variable[6], ";  //sede
				$cadena_sql.="'".$variable[7]."', "; //salon
				$cadena_sql.="'".$variable[9]."', "; //fecha
				$cadena_sql.="'A', ";
				$cadena_sql.="$variable[8] ";
				$cadena_sql.=")";
				break;
				
			case "borraActividad":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.="acdocplantrabajo ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="dpt_doc_nro_iden = ".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_ape_ano = ".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_ape_per = ".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_dia_nro = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="dpt_hora = ".$variable[4]."";
				break;
			
                            
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="cast(TO_CHAR(ACE_FEC_INI,'YYYYMMDD') as numeric), ";
				$cadena_sql.="cast(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD') as numeric), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy'), ";
                                $cadena_sql.="ACE_HABILITAR_EX ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="WHERE ACE_ANIO =  '".$variable[1]."' ";
				$cadena_sql.="AND ACE_PERIODO =  '".$variable[2]."' ";
				$cadena_sql.="AND ACE_COD_EVENTO = 41 ";
				$cadena_sql.="AND ace_cra_cod in (SELECT distinct cur_cra_cod FROM accursos ";
				$cadena_sql.="INNER JOIN achorarios ON cur_id=hor_id_curso ";
				$cadena_sql.="INNER JOIN accargas ON hor_id=car_hor_id WHERE car_doc_nro=".$variable[0].") ";
				$cadena_sql.="ORDER BY 2 DESC ";
				//$cadena_sql.="'".$variable[9]."' BETWEEN TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')) AND TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd')) ";
				break;
                            

                            
			case "validaFechasPersonalizada":
				$cadena_sql="SELECT ";
				$cadena_sql.="cast(TO_CHAR(ACX_FECHA_INI,'YYYYMMDD') as numeric), ";
				$cadena_sql.="cast(TO_CHAR(ACX_FECHA_FIN,'YYYYMMDD') as numeric), ";
				$cadena_sql.="TO_CHAR(ACX_FECHA_FIN,'dd-Mon-yy') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acexevento";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACX_ANO='".$variable[1]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACX_PERIODO = '".$variable[2]."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACX_COD_EVENTO = 41 ";
                                $cadena_sql.="AND ";
				$cadena_sql.="ACX_ID_USUARIO = '".$variable[0]."' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="ACX_ESTADO = 'A' ";
                                $cadena_sql.="AND ";
				$cadena_sql.="'".$variable[9]."' BETWEEN cast(TO_CHAR(ACX_FECHA_INI, 'yyyymmdd') as numeric) AND cast(TO_CHAR(ACX_FECHA_FIN, 'yyyymmdd') as numeric) ";
				$cadena_sql.="ORDER BY 2 DESC ";
                                break;
                            
                        case "validaFechasDocentePlanta":
                                $cadena_sql=" SELECT cast(TO_CHAR(ACE_FEC_INI,'YYYYMMDD') as numeric),";
                                $cadena_sql.=" cast(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD') as numeric),";
                                $cadena_sql.=" TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy'),";
                                $cadena_sql.=" emp_nro_iden,";
                                $cadena_sql.=" emp_dep_cod";
                                $cadena_sql.=" FROM pecargo,peemp,accaleventos";
                                $cadena_sql.=" WHERE car_tc_cod IN ('DP','DC','DH')";
                                $cadena_sql.=" AND car_cod = emp_car_cod";
                                $cadena_sql.=" and emp_estado_e <> 'R'";
                                $cadena_sql.=" and emp_nro_iden='".$variable[0]."' ";
                                $cadena_sql.=" and ace_anio='".$variable[1]."' ";
                                $cadena_sql.=" and ace_periodo='".$variable[2]."' ";
                                $cadena_sql.=" and ace_cod_evento=41";
                                $cadena_sql.=" and ace_dep_cod=emp_dep_cod";
                                $cadena_sql.=" and emp_nro_iden not in (select car_doc_nro from accursos";
                                $cadena_sql.=" inner join achorarios on hor_id_curso=cur_id";
                                $cadena_sql.=" inner join accargas on car_hor_id=hor_id";
                                //$cadena_sql.=" inner join acasperi on ape_ano=cur_ape_ano and ape_per=cur_ape_per";
                                $cadena_sql.=" where cur_ape_ano='".$variable[1]."'";
                                $cadena_sql.=" and cur_ape_per='".$variable[2]."'";
                                $cadena_sql.=" and car_doc_nro=emp_nro_iden)";
                                $cadena_sql.=" order by ace_fec_fin desc";
				break;
                            
                            
			case "insertaObservacion":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="acdocplantrabajobs ";
				$cadena_sql.="(";
				$cadena_sql.="DPO_APE_ANO, ";
				$cadena_sql.="DPO_APE_PER, ";
				$cadena_sql.="DPO_DOC_NRO_IDEN, ";
				$cadena_sql.="DPO_OBS, ";
				$cadena_sql.="DPO_ESTADO ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.=$variable[1].", ";
				$cadena_sql.=$variable[2].", ";
				$cadena_sql.=$variable[0].", ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="'A' ";
				$cadena_sql.=")";
				break;
				
			case "consultaObservacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="DPO_APE_ANO, ";
				$cadena_sql.="DPO_APE_PER, ";
				$cadena_sql.="DPO_DOC_NRO_IDEN, ";
				$cadena_sql.="DPO_OBS, ";
				$cadena_sql.="DPO_ESTADO ";
				$cadena_sql.="FROM ACDOCPLANTRABAJOBS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPO_APE_ANO = ".$variable[1]." ";
				$cadena_sql.="AND DPO_APE_PER = ".$variable[2]." ";
				$cadena_sql.="AND DPO_DOC_NRO_IDEN = ".$variable[0]." ";
				$cadena_sql.="AND DPO_ESTADO = 'A'";
				break;
				
			case "modificaObservacion":
				$cadena_sql="UPDATE ";
				$cadena_sql.="ACDOCPLANTRABAJOBS ";
				$cadena_sql.="SET ";
				$cadena_sql.="DPO_OBS ='".$variable[3]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="DPO_APE_ANO = ".$variable[1]."  ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPO_APE_PER = ".$variable[2]."  ";
				$cadena_sql.="AND ";
				$cadena_sql.="DPO_DOC_NRO_IDEN = ".$variable[0]."";
				break;
				
			case "codigoSede":
				$cadena_sql="SELECT ";
				$cadena_sql.="SED_COD ";
				$cadena_sql.="FROM GESEDE ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="SED_ID = '".$variable."' ";
				break;

			case "seleccionarPeriodo":
                                $cadena_sql=" SELECT";
                                $cadena_sql.=" P.ano, P.per from (";
                                $cadena_sql.=" SELECT DISTINCT dpt_ape_ano ano, dpt_ape_per per FROM acdocplantrabajo";
                                $cadena_sql.=" UNION";
                                $cadena_sql.=" SELECT DISTINCT car_ape_ano ano, car_ape_per per FROM accargahis";
                                $cadena_sql.=" UNION";
                                $cadena_sql.=" SELECT DISTINCT cur_ape_ano ano, cur_ape_per per FROM accursos";
                                $cadena_sql.=" ) AS P";
                                $cadena_sql.=" ORDER BY P.ano desc, P.per desc";
				break;
                            
			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
