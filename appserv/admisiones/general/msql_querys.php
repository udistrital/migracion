<?PHP
$QryMed = OCIParse($oci_conecta, "SELECT med_cod, med_nombre FROM acmedio WHERE med_estado = 'A' ORDER BY med_nombre"); 
OCIExecute($QryMed) or die(Ora_ErrorCode());
$RowMed = OCIFetch($QryMed);

$QryCra = OCIParse($oci_conecta, "SELECT cra_cod, cra_nombre FROM accra WHERE cra_estado = 'A' AND cra_se_ofrece = 'S' ORDER BY cra_dep_cod, cra_cod, cra_nombre"); 
OCIExecute($QryCra) or die(Ora_ErrorCode());
$RowCra = OCIFetch($QryCra);

$QryTCra = OCIParse($oci_conecta, "SELECT cra_cod, cra_nombre FROM accra WHERE cra_estado = 'A' AND cra_se_ofrece = 'S' ORDER BY cra_nombre"); 
OCIExecute($QryTCra) or die(Ora_ErrorCode());
$RowTCra = OCIFetch($QryTCra);

$QryLoc = OCIParse($oci_conecta, "SELECT loc_nro, loc_nombre
									FROM aclocalidad,acasperiadm
									WHERE ape_ano = loc_ape_ano
									  AND ape_per = loc_ape_per
									  AND ape_estado = 'X'
									  AND loc_estado = 'A'
									ORDER BY loc_nombre"); 
OCIExecute($QryLoc) or die(Ora_ErrorCode());
$RowCra = OCIFetch($QryLoc);

$QryEstrato = OCIParse($oci_conecta, "SELECT str_nro, str_nombre
										FROM acestrato,acasperiadm
										WHERE ape_ano = str_ape_ano
										  AND ape_per = str_ape_per
										  AND ape_estado = 'X'
										  AND str_estado = 'A'
										ORDER BY str_nombre"); 
OCIExecute($QryEstrato) or die(Ora_ErrorCode());
$RowCra = OCIFetch($QryEstrato);

$QryLocCol = OCIParse($oci_conecta, "SELECT loc_nro, loc_nombre
									FROM aclocalidad,acasperiadm
									WHERE ape_ano = loc_ape_ano
									  AND ape_per = loc_ape_per
									  AND ape_estado = 'X'
									  AND loc_estado = 'A'
									ORDER BY loc_nombre"); 
OCIExecute($QryLocCol) or die(Ora_ErrorCode());
$RowCra = OCIFetch($QryLocCol);

$QryTipIns = OCIParse($oci_conecta, "SELECT ti_cod, ti_nombre FROM actipins WHERE ti_cod IN(25,26) AND ti_estado = 'A' ORDER BY ti_nombre"); 
OCIExecute($QryTipIns) or die(Ora_ErrorCode());
$RowTipIns = OCIFetch($QryTipIns);

$QryTipInsEx = OCIParse($oci_conecta, "SELECT ti_cod, ti_nombre FROM actipins WHERE ti_cod = 20 AND ti_estado = 'A' ORDER BY ti_nombre"); 
OCIExecute($QryTipInsEx) or die(Ora_ErrorCode());
$RowTipInsEx = OCIFetch($QryTipInsEx);
?>