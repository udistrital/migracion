<?PHP
$confec = OCIParse($oci_conecta, "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'yyyymmdd')) FROM dual");
OCIExecute($confec, OCI_DEFAULT);
$rows = OCifetch($confec);

$fechahoy = OCIResult($confec, 1);
OCIFreeCursor($confec);

$cod_consulta = "SELECT to_number(TO_CHAR(ACE_FEC_INI, 'yyyymmdd')),
						to_number(TO_CHAR(ACE_FEC_FIN, 'yyyymmdd'))
  				   FROM accaleventos
 				  WHERE ACE_CRA_COD =".$_SESSION["C"]."
   					AND ACE_COD_EVENTO = 5";
$consulta = OCIParse($oci_conecta, $cod_consulta);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCifetch($consulta);

if(($fechahoy < OCIResult($consulta, 1) || $fechahoy > OCIResult($consulta, 2)) || (OCIResult($consulta, 1) == " ") || OCIResult($consulta, 2) == " "){
  	$not = "readonly";
	$obs = "readonly";
}
OCIFreeCursor($consulta);
?>