<?php
$QryEstE = "SELECT cla_codigo,est_nombre,est_estado_est,cla_estado,cra_abrev
	FROM geclaves,acest,accra
	WHERE est_cod = cla_codigo
	AND cra_cod = est_cra_cod
	AND cla_tipo_usu = 51
	AND cla_estado IN('A','I')
	AND EXISTS (SELECT est_cod
	FROM acest
	WHERE geclaves.cla_codigo = est_cod
	AND est_estado_est = 'E')
	ORDER BY 5,2";
?>