<?PHP

$cadena_sql= "SELECT 
   tvi_nombre,
   actividades,  
   carga,
   decode(tvi_cod,1,'PL',6,'PL','VE'),
   tvi_cod  
FROM  
(
 SELECT tvi_cod,
    tvi_nombre,
   (SELECT COUNT(CLE_HORA)
    FROM v_accargalectiva
    WHERE actipvin.TVI_COD = cle_tvi_cod
        AND cle_doc_nro_iden = ".$_SESSION['usuario_login']." ) carga,
   (SELECT COUNT(DPT_HORA) numactividades
    FROM acasperi, acdocplantrabajo
    WHERE ape_estado = 'A'
       AND actipvin.tvi_cod= dpt_tvi_cod
        AND ape_ano = DPT_APE_ANO
        AND ape_per = DPT_APE_PER
        AND DPT_DOC_NRO_IDEN = ".$_SESSION['usuario_login'].") actividades
 FROM actipvin
)
WHERE (carga+actividades) <> 0
ORDER BY tvi_cod ASC"; 




$QryHor = OCIParse($oci_conecta,$cadena_sql);



OCIExecute($QryHor, OCI_DEFAULT);// or die(Ora_ErrorCode());

$i=0;
while(OCIFetch($QryHor))
{
	$tipoVinculacion[(int)OCIResult($QryHor,5)]=OCIResult($QryHor,1);
	$NroAct[(int)OCIResult($QryHor,5)] = OCIResult($QryHor,2);
	$NroLec[(int)OCIResult($QryHor,5)] = OCIResult($QryHor,3);
	$NroTip[(int)OCIResult($QryHor,5)] = OCIResult($QryHor,4);
	$i++;
}
/*
echo "<pre>";
//var_dump($NroAct);
var_dump($NroLec);
echo "</pre>";
*/
//$RowHor = OCIFetch($QryHor);

OCIFreeCursor($QryHor);


?>