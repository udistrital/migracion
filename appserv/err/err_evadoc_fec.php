<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion("evaldocente");

$cons = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
	TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
	FROM accaleventos, acasperi
	WHERE APE_ANO = ACE_ANIO
	AND APE_PER = ACE_PERIODO
	AND APE_ESTADO = 'A'
	AND ACE_COD_EVENTO = 11
	AND ACE_ESTADO = 'A'";
	
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cons,"busqueda");

?>
<HTML>
<HEAD><TITLE>Oficina Asesora de Sistemas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<body>
<p>&nbsp;</p>
<table width="60%" height="60%" border="1" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td valign="top">
  </td>
  </tr>
<tr align="center">
  <td valign="middle"> 
		
		<table width="75%" border="0" cellpadding="2" cellspacing="0" align="center">
		<tr><td valign="top">
		<div><h3>PROCESO DE EVALUACI&Oacute;N DOCENTE</h3></div>
		
		<fieldset style="padding:10; border-width:1;border-color:#FF0000; border-style:dashed">
		<p align="justify">Seg&uacute;n el Calendario Acad&eacute;mico, las fechas para el proceso de evaluaci&oacute;n docente ser&aacute;n las siguientes:<br><br>
		  Inicio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? print $consulta[0][0];?><br>
		  Terminaci&oacute;n: <? print $consulta[0][1];?></p>
		  
		<p align="center"><strong>Calendario Acad&eacute;mico</strong></p>
		<p align="center">&nbsp;</p>
		</fieldset>
		<p align="center"></p>
		  </td></tr>
	  </table>
    
    </td>
</tr>
</table>
</body>
</html>