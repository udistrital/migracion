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
        AND cle_doc_nro_iden = ".$_REQUEST['HtpC']." ) carga,
   	(SELECT COUNT(DPT_HORA) numactividades
   	FROM acasperi, acdocplantrabajo
    	WHERE ape_estado = 'A'
       	AND actipvin.tvi_cod= dpt_tvi_cod
        AND ape_ano = DPT_APE_ANO
        AND ape_per = DPT_APE_PER
        AND DPT_DOC_NRO_IDEN = ".$_REQUEST['HtpC'].") actividades
 	FROM actipvin
	)
	WHERE (carga+actividades) <> 0
	ORDER BY tvi_cod ASC"; 
//echo $cadena_sql;
$QryHor=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

$i=0;
while(isset($QryHor[$i][0]))
{
	$tvi_cod=$QryHor[$i][4]; //echo "otra cosa jejej".$tvi_cod;
	$tipoVinculacion[(int)$tvi_cod]=$QryHor[$i][0];
	$NroAct[(int)$tvi_cod] = $QryHor[$i][1];
	$NroLec[(int)$tvi_cod] = $QryHor[$i][2];
	$NroTip[(int)$tvi_cod] = $QryHor[$i][3];
$i++;
}
?>