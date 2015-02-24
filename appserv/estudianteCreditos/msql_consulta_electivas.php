<?PHP
$asicod = OCIParse($oci_conecta, "SELECT emi_asi_cod, 
									   	 emi_asi_nombre ,
									   	 emi_nro_sem,
									   	 emi_pen_nro
								  	FROM v_acestmatelectivas,acest
								   WHERE emi_est_cod = ".$_SESSION['usuario_login']."
								   	 AND emi_est_cod = est_cod
								   	 AND est_estado_est IN('A','B')
								ORDER BY 3,2");
OCIExecute($asicod) or die(ora_errorcode());
$rowc = OCIFetch($asicod);
?>