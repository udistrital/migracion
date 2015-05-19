<?PHP
/*
$netop = OCIParse($oci_conecta, "SELECT lne_valor
  								   FROM mntpe.liquidaneto
 								  WHERE lne_emp_cod = ".$_SESSION["fun_cod"]);
OCIExecute($netop) or die(Ora_ErrorCode());
$rownetop = OCIFetch($netop);
*/
$Dev = "SELECT sum(liq_valor)
	FROM mntpe.prliquid
	WHERE liq_emp_cod  = ".$_SESSION["fun_cod"]."
	AND liq_ano = '".$_REQUEST['anio']."'
	AND liq_mes = '".$_REQUEST['mes']."'
	AND liq_tq_cod = '".$_REQUEST['tipq']."'
	AND liq_con_tipo = 1";

$Des = "SELECT sum(liq_valor)
	FROM mntpe.prliquid
	WHERE liq_emp_cod  =".$_SESSION["fun_cod"]."
	AND liq_ano = '".$_REQUEST['anio']."'
	AND liq_mes = '".$_REQUEST['mes']."'
	AND liq_tq_cod = '".$_REQUEST['tipq']."'
	AND liq_con_tipo = 2";
?>