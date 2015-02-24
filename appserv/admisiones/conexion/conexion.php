<?PHP
include('interno.php');

session_name($usuarios_sesion);
//session_start();

$WUsu = OCIParse($interno_conecta, "SELECT * FROM $tab_interna WHERE auw_codigo = 3 AND auw_estado = 'A'");
OCIExecute($WUsu) or die(ora_errorcode());

$RoWu = OCIFetch($WUsu);
$_SESSION['u1'] = OCIResult($WUsu,2);
$_SESSION['c2'] = OCIResult($WUsu,3);
$_SESSION['b3'] = OCIResult($WUsu,4);
$oci_conecta = ocilogon($_SESSION['u1'], $_SESSION['c2'], $_SESSION['b3']);

OCIFreeCursor($WUsu);
OCILogOff($interno_conecta);
if(!$oci_conecta) header("Location: ../../err/aviso.php");

?>
