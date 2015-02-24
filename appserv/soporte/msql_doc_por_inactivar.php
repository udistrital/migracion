<?PHP
$cursor = OCIParse($oci_conecta, "SELECT doc_nro_iden,
									     doc_nombre||' '||doc_apellido,
									     doc_estado,
									     cla_estado
								    FROM acdocente,geclaves
								   WHERE doc_nro_iden = cla_codigo
								     AND cla_tipo_usu = 30
								     AND doc_estado = 'I'
								     AND cla_estado = 'A'
								  ORDER BY 1");
OCIExecute($cursor) or die(Ora_ErrorCode());
$RowDoc = OCIFetch($cursor);
?>