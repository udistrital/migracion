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

class sql_adminVinculacion extends sql
{
	function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{       
                        case "proyectos":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" CRA.CRA_COD ,";
                            $cadena_sql.=" CRA.CRA_NOMBRE,";
                            $cadena_sql.=" CRA.CRA_DEP_COD, ";
                            $cadena_sql.=" CRA.CRA_TIP_CRA ";
                            $cadena_sql.=" FROM ACCRA CRA";
                            $cadena_sql.=" WHERE CRA.CRA_COD=".$variable['proyecto']." ";
                            break;
                        case "datosUsuario":
                            //En ORACLE
                            $cadena_sql="SELECT ";
                            $cadena_sql.="doc_apellido DOC_APEL, ";
                            $cadena_sql.="doc_nombre DOC_NOM, ";
                            $cadena_sql.="doc_tip_iden DOC_TIP_IDEN, ";
                            $cadena_sql.="doc_nro_iden DOC_NRO_IDEN ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="acdocente ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="doc_nro_iden=".$variable['identificacion']." ";
                            $cadena_sql.="AND ";
                            $cadena_sql.="doc_estado = 'A' ";
                            break;
                        
                        case "vinculaciones":
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="dvin.dtv_ape_ano VIN_ANIO, ";
                            $cadena_sql.="dvin.dtv_ape_per VIN_PER, ";
                            $cadena_sql.="dvin.dtv_cra_cod VIN_CRA_COD , ";
                            $cadena_sql.="cra.cra_nombre VIN_CRA_NOM , ";
                            $cadena_sql.="dvin.dtv_tvi_cod VIN_COD, ";
                            $cadena_sql.="tvin.tvi_nombre VIN_NOMBRE, ";
                            $cadena_sql.="dvin.dtv_estado VIN_ESTADO, ";
                            $cadena_sql.="dvin.dtv_resolucion VIN_RESOLUCION, ";
                            $cadena_sql.="dvin.dtv_interno_res VIN_INT_RES ";
                            $cadena_sql.=" FROM ";
                            $cadena_sql.="acdoctipvin dvin ,actipvin tvin ,accra cra ";
                            $cadena_sql.=" WHERE ";
                            $cadena_sql.="dvin.dtv_tvi_cod=tvin.tvi_cod";
                            $cadena_sql.=" AND ";
                            $cadena_sql.="dvin.dtv_cra_cod=cra.cra_cod";
                            if(isset($variable['anio']))
                                    {
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_ape_ano=".$variable['anio']." ";
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_ape_per=".$variable['periodo']." ";
                                    $cadena_sql.=" AND ";
                                    $cadena_sql.="dvin.dtv_estado='A' ";
                                    }
                            $cadena_sql.=" AND ";
                            $cadena_sql.="dvin.dtv_doc_nro_iden=".$variable['identificacion']." ";
                            $cadena_sql.=" ORDER BY dvin.dtv_ape_ano DESC,dvin.dtv_ape_per DESC,dvin.dtv_cra_cod ";
                            break;
                       
                      case "actosAdministrativos":
                            //En MYSQL
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="acto_cod ACTO_COD, ";
                            $cadena_sql.="acto_nro_doc_docente ACTO_DOC_ID, ";
                            $cadena_sql.="acto_descripcion ACTO_DESC, ";
                            $cadena_sql.="acto_fecha_registro ACTO_FECHA, ";
                            $cadena_sql.="acto_nombre_archivo ACTO_NOM_ARCHIVO, ";
                            $cadena_sql.="acto_archivo_interno ACTO_AINT, ";
                            $cadena_sql.="acto_usuario ACTO_US ";
                            $cadena_sql.="FROM ";
                            $cadena_sql.="sga_acto_administrativo_docente  ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="acto_nro_doc_docente=".$variable['identificacion']." ";
                            break;
                        
                      case "vigenciaVinculacionDocente":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" dtv_ape_ano COD_ANIO,";
                            $cadena_sql.=" dtv_ape_ano ANIO";
                            $cadena_sql.=" FROM acdoctipvin";
                            $cadena_sql.=" where dtv_doc_nro_iden=".$variable['identificacion']." ";
                            $cadena_sql.=" ORDER BY dtv_ape_ano DESC";
                            break; 
 

                      case "vigenciaVinculacion":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" ape_ano COD_ANIO,";
                            $cadena_sql.=" ape_ano ANIO";
                            $cadena_sql.=" FROM acasperi";
                            $cadena_sql.=" where ape_ano <=  ";
                            $cadena_sql.=" (SELECT DISTINCT";
                            $cadena_sql.=" ape_ano ANIO";
                            $cadena_sql.=" FROM acasperi ";
                            $cadena_sql.=" where ape_estado='A') ";
                            $cadena_sql.=" ORDER BY ape_ano DESC";
                            break;                         
                        
                        
                      case "mes":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" LPAD(mes_cod::text,2,'0') COD_MES,";
                            $cadena_sql.=" mes_nombre MES ";
                            $cadena_sql.=" FROM gemes";
                            $cadena_sql.=" WHERE mes_cod<13";
                            $cadena_sql.=" ORDER BY COD_MES ASC";
                            break;                          
                        
                      case "pagos_PG":
                            //En postgres
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" nom_detalle.dtlle_vigencia AS vigencia, ";
                            $cadena_sql.=" nom_detalle.dtlle_mes AS mes, ";
                            $cadena_sql.=" (CASE WHEN nom_detalle.dtlle_mes='1' ";
                            $cadena_sql.="  THEN 'ENERO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='2' ";
                            $cadena_sql.="  THEN 'FEBRERO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='3' ";
                            $cadena_sql.="  THEN 'MARZO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='4' ";
                            $cadena_sql.="  THEN 'ABRIL' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='5' ";
                            $cadena_sql.="  THEN 'MAYO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='6' ";
                            $cadena_sql.="  THEN 'JUNIO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='7' ";
                            $cadena_sql.="  THEN 'JULIO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='8' ";
                            $cadena_sql.="  THEN 'AGOSTO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='9' ";
                            $cadena_sql.="  THEN 'SEPTIEMBRE' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='10' ";
                            $cadena_sql.="  THEN 'OCTUBRE' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='11' ";
                            $cadena_sql.="  THEN 'NOVIEMBRE' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_mes='12' ";
                            $cadena_sql.="  THEN 'DICIEMBRE' ";
                            $cadena_sql.="  ELSE 'SIN DATO' ";
                            $cadena_sql.=" END ) AS nom_mes, "; 
                            $cadena_sql.=" nom_detalle.dtlle_ident_docente AS identificacion, ";
                            $cadena_sql.=" nom_detalle.dtlle_cdp AS cdp, ";
                            $cadena_sql.=" nom_detalle.dtlle_crp AS crp, ";
                            $cadena_sql.=" nom_detalle.dtlle_facultad AS facultad, ";
                            $cadena_sql.=" nom_detalle.dtlle_proyecto AS proyecto, ";
                            $cadena_sql.=" nom_detalle.dtlle_valor_bruto AS valor_bruto, ";
                            $cadena_sql.=" nom_detalle.dtlle_tipo_nomina AS tipo_cod, ";
                            $cadena_sql.=" (CASE WHEN nom_detalle.dtlle_tipo_nomina='S' ";
                            $cadena_sql.="  THEN 'CONTRATO' ";
                            $cadena_sql.="  WHEN nom_detalle.dtlle_tipo_nomina='H' ";
                            $cadena_sql.="  THEN 'HONORARIOS' ";
                            $cadena_sql.="  ELSE 'SIN DATO' ";
                            $cadena_sql.=" END ) AS tipo_nom, ";
                            $cadena_sql.=" nom_detalle.dtlle_cta_banco AS num_cuenta, ";
                            $cadena_sql.=" nom_detalle.dtlle_tipo_cta AS tipo_cuenta, ";
                            $cadena_sql.=" nom_detalle.dtlle_banco AS nom_banco";
                            $cadena_sql.=" FROM ";
                            $cadena_sql.=" \"nominaVE\".nom_detalle";
                            $cadena_sql.=" WHERE ";
                            $cadena_sql.=" nom_detalle.dtlle_vigencia = '".$variable['anio']."' ";
                            $cadena_sql.=" AND nom_detalle.dtlle_mes = '".$variable['mes']."' ";
                            $cadena_sql.=" AND nom_detalle.dtlle_ident_docente ='".$variable['identificacion']."'";
                            $cadena_sql.=" ORDER BY";
                            $cadena_sql.=" nom_detalle.dtlle_vigencia ASC, ";
                            $cadena_sql.=" nom_detalle.dtlle_mes ASC, ";
                            $cadena_sql.=" nom_detalle.dtlle_cdp ASC, ";
                            $cadena_sql.=" nom_detalle.dtlle_crp ASC;";

                            break; 
                        
                       case "descuentos_PG":
                            //En postgres
                            $cadena_sql=" SELECT DISTINCT";
                            $cadena_sql.=" nom_descuento.desc_nombre AS descuento, ";
                            $cadena_sql.=" nom_detalle_desc.dtll_desc_valor AS valor_descuento, ";
                            $cadena_sql.=" nom_detalle_desc.dtll_desc_id AS id_descuento";
                            $cadena_sql.=" FROM ";
                            $cadena_sql.=" \"nominaVE\".nom_detalle_desc, ";
                            $cadena_sql.=" \"nominaVE\".nom_descuento";
                            $cadena_sql.=" WHERE ";
                            $cadena_sql.=" nom_detalle_desc.dtll_desc_id = nom_descuento.desc_id ";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_vigencia ='".$variable['vigencia']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_mes ='".$variable['mes']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_ident_docente ='".$variable['identificacion']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_cdp ='".$variable['cdp']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_crp ='".$variable['crp']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_facultad ='".$variable['facultad']."'";
                            $cadena_sql.=" AND nom_detalle_desc.dtll_desc_proyecto ='".$variable['proyecto']."'";
                            $cadena_sql.=" AND nom_descuento.desc_estado = 1";
                            $cadena_sql.=" ORDER BY ";
                            $cadena_sql.=" nom_detalle_desc.dtll_desc_id ASC; ";
                            break;       

                      case "pagos_SIC":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT TO_CHAR(DET_OP.VIGENCIA) VIGENCIA, ";
                            $cadena_sql.=" TO_CHAR(DET_OP.VIGENCIA) VIGENCIA_PRESUPUESTAL, ";
                            $cadena_sql.=" DET_OP.UNIDAD_EJECUTORA UNIDAD_EJECUTORA, ";
                            $cadena_sql.=" RUB.INTERNO_RUBRO INTERNO_RUBRO, ";
                            $cadena_sql.=" RUB.CODIGO_NIVEL1 ||'-'||RUB.CODIGO_NIVEL2||'-'||RUB.CODIGO_NIVEL3||'-'||RUB.CODIGO_NIVEL4||'-'||RUB.CODIGO_NIVEL5||'-'||RUB.CODIGO_NIVEL6||'-'||RUB.CODIGO_NIVEL7||'-'||RUB.CODIGO_NIVEL8 CODIGO_RUBRO, ";
                            $cadena_sql.=" RUB.DESCRIPCION RUBRO, ";
                            $cadena_sql.=" OP.NUMERO_DE_COMPROMISO NUMERO_COMPROMISO, ";
                            $cadena_sql.=" DET_OP.DISPONIBILIDAD DISPONIBILIDAD_PRESUPUESTAL, ";
                            $cadena_sql.=" DET_OP.REGISTRO REGISTRO_PRESUPUESTAL, ";
                            $cadena_sql.=" TO_NUMBER(DET_OP.CONSECUTIVO) ORDEN_PAGO, ";
                            $cadena_sql.=" DET_OP.VALOR_BRUTO VALOR_ORDEN, ";
                            $cadena_sql.=" UPPER(DECODE(OP.FECHA_APROBACION,'',DECODE(EGR.FECHA_REGISTRO,'',TO_CHAR('01/01/1900','fmmonth dd \"de\" yyyy','nls_date_language=spanish'),TO_CHAR(EGR.FECHA_REGISTRO,'fmmonth dd \"de\" yyyy','nls_date_language=spanish')),TO_CHAR(OP.FECHA_APROBACION,'fmmonth dd \"de\" yyyy','nls_date_language=spanish'))) FECHA_ORDEN, ";
                            $cadena_sql.=" UPPER(TO_CHAR(EGR.FECHA_REGISTRO,'fmmonth dd \"de\" yyyy','nls_date_language=spanish' )) FECHA_PAGO, ";
                            $cadena_sql.=" BEN.IB_CODIGO_IDENTIFICACION IDENTIFICACION, ";
                            $cadena_sql.=" BEN.IB_PRIMER_NOMBRE||' '||BEN.IB_SEGUNDO_NOMBRE||' '||BEN.IB_PRIMER_APELLIDO||' '||BEN.IB_SEGUNDO_APELLIDO BENEFICIARIO, ";
                            $cadena_sql.=" UPPER(OP.DETALLE) DETALLE_ORDEN ";
                            $cadena_sql.=" FROM SHD.SHD_INFORMACION_BASICA BEN ";
                            $cadena_sql.=" INNER JOIN OGT.OGT_ORDEN_PAGO OP  ";
                            $cadena_sql.=" ON OP.TIPO_DOCUMENTO ='OP' ";
                            $cadena_sql.=" AND OP.IND_APROBADO = 1 ";
                            $cadena_sql.=" AND OP.TIPO_OP != 2 ";
                            $cadena_sql.=" AND BEN.ID =OP.TER_ID ";
                            $cadena_sql.=" INNER JOIN OGT_IMPUTACION DET_OP ";
                            $cadena_sql.=" ON DET_OP.TIPO_DOCUMENTO = 'OP' ";
                            $cadena_sql.=" AND DET_OP.VIGENCIA = OP.VIGENCIA ";
                            $cadena_sql.=" AND DET_OP.ENTIDAD  = OP.ENTIDAD ";
                            $cadena_sql.=" AND DET_OP.UNIDAD_EJECUTORA = OP.UNIDAD_EJECUTORA ";
                            $cadena_sql.=" AND DET_OP.CONSECUTIVO  = OP.CONSECUTIVO ";
                            $cadena_sql.=" INNER JOIN PR.PR_V_RUBROS RUB ";
                            $cadena_sql.=" ON DET_OP.VIGENCIA      =RUB.VIGENCIA ";
                            $cadena_sql.=" AND DET_OP.RUBRO_INTERNO=RUB.INTERNO_RUBRO ";
                            $cadena_sql.=" LEFT OUTER JOIN OGT.OGT_DETALLE_EGRESO EGR ";
                            $cadena_sql.=" ON OP.CONSECUTIVO=EGR.CONSECUTIVO ";
                            $cadena_sql.=" AND OP.TER_ID =EGR.TER_ID ";
                            $cadena_sql.=" AND OP.VIGENCIA =EGR.VIGENCIA ";
                            $cadena_sql.=" AND OP.UNIDAD_EJECUTORA  = EGR.UNIDAD_EJECUTORA ";
                            $cadena_sql.=" WHERE ";
                            $cadena_sql.=" OP.VIGENCIA='".$variable['anio']."' ";
                            $cadena_sql.=" AND BEN.IB_CODIGO_IDENTIFICACION='".$variable['identificacion']."'";
                            $cadena_sql.=" AND TO_CHAR((DECODE(OP.FECHA_APROBACION,'',DECODE(EGR.FECHA_REGISTRO,'',TO_DATE('01/01/1900','DD-MM-YY'),TO_DATE(EGR.FECHA_REGISTRO,'DD-MM-YY')),TO_DATE(OP.FECHA_APROBACION,'DD-MM-YY'))),'MMYYYY')='".$variable['mes']."".$variable['anio']."' ";
                            $cadena_sql.=" ORDER BY ";
                            $cadena_sql.=" ORDEN_PAGO, ";
                            $cadena_sql.=" VIGENCIA, ";
                            $cadena_sql.=" UNIDAD_EJECUTORA, ";
                            $cadena_sql.=" CODIGO_RUBRO, ";
                            $cadena_sql.=" NUMERO_COMPROMISO, ";
                            $cadena_sql.=" DISPONIBILIDAD_PRESUPUESTAL, ";
                            $cadena_sql.=" REGISTRO_PRESUPUESTAL ";
                            
                            break;      
                        

                      case "descuentosSIC":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT ";
                            $cadena_sql.=" DTE.VIGENCIA VIGENCIA,";
                            $cadena_sql.=" DTE.UNIDAD_EJECUTORA UNIDAD, ";
                            $cadena_sql.=" DTE.CONSECUTIVO ORDEN,";
                            $cadena_sql.=" DTO.DESCRIPCION_CODIGO NOMBRE_DESC, ";
                            $cadena_sql.=" DTO.PORCENTAJE PORC_DESC, ";
                            $cadena_sql.=" DTE.VALOR_BASE_RETENCION BASE_RTE, ";
                            $cadena_sql.=" DTE.VALOR_DESCUENTO VALOR_DESC";
                            $cadena_sql.=" FROM OGT.OGT_DETALLE_DESCUENTO DTE, OGT.OGT_DESCUENTO DTO";
                            $cadena_sql.=" WHERE DTE.CODIGO_INTERNO = DTO.CODIGO_INTERNO";
                            $cadena_sql.=" AND DTE.TIPO_DOCUMENTO='OP'";
                            $cadena_sql.=" aND DTE.UNIDAD_EJECUTORA='".$variable['unidad']."'";
                            $cadena_sql.=" AND DTE.CONSECUTIVO='".$variable['orden']."'";
                            $cadena_sql.=" AND DTE.VIGENCIA=".$variable['vigencia']."";
                            break;    
                        
                     case "devengosSIC":
                            //En ORACLE
                            $cadena_sql=" SELECT DISTINCT ";
                            $cadena_sql.=" O.VIGENCIA VIG, ";
                            $cadena_sql.=" O.UNIDAD_EJECUTORA UNIDAD, ";
                            $cadena_sql.=" O.CONSECUTIVO ORDEN, ";
                            $cadena_sql.=" O.VALOR_CAUSADO VALOR, ";
                            $cadena_sql.=" CTA.CDGO_SDSCRPCION NOMBRE ";
                            $cadena_sql.=" FROM  OGT_CUENTAS_ORDEN_PAGO  O ";
                            $cadena_sql.=" INNER JOIN OGT_ORDEN_PAGO D  ";
                            $cadena_sql.="   ON O.CONSECUTIVO=D.CONSECUTIVO  ";
                            $cadena_sql.="   AND O.ENTIDAD=D.ENTIDAD  ";
                            $cadena_sql.="   AND O.TIPO_DOCUMENTO=D.TIPO_DOCUMENTO ";
                            $cadena_sql.="   AND O.UNIDAD_EJECUTORA=D.UNIDAD_EJECUTORA  ";
                            $cadena_sql.="   AND O.VIGENCIA=D.VIGENCIA ";
                            $cadena_sql.="   AND O.TIPO_DOCUMENTO='OP' ";
                            $cadena_sql.=" INNER JOIN LM_CODIGO CTA on CTA.RPCU_SNMBRE='CGN 2001' ";
                            $cadena_sql.=" AND CTA.CDGO_SCDGO_CMPLTO=O.CUENTA_CONTABLE ";
                            $cadena_sql.=" WHERE O.CUENTA_CONTABLE ";
                            $cadena_sql.=" IN  ('7-2-09-02-11','7-2-08-03-01','5-1-11-11', ";
                            $cadena_sql.="      '2-5-05-02-01','2-5-05-02-01','2-5-05-02-01-01','2-5-05-02-01-02', ";
                            $cadena_sql.="      '2-5-05-02-02','2-5-05-02-02-01','2-5-05-02-02-02','2-5-05-03-01', ";
                            $cadena_sql.="      '2-5-05-03-02','2-5-05-05-02','2-7-15-01-01','2-7-15-01-02','2-7-15-02-01', ";
                            $cadena_sql.="      '2-7-15-02-02','2-7-15-03-01','2-7-15-03-02','2-7-15-04-01','2-7-15-04-02', ";
                            $cadena_sql.="      '2-7-15-06-01','2-7-15-06-02','2-7-15-07-01','2-7-15-09-01','2-7-15-09-02', ";
                            $cadena_sql.="      '2-7-15-12-01','5-1-01-18','5-1-01-24','7-2-08-03-13','7-2-08-03-14', ";
                            $cadena_sql.="      '7-2-08-03-24','7-2-08-03-25','7-2-09-03-13','7-2-09-03-14','7-2-09-03-24', ";
                            $cadena_sql.="      '7-2-09-03-25') ";
                            $cadena_sql.=" AND O.VIGENCIA=".$variable['vigencia']."";
                            $cadena_sql.=" AND O.CONSECUTIVO='".$variable['orden']."'";
                            $cadena_sql.=" AND O.UNIDAD_EJECUTORA='".$variable['unidad']."'";
                            $cadena_sql.=" ORDER BY CTA.CDGO_SDSCRPCION DESC ";                            
                            break;  
                        
                
           		}
                
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>