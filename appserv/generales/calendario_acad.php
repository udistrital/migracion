<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'msql_ano_per.php');
?>
<html>
<head>
<title>Calendario Académico</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>
<body>
<?PHP
$qry_cal = OCIParse($oci_conecta, "SELECT CAL_ANIO, 
										  CAL_PERIODO, 
										  CAL_COD_EVENTO, 
										  CAL_DES_EVENTO, 
										  to_char(CAL_FEC_INI, 'dd-Mon-yyyy'),
										  to_char(CAL_FEC_FIN, 'dd-Mon-yyyy'), 
										  CAL_ESTADO, 
										  CAL_ACDES_EVENTO
									 FROM ACCALENDARIO
									ORDER BY 1");
OCIExecute($qry_cal, OCI_DEFAULT);
$row = OCIFetch($qry_cal);
if($row != 1) die('<center><h3>No hay registros para esta consulta.</h3></center>');

if($per == 1) $peri = 'PRIMER';
if($per == 3) $peri = 'SEGUNDO';
?>

<div align="center">
  <table width="700" align="center" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td align="center">
        <samp class="Estilo5">CALENDARIO PARA EL <? print $peri;?> PER&Iacute;ODO ACAD&Eacute;MICO DEL A&Ntilde;O <? print $ano;?></samp>
      </td>
    </tr>
  </table>
<p></p>
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr class="tr">
    <td width="30"  align="center">ID</td>
    <td width="510" align="center">ACTIVIDAD</td>
    <td width="80" align="center">FEC. INICIAL</td>
    <td width="80" align="center">FEC. FINAL</td>
  </tr>
<?php
do{  
   echo'<tr>
    <td width="30" align="right">'.OCIresult($qry_cal, 3).'</td>
    <td width="510" align="justify">'.OCIresult($qry_cal, 4).'</td>
    <td width="80" align="right">'.OCIresult($qry_cal, 5).'</td>
    <td width="80" align="right">'.OCIresult($qry_cal, 6).'</td></tr>';
}while(OCIFetch($qry_cal));
cierra_bd($qry_cal,$oci_conecta);
?>
</table>
<table width="700" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php fu_pie(); ?>
</div>
</body>
</html>