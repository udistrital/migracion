<?php
//LLAMADO DE adm_admon.php, adm_actualiza.php
$datos = OCIParse($oci_conecta,"SELECT CLA_CODIGO,
       								   CLA_CLAVE,
	   								   CLA_TIPO_USU,
	   								   CLA_ESTADO,
	   								   LTRIM(DOC_NOMBRE||' '||DOC_APELLIDO), 
									   DOC_NRO_IDEN
  								  FROM GECLAVES,ACDOCENTE
 							     WHERE CLA_CODIGO = DOC_NRO_IDEN
   								   AND CLA_CODIGO = $codigo
   								   AND DOC_ESTADO = 'A'");
OCIExecute($datos) or die(Ora_ErrorCode());
$row = OCIFetch($datos);
?>