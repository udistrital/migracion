<?php
//LLAMADO DE adm_admon.php, adm_actualiza.php
$addcan = "SELECT clo_cla_codigo,
	fua_invierte_nombre(est_nombre),
	clo_url,
	clo_ip,
	decode(clo_transaccion, 'AD', 'Adicion', 'CG', 'Cambio de Grupo', 'CA', 'Cancelacion'),
	TO_CHAR(clo_fecha, 'dd-Mon-YYYY'),
	clo_hora
	FROM accondorlog,acest
	WHERE clo_cla_codigo = est_cod
	AND clo_cla_codigo = ".$_REQUEST['estcod'];

?>