<?php
//LLAMADO DE adm_actualiza_datos_est.php
$consulta = OCIParse($oci_conecta, "SELECT EST_COD,
										   EST_NOMBRE,
       									   EST_NRO_IDEN,
       									   EST_DIRECCION,
	   									   EST_TELEFONO,
	   									   EST_ZONA_POSTAL,
										   EST_SEXO,
	   									   EOT_TIPOSANGRE,
	   									   EOT_RH,
										   EOT_EMAIL,
										   EOT_ESTADO_CIVIL,
										   TEC_NOMBRE,
										   TO_CHAR(EOT_FECHA_NAC, 'DD/MM/YYYY'),
										   EOT_COD_LUG_NAC,
										   LUG_NOMBRE,
										   CRA_NOMBRE,
										   EST_VALOR_MATRICULA,
										   ESTADO_NOMBRE,
										   EOT_EMAIL_INS
  									  FROM ACEST,ACESTOTR,GETIPESCIVIL,GELUGAR,ACCRA,ACESTADO
 								 	 WHERE EST_COD = EOT_COD
									   AND CRA_COD = EST_CRA_COD
									   AND TEC_CODIGO = EOT_ESTADO_CIVIL
									   AND LUG_COD = EOT_COD_LUG_NAC
									   AND ESTADO_COD = EST_ESTADO_EST
   									   AND EST_COD =".$_POST['estcod']);
OCIExecute($consulta) or die(Ora_ErrorCode());
$rowest = OCIFetch($consulta);
?>