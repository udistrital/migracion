<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once ("../calendario/calendario.php");
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
fu_tipo_user(20);
//LLAMADO DE: adm_consulta_datos_est.php
?>
<HTML>
<HEAD>
<TITLE>Estudiantes</TITLE>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
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
global $raiz;
$nombreformulario = "dat";
$nombrecampo = "fecnac";

//Actualiza datos
if($_POST['actualizar']) {
   require_once('msql_actualiza_datos_est.php');
}

//Edita los datos
if($_POST['estcod']=="") 
   die("<br><br><br><center><span class='estilo11'><a OnMouseOver='history.go(-1)'>No hay registros para esta consulta.<br><br>Regresar</a></span></center>");

$datos = OCIParse($oci_conecta, "SELECT lug_cod,lug_nombre FROM gelugar ORDER BY lug_nombre");
OCIExecute($datos);
$rows = OCIFetch($datos);

require_once('msql_consulta_datos_est.php');  
$vlrmatri = sprintf("%.0f", OCIResult($consulta, 17));
$foto = est_foto.$_POST['estcod'].'.jpg';

if(!file_exists($foto)) {
	$foto="../img/sinfoto.png";
	$imgfoto='<img border="0" src="'.$foto.'" width="130" height="100"  alt="Sin fotografia almacenada">';
}
else{ $imgfoto='<img border="0" src="'.$foto.'" width="130" height="100" alt="Fotografía del Estudiante">'; }

echo'<form name="dat" method="post" action="adm_actualiza_datos_est.php">
<div align="center">
<table border="0" width="650" cellspacing="0" cellpadding="0" class="fondoTab">
 <tr>
  <td width="142" align="center" rowspan="5">'.$imgfoto.'&nbsp;</td>
  <td width="86" align="right"><span class="Estilo5">Código:</span></td>
  <td width="422" colspan="3">'.OCIResult($consulta, 1).'</td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Carrera:</span></td>
  <td width="422" colspan="3">'.OCIResult($consulta, 16).'</td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Estado:</span></td>
  <td width="422" colspan="3">'.OCIResult($consulta, 18).'</td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Nombre:</span></td>
  <td width="422" colspan="3"><span class="Estilo5">
  <input name="estnom" type="text" id="estnom" value="'.OCIResult($consulta, 2).'" size="62" onChange="javascript:this.value=this.value.toUpperCase();" readonly>
  </span></td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Identificación:</span></td>
  <td width="422" colspan="3"><span class="Estilo5">
  <input name="nroiden" type="text" id="nroiden" value="'.OCIResult($consulta, 3).'" size="12" readonly>
  </span></td></tr>
 <tr>
  <td width="142" align="center" rowspan="10">&nbsp;</td>
  <td width="86" align="right"><span class="Estilo5">Fecha Nac.:</span></td>
  <td width="179"><input name="fecnac" type="text" id="fecnac" value="'.OCIResult($consulta, 13).'" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')" readonly>
  <input type=button value="Cal" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')"></td>
  <td width="120" align="right"><span class="Estilo5">Lugar:</span></td>
  <td width="190">
  <select size="1" name="CIUNOM" onclick="javascript:document.forms.dat.lugnac.value = document.forms.dat.CIUNOM.value" style="font-family: Tahoma; font-size: 8pt">';
  do{
     echo'<option value="'.OCIResult($datos, 1).'" selected>'.OCIResult($datos, 2).'</option>\n';
  }while(OCIFetch($datos));
  echo'<option value="'.OCIResult($consulta, 14).'" selected>'.OCIResult($consulta, 15).'</option>
  \n</select><input name="lugnac" type="hidden" id="lugnac" value="'.OCIResult($consulta, 14).'" size="12" readonly>
  </td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Dirección:</span></td>
  <td width="422" colspan="3"><input name="dir" type="text" id="dir" value="'.OCIResult($consulta, 4).'" size="62" onChange="javascript:this.value=this.value.toUpperCase();" readonly></td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Teléfono:</span></td>
  <td width="179"><input name="tel" type="text" id="tel" value="'.OCIResult($consulta, 5).'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" readonly></td>
  <td width="88" align="right"><span class="Estilo5">Zona postal:</span>
  </td>
  <td width="190"><input name="zonap" type="text" id="zonap" value="'.OCIResult($consulta, 6).'" size="4" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" readonly></td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Estado Civil:</span></td>
  <td width="179">
   <select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.OCIResult($consulta, 12).'" selected>'.OCIResult($consulta, 12).'</option>
	<option value="0">0 Sin dato</option>
	<option value="1">1 Soltero(a)</option>
    <option value="2">2 Casado(a)</option>
    <option value="3">3 Unión libre</option>
    <option value="4">4 Separado(a)</option>
    <option value="5">5 Viudo</option>
   </select>
  <input name="estc" type="hidden" id="estc" value="'.OCIResult($consulta, 11).'" size="3" style="text-align: center" readonly></td>
  <td width="88" align="right"><span class="Estilo5">Sexo:</span>
  </td>
  <td width="190" align="left">
  <select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.OCIResult($consulta, 7).'" selected>'.OCIResult($consulta, 7).'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select>
   <input name="sex" type="hidden" id="sex" value="'.OCIResult($consulta, 7).'" size="3" style="text-align: center" readonly></td>
  </tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Tipo sangre:</span></td>
  <td width="179" valign="middle" align="left">
 <select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.OCIResult($consulta, 8).'" selected>'.OCIResult($consulta, 8).'</option>
	<option value="A">A</option>
    <option value="B">B</option>
    <option value="AB">AB</option>
    <option value="O">O</option>
  </select><input name="tisa" type="hidden" id="tisa" value="'.OCIResult($consulta, 8).'" size="3" style="text-align: center" readonly></td>
  <td width="88" align="right"><span class="Estilo5">RH:</span></td>
  <td width="190">
  <input name="rh" type="text" id="rh" value="'.OCIResult($consulta, 9).'" size="3" style="text-align: center" style="font-size: 10 pt; font-weight: bold" readonly></b></td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">E-mail:</span></td>
  <td width="422" colspan="3">
  <input name="mail" type="text" id="mail" value="'.OCIResult($consulta, 10).'" size="62" onChange="javascript:this.value=this.value.toLowerCase();" readonly></td></tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">E-mail-UD:</span></td>
  <td width="422" colspan="3"><input name="mailud" type="text" id="mailud" value="'.OCIResult($consulta, 19).'" size="62" onChange="javascript:this.value=this.value.toLowerCase();" readonly></td>
  </tr>
 <tr>
  <td width="86" align="right"><span class="Estilo5">Vlr.Matrícula:</span></td>
  <td width="422" colspan="3">$'.number_format($vlrmatri).'</td></tr>
 <tr>
  <td width="650" align="center" colspan="4" height="20">&nbsp;'; 
  require_once(dir_script.'mensaje_error.inc.php');
  if(isset($_GET['error_login'])){
     $error=$_GET['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a>";
  }
echo'</td></tr><tr><td width="650" align="center" colspan="4" height="10">
     <!-- <input type=submit name="actualizar" value="Grabar" class="button" '.$evento_boton.'></td> -->
	 </tr>
</table>
<input name="estcod" type="hidden" id="estcod" value="'.$_POST['estcod'].'" size="10" readonly>
</form>';
cierra_bd($consulta,$oci_conecta);
cierra_bd($datos, $oci_conecta);
fu_pie(); 
?>
</BODY>
</HTML>