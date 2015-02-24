<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_conect.'cierra_bd.php');
require_once('../../calendario/calendario.php');
require_once(dir_general.'asp_pie_pagAdm.php');

require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_formulario_formulario.php');
require_once(dir_general.'valida_inscripcion.php');

global $raiz;
$form = "reingreso";
$item = "FechaNac";

ob_start();
?>
<html>
<head>
<title>Reingreso</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../general/ValidaReingreso.js"></script>
<script language="JavaScript" src="../../calendario/javascripts.js"></script>
<script language="JavaScript" src="../../script/LisLov.js"></script>
<script language="JavaScript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" src="../../script/SoloNumero.js"></script>
<script type='text/javascript' src='../general/formexp.js'></script>
<script language="JavaScript" src="Logout.js"></script>
<script>
function expandir_formulario(valor){
	if (valor == "0"){ xDisplay('CapCra', 'none') }
	if (valor == "26"){ xDisplay('CapCra', 'none') }
	if (valor == "25"){ xDisplay('CapCra', 'block') }
}
</script>
<style type="text/css">
#capainicio{position:relative;}
#CapCra{position:relative; display:none; }
#capafinal{position:relative;}
</style>
</head>
<body>
<? 
require_once(dir_general.'cabezote.php');
require_once(dir_general.'msql_querys.php');
?>
<p align="center" class="Estilo6">FORMULARIO DE REINGRESO O TRANSFERENCIA INTERNA<BR><? print $periodo; ?></p>

<form name="reingreso" method="post" action="asp_verifica_reingreso.php">
<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
<br>
<? //require_once('ins.php'); ?>
<div id=capainicio>
<table width="98%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
	<tr>
	  <td width="40%" align="right"><b>Seleccione el tipo de inscripci&oacute;n:</b></td>
	  <td>
	  <?php
	  print'<select size="1" name="TipoIns" onchange="expandir_formulario(this.value)">
	  <option value="0" selected>Tipos de Inscripción</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryTipIns, 1).'">'.OCIResult($QryTipIns, 2).'</option>\n';
	  }while(OCIFetch($QryTipIns));
	  OCIFreeCursor($QryTipIns);
	  print'</select>'; 
	  ?>
	  </td>
	  </tr>
	<tr>
	  <td align="right">Documento de identidad: </td>
	  <td><input name="DocActual" type="text" id="DocActual" size="15" onKeyPress="return SoloNumero(event)"></td>
	  </tr>
	<tr>
	  <td align="right">C&oacute;digo de estudiante en la Universidad Distrital:</td>
	  <td><input name="EstCod" type="text" id="EstCod" size="15" onKeypress="return SoloNumero(event)"></td>
	  </tr>
	<tr>
      <td align="right"><b>Confirme</b> el c&oacute;digo de estudiante en la Universidad Distrital:</td>
      <td>
	  <input name="ConEstCod" type="text" id="ConEstCod" size="15" onKeypress="return SoloNumero(event)" onBlur="ComparaEstCod();">	  </td>
    </tr>
    <tr>
      <td align="right">Cancel&oacute; semestre: </td>
      <td>
	  <label>Si<input name="CanSem" type="radio"  value="S">
      <label>&nbsp;No<input name="CanSem" type="radio" value="N" checked>
      </label></td>
    </tr>
    <tr>
      <td align="right">Motivo del retiro: </td>
      <td align="left">
	  <textarea rows="2" cols="80" name="MotRetiro"></textarea>	  </td>
    </tr>
	<tr>
      <td align="right">Tel&eacute;fono:</td>
      <td colspan="3" align="left"><input name="tel" type="text" id="tel" size="15"></td>
    </tr>
	<tr>
      <td align="right">Correo electr&oacute;nico: </td>
      <td align="left"><input name="CtaCorreo" type="text" id="CtaCorreo" size="60" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td>
    </tr>
	</table>
	</div>
	
	<div id=CapCra>
	<table width="98%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
	<tr>
	  <td width="40%" align="right">Carrera que ven&iacute;a cursando: </td>
	  <td align="left">
	  <?php
	  print'<select size="1" name="CraCod">
	  <option value="0" selected>Seleccione el Proyecto Curricular</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryCra, 1).'">'.OCIResult($QryCra, 2).'</option>\n';
	  }while(OCIFetch($QryCra));
	  OCIFreeCursor($QryCra);
	  print'</select>'; 
	  ?>
	  </td>
	  </tr>
	<tr>
      <td align="right">Carrera a la cual se transfiere:</td>
      <td colspan="3">
	  <?php
	  print'<select size="1" name="TCraCod">
	  <option value="0" selected>Seleccione el Proyecto Curricular</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryTCra, 1).'">'.OCIResult($QryTCra, 2).'</option>\n';
	  }while(OCIFetch($QryTCra));
	  OCIFreeCursor($QryTCra);
	  print'</select>'; 
	  ?>
	  </td>
    </tr>
  </table>
  </div>
    <br>
  </fieldset>
</td></tr>
</table>

  <? require_once(dir_general.'botones_formularios.php'); ?>
  
</form>
</fieldset>
<p></p>
<?php
OCIFreeCursor($QryMed);
OCIFreeCursor($QryLoc);
OCIFreeCursor($QryEstrato);
OCIFreeCursor($QryLocCol);
OCIFreeCursor($QryTipInsEx);
fu_pie();
ob_end_flush();
?>
</body>
</html>