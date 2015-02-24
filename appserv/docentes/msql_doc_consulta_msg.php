<?php
require_once('dir_relativo.cfg');
//OJO la consulta puede retornar mas de una carrera.
$qry_cra = OCIParse($oci_conecta, "SELECT unique(car_cra_cod)
									 FROM acasperi, accarga
									WHERE ape_estado = 'A'
									  AND ape_ano = car_ape_ano
									  AND ape_per = car_ape_per
									  AND car_doc_nro_iden =".$_SESSION['usuario_login']);
OCIExecute($qry_cra) or die(ora_errorcode());
$row_cra = OCIFetch($qry_cra);
$CodCra = OCIresult($qry_cra, 1);
OCIFreeCursor($qry_cra);

$qry_msg = OCIParse($oci_conecta, "SELECT CME_AUTOR, 
										   CME_TITULO, 
										   TO_CHAR(CME_FECHA_INI,'dd/Mon/yyyy'),
										   CME_HORA_INI, 
										   TO_CHAR(CME_FECHA_FIN,'dd/Mon/yyyy'), 
										   CME_MENSAJE
									  FROM accoormensaje
									  WHERE CME_CRA_COD = $CodCra
										AND CME_TIPO_USU IN(0,30)
										AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) BETWEEN TO_NUMBER(TO_CHAR(CME_FECHA_INI,'yyyymmdd')) AND TO_NUMBER(TO_CHAR(CME_FECHA_FIN,'yyyymmdd'))
										AND EXISTS (SELECT unique(car_doc_nro_iden)
													  FROM acasperi, accarga
													 WHERE ape_estado = 'A'
													   AND ape_ano = car_ape_ano
													   AND ape_per = car_ape_per
													   AND car_cra_cod = $CodCra
													   AND car_doc_nro_iden =".$_SESSION['usuario_login'].")");
OCIExecute($qry_msg) or die(ora_errorcode());
$row_msg = OCIFetch($qry_msg);
?>