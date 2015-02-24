<?PHP
$estado = 'W';
$QryUpdateEstado = OCIParse($oci_conecta, "UPDATE geclaves 
											  SET cla_estado = :bestado
		  									WHERE cla_codigo = ".$_SESSION['usuario_login']."
											  AND cla_tipo_usu = ".$_SESSION['usuario_nivel']); 
OCIBindByName($QryUpdateEstado, ":bestado", $estado);
OCIExecute($QryUpdateEstado);
OCICommit($oci_conecta);
OCIFreeCursor($QryUpdateEstado);
OCILogOff($oci_conecta);
?>