<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(51);

$ConObs = OCIParse($oci_conecta, "SELECT EOB_APE_ANO, EOB_APE_PER, EOB_OBSERVACION
								    FROM ACESTOBS
								   WHERE EOB_EST_COD = ".$_SESSION['usuario_login']."
								     AND EOB_ESTADO = 'A'
									 ORDER BY 1,2");
OCIExecute($ConObs);
$RowConObs = OCIFetch($ConObs);
if($RowConObs != 1) header("Location: ../err/err_sin_observaciones.php");
?>
<html>
<head>
<title>Observaciones</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="10" leftmargin="27">			   
<?php
ob_start();
   
print'<p align="center"><span class="Estilo2">OBSERVACIONES A LA HOJA DE VIDA</span></p>';
print'<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">A&ntilde;o</td>
    <td align="center">Per</td>
    <td align="center">Observaci&oacute;n</td>
  </tr>';
do{
   print'<tr>
    <td align="center">'.OCIResult($ConObs,1).'</td>
    <td align="center">'.OCIResult($ConObs,2).'</td>
    <td align="left">'.OCIResult($ConObs,3).'</td>
  </tr>';
}while(OCIFetch($ConObs));
print'</table>';
cierra_bd($ConObs,$oci_conecta);
ob_end_flush();
?>
</body>
</html>