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
$form = "transferencia";
$item = "FechaNac";

ob_start();
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../general/ValidaTransferencia.js"></script>
<script language="javascript" src="../general/ValidaCampoFecha.js"></script>
<script language="JavaScript" src="../calendario/javascripts.js"></script>
<script language="JavaScript" src="pseLogout.js"></script>
<script language="JavaScript" src="../../BorraLink.js"></script>
<script language="JavaScript" src="../../SoloNumero.js"></script>
<script language="JavaScript" src="../general/CuentaCaracteres.js"></script>
<SCRIPT language="JavaScript">
<!--
var WinOpen=0;
function ListaValores(pag, R, S, D, H, an, al, iz, ar){
  if(WinOpen){
     if(!WinOpen.closed)
	    WinOpen.close();
  }
   WinOpen = window.open(pag+'?httpR='+R+'&httpS='+S+'&httpD='+D+'&httpH='+H, "Lov", "width="+an+",height="+al+",scrollbars=YES,left="+iz+",top="+ar);
}
//-->
</script>
</head>

<body>
<?
require_once(dir_general.'cabezote.php'); 
require_once(dir_general.'msql_querys.php');
?>
<p align="center" class="Estilo6">FORMULARIO DE TRANSFERENCIA EXTERNA<br><? print $periodo; ?></p>

<form name="transferencia" method="post" action="pse_verifica_transferencia.php">
<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
<br>
<? //require_once('../aspirantes/ins.php'); ?>
<table width="99%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
    <tr>
	<td width="472" align="right">Carrera a la que se transfiere:</td>
      <td colspan="5">
	  <?php
	  print'<select size="1" name="CraCodT">
	  <option value="0" selected>Seleccione el Proyecto Curricular</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryCra, 1).'">'.OCIResult($QryCra, 1).' - '.OCIResult($QryCra, 2).'</option>\n';
	  }while(OCIFetch($QryCra));
	  OCIFreeCursor($QryCra);
	  print'</select>'; 
	  ?>
	  </td>
    </tr>
    <tr>
      <td align="right">Tipo de inscripci&oacute;n: </td>
      <td colspan="5"><? print OCIResult($QryTipInsEx, 2); ?>
	  <input name="TiCod" type="hidden" value="<? print OCIResult($QryTipInsEx, 1);?>"></td>
    </tr>
    <tr>
      <td align="right">Universidad de donde viene:</td>
      <td colspan="5"><input name="UdPro" type="text" id="UdPro" size="60" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50"></td>
    </tr>
    <tr>
      <td align="right">Carrera que venia cursando:</td>
      <td colspan="5"><input name="CraCur" type="text" id="CraCur" size="60" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50"></td>
    </tr>
    <tr>
      <td align="right">&Uacute;ltimo semestre cursado:</td>
      <td colspan="5">        
	    <input name="LastSem" type="text" id="LastSem" size="5" onKeypress="return SoloNumero(event)">      </td>
    </tr>
    <tr>
      <td align="right">Motivo de la transferencia:</td>
      <td colspan="5" align="left">
	  <textarea rows="2" cols="80" name="motivo"></textarea></td>
	</tr>
    <tr>
      <td colspan="6" align="center" class="Estilo9">LUGAR Y FECHA DE NACIMIENTO</td>
    </tr>
    <tr>
      <td align="right">Pais:</td>
      <td width="121">
	  <select name="PaisNac" id="PaisNac">
        <option value="COLOMBIA" selected>COLOMBIA</option>
        <option value="EXTRANJERO">EXTRANJERO</option>
      </select>
	  </td>
      <td><div align="right">Departamento:</div></td>
      <td><? print'<input name="DptoNac" type="text" id="DptoNac" value="" size="12" onClick="ListaValores(\'lov_departamento.php\', this.name, DptoNac.value, \'NomDep\', DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?></td>
      <td width="98"><div align="right">Municipio:</div></td>
      <td width="68"><? print'<input name="CiudadNac" type="text" id="CiudadNac" value="" size="12" onClick="ListaValores(\'lov_municipio.php\', this.name, DptoNac.value, DptoNac.value, DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?></td>
    </tr>
    <tr>
      <td align="right">Fecha de nacimiento:</td>
      <td colspan="5"><? print'<input name="FechaNac" type="text" id="FechaNac" size="12" onBlur="ValidaCampoFecha();>
	  <input type="button" value="Calendario" onClick="muestraCalendario(\''.$raiz.'\',\''.$form .'\',\''.$item.'\')" title="Haga clic para ver el calendario"  style="width:80; cursor:pointer">'; 
	  ?> <span class="Estilo3">DD/MM/AAAA (30/12/1983) </span> </td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Sexo:</td>
      <td colspan="5">        
	  <select name="Sexo" id="Sexo">
        <option value="M" selected>M</option>
        <option value="F">F</option>
        </select></td>
    </tr>
    <tr>
      <td align="right">Estado civil:</td>
      <td colspan="5">        
	  <select name="EstCivil" id="EstCivil">
        <option value="1" selected>Soltero</option>
        <option value="2">Casado</option>
        <option value="3">Otro</option>
        </select></td>
    </tr>
    <tr>
      <td align="right">Direcci&oacute;n:</td>
      <td colspan="5" align="left"><input name="dir" type="text" id="dir" size="60" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50"></td>
    </tr>
    <tr>
      <td align="right">Localidad de residencia: </td>
      <td colspan="5" align="left">
	  <?php
	  print'<select size="1" name="LocRes">
	  <option value="0" selected>Seleccione la localidad de residencia</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryLoc, 1).'">'.OCIResult($QryLoc, 2).'</option>\n';
	  }while(OCIFetch($QryLoc));
	  OCIFreeCursor($QryLoc);
	  print'</select>'; 
	  ?>
	  </td>
    </tr>
    <tr>
      <td align="right">Estrato de residencia:</td>
      <td colspan="5" align="left">
	  <?php
	  print'<select size="1" name="StrRes">
	  <option value="0" selected>Seleccione el estrato de la residencia</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryEstrato, 1).'">'.OCIResult($QryEstrato, 2).'</option>\n';
	  }while(OCIFetch($QryEstrato));
	  OCIFreeCursor($QryEstrato);
	  print'</select>'; 
	  ?>
	  </td>
    </tr>
    <tr>
      <td align="right">Tel&eacute;fono:</td>
      <td colspan="5" align="left"><input name="tel" type="text" id="tel" size="15"></td>
    </tr>
    <tr>
      <td align="right">Correo electr&oacute;nico: </td>
      <td colspan="5" align="left"><input name="CtaCorreo" type="text" id="CtaCorreo" size="60" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td>
    </tr>
    <tr>
      <td colspan="6" align="center" class="Estilo9">DOCUMENTO DE IDENTIDAD</td>
    </tr>
    <tr>
      <td align="right">Documento actual: </td>
      <td align="left"><input name="DocActual" type="text" id="DocActual" size="15" onKeypress="return SoloNumero(event)"></td>
      <td align="left"><div align="right">Tipo:</div></td>
      <td width="86" align="left"><select name="TipDocAct" id="TipDocAct">
        <option value="1" selected>C.C.</option>
        <option value="2">T.I.</option>
        <option value="3">C.E.</option>
      </select></td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;	  	</td>
      </tr>
    <tr>
      <td align="right">Documento de identidad con el que present&oacute; el ICFES: </td>
      <td align="left">
        <input name="DocIcfes" type="text" id="DocIcfes" size="15" onKeypress="return SoloNumero(event)">      </td>
      <td align="left"><div align="right">Tipo:</div></td>
      <td align="left"><select name="TipDocIcfes" id="TipDocIcfes">
        <option value="1" selected>C.C.</option>
        <option value="2">T.I.</option>
        <option value="3">C.E.</option>
      </select></td>
      <td align="right">&nbsp;</td>
      <td>&nbsp;	  	  </td>
      </tr>
    <tr>
      <td colspan="6" align="center" class="Estilo9">REGISTRO ICFES</td>
    </tr>
    <tr>
      <td align="right">N&uacute;mero del registro del icfes (SNP): </td>
      <td colspan="5" align="left">
        <select name="TipoIcfes" id="TipoIcfes">
          <option value="AC" selected>AC</option>
          <option value="VG">VG</option>
        </select>
        <input name="NroIcfes" type="text" id="NroIcfes" size="15" onKeypress="return SoloNumero(event)" maxlength="12"></td>
    </tr>
    <tr>
      <td align="right"><strong>CONFIRME</strong> el N&uacute;mero del registro del icfes (SNP):</td>
      <td colspan="5" align="left">
	  <select name="CVTipoIcfes" id="CVTipoIcfes">
          <option value="AC" selected>AC</option>
          <option value="VG">VG</option>
        </select>
        <input name="CNroIcfes" type="text" id="CNroIcfes" size="15" onKeypress="return SoloNumero(event)" onBlur="ValidaSNP()" maxlength="12"></td>
    </tr>
    <tr>
      <td align="right">Localidad del colegio donde culmin&oacute; el grado 11: </td>
      <td colspan="5" align="left">
	  <?php
	  print'<select size="1" name="LocCol">
	  <option value="0" selected>Seleccione la localidad del colegio</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryLocCol, 1).'">'.OCIResult($QryLocCol, 2).'</option>\n';
	  }while(OCIFetch($QryLocCol));
	  OCIFreeCursor($QryLocCol);
	  print'</select>'; 
	  ?>
	  </td>
    </tr>
    <tr>
      <td align="right">Observaciones:</td>
      <td colspan="5" align="left">
	  <textarea rows="3" cols="80" name="obs" onKeyDown="ConTex(this.form.obs,this.form.contador);" onKeyUp="ConTex(this.form.obs,this.form.contador);"></textarea><br>
	  Sólo puede digitar <input type="text" name="contador" size="2" value="500" style="text-align:center; border:0; height:auto" readonly> caracteres.</td>
    </tr>
  </table>
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
OCIFreeCursor($QryTCra);
OCIFreeCursor($QryTipIns);
OCIFreeCursor($QryTipInsEx);
fu_pie();
ob_end_flush();
?>
</body>
</html>