<?PHP
$cadena_sql="SELECT
	tvi_nombre,
	actividades,
	carga,
	DECODE(tvi_cod,1,'PL',6,'PL','VE'),
	tvi_cod
	FROM
	(
	SELECT tvi_cod,
		tvi_nombre,
	(SELECT SUM(car_nro_hrs)
		FROM acasperi, acdoctipvin, accarga
		WHERE ape_estado = 'A'
		AND ape_ano = dtv_ape_ano
		AND ape_per = dtv_ape_per
		AND actipvin.tvi_cod = dtv_tvi_cod
		AND dtv_doc_nro_iden = ".$_SESSION['usuario_login']."
		AND dtv_ape_ano = car_ape_ano
		AND dtv_ape_per = car_ape_per
		AND dtv_cra_cod = car_cra_cod
		AND dtv_doc_nro_iden = car_doc_nro_iden
		AND car_estado = 'A') carga,
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