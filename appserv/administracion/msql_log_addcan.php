<?php
//LLAMADO DE adm_admon.php, adm_actualiza.php
$addcan = "SELECT clo_cla_codigo,
	fua_invierte_nombre(est_nombre),
	clo_url,
	clo_ip,
	(CASE WHEN CLO_TRANSACCION= 'AD' THEN 'Adicion' WHEN CLO_TRANSACCION= 'CG' THEN 'Cambio de grupo' WHEN CLO_TRANSACCION= 'CA' THEN 'Cancelacion' ELSE CLO_TRANSACCION::text END),
	TO_CHAR(clo_fecha, 'dd-Mon-YYYY'),
	clo_hora
	FROM accondorlog,acest
	WHERE clo_cla_codigo = est_cod
	AND clo_cla_codigo = ".$_REQUEST['estcod'];

?>