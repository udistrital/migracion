<?php
//LLAMADO DE adm_admon.php, adm_actualiza.php
$datos = OCIParse($oci_conecta,"SELECT CLA_CODIGO,
				 	   				   CLA_CLAVE,
					   				   CLA_TIPO_USU,
					   				   CLA_ESTADO,
									   ".$nom.",
									   CLA_CODIGO
	  			  				  FROM ".$tab."
 				 				 WHERE CLA_CODIGO =".$_SESSION["codigo"]."
								   AND CLA_TIPO_USU =".$_SESSION["tipo"]."
								   ".$and);
OCIExecute($datos) or die(Ora_ErrorCode());
$row = OCIFetch($datos);
?>