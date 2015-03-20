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

class sql_registroNotasDocentes extends sql
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
				//$cadena_sql.="ape_estado='P'";	
				$cadena_sql.="ape_estado='".$variable[10]."'";	
				break;
                            
			case "acasperieventos":
				$cadena_sql="SELECT ";
				$cadena_sql.="acn_cra_cod, ";
				$cadena_sql.="acn_estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acasperieventos";
				break;
                            
			case "carreras":
                                if($variable[4]=='ANTERIOR'){
                                    $nivel="PREGRADO','EXTENSION','POSGRADO','MAESTRIA','DOCTORADO";
                                }else{$nivel=$variable[4];}
                                $cadena_sql=" SELECT DISTINCT cur_cra_cod";
                                $cadena_sql.=" FROM accargas ";
                                $cadena_sql.=" INNER JOIN achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN accursos ON hor_id_curso=cur_id";
                                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano=ape_ano AND cur_ape_per=ape_per";
                                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";
                                $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod ";
                                $cadena_sql.=" WHERE ape_estado='A'";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" AND car_doc_nro=".$variable[0]." ";
                                $cadena_sql.=" AND tra_nivel IN ('".$nivel."')  ";
                                break;
                                
			case "listaClase":
                                if($variable[4]=='PREGRADO')
                                {
                                        $nivel="'PREGRADO', 'EXTENSION'";
                                }
                                elseif($variable[4]=='POSGRADO')
                                {
                                        $nivel="'POSGRADO','MAESTRIA','DOCTORADO'";
                                }elseif($variable[4]=='ANTERIOR'){
                                    $nivel="'PREGRADO','EXTENSION','POSGRADO','MAESTRIA','DOCTORADO'";
                                }
                                $cadena_sql=" SELECT DISTINCT ";
                                $cadena_sql.=" DOC_NRO_IDEN,";
                                $cadena_sql.=" LTRIM(doc_nombre || ' ' ||doc_apellido) nombre,";
                                $cadena_sql.=" dep_cod,";
                                $cadena_sql.=" dep_nombre,";
                                $cadena_sql.=" cur_cra_cod,";
                                $cadena_sql.=" cra_nombre,";
                                $cadena_sql.=" tvi_cod,";
                                $cadena_sql.=" tvi_nombre,";
                                $cadena_sql.=" CUR_ASI_COD,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" cur_id,";
                                $cadena_sql.=" cur_nro_ins,";
                                $cadena_sql.=" tra_nivel,";
                                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO";
                                $cadena_sql.=" FROM accargas";
                                $cadena_sql.=" INNER JOIN acdocente ON car_doc_nro=doc_nro_iden";
                                $cadena_sql.=" INNER JOIN actipvin ON car_tip_vin = tvi_cod";
                                $cadena_sql.=" INNER JOIN achorarios ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN accursos ON hor_id_curso=cur_id";
                                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano =ape_ano AND cur_ape_per=ape_per";
                                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";
                                $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra =tra_cod";
                                $cadena_sql.=" INNER JOIN gedep ON dep_cod = cra_dep_cod";
                                $cadena_sql.=" INNER JOIN acasi ON cur_asi_cod=asi_cod";
                                $cadena_sql.=" WHERE ape_estado ='".$variable[10]."'";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" AND doc_estado = 'A'";
                                $cadena_sql.=" AND cra_estado = 'A'";
                                $cadena_sql.=" AND cur_estado = 'A'";
                                $cadena_sql.=" AND car_doc_nro =".$variable[0]." ";
                                $cadena_sql.=" AND tra_nivel in (".$nivel.")";
                                $cadena_sql.=" ORDER BY ";
				$cadena_sql.=" dep_cod, cur_cra_cod, cur_asi_cod, grupo ASC ";
				break; 
			
			case "notasparciales":
				//En ORACLE
                                $cadena_sql=" SELECT DISTINCT doc_nro_iden ,";
                                $cadena_sql.=" (doc_nombre";
                                $cadena_sql.=" ||' '";
                                $cadena_sql.=" ||doc_apellido) eca_nombre,";
                                $cadena_sql.=" cur_ape_ano,";
                                $cadena_sql.=" cur_ape_per,";
                                $cadena_sql.=" cur_asi_cod,";
                                $cadena_sql.=" asi_nombre,";
                                $cadena_sql.=" cur_id,";
                                $cadena_sql.=" ins_est_cod,";
                                $cadena_sql.=" est_nombre,";
                                $cadena_sql.=" est_estado_est,";
                                $cadena_sql.=" ins_nota_par1,";
                                $cadena_sql.=" cur_par1,";
                                $cadena_sql.=" ins_nota_par2,";
                                $cadena_sql.=" cur_par2,";
                                $cadena_sql.=" ins_nota_par3,";
                                $cadena_sql.=" cur_par3,";
                                $cadena_sql.=" ins_nota_par4,";
                                $cadena_sql.=" cur_par4,";
                                $cadena_sql.=" ins_nota_par5,";
                                $cadena_sql.=" cur_par5,";
                                $cadena_sql.=" ins_nota_par6,";
                                $cadena_sql.=" cur_par6,";
                                $cadena_sql.=" ins_nota_exa,";
                                $cadena_sql.=" cur_exa,";
                                $cadena_sql.=" ins_nota_lab,";
                                $cadena_sql.=" cur_hab,";
                                $cadena_sql.=" ins_nota_hab,";
                                $cadena_sql.=" cur_lab,";
                                $cadena_sql.=" ins_nota,";
                                $cadena_sql.=" ins_obs,";
                                $cadena_sql.=" cur_hab,";
                                $cadena_sql.=" ins_nota_acu,";
                                $cadena_sql.=" cur_nro_ins,";
                                $cadena_sql.=" cur_cra_cod,";
                                $cadena_sql.=" (coalesce(cur_par1,0)+coalesce(cur_par2,0)+coalesce(cur_par3,0)+coalesce(cur_par4,0)+coalesce(cur_par5,0)+coalesce(cur_par6,0)+coalesce(cur_exa,0)+coalesce(cur_lab,0)) PARCIAL,";
                                $cadena_sql.=" ins_tot_fallas,";
                                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) grupo";
                                $cadena_sql.=" FROM acins";
                                $cadena_sql.=" INNER JOIN accursos ON ins_gr =cur_id AND ins_ano=cur_ape_ano AND ins_per=cur_ape_per";
                                $cadena_sql.=" INNER JOIN acasperi ON cur_ape_ano =ape_ano AND cur_ape_per=ape_per";
                                $cadena_sql.=" INNER JOIN acasi ON cur_asi_cod =asi_cod";
                                $cadena_sql.=" INNER JOIN acest ON ins_est_cod = est_cod";
                                $cadena_sql.=" INNER JOIN achorarios ON hor_id_curso=cur_id";
                                $cadena_sql.=" INNER JOIN accargas ON car_hor_id=hor_id";
                                $cadena_sql.=" INNER JOIN acdocente ON car_doc_nro =doc_nro_iden";
                                $cadena_sql.=" WHERE ape_estado = '".$variable[10]."'";
                                $cadena_sql.=" AND ins_estado = 'A'";
                                $cadena_sql.=" AND cur_estado = 'A'";
                                $cadena_sql.=" AND car_estado = 'A'";
                                $cadena_sql.=" AND doc_nro_iden =".$variable[0]." ";
                                $cadena_sql.=" AND asi_cod =".$variable[1]." ";
                                $cadena_sql.=" AND cur_id =".$variable[2]." ";
                                $cadena_sql.=" ORDER BY cur_asi_cod,";
                                $cadena_sql.=" grupo,";
                                $cadena_sql.=" ins_est_cod";
                            break;
				
			case "fechasDigNotas":
				$cadena_sql="SELECT ";
				$cadena_sql.="NPF_CRA_COD, ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR1, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR1, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR2, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR2, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR3, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR3, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR4, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR4, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR5, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR5, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IPAR6, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FPAR6, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_ILAB, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FLAB, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IEXA, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FEXA, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_IHAB, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(NPF_FHAB, 'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'YYYYMMDD'),'99999999') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acnotparfec, accaleventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="NPF_CRA_COD=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="NPF_ESTADO = 'A' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_anio=".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_periodo=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cra_cod = npf_cra_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_cod_evento = 7";
				break;
	 
			case "fechaactual":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(CURRENT_TIMESTAMP, 'YYYYMMDD'),'99999999') ";
				break;
				 
					
//			case "insertarRegistro":
//				$cadena_sql="INSERT INTO ";
//				$cadena_sql.="acestecaes ";
//				$cadena_sql.="(";
//				$cadena_sql.="eca_ano, ";
//				$cadena_sql.="eca_per, ";
//				$cadena_sql.="eca_cod, ";
//				$cadena_sql.="eca_genero_recibo, ";
//				$cadena_sql.="eca_pago_recibo, ";
//				$cadena_sql.="eca_estado, ";
//				$cadena_sql.="eca_presento ";
//				$cadena_sql.=") ";
//				$cadena_sql.="VALUES ";
//				$cadena_sql.="( ";
//				$cadena_sql.="$variable[1], ";
//				$cadena_sql.="$variable[2], ";
//				$cadena_sql.="$variable[0], ";
//				$cadena_sql.="'N', ";
//				$cadena_sql.="'N', ";
//				$cadena_sql.="'A', ";
//				$cadena_sql.="'$variable[3]' ";
//				$cadena_sql.=")";
//				break;
//							
			case "validaFechas":
				$cadena_sql="SELECT ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ACE_FEC_INI,'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_NUMBER(TO_CHAR(ACE_FEC_FIN,'YYYYMMDD'),'99999999'), ";
				$cadena_sql.="TO_CHAR(ACE_FEC_FIN,'dd-Mon-yy') ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ace_cra_cod=".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_anio=".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ace_periodo=".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 7 ";
				break;
				
			case "notasobs":
				$cadena_sql="SELECT ";
				$cadena_sql.="nob_cod, ";
				$cadena_sql.="nob_nombre ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acnotobs ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="nob_digita = 'S' ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="nob_cod";
				break;

			case "verificaRegistro":
				$cadena_sql="SELECT ";
				$cadena_sql.="eca_ano, ";
				$cadena_sql.="eca_per, ";
				$cadena_sql.="eca_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="acestecaes ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="eca_ano=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="eca_per=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="eca_cod=".$variable[0]." ";
				break;
				
			case "cierreSemestre":
				$cadena_sql="SELECT 'S' ";
				$cadena_sql.="FROM ";
				$cadena_sql.="accaleventos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ACE_ANIO = ".$variable[5]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_PERIODO = ".$variable[6]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_CRA_COD = ".$variable[3]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ACE_COD_EVENTO = 71";
				break;
				
			default:
				$cadena_sql="";
				break;
		}
		return $cadena_sql;
	}
	
	
}
?>
