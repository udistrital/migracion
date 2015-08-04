<?PHP
/*
session_name($usuarios_sesion);
session_start();

$per_consulta = OCIParse($oci_conecta,"SELECT ape_ano, ape_per FROM acasperiadm WHERE ape_estado='X'");
OCIExecute($per_consulta, OCI_DEFAULT) or die(Ora_ErrorCode());
$row = OCIFetch($per_consulta);
$ano = OCIResult($per_consulta, 1);
$per = OCIResult($per_consulta, 2);
OCIFreeCursor($per_consulta);

$confec = OCIParse($oci_conecta, "SELECT to_char(SYSDATE, 'dd/mm/yyyy') FROM dual");
OCIExecute($confec) or die(Ora_ErrorCode());
$rows = OCIFetch($confec);
$fecha = OCIResult($confec, 1);
OCIFreeCursor($confec);

$conhor = OCIParse($oci_conecta, "SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual");
OCIExecute($conhor) or die(Ora_ErrorCode());
$rows = OCIFetch($conhor);
$hora = OCIResult($conhor, 1);
$hoy = $fecha.' '.$hora;

OCIFreeCursor($conhor);

$ins_usu = OCIParse($oci_conecta, "INSERT INTO mntac.acrecbanasplog VALUES(:bano, :bper, :brefpago, :bnroiden, to_date(:bfecha, 'dd/mm/yyyy hh24:mi:ss'), :bmaq)");				  
OCIBindByName($ins_usu, ":bano", $ano);
OCIBindByName($ins_usu, ":bper", $per);
OCIBindByName($ins_usu, ":brefpago", OCIResult($consulta, 5));
OCIBindByName($ins_usu, ":bnroiden", OCIResult($consulta, 1));
OCIBindByName($ins_usu, ":bfecha", $hoy);
OCIBindByName($ins_usu, ":bmaq", $_SERVER['REMOTE_ADDR']);
OCIExecute($ins_usu) or die(Ora_ErrorCode());
OCICommit($oci_conecta);
*/
?>
