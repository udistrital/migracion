<?PHP 
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
fu_tipo_user(51);
?>
<html>
<head>
<TITLE>Pago Diferido</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<link href="../script/estinx.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFCC" topmargin="10" leftmargin="0">

<?php
ob_start();
fu_cabezote("DETALLE DE LA MATRICULA");

$usuario = $_SESSION['usuario_login'];
$nivel = $_SESSION["usuario_nivel"];

$RelPago = OCIParse($oci_conecta, "SELECT RBA_BAN_COD,
										  BAN_NOMBRE,
										  RBA_OFICINA,
										  RBA_DIA||'/'||MES_ABREV||'/'||RBA_ANO FECHA,
										  RBA_VALOR, 
										  RBA_SECUENCIA
									 FROM ACRECBAN,ACBANCO,GEMES
									WHERE RBA_COD = $usuario
									  AND BAN_COD = RBA_BAN_COD
									  AND BAN_ESTADO = 'A'
									  AND MES_COD = RBA_MES
									ORDER BY 3,4,5 DESC");
OCIExecute($RelPago);
$RowRelPago = OCIFetch($RelPago);

$conpago = OCIParse($oci_conecta, "SELECT EMA_CUOTA, 
										  EMA_VALOR, 
										  TO_CHAR(EMA_FECHA_ORD, 'DD-Mon-YYYY'), 
										  TO_CHAR(EMA_FECHA_EXT, 'DD-Mon-YYYY')
								     FROM ACESTMAT
								    WHERE (EMA_EST_COD = $usuario
								   	  AND EMA_ANO = $ano
								   	  AND EMA_PER = $per
								   	  AND EMA_ESTADO='A')
								   AND ROWNUM=1");
OCIExecute($conpago);
$row = OCIFetch($conpago);

$QryPag = OCIParse($oci_conecta, "SELECT emb_est_cod est, 
										 emb_valor_matricula vr_mat, 
										 vlr_seguro
									FROM V_ACESTMATBRUTO,v_valor_seguro
								   WHERE emb_est_cod = $usuario");
OCIExecute($QryPag);
$RowPag = OCIFetch($QryPag);

$matricula = OCIResult($QryPag, 2);
$seguro = OCIResult($QryPag, 3);
$totPago = ($matricula+$seguro);

$QryDes = OCIParse($oci_conecta, "SELECT emb_est_cod,
									     Pck_Pr_Detalle_Matricula.Fua_Ver_Certificado_Electoral(emb_est_cod) cer_ele, 
									     Pck_Pr_Detalle_Matricula.Fua_Ver_Motivo_Exento(emb_est_cod) mot_exe, 
									     Pck_Pr_Detalle_Matricula.Fua_Ver_Valor_Exepcion(emb_est_cod) val_exe 
								    FROM V_ACESTMATBRUTO
								   WHERE emb_est_cod = $usuario");
OCIExecute($QryDes);
$RowDes = OCIFetch($QryDes);

$cerelectoral = OCIResult($QryDes, 2);
$motExe = OCIResult($QryDes,3);

if(OCIResult($QryDes, 4) == 0) $otroDes = '&nbsp;';
else $otroDes = '$'.number_format(OCIResult($QryDes, 4));

$totDes = ($cerelectoral+$otroDes);
$valorMatricula = ($totPago-$totDes);
?>
<p></p>
<table width="250" border="1" align="center" cellpadding="0" cellspacing="0">
<caption>DETALLE DE LA MATRICULA - periodo acad&eacute;mico: <? print $ano.'-'.$per; ?></caption>
  <tr>
    <td colspan="2" align="center" valign="top">
	<!-- TABLA DE PAGOS -->
	<table width="250" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr  class="tr">
    <td colspan="2" align="center">DETALLE DE PAGOS</td>
    </tr>
    <tr>
    <td width="150" align="right" class="Estilo3">MATRICULA</td>
    <td width="100" align="right">$<? print number_format($matricula);?></td>
    </tr>
    <tr>
    <td align="right" class="Estilo3">SEGURO</td>
    <td align="right">$<? print number_format($seguro);?></td>
    </tr>
    </table>
	<!-- FIN TABLA DE PAGOS -->
	</td>
    <td colspan="2" align="center" valign="top">
	
	<!-- TABLA DE DESCUENTOS -->
	<table width="250" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr class="tr">
    <td colspan="2" align="center">DESCUENTOS</td>
    </tr>
    <tr>
    <td width="150" align="right" class="Estilo3">CERTIFICADO ELECTORAL </td>
    <td width="100" align="right">$<? print number_format($cerelectoral);?></td>
    </tr>
	<tr>
    <td width="150" align="right" class="Estilo3"><? print $motExe;?></td>
    <td width="100" align="right"><? print $otroDes;?></td>
    </tr>
    </table>
	<!-- FIN TABLA DE DESCUENTOS -->
	</td>
  </tr>
  <tr>
    <td width="150" align="right" class="Estilo14">Total Pagos:</td>
    <td width="100" align="right">$<? print number_format($totPago);?></td>
    <td width="150" align="right"><SPAN class=Estilo14>Total Descuentos:</SPAN></td>
    <td width="100" align="right">$<? print number_format($totDes);?></td>
  </tr>
  <tr>
    <td align="right" class="Estilo14">Valor Matricula:</td>
    <td align="right">$<? print number_format($valorMatricula);?></td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p></p>
<?PHP
echo'<table border="1" width="400" align="center" cellpadding="2" cellspacing="0">
<tr class="tr"><td width="100%" colspan="4" align="center">FORMA DE PAGO</td></tr>
<tr class="td"><td width="25%" align="center">No. Cuota</td>
<td width="25%" align="center">Valor</td>
<td width="25%" align="center">Fecha</td></tr>';
$NetoMat=0;
do{
   if(OCIResult($conpago, 1) == 1) 
      $vlrcta = OCIResult($conpago, 2)+$seguro;
   else $vlrcta = OCIResult($conpago, 2);
   echo'<tr><td width="25%" align="center">'.OCIResult($conpago, 1).'</td>
   <td width="25%" align="right">$'.number_format($vlrcta).'</td>
   <td width="25%" align="right">'.OCIResult($conpago, 3).'</td></tr>';   
   $NetoMat = $NetoMat+$vlrcta;
}while(OCIFetch($conpago));
OCIFreeCursor($QryPag);
OCIFreeCursor($QryDes);
?>
<tr><td width="25%" align="right" class="Estilo14">Neto a Pagar:</td>
   <td width="25%" align="right">$<? echo number_format($NetoMat); ?></td>
   <td width="25%" align="center">&nbsp;</td></tr></table>
   <p align="center">El valor del seguro no se difiere, se le suma al valor de la primera cuota. </p>
<p></p>
<table width="760" border="1" align="center" cellpadding="0" cellspacing="0">
<caption><span class="Estilo5">HIST&Oacute;RICO DE PAGOS DE MATR&Iacute;CULA<caption>
  <tr class="tr" align="center">
    <td>C&oacute;d</td>
    <td width="209">Banco</td>
    <td width="204">Sucursal</td>
    <td width="120">Fecha</td>
    <td width="100">Valor</td>
    <td width="90">C.Pago Nro.</td>
  </tr>
<?PHP
do{
print'<tr>
    <td align="right">'.OCIResult($RelPago, 1).'</td>
    <td align="left">'.OCIResult($RelPago, 2).'</td>
    <td align="left">'.OCIResult($RelPago, 3).'</td>
    <td align="center">'.OCIResult($RelPago, 4).'</td>
    <td align="right">$'.number_format(OCIResult($RelPago, 5)).'</td>
    <td align="right">'.OCIResult($RelPago, 6).'</td>
  </tr>';
}while($RowRelPago = OCIFetch($RelPago));
print'</table>
<p></p>';
OCIFreeCursor($RelPago);
OCIFreeCursor($conpago);
fu_pie();
ob_end_flush();
?>  
</body>
</html>