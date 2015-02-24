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
$form = "acasp";
$item = "FechaNac";

ob_start();
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../general/ValidaAcasp.js"></script>
<script language="javascript" src="../general/ValidaCampoFecha.js"></script>
<script language="JavaScript" src="../../calendario/javascripts.js"></script>
<script language="JavaScript" src="Logout.js"></script>
<script language="JavaScript" src="../../script/BorraLink.js"></script>
<script language="JavaScript" src="../../script/SoloNumero.js"></script>
<script language="JavaScript" src="../general/CuentaCaracteres.js"></script>
<script language="javascript">
<!--
var WinOpen=0;
function ListaValores(pag, R, S, D, H, an, al, iz, ar){
  if(WinOpen){
     if(!WinOpen.closed) WinOpen.close();
  }
  WinOpen = window.open(pag+'?httpR='+R+'&httpS='+S+'&httpD='+D+'&httpH='+H, "Lov", "width="+an+",height="+al+",scrollbars=YES,left="+iz+",top="+ar);
}
//-->
</script>
<style type="text/css">
<!--
.Estilo18 {
	font-size: 11px;
	color: #FF0000;
}
.Estilo19 {color: #FF0000}
-->
</style>
</head>

<body>
<?
require_once(dir_general.'cabezote.php'); 
require_once(dir_general.'msql_querys.php');

require_once("../../script/mensaje_error.inc.php");
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   print $err="<center><a OnMouseOver='history.go(-1)'><img src='../../img/asterisco.gif'>$error_login_ms[$error]</a></center>";
}
?>
<p align="center" class="Estilo6">FORMULARIO DE INSCRIPCI&Oacute;N PARA INGRESO<br><? print $periodo; ?></p>

<form name="acasp" onsubmit="return verifica();" method="post" action="asp_verifica_acasp.php">
<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
<br>
<? //require_once('ins.php'); ?>
<table width="99%" border="1" align="center" cellpadding="0" cellspacing="2" style="border-collapse:collapse">
    <tr>
      <td align="right">Por que medio se enter&oacute; de la Universidad Distrital:</td>
      <td colspan="5">
	  <?php
	  print'<select size="1" name="MedPub">
	  <option value="0" selected>Seleccione el medio de publicidad</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryMed, 1).'">'.OCIResult($QryMed, 2).'</option>\n';
	  }while(OCIFetch($QryMed));
	  OCIFreeCursor($QryMed);
	  print'</select>'; 
	  ?></td>
    </tr>
    <tr>
      <td width="439" align="right">Se presenta a la Universidad por:</td>
      <td colspan="5">
	  <select name="SePresentaPor" id="SePresentaPor">
        <option value="1" selected>Primera vez</option>
        <option value="2">Segunda vez</option>
        <option value="3">Tercera o m&aacute;s veces</option>
        </select>
	</td>
    </tr>
    <tr>
      <td align="right">Carrera en la que se inscribe:</td>
      <td colspan="5">
	  <?php
	  print'<select size="1" name="CraCod">
	  <option value="0" selected>Seleccione el Proyecto Curricular</option>';
	  do{
   		 echo'<option value="'.OCIResult($QryCra, 1).'">'.OCIResult($QryCra, 1).' - '.OCIResult($QryCra, 2).'</option>\n';
	  }while(OCIFetch($QryCra));
	 print'</select>'; 
	  ?></td>
    </tr>
    <tr>
      <td align="right">Tipo de inscripci&oacute;n:</td>
      <td colspan="5">        
	   <select name="TipoIns" id="TipoIns">
          <option value="1" selected>Normal</option>
          <option value="1">Negritudes</option>
          <option value="1">Indigenas</option>
          <option value="22">Desplazados</option>
          <option value="1">Mejor bachiller</option>
       </select>
	</td>
    </tr>
    <tr>
      <td align="justify" class="Estilo10">&nbsp;</td>
      <td colspan="5" align="justify" class="Estilo10">Si selecciona un <b>Tipo de inscripci&oacute;n</b> diferente de normal, debe presentar los soportes en el sitio y la fecha indicadas, (ver instructivo de inscripciones especiales).</td>
    </tr>
    <tr>
      <td colspan="6" align="center" class="Estilo9">LUGAR Y FECHA DE NACIMIENTO</td>
    </tr>
    <tr>
      <td align="right">Pais:</td>
      <td width="101">
	  <select name="PaisNac" id="PaisNac">
        <option value="COLOMBIA" selected>COLOMBIA</option>
        <option value="EXTRANJERO">EXTRANJERO</option>
      </select>	  </td>
      <td width="108"><div align="right">Departamento: </div></td>
      <td width="46"><? print'<input name="DptoNac" type="text" id="DptoNac" value="" size="12" onClick="ListaValores(\'lov_departamento.php\', this.name, DptoNac.value, \'NomDep\', DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?></td>
      <td width="128"><div align="right">Municipio:</div></td>
      <td><? print'<input name="CiudadNac" type="text" id="CiudadNac" value="" size="12" onClick="ListaValores(\'lov_municipio.php\', this.name, DptoNac.value, DptoNac.value, DptoNac.value, 340, 200, 650, 390)" title="Haga clic para ver la lista de valores" onKeypress="return SoloNumero(event)">'; ?></td>
    </tr>
    <tr>
      <td align="right">Fecha de nacimiento:</td>
      <td colspan="5"><? print'<input name="FechaNac" type="text" id="FechaNac" size="12" onBlur="ValidaCampoFecha();">
	  <input type="button" value="Calendario" onClick="muestraCalendario(\''.$raiz.'\',\''.$form .'\',\''.$item.'\')" title="Haga clic para ver el calendario"  style="width:80; cursor:pointer">'; ?>
	  <span class="Estilo3"> DD/MM/AAAA (25/12/1985)</span> </td>
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
      <td align="left">
        <div align="left">
          <select name="TipDocAct" id="TipDocAct">
              <option value="1" selected>C.C.</option>
              <option value="2">T.I.</option>
              <option value="3">C.E.</option>
          </select>
        </div></td>
      <td align="right"><div align="left">
      </div></td>
      <td width="106">&nbsp;	  	</td>
    </tr>
    <tr>
      <td align="right">Documento de identidad con el que present&oacute; el ICFES: </td>
      <td align="left">
        <input name="DocIcfes" type="text" id="DocIcfes" size="15" onKeypress="return SoloNumero(event)"></td>
      <td align="left"><div align="right">Tipo:</div></td>
      <td align="left">
        <div align="left">
          <select name="TipDocIcfes" id="TipDocIcfes">
              <option value="1" selected>C.C.</option>
              <option value="2">T.I.</option>
              <option value="3">C.E.</option>
          </select>
        </div></td>
      <td align="right"><div align="left">
      </div></td>
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
        <input name="NroIcfes" type="text" id="NroIcfes" size="15" onKeypress="return SoloNumero(event)" maxlength="12"> 
        <span class="Estilo18">Nota: Este dato corresponde a los 12 n&uacute;meros en La cuadricula marcada como <strong>REGISTRO N&deg;</strong> (ICFES) </span></td>
    </tr>
    <tr>
      <td align="right"><strong>CONFIRME</strong> el N&uacute;mero del registro del icfes (SNP):</td>
      <td colspan="5" align="left">
	  <select name="CVTipoIcfes" id="CVTipoIcfes">
          <option value="AC" selected>AC</option>
          <option value="VG">VG</option>
        </select>
        <input name="CNroIcfes" type="text" id="CNroIcfes" size="15" onKeypress="return SoloNumero(event)" onBlur="ValidaSNP()" maxlength="12"> 
        <span class="Estilo18">Recuerde que en estos dos campos no se deben digitar letras (AC o VG)</span> </td>
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
      <td colspan="5" align="left">	  <textarea rows="3" cols="70" name="obs" onKeyDown="ConTex(this.form.obs,this.form.contador);" onKeyUp="ConTex(this.form.obs,this.form.contador);"></textarea>
        <br>
	  S�lo puede digitar <input type="text" name="contador" size="2" value="500" style="text-align:center; border:0; height:auto" readonly> caracteres.</td>
    </tr>
  </table>
  <br>
  </fieldset>
</td></tr>
</table>
  
  <? require_once(dir_general.'botones_formularios.php'); ?>
  
</form>
<?php
print '<center>'.$err.'</center>';
?>
</fieldset>
<p></p>
<?php
OCIFreeCursor($QryTCra);
OCIFreeCursor($QryTipIns);
OCIFreeCursor($QryTipInsEx);
fu_pie();
ob_end_flush();
?>
</body>
</html>