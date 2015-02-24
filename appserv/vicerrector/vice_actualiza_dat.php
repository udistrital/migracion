<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(32);
?>
<HTML>
<HEAD>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
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

fu_cabezote("ACTUALIZACIÓN DE DATOS");

$cedula = $_SESSION['usuario_login'];
//Actualiza datos
if($_POST['actualizar']) {
   require_once(dir_script.'msql_actualiza_datos_doc.php');
}
//Edita los datos
require_once(dir_script.'msql_consulta_datos_doc.php');

echo'<p>&nbsp;</p><form name="dat" method=post action="'.$_SERVER['PHP_SELF'].'">
<table width="80%" border="1" align="center">
  <tr>
    <td width="17%" align="right"><SPAN class=Estilo5>Nombre:</SPAN>
    <td colspan="3">'.OCIResult($consulta, 1).'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Identificaci&oacute;n:</SPAN>
    <td colspan="3">'.$cedula.'</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Direcci&oacute;n:</SPAN>
    <td colspan="3"><input name="dir" type="text" id="dir" value="'.OCIResult($consulta, 2).'" size="63" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="50"></td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Tel&eacute;fono:</SPAN>
    <td width="26%"><input name="tel" type="text" id="tel" value="'.OCIResult($consulta, 3).'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td>
    <td width="12%" align="right"><SPAN class=Estilo5>Tel&eacute;fono alt:</SPAN></td>
    <td width="45%"><input name="tela" type="text" id="tela" value="'.OCIResult($consulta, 4).'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Celular:</SPAN>
    <td><input name="cel" type="text" id="cel" value="'.OCIResult($consulta, 5).'" size="18" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" maxlength="15"></td>
    <td align="right"><SPAN class=Estilo5>Sexo:</SPAN></td>
    <td>
	<select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.OCIResult($consulta, 6).'" selected>'.OCIResult($consulta, 6).'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select>
  </td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>Estado civil:</SPAN>
    <td>
	<select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.OCIResult($consulta, 8).'" selected>'.OCIResult($consulta,
    8).'</option>
	<option value="1">1 Soltero</option>
    <option value="2">2 Casado</option>
    <option value="3">3 Unión libre</option>
    <option value="4">4 Separado</option>
    <option value="5">5 Viudo</option>
  </select>
  <input name="estc" type="hidden" id="estc" value="'.OCIResult($consulta, 7).'" size="1" style="text-align: center" readonly>
	</td>
    <td align="right"><SPAN class=Estilo5>Tipo sangre:</SPAN></td>
    <td>
	<select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.OCIResult($consulta, 9).'" selected>'.OCIResult($consulta, 9).'</option>
	<option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
  </select>
  <input name="tisa" type="hidden" id="tisa" value="'.OCIResult($consulta, 9).'" size="1" style="text-align: center" readonly>
	</td>
  </tr>
  <tr>
    <td align="right"><SPAN class=Estilo5>E-mail:</SPAN>
    <td colspan="3"><input name="mail" type="text" id="mail" value="'.OCIResult($consulta, 10).'" size="63" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td>
  </tr>
  <tr>
  <td colspan="4" align="center">&nbsp;';
  require_once(dir_script.'mensaje_error.inc.php');
  if(isset($_GET['error_login'])){
     $error=$_GET['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>Error: $error_login_ms[$error]</a>";
  }
echo'</td>
  </tr>
  <tr>
    <td colspan="4" align="center"><input type=submit name="actualizar" value="Grabar" title="Grabar cambios"></td>
  </tr>
</table></form><p>&nbsp;&nbsp;</p><p>&nbsp;&nbsp;</p>';
cierra_bd($consulta,$oci_conecta);
fu_pie(); ?>
</BODY>
</HTML>