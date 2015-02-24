<?PHP
$per_consulta = OCIParse($oci_conecta, "SELECT ape_ano, ape_per FROM acasperiadm WHERE ape_estado='X'");
OCIExecute($per_consulta, OCI_DEFAULT) or die(Ora_ErrorCode());
$row = OCIFetch($per_consulta);
$ano = OCIResult($per_consulta, 1);
$per = OCIResult($per_consulta, 2);
if($per==1) $peri ='PRIMER';
if($per==3) $peri ='SEGUNDO';
$periodo = $peri.' PERÍODO ACADÉMICO DEL '.$ano;
OCIFreeCursor($per_consulta);
?>