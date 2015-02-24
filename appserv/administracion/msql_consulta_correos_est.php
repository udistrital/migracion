<?php
$Qrycorreo = OCIParse($oci_conecta, "SELECT est_cod,est_nombre,eot_email,eot_email_ins,est_estado_est
									   FROM acestotr,acest
									  WHERE eot_cod = est_cod
									    AND eot_email IS NULL
									    AND eot_email_ins IS NOT NULL
									    AND est_estado_est IN('A','B','H','J','L','T','V')
										ORDER BY 2");
OCIExecute($Qrycorreo) or die(Ora_ErrorCode());
$Rowcorreo = OCIFetch($Qrycorreo);
?>