<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require(dir_conect.'conexion.php');
require_once('valida_http_referer.php');
//require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once('../calendario/calendario.php');
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'msql_salmin.php');
require_once(dir_script.'mensaje_error.inc.php');
fu_tipo_user(51);
?>
<HTML>
<HEAD>
<TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<link href="../script/estinx.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../script/SoloNumero.js"></script>
<script language="JavaScript" type="text/javascript" src="../script/clicder.js"></script>
<script language="JavaScript" type="text/javascript" src="../calendario/javascripts.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script> 
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
<BODY style="margin:0" onLoad="this.document.dat.actualizar.focus();">
<?php
ob_start();
global $raiz;
$nombreformulario = "dat";
$nombrecampo = "fecnac";

fu_cabezote("ACTUALIZACI&Oacute;N DE DATOS");

$redir = "est_actualiza_dat.php";
$estcod = $_SESSION['usuario_login'];

//Actualiza datos
if($_POST['actualizar']) {

   require(dir_conect.'conexion.php');
   require_once(dir_script.'msql_actualiza_datos_est.php');
}
//Edita los datos
require(dir_conect.'conexion.php');
$datos = OCIParse($oci_conecta, "SELECT lug_cod,lug_nombre FROM gelugar ORDER BY lug_nombre");
OCIExecute($datos);
$rows = OCIFetch($datos);

require_once(dir_script.'msql_consulta_datos_est.php');
if($row != 1) die('<center><h3>No hay registros para esta consulta.</h3><br><h3>Posiblemente se encuentra registrado en un estado que impide realizar esta acci&oacute;n.<br>Por favor contacte a su coordinaci&oacute;n para obtener detalles.</h3></center>');

$vlrmatri = number_format(OCIResult($consulta, 15));
$dif = OCIResult($consulta, 16);

//INICIO Controla la selecci�n de la matricula diferida
require_once(dir_script.'msql_ano_per.php');
$ConFecha = OCIParse($oci_conecta, "select 'S'
  									  from accaleventos
 									 where to_char(sysdate,'yyyymmdd') between to_char(ace_fec_ini,'yyyymmdd') and to_char(ace_fec_fin,'yyyymmdd')
   									   and ace_cod_evento=10
   									   and ace_anio=$ano
   									   and ace_periodo=$per");
OCIExecute($ConFecha );
$Row_ConFecha = OCIFetch($ConFecha );							   
									   
$fecha=OCIResult($ConFecha, 1);
$dat_diferido=OCIResult($consulta, 16);
$vlrmat = OCIResult($consulta, 15);

$inf = "javascript:popUpWindow('ay_inf_diferido.php', 'yes', 350, 200, 600, 450)";
$deu = "javascript:popUpWindow('est_deudor.php', 'no', 200, 200, 750, 400)";
$Botdeu = '<input type="button" name="Deudor" value="Deudor" onClick="'.$deu.'" title="Deudor" style="cursor:pointer">';
$obs = "javascript:popUpWindow('est_observaciones.php', 'no', 200, 200, 750, 400)";
$Botobs = '<input type="button" name="Observaciones" value="Observaciones" onClick="'.$obs.'" title="Obeservaciones" style="cursor:pointer">';

$carrera = $_SESSION['carrera'];
//RETORNA EL NIVEL DE LA CARRERA $Nivel == 'PREGRADO','POSGRADO'
require_once(dir_script.'NivelCarrera.php');
$TitMat = '';
$VlrTitMat = '';
if($Nivel != 'PREGRADO'){
   $anuncio='';
   $MatDif='';
   $TitMat = 'Vlr. Matricula:';
   $VlrTitMat = '$'.number_format($vlrmat);
}
else{
	if(($fecha=='S') && ($vlrmat > $med_salmin)){
	   $anuncio='Para diferir la matr&iacute;cula, seleccione Si/No, y haga clic en el bot&oacute;n Grabar.<br><br>';
	   $TitMatDif='Dif. Matr&iacute;cula:';
	   $BotMatDif = '<INPUT TYPE="button" value="Informacion" onClick="'.$inf.'" title="Informaci&oacute;n del diferido de la matricula" style="cursor:pointer">';
	   $MatDif='<select size="1" name="MD" onclick="javascript:document.forms.dat.matdif.value = document.forms.dat.MD.value">
				<option value="'.OCIResult($consulta, 16).'" selected>'.OCIResult($consulta, 16).'</option>
				<option value="S">Si</option>
				<option value="N">No</option></select>';
	}
	elseif(($fecha!='S') || ($vlrmat < $med_salmin)){
			$anuncio='<b>No puede diferir matr&iacute;cula por:</b><br>1. El valor de la matr&iacute;cula es menor a medio salario m&iacute;nimo.<br>2. La fecha de selecci&oacute;n caduc&oacute;, o Ud. ya difiri&oacute;.<br><br>';
			$TitMatDif='';
			$BotMatDif = '<INPUT TYPE="button" value="Informacion" onClick="'.$inf.'" title="Informaci&oacute;n del diferido de la matricula" style="cursor:pointer">';
			$MatDif='';
	}
}
//FIN Controla la selecci�n de la matricula diferida
echo'<br><br><form name="dat" method=post action="est_actualiza_dat.php">
<table border="0" width="550" align="center"  cellspacing="0" cellpadding="1">
 <tr>
  <td width="80" align="right">C&oacute;digo:</td>
  <td width="442" colspan="4">'.$estcod.'</td></tr>
 <tr>
  <td width="80" align="right">Nombre:</td>
  <td width="442" style="font-weight: bold" colspan="4">'.OCIResult($consulta, 1).'</td></tr>
 <tr>
  <td width="80" align="right">Identificaci&oacute;n:</td>
  <td width="442" colspan="4">'.OCIResult($consulta, 2).'</td></tr>
 <tr>
  <td width="80" align="right">Pry Curricular:</td>
  <td width="442" colspan="4">'.OCIResult($consulta, 17).'</td></tr>
 <tr>
  <td width="80" align="right">Fecha Nac.:</td>
  <td width="182"><input name="fecnac" type="text" id="fecnac" value="'.OCIResult($consulta, 12).'" size="12" onclick="muestraCalendario(\''.$raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')" maxlength="10" title="Ejm: 15-01-1985">
  <input TYPE="image" SRC="../img/cal.gif" width="19" height="19" alt="DD-MM-YYYY" onclick="muestraCalendario(\''.$raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')">
  </td>
  <td width="8require(dir_conect'.conexion.php.');1" align="right">Lugar:</td>
  <td width="179"  colspan="2">
  <select size="1" name="CIUNOM" onclick="javascript:document.forms.dat.lugnac.value = document.forms.dat.CIUNOM.value" style="font-family: Tahoma; font-size: 9px">';
  do{
      echo'<option value="'.OCIResult($datos, 1).'" selected>'.OCIResult($datos, 2).'</option>\n';
  }while(OCIFetch($datos));
  echo'<option value="'.OCIResult($consulta, 13).'" selected>'.OCIResult($consulta, 14).'</option>\n</select>
       <input name="lugnac" type="hidden" id="lugnac" value="'.OCIResult($consulta, 13).'" size="12"></td></tr>
 <tr>
  <td width="80" align="right">Direcci&oacute;n:</td>
  <td width="442" colspan="4"><input name="dir" type="text" id="dir" value="'.OCIResult($consulta, 3).'" size="62" onChange="javascript:this.value=this.value.toUpperCase();" maxlength="100"></td></tr>
 <tr>
  <td width="80" align="right">Tel&eacute;fono:</td>
  <td width="182"><input name="tel" type="text" id="tel" value="'.OCIResult($consulta, 4).'" size="18" onKeypress="return SoloNumero(event)" maxlength="10"></td>
  <td width="81" align="right" colspan="2">Zona postal:</td>
  <td width="179"><input name="zonap" type="text" id="tela" value="'.OCIResult($consulta, 5).'" size="4" onKeypress="return SoloNumero(event)"></td></tr>
 <tr>
  <td width="80" align="right">Estado Civil:</td>
  <td width="182">
   <select size="1" name="LEC" onclick="javascript:document.forms.dat.estc.value = document.forms.dat.LEC.value">
    <option value="'.OCIResult($consulta, 11).'" selected>'.OCIResult($consulta, 11).'</option>
	<option value="0">0 Sin dato</option>
	<option value="1">1 Soltero(a)</option>
    <option value="2">2 Casado</option>
    <option value="3">3 Uni&oacute;n libre</option>
    <option value="4">4 Separado</option>
    <option value="5">5 Viudo</option>
   </select>
  <input name="estc" type="hidden" id="estc" value="'.OCIResult($consulta, 10).'" size="3" style="text-align: center" readonly></td>
  <td width="81" align="right" colspan="2">Sexo:</td>
  <td width="179" align="left">
  <select size="1" name="SX" onclick="javascript:document.forms.dat.sex.value = document.forms.dat.SX.value">
    <option value="'.OCIResult($consulta, 6).'" selected>'.OCIResult($consulta, 6).'</option>
	<option value="M">M</option>
    <option value="F">F</option>
  </select>
  <input name="sex" type="hidden" id="sex" value="'.OCIResult($consulta, 6).'" size="3" style="text-align: center" readonly></td>
  </tr>
 <tr>
  <td width="80" align="right">Tipo sangre:</td>
  <td width="182" valign="middle" align="left">
 <select size="1" name="LTS" onclick="javascript:document.forms.dat.tisa.value = document.forms.dat.LTS.value">
    <option value="'.OCIResult($consulta, 7).'" selected>'.OCIResult($consulta, 7).'</option>
	<option value="A">A</option>
    <option value="B">B</option>
    <option value="AB">AB</option>
    <option value="O">O</option>
  </select><input name="tisa" type="hidden" id="tisa" value="'.OCIResult($consulta, 7).'" size="3" style="text-align: center" readonly></td>
  <td width="81" colspan="2" align="right">RH:</td>
  <td width="179"><p align="left"><b><font size="3">
  <input name="rh" type="text" id="rh" value="'.OCIResult($consulta, 8).'" size="3" style="text-align: center" style="font-size: 10 pt; font-weight: bold" maxlength="5"></b></p></td></tr>
 <tr>
  <td width="80" align="right">E-mail:</td>
  <td width="442" colspan="4">
  <input name="mail" type="text" id="mail" value="'.OCIResult($consulta, 9).'" size="62" onChange="javascript:this.value=this.value.toLowerCase();" maxlength="50"></td>
 </tr>
 <tr>
  <td width="80" align="right">E-mail UD:</td>
  <td width="442" colspan="4" style="font-weight: bold">
  <input name="institucional" type="text" id="institucional" value="'.OCIResult($consulta, 18).'" size="62" maxlength="1" readonly>
  </td>
 </tr>
 <tr>
  <td width="80" align="right">'.$TitMat.'</td>
  <td width="442" colspan="4"><strong>'.$VlrTitMat.'</strong></td>
 </tr>
 <tr>
  <td width="80" align="right">&nbsp;</td>
  <td width="442" colspan="4" align="left">'.$anuncio.'</td>
  </tr>
  
  <tr>
  <td width="80" align="right">'.$TitMatDif.'</td>
  <td width="442" colspan="4" align="left">'.$MatDif.'<span class="Estilo2">Seleccion&oacute; Matr&iacute;cula Diferida: <b>'.$dif.'</b></span>
  </td>
  </tr>
  <tr>
  <td width="80" align="right">
  <input name="matdif" type="hidden" id="matdif" value="'.OCIResult($consulta, 16).'" size="3"></td>
  <td width="400" colspan="4"><br><center>'.$BotMatDif.''.$Botdeu.''.$Botobs.'&nbsp;
  <input type=submit name="actualizar" value="Grabar" title="Graba los cambios" style="cursor:pointer">
  </center></td>
  </tr>
 <tr>
  <td width="522" align="center" colspan="5" height="20">
  <font color="#C0C080">&nbsp;';
  if(isset($_GET['error_login'])){
     $error=$_GET['error_login'];
     echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
		  <a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</font></a>";
  }
echo'</td></tr></table></form>';
//OCIFreeCursor($ConFecha);
//cierra_bd($consulta,$oci_conecta);
//cierra_bd($datos, $oci_conecta);
fu_pie();
//ob_end_flush();
?>
</BODY>
</HTML>
