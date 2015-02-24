<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
//LLAMADO DE: adm_consulta_datos_doc.php
?>
<HTML>
<HEAD>
<TITLE>Docentes</TITLE>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
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
fu_tipo_user(20);

//Actualiza datos
if($_POST['actualizar']) {
   $qry = OCIParse($oci_conecta, "UPDATE acdocente
		                             SET DOC_NOMBRE = :bnombre,
								         DOC_APELLIDO = :bapellido,
										 DOC_DIRECCION = :bdir,
			    					     DOC_TELEFONO = :btel,
										 DOC_TELEFONO_ALT = :btela,
										 DOC_SEXO = :bsexo,
										 DOC_ESTADO_CIVIL = :bestc,
										 DOC_TIPO_SANGRE = :btisa,
										 DOC_CELULAR = :bcel,
										 DOC_EMAIL = :bmail,
										 DOC_EMAIL_INS = :bmailud
							  	   WHERE doc_nro_iden =".$_POST['cedula']);	 
	OCIBindByName($qry, ":bnombre", trim(strtoupper($_POST['nombre'])));
	OCIBindByName($qry, ":bapellido", trim(strtoupper($_POST['apellido'])));
	OCIBindByName($qry, ":bdir", trim(strtoupper($_POST['dir'])));
	OCIBindByName($qry, ":btel", $_POST['tel']);
	OCIBindByName($qry, ":btela", $_POST['tela']);
	OCIBindByName($qry, ":bsexo", $_POST['sex']);
	OCIBindByName($qry, ":bestc", $_POST['estc']);
	OCIBindByName($qry, ":btisa", $_POST['tisa']);
	OCIBindByName($qry, ":bcel", $_POST['cel']);
	OCIBindByName($qry, ":bmail", trim(strtolower($_POST['mail'])));
	OCIBindByName($qry, ":bmailud", trim(strtolower($_POST['mailud'])));
	OCIExecute($qry);
	OCICommit($oci_conecta);
	cierra_bd($qry, $oci_conecta);
}
//Edita los datos
if($_POST['cedula']=="") 
   die("<br><br><br><center><span class='estilo11'><a OnMouseOver='history.go(-1)'>No hay registros para esta consulta.<br><br>Regresar</a></span></center>");

$consulta = OCIParse($oci_conecta, "SELECT DOC_NRO_IDEN,
										   DOC_NOMBRE,
										   DOC_APELLIDO,
	   									   DOC_DIRECCION,
										   DOC_TELEFONO,
										   DOC_TELEFONO_ALT,
										   DOC_CELULAR,
										   DOC_SEXO,
										   DOC_ESTADO_CIVIL,
										   TEC_NOMBRE,
										   DOC_TIPO_SANGRE,
										   DOC_EMAIL,
										   DOC_EMAIL_INS
  									  FROM ACDOCENTE,GETIPESCIVIL
 									 WHERE DOC_NRO_IDEN = ".$_POST['cedula']."
									   AND DOC_ESTADO_CIVIL = TEC_CODIGO(+)
   									   AND DOC_ESTADO = 'A'");
									    
OCIExecute($consulta);
$row = OCIFetch($consulta);
if($row != 1) die('<center><h3>No hay registros para esta consulta.</h3></center>');

echo'<br><br><br><form name="dat" method=post action="coor_actualiza_dat_doc.php">
<div align="center">
<table border="0" width="500" cellspacing="0" cellpadding="0" class="fondoTab">
<tr><td>&nbsp;</td></tr>
 <tr>
  <td width="91" align="right" height="1"><span class="Estilo5">Nombre:</span></td>
  <td width="451" colspan="4">
  <input name="nombre" type="text" id="nombre" value="'.OCIResult($consulta, 2).'" size="20" onChange="javascript:this.value=this.value.toUpperCase();" readonly>
  <input name="apellido" type="text" id="apellido" value="'.OCIResult($consulta, 3).'" size="37" onChange="javascript:this.value=this.value.toUpperCase();" readonly>
  </td></tr>
 <tr>
  <td width="91" align="right"><span class="Estilo5">Dirección:</span></td>
  <td width="451" colspan="4" align="left">
    <input name="dir" type="text" id="dir" value="'.OCIResult($consulta, 4).'" size="62" onChange="javascript:this.value=this.value.toUpperCase();" readonly>
 </td></tr>

 <tr>
  <td width="91" align="right"><span class="Estilo5">Teléfono:</span></td>
  <td width="189" align="left">
    <input name="tel" type="text" id="tel" value="'.OCIResult($consulta, 5).'" size="18"  onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" readonly>
    </td>
  <td width="83" align="right"><span class="Estilo5">Teléfono alt:</span></td>
  <td width="206" colspan="2" align="left"><input name="tela" type="text" id="tela" value="'.OCIResult($consulta, 6).'" size="15" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" readonly></td></tr>
 <tr>
  <td width="91" align="right"><span class="Estilo5">Celular:</span></td>
  <td width="189" align="left">
    <input name="cel" type="text" id="cel" value="'.OCIResult($consulta, 7).'" size="18" onKeypress="if(event.keyCode<45 || event.keyCode>57) event.returnValue=false;" readonly>
    </td>

  <td width="83" align="right"><span class="Estilo5">Sexo:</span></td>
  <td width="182" align="left">
  <select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.OCIResult($consulta, 8).'" selected>'.OCIResult($consulta, 8).'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select>
 </td>
  <td width="20" align="right">
  <input name="sex" type="hidden" id="sex" value="'.OCIResult($consulta, 8).'" size="1" style="text-align: center" readonly></td>
  </tr>
 <tr>
  <td width="91" align="right"><span class="Estilo5">Estado civil:</span></td>
  <td width="189" align="left">
  <select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.OCIResult($consulta, 9).'" selected>'.OCIResult($consulta, 10).'</option>
	<option value="1">1 Soltero</option>
    <option value="2">2 Casado</option>
    <option value="3">3 Unión libre</option>
    <option value="4">4 Separado</option>
    <option value="5">5 Viudo</option>
  </select>
  <input name="estc" type="hidden" id="estc" value="'.OCIResult($consulta, 9).'" size="1" style="text-align: center" readonly>
  </td>
  <td width="83" align="right"><span class="Estilo5">Tipo sangre:</span>
  </td>
  <td width="206" colspan="2" align="left">
  <select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.OCIResult($consulta, 11).'" selected>'.OCIResult($consulta, 11).'</option>
	<option value="A+">A+</option>
    <option value="A-">A-</option>
    <option value="B+">B+</option>
    <option value="B-">B-</option>
    <option value="AB+">AB+</option>
    <option value="AB-">AB-</option>
    <option value="O+">O+</option>
    <option value="O-">O-</option>
  </select>
  <input name="tisa" type="hidden" id="tisa" value="'.OCIResult($consulta, 11).'" size="1" style="text-align: center" readonly></td></tr>
 <tr><td width="91" align="right"><span class="Estilo5">E-mail:</span></td>
 <td width="451" colspan="4" align="left">
  <input name="mail" type="text" id="mail" value="'.OCIResult($consulta, 12).'" size="62" onChange="javascript:this.value=this.value.toLowerCase();" readonly>
 </td>
	</tr>
 <tr><td width="91" align="right"><span class="Estilo5">E-mail-UD:</span></td>
 <td width="451" colspan="4" align="left">
  <input name="mailud" type="text" id="mailud" value="'.OCIResult($consulta, 13).'" size="62" onChange="javascript:this.value=this.value.toLowerCase();" readonly>
 </td>
</tr>

 <tr><td width="510" align="center" colspan="5">&nbsp;';
  require_once(dir_script.'mensaje_error.inc.php');
  if(isset($_GET['error_login'])){
     $error=$_GET['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>Error: $error_login_ms[$error]</a>";
  }
echo'</td></tr>
 <tr><td width="510" align="center" colspan="5">
 <!-- <input type=submit name="actualizar" value="Grabar" class="button" '.$evento_boton.'></td> --> </tr>
</table>
<input name="cedula" type="hidden" id="cedula" value="'.$_POST['cedula'].'" size="10" readonly>
</div></form><br><br>';
cierra_bd($consulta,$oci_conecta);
fu_pie(); ?>
</BODY>
</HTML>