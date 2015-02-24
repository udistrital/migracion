<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per_resultado.php');
require_once('fu_pie_pagAdm.php');
require_once('fecha_matricula.php');

$log = "<embed width='57' height='58' src='../../img/cdr.swf'>";

?>
<html>
<head>
<title>Comit&eacute; de Admisiones</title>
<style>
.CapLink{text-decoration:none;cursor:pointer;color:#0000FF;font-family:Tahoma;font-size:12px;}
</style>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<LINK REL="SHORTCUT ICON" HREF="http://condor.udistrital.edu.co/appserv/img/favicon.ico">
<link href="../../script/estilo.css" rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../script/KeyIntro.js"></script>
<script language="JavaScript" type="text/javascript" src="../../script/SoloNumero.js"></script>
<script language="JavaScript" src="../../script/MuestraLayer.js"></script>
<style type="text/css">
<!--
.Estilo1 {color: #FF0000}
-->
</style>
</head>

<body onLoad="this.document.doc.cred.focus();">
<!-- Capa de C�ndor  -->
<?php 
require_once('capa_matricula.html');
require_once('capa_matricula_asab.htm');
require_once('capa_bienestar.html');
?>
<!-- Fin de la capa  -->
<p>&nbsp;</p>
<table width="750" height="460" align="center" cellpadding="3" cellspacing="0" style="border-color:#999999; border-style:double">
  <tr bgcolor="#E4E5DB">
    <td width="98" height="124">
	 <a href="http://www.udistrital.edu.co" title="Universidad Distrital Francisco Jos&eacute; de Caldas" target="_self">
	 <img src="../../img/EscudoUD.gif" width="90" height="110" border="0"></a>
    </td>
    <td width="677" align="center">
	  <br><img src="../../img/12cw03003.png" border="0"><br>
      <span class="Estilo14">VICERRECTOR&Iacute;A ACAD&Eacute;MICA - COMIT&Eacute; DE ADMISIONES</span><br>
      <br>
      <span class="Estilo12">CONSULTA DE ASPIRANTES PARA EL <br>
      <? print $peri; ?> PER&Iacute;ODO ACAD&Eacute;MICO DE <? print $ano; ?>
    </span> </td>
    <td width="97" align="center" title="Sistema de Informaci&oacute;n C&oacute;ndor"><? echo $log ?><span class="Estilo9"><br>C�NDOR</span></td>
  </tr>
   <tr>
   <td height="216" colspan="3"><div align="center" ><h3>DIGITE EL N&uacute;MERO DE CREDENCIAL Y HAGA CLIC EN &quot;Consultar&quot;</h3></div>
   <FORM method="post" NAME='doc' ACTION="adm_muestra_cred.php">
<table width="298" border="0" align="center">
<tr>
  <td class="Estilo9">Credencial:</td>
  <td align="right">
      <input name="cred" type="text" size="15" autocomplete="off" style="text-align:right" onKeyPress="check_enter_key(event,document.getElementById('doc')); return SoloNumero(event)">
  </td>
    <td><input type="Submit" value="Consultar" style="cursor:pointer" title="Ejecutar la consulta."></td>
  </tr>
</table>
<div align="center">
    <input name="ref" type="hidden" value="ref">
    <br>
    <a class="CapLink" onMouseOver="MostrarCapa('Matricula',5,5)" onClick="MostrarCapa('Matricula',5,5)" title="Haga clic para m&aacute;s informaci&oacute;n"><span class="CONDOR">PROCESO DE MATR&Iacute;CULA, ASPIRANTES Y ADMITIDOS</span></a><br>
</div>
<!--<p align="center" class="Estilo1">RESULTADOS ADMISIONES CONVENIO INTERADMINISTRATIVO 174 SUSCRITO ENTRE LA UNIVERSIDAD DISTRITAL Y LA SECRETARIA DE EDUCACI&Oacute;N DEL DISTRITO &ndash; SED</p>
<p class="Estilo1">Se informa a los aspirantes inscritos y admitidos en el marco del Convenio Interadministrativo 174, suscrito entre la Universidad Distrital y la Secretaria de Educaci&oacute;n del Distrito &ndash; SED que la admisi&oacute;n para el primer semestre acad&eacute;mico del a&ntilde;o 2009-1 fue aplazada para el mes de agosto de 2009. Por consiguiente el inicio de las actividades acad&eacute;micas ser&aacute;n en ese mes.</p>
<p class="Estilo1">No obstante lo anterior, la Secretaria de Educaci&oacute;n del Distrito -SED, ofrece a los aspirantes admitidos de esta convocatoria, en caso que lo deseen, el ingreso a otras Instituciones para iniciar actividades acad&eacute;micas en el primer semestre acad&eacute;mico del a&ntilde;o 2009.</p>
<p class="Estilo1">En el caso de querer acogerse a esteiniciativa los interesados deben acercarse el d&iacute;a miercoles <strong><u>18 de febrero</u></strong> del a&ntilde;o en curso a las 8:AM al Auditorio Fabio Chaparro de la Secretaria de Educaci&oacute;n del Distrito - SED, ubicado en la Avenida el Dorado No. 66-63 promer piso frente a los parqueaderos p&uacute;blicos, para una reuni&oacute;n general para este efecto.</p>
<p class="Estilo1">Esde anotar que los criterios de admisi&oacute;n est&aacute;n sustentados en la Resoluci&oacute;n 034 de Agosto de 2008, expedida por el Consejo de Facultad de la SedeTecnol&oacute;gica y el Acta No 026 del 11 de Agosto de 2008 del Consejo de Facultad del Medio Ambiente y Recursos Naturales.</p>
<p><span class="Estilo1">Los resultados de los aspirantes admitidos pueden consultarse en lasiguiente direcci&oacute;n www.udistrital.edu.co - admisiones pregrado 2009-1, o en el siguiente link <strong><em>http://condor.udistrital.edu.co/appserv/admisiones/index.php</em></strong></span></p>
<p align="justify" class="Estilo1">&nbsp;</p>-->
   <P class=bodyText align=justify>Nota: Los requisitos de Matr&iacute;cula para los aspirantes Admitidos se encuentran en el instructivo de Admisiones.</P>
   </FORM>
</td>
  </tr>
   <tr>
     <td height="10" colspan="3">
	 <table width="580" border="0" align="center">
  <tr align="center">
    <td width="299"><form action="listados.php" method="post" name="list" target="_self"><input name="cl" type="submit" value="Consultar Listados" style="cursor:pointer" title="Consultar resultados de aspirantes en listados PDF."></form></td>
    <td width="271"><form action="http://www.udistrital.edu.co/" method="post" name="salida" target="_self"><input name="salir" type="submit" value="Salir" style="cursor:pointer" title="Salir de esta p�gina."></form></td>
  </tr>
</table>
	 
	 </td>
   </tr>
   <tr>
     <td height="10" colspan="3" align="center" class="Estilo10">Mayores informes carrera 8 No 40 - 62 Admisiones, Edificio Sabio Caldas primer piso.</td>
   </tr>
   <tr>
     <td height="60" colspan="3"><? fu_pie(); ?></td>
   </tr>
</table>
</body>
</html>