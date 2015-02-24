<?php
//LLAMADO DE adm_actualiza_dec.php, adm_actualiza_coor.php, adm_actualiza_doc.php
$consulta = OCIParse($oci_conecta, "SELECT cla_codigo,
										   doc_nombre, 
	   									   doc_apellido,
	   									   cla_tipo_usu,
	   									   cla_estado
  									  FROM geclaves,acdocente
 									 WHERE cla_codigo = doc_nro_iden
   									   AND cla_codigo = ".$_SESSION["codigo"]."
 								  ORDER BY doc_nombre");
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);
?>