<?php	
//LLAMADO DE adm_admon.php, adm_actualiza.php					   
$datos = OCIParse($oci_conecta,"SELECT CLA_CODIGO,
       								   CLA_CLAVE,
	   								   CLA_TIPO_USU,
	   								   CLA_ESTADO,
	   								   fua_invierte_nombre(EST_NOMBRE), 
									   EST_NRO_IDEN
  								  FROM GECLAVES,ACEST
 							     WHERE CLA_CODIGO = EST_COD
   								   AND CLA_CODIGO =".$_SESSION["codigo"]."
								   AND CLA_TIPO_USU =".$_SESSION["tipo"]."
   								   AND EST_ESTADO_EST IN('A','B','H','J','L','T','V')");
OCIExecute($datos) or die(Ora_ErrorCode());
$row = OCIFetch($datos);
?>