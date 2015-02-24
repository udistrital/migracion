<?PHP
require_once('dir_relativo.cfg');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');
include_once("../clase/multiConexion.class.php");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion(50);

//echo "hsdfasd";

$log = "<embed width='57' height='58' src='../img/cdrlogo.png'>";

if($_REQUEST['cred']==""){
   header("Location: ../err/err_crednull.php");
   exit;
}
elseif(!is_numeric($_REQUEST['cred'])){
   	   header("Location: ../err/err_crednotnumner.php");
   	   exit;
}
$QryCred = " SELECT APE_ANO,
                decode(APE_PER, 1,'PRIMERO', 3,'SEGUNDO'),
                CRA_COD,
                CRA_NOMBRE,
                decode(CRA_IND_CICLO,'N',ASP_PUESTO,null) ASP_PUESTO,
                ASP_CRED CREDENCIAL,
                (trim(ASP_NOMBRE)||' '||trim(ASP_APELLIDO)),
                DECODE(ASP_TIP_ICFES,'A','ANTIGUO','N','NUEVO'),
                ASP_PTOS_CAL,
                TI_NOMBRE,
                DECODE(ASP_ADMITIDO,'A','ADMITIDO','O','OPCIONADO','NO
ADMITIDO')
                FROM ACASPERIADM, ACCRA, ACTIPINS, ACASP
                WHERE APE_ESTADO = 'X'
                AND APE_ANO = ASP_APE_ANO
                AND APE_PER = ASP_APE_PER
                and CRA_DEP_COD <> 24
                AND CRA_COD = ASP_CRA_COD
                AND TI_COD = ASP_TI_COD
                AND ASP_CRED = '".$_REQUEST['cred']."'
                union
                SELECT APE_ANO,
                decode(APE_PER, 1,'PRIMERO', 3,'SEGUNDO'),
                CRA_COD,
                CRA_NOMBRE,
                null ASP_PUESTO,
                ASP_CRED CREDENCIAL,
                (trim(ASP_NOMBRE)||' '||trim(ASP_APELLIDO)),
                DECODE(ASP_TIP_ICFES,'A','ANTIGUO','N','NUEVO'),
                ASP_PTOS_CAL,
                TI_NOMBRE,
                DECODE(ASP_ADMITIDO,'A','ADMITIDO','O','OPCIONADO','NO
ADMITIDO')
                FROM ACASPERIADM, ACCRA, ACTIPINS, ACASP
                WHERE APE_ESTADO = 'X'
                AND APE_ANO = ASP_APE_ANO
                AND APE_PER = ASP_APE_PER
                and CRA_DEP_COD = 24
                AND CRA_COD = ASP_CRA_COD
                AND TI_COD = ASP_TI_COD
                AND ASP_CRED = '".$_REQUEST['cred']."'";
//echo $QryCred."<br>";

$RowCred = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCred,"busqueda");

if(!is_array($RowCred))
{
  // echo "<script>location.replace(' ../aspirantes/err/err_aspirante.php')</script>";
   //exit;
}

/*if($RowCred[0][2]==16 || $RowCred[0][2]==98 || $RowCred[0][2]==102 || $RowCred[0][2]==104)
{
    $OP = '<font color="#009900" size="3"><b>PUBLICACI&Oacute;N DE RESULTADOS 23 DE DICIEMBRE DE 2012.</b></font>';
}*/
if($RowCred[0][10] == 'ADMITIDO')
{
	$OP = '<font color="#009900" size="3"><b>ADMITIDO</b></font>';
}
elseif($RowCred[0][10] == 'OPCIONADO')
{
	$OP = '<font color="#FB9524" size="3"><b>OPCIONADO</b></font>';
}
elseif(!is_array($RowCred))
{
	$OP='<font color="#FF0000" size="3"><b>No existe un aspirante con ese n&uacute;mero de credencial.</b></font>';
}
else
{
	$OP = '<font color="#FF0000" size="2"><b>NO ADMITIDO</b></font>';
}
?>
<html>
<head>
<title>Comit&eacute; de Admisiones</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<script language="JavaScript" type="text/javascript" src="../script/KeyIntro.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<p>&nbsp;</p>
<table width="750" height="506" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98" height="124">
	  <a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco Jos&eacute; de Caldas" target="_self">
	  <img src="../img/EscudoUD.gif" width="90" height="110" border="0"></a>
	</td>
    <td width="677" align="center">
	  <br><img src="../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA - COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br><? print $peri; ?> PER&Iacute;ODO ACAD&Eacute;MICO DE <? print $ano; ?></span>
	</td>
    <td width="97" align="center" title="Sistema de Informaci&oacute;n C&oacute;ndor"><? echo $log ?><span class="Estilo9"><br>C&Oacute;NDOR</span></td>
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
    <td align="center">'.$RowCred[0][5].'</td>
    <td align="left">'.$RowCred[0][6].'</td>
  </tr>
  </table><p></p>
  
  <table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="80" align="center"><span class="Estilo5">C&Oacute;DIGO</span></td>
    <td width="450" align="center"><span class="Estilo5">PROYECTO CURRICULAR</span></td>
  </tr>
  <tr>
    <td align="center">'.$RowCred[0][2].'</td>
    <td align="left">'.$RowCred[0][3].'</td>
  </tr>
</table><p></p>

<table width="530" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#999999" style="border-collapse:collapse;border-style:solid">
  <tr>
    <td width="80" align="center"><span class="Estilo5">A&Ntilde;O</span></td>
    <td align="center"><span class="Estilo5">PERIODO</span></td>
    <td align="center"><span class="Estilo5">TIPO DE INSCRIPCI&Oacute;N</span></td>
  </tr>
  <tr>
    <td align="center">'.$RowCred[0][0].'</td>
    <td align="center">'.$RowCred[0][1].'</td>
    <td align="center">'.$RowCred[0][9].'</td>
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
    <td align="center">'.$RowCred[0][7].'</td>
	<td align="center"> </td>
    <td align="center">'.$RowCred[0][4].'</td>
  </tr>
</table><p></p>';

print'<table width="450" border="0" align="center">
  <tr align="center">
    <td><form action="listados.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Consultar Listados" style="cursor:pointer" title="Consultar resultados de aspirantes en listados PDF."></form></td>
    <td><form action="datos.php" method="post" name="revlis" target="_self"><input name="rd" type="submit" value="Revisar Inscripci&oacute;n" style="cursor:pointer" title="Revisar los datos de su inscripci&oacute;n.">
	<input name="cred" type="hidden" value="'.$_POST['cred'].'"></form></td>
	<td><form action="index.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Credencial" style="cursor:pointer" title="Consultar resultados de aspirantes por n&uacute;mero de credencial."></form></td>
    <td><form action="http://www.udistrital.edu.co/" method="post" name="salida" target="_self"><input name="salir" type="submit" value="Salir" style="cursor:pointer" title="Salir de esta p&aacute;gina."></form></td>
  </tr>
</table>';
?>
<div align="justify"><strong>Nota 1</strong>: - La asignaci&oacute;n de cupos, fue reglamentada por el Consejo Acad&eacute;mico proporcionalmente a la demanda de inscritos.<br>
    <strong>Nota 2</strong>:- Se&ntilde;or aspirante, si desea consultar el puesto que ocup&oacute;, dentro de los aspirantes inscritos al Proyecto Curricular al cu&aacute;l realiz&oacute; su inscripci&oacute;n, debe consultar los listados publicados en esta p&aacute;gina, teniendo en cuenta la fecha de presentaci&oacute;n de su ex&aacute;men de estado ICFES, es decir, los presentados hasta el 2014 1 y los presentados a partir del 2014 2.</div></td>   
  </tr>
  <tr>
     <td height="60" colspan="3"><? fu_pie(); ?></td>
  </tr>
</table>
</body>
</html>
