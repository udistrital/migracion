<?PHP
$QryEstR = OCIParse($oci_conecta, "SELECT cla_codigo,est_nombre,cra_abrev,est_estado_est,cla_estado 
								    FROM geclaves,acest,accra
								   WHERE est_cod = cla_codigo
								     AND cra_cod = est_cra_cod
								     AND cla_tipo_usu = 51
								     AND cla_estado = 'I'
								     AND EXISTS (SELECT est_cod
												   FROM acest
												  WHERE geclaves.cla_codigo = est_cod
												    AND est_estado_est IN('A','B','H','L'))
								   ORDER BY 3");
OCIExecute($QryEstR) or die(Ora_ErrorCode());
$RowEstR = OCIFetch($QryEstR);
?>