<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');

//echo "hsdfasd";

$log = "<embed width='57' height='58' src='../../img/cdr.swf'>";

if($_POST['cred']==""){
   header("Location: ../err/err_crednull.php");
   exit;
}
elseif(!is_numeric($_POST['cred'])){
   	   header("Location: ../err/err_crednotnumner.php");
   	   exit;
}
$QryCred = OCIParse($oci_conecta, "SELECT ape_ano,
										  decode(ape_per, 1,'PRIMERO', 3,'SEGUNDO'),
										  cra_cod,
										  cra_nombre,
										  asp_puesto,
										  asp_cred credencial,
										  (trim(asp_nombre)||' '||trim(asp_apellido)),
										  DECODE(asp_tip_icfes,'A','ANTIGUO','N','NUEVO'),
										  asp_ptos_cal,
										  ti_nombre,
										  DECODE(asp_admitido,'A','ADMITIDO','O','OPCIONADO','NO ADMITIDO')
									 FROM acasperiadm, accra, actipins, acasp
								    WHERE ape_estado = 'X'
									  AND ape_ano = asp_ape_ano
									  AND ape_per = asp_ape_per
									  AND cra_cod = asp_cra_cod
									  AND ti_cod = asp_ti_cod
									  AND asp_cred =".$_POST['cred']);
									  
									  
OCIExecute($QryCred) or die(Ora_ErrorCode());
$RowCred = OCIFetch($QryCred);

if($RowCred != 1){
   header("Location: ../err/err_aspirante.php");
   exit;
}

if(OCIResult($QryCred, 11) == 'ADMITIDO')
   $OP = '<font color="#009900" size="3"><b>ADMITIDO</b></font>';
elseif(OCIResult($QryCred, 11) == 'OPCIONADO')
	   $OP = '<font color="#FB9524" size="3"><b>OPCIONADO</b></font>';
else
	$OP = '<font color="#FF0000" size="2"><b>NO ADMITIDO</b></font>';
?>
<html>
<head>
<title>Comité de Admisiones</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<script language="JavaScript" type="text/javascript" src="../../script/KeyIntro.js"></script>
<link href="../../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<p>&nbsp;</p>
<table width="750" height="506" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98" height="124">
	  <a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco José de Caldas" target="_self">
	  <img src="../../img/EscudoUD.gif" width="90" height="110" border="0"></a>
	</td>
    <td width="677" align="center">
	  <br><img src="../../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA - COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br><? print $peri; ?> PER&Iacute;ODO ACAD&Eacute;MICO DE <? print $ano; ?></span>
	</td>
    <td width="97" align="center" title="Sistema de Información Cóndor"><? echo $log ?><span class="Estilo9"><br>CÓNDOR</span></td>
  </tr>
   <tr>
   <td height="309" colspan="3">
   
<?PHP 
print'<table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="80" align="center"><span class="Estilo5">CREDENCIAL</span></td>
    <td width="450" align="center"><span class="Estilo5">NOMBRE</span></td>
  </tr>
  <tr>
    <td align="center">'.OCIResult($QryCred, 6).'</td>
    <td align="left">'.OCIResult($QryCred, 7).'</td>
  </tr>
  </table><p></p>
  
  <table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="80" align="center"><span class="Estilo5">C&Oacute;DIGO</span></td>
    <td width="450" align="center"><span class="Estilo5">PROYECTO CURRICULAR</span></td>
  </tr>
  <tr>
    <td align="center">'.OCIResult($QryCred, 3).'</td>
    <td align="left">'.OCIResult($QryCred, 4).'</td>
  </tr>
</table><p></p>

<table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="80" align="center"><span class="Estilo5">A&Ntilde;O</span></td>
    <td align="center"><span class="Estilo5">PERIODO</span></td>
    <td align="center"><span class="Estilo5">TIPO DE INSCRIPCIÓN</span></td>
  </tr>
  <tr>
    <td align="center">'.OCIResult($QryCred, 1).'</td>
    <td align="center">'.OCIResult($QryCred, 2).'</td>
    <td align="center">'.OCIResult($QryCred, 10).'</td>
  </tr>
</table><p></p>

<table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="213" align="center"><span class="Estilo5">TIPO DE ICFES</span></td>
	<td align="center"><span class="Estilo5">PUNTAJE</span></td>
    <td align="center"><span class="Estilo5">PUESTO</span></td>
    <td width="150" align="center" rowspan="2" bgcolor="#E4E5DB">'.$OP.'</td>
  </tr>
  <tr>
    <td align="center">'.OCIResult($QryCred, 8).'</td>
	<td align="center"> </td>
    <td align="center">'.OCIResult($QryCred, 5).'</td>
  </tr>
</table><p></p>';

print'<table width="450" border="0" align="center">
  <tr align="center">
    <td><form action="listados.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Consultar Listados" style="cursor:pointer" title="Consultar resultados de aspirantes en listados PDF."></form></td>
    <td><form action="datos.php" method="post" name="revlis" target="_self"><input name="rd" type="submit" value="Revisar Inscripci&oacute;n" style="cursor:pointer" title="Revisar los datos de su inscripción.">
	<input name="cred" type="hidden" value="'.$_POST['cred'].'"></form></td>
	<td><form action="index.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Credencial" style="cursor:pointer" title="Consultar resultados de aspirantes por número de credencial."></form></td>
    <td><form action="http://www.udistrital.edu.co/" method="post" name="salida" target="_self"><input name="salir" type="submit" value="Salir" style="cursor:pointer" title="Salir de esta página."></form></td>
  </tr>
</table>';
OCIFreeCursor($QryCred);
OCILogOff($oci_conecta);
?>
<div align="center"><strong>Nota</strong>: La asignación de cupos, fue reglamentada por el Consejo Académico proporcionalmente a la demanda de inscritos. </div></td>
  </tr>
  <tr>
     <td height="60" colspan="3"><? fu_pie(); ?></td>
  </tr>
</table>
</body>
</html>
