<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(51);

$ConDeudor = OCIParse($oci_conecta, "SELECT CPTO_NOMBRE,DEU_MATERIAL, DEU_ANO, DEU_PER, TO_CHAR(DEU_FECHA, 'DD-MON-YYYY'), DEU_MULTA
									   FROM ACDEUDORES,ACCONCEPTO
									  WHERE CPTO_COD = DEU_CPTO_COD 
									    AND DEU_EST_COD = ".$_SESSION['usuario_login']."
									    AND DEU_ESTADO = 'A'
										ORDER BY 3,4");
OCIExecute($ConDeudor);
$RowConDeudor = OCIFetch($ConDeudor );
if($RowConDeudor != 1) header("Location: ../err/err_sin_deuda.php");
?>
<html>
<head>
<title>Deudor</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="10" leftmargin="27">			   
<?php
ob_start();

print'<p align="center"><span class="Estilo2">DEUDAS CON LA UNIVERSIDAD</span></p>';
print'<table width="90%"  border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">Dependencia</td>
    <td align="center">Material</td>
    <td align="center">A&ntilde;o</td>
    <td align="center">Per</td>
    <td align="center">Fecha</td>
    <td align="center">Multa</td>
  </tr>';
do{  
  print'<tr>
    <td>'.OCIResult($ConDeudor,1).'</td>
    <td>'.OCIResult($ConDeudor,2).'</td>
    <td align="center">'.OCIResult($ConDeudor,3).'</td>
    <td align="center">'.OCIResult($ConDeudor,4).'</td>
    <td align="right">'.OCIResult($ConDeudor,5).'</td>
    <td align="right">'.number_format(OCIResult($ConDeudor,6)).'</td>
  </tr>';
}while(OCIFetch($ConDeudor));
print'</table>';
cierra_bd($ConDeudor,$oci_conecta);
ob_end_flush();
?>
</body>
</html>