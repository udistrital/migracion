<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<SCRIPT language="JavaScript" type="text/javascript">
function seleccion(){
  for(var i = 0; i < document.forms[0].tipo.length; i++ ){
      if(document.forms[0].tipo[i].checked){
         document.forms[0].sex.value = document.forms[0].tipo[i].value;
         break;
       }
  }
}
</SCRIPT>
</HEAD>
<BODY>

<?php
fu_cabezote("ACTUALIZACI&Oacute;N DE DATOS");

$cedula = $_SESSION['usuario_login'];
$carrera = $_SESSION['carrera'];
//Actualiza datos
if($_REQUEST['actualizar']) {
   require_once(dir_script.'msql_actualiza_datos_doc.php');
}
//Edita los datos
require_once(dir_script.'msql_consulta_datos_doc.php');
$registro = $conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");
if(!is_array($registro))
{
die("<center><h3>No hay registros para esta consulta.</h3></center>");
}

echo'<form name="dat" method=post action="coor_actualiza_dat.php">
<div align="center" style="width: 840; height: 300"><br><br>
<table border="0" width="50%" cellspacing="3" cellpadding="1">
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Nombre:</span></td>
  <td width="81%" style="font-weight: bold" colspan="4">'.$registro[0][0].'</td></tr>
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Identificaci&oacute;n:</span></td>
  <td width="81%" style="font-weight: bold" colspan="4">'.$cedula.'</td></tr>
 <tr>
  <td width="19%" align="right">&nbsp;</td>
  <td width="81%" style="font-weight: bold" colspan="4"></td></tr>
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Direcci&oacute;n:</span></td>
  <td width="81%" colspan="4" align="right"><input name="dir" type="text" id="dir" value="'.$registro[0][1].'" size="63" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50"></td></tr>
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Tel&eacute;fono:</span></td>
  <td width="27%" align="left"><input name="tel" type="text" id="tel" value="'.$registro[0][2].'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td>
  <td width="8%" align="right"><span class="Estilo5">Tel&eacute;fono alt:</span></td>
  <td width="49%" colspan="2" align="left"><input name="tela" type="text" id="tela" value="'.$registro[0][3].'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td></tr>
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Celular:</span></td>
  <td width="27%" align="left"><input name="cel" type="text" id="cel" value="'.$registro[0][4].'" size="18" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td>
  <td width="19%" align="right"><span class="Estilo5">Sexo:</span></td>
  <td width="27%" align="left">
  <select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.$registro[0][5].'" selected>'.$registro[0][5].'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select>
  </td>
  <td width="7%" align="right">
  <input name="sex" type="hidden" id="sex" value="'.$registro[0][5].'" size="1" style="text-align: center" readonly></td>
  </tr>
 <tr>
  <td width="19%" align="right"><span class="Estilo5">Estado civil:</span></td>
  <td width="21%" align="left">
  <select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.$registro[0][7].'" selected>'.$registro[0][7].'</option>
	<option value="1">1 Soltero</option>
    <option value="2">2 Casado</option>
    <option value="3">3 Uni&oacute;n libre</option>
    <option value="4">4 Separado</option>
    <option value="5">5 Viudo</option>
  </select>
  <input name="estc" type="hidden" id="estc" value="'.$registro[0][6].'" size="1" style="text-align: center" readonly>
  </td>
  <td width="14%" align="right"><span class="Estilo5">Tipo sangre:</span>
  </td>
  <td width="46%" colspan="2" align="left">
  <select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.$registro[0][8].'" selected>'.$registro[0][8].'</option>
	<option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
  </select>
  <input name="tisa" type="hidden" id="tisa" value="'.$registro[0][8].'" size="1" style="text-align: center" readonly></td></tr>
 
 <tr>
 <td width="19%" align="right"><span class="Estilo5">E-mail:</span></td>
 <td width="81%" colspan="4" align="right"><input name="mail" type="text" id="mail" value="'.$registro[0][9].'" size="63" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td>
 </tr>
 
 <tr>
 <td width="19%" align="right"><span class="Estilo5">E-mail-Ins:</span></td>
 <td width="81%" colspan="4">'.$registro[0][10].'</td>
 </tr>
 
 <tr><td width="85%" align="center" colspan="5" height="30">&nbsp;';
  require_once(dir_script.'mensaje_error.inc.php');
  if(isset($_REQUEST['error_login'])){
     $error=$_REQUEST['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>Error: $error_login_ms[$error]</a></font>";
  }
echo'</td></tr>
 <tr><td width="85%" align="center" colspan="5" height="10"><input type=submit name="actualizar" value="Grabar"></td></tr>
</table></div>';
?>
</BODY>
</HTML>