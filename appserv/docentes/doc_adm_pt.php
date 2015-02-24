<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_fecha_pt.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);

if($_POST['insobs']) $OnL = 'onLoad="this.document.observaciones.texobs.focus()"';
else $OnL = '';
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script>
var WinOpen=0;
function ListaValores(pag, R, S, D, H, an, al, iz, ar){
  if(WinOpen){
     if(!WinOpen.closed)
	    WinOpen.close();
  }
   WinOpen = window.open(pag+'?httpR='+R+'&httpS='+S+'&httpD='+D+'&httpH='+H, "Lov", "width="+an+",height="+al+",scrollbars=YES,left="+iz+",top="+ar);
}
</script>
<SCRIPT language="JavaScript" type="text/javascript">
<!--
function ConTex(Char, ConChar) {
  var Limite=500;
  if(Char.value.length > Limite) 
     Char.value = Char.value.substring(0, Limite);
  else 
     ConChar.value = Limite - Char.value.length;
}
//-->
</script>
</HEAD>
<BODY style="margin-top:0" <? print $OnL; ?>>
<?php
fu_cabezote("PLAN DE TRABAJO");

$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Insertar Actividad" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar Actividad" border="0">';
$usuario = $_SESSION['usuario_login'];
$nivel  = $_SESSION["usuario_nivel"];

require_once(dir_script.'NombreUsuario.php');
require_once('msql_doc_consulta_carlec_pt.php');
require_once('msql_doc_consulta_pt.php');
require_once('msql_doc_consulta_obs_pt.php');
require_once('msql_doc_cuenta_act_pt.php');

$LiaAct = "SELECT DAC_COD,DAC_NOMBRE,DAC_INTENSIDAD
					FROM ACDOCACTIVIDAD
					WHERE DAC_COD > 1
					AND DAC_ESTADO = 'A' ORDER BY 1";
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$LiaAct,"busqueda");
for ($i=0; $i<count($registro);$i++)
{
	$result[$i][1]=UTF8_DECODE($registro[$i][1]);
}

$Tipovinculacion = "select distinct dtv_tvi_cod,tvi_nombre
from acdoctipvin,acasperi,actipvin
where ape_ano=dtv_ape_ano
and tvi_cod=dtv_tvi_cod
and ape_per=dtv_ape_per
and ape_estado='A'
and dtv_estado='A'
and dtv_doc_nro_iden=$usuario";
$registro1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Tipovinculacion,"busqueda");
if(is_array($registro1))
{
	$InsObs="<input name='insobs' type='submit' value='Observaci&oacute;n al plan de trabajo' class='button' style='width:470' title='Haga clic para digitar una observaci&oacute;n a su plan de trabajo.'>";
}
$InsAct="<input name='InsAct' type='submit' value='Ingresar una actividad' class='button' style='width:470' title='Haga clic para digitar una observaci&acute;on a su plan de trabajo.'>";
$EstiloBoton = 'value="" style=\'background:url("../img/b_browse.png");width:22; background-position:midle;cursor:pointer;\' title="Lista de Valores"';

echo'<p></p>
	<table border="0" align="center" cellspacing="0" width="98%">
    <tr><td width="13%" align="right">Docente:</td>
      <td width="55%" align="left"><b>'.$Nombre.'</b></td>
      <td width=$resultado"25%" align="right">Per&iacute;odo:</td>
      <td width="8%" align="left">'.$ano.'-'.$per.'</td></tr>
    <tr><td align="right">Identificaci&oacute;n:</td>
      <td align="left">'.$usuario.'</td>
      <td align="right"><strong>Total Horas Plan de Trabajo:</strong></td>
      <td align="left"><b>'.$HorPt.'</b></td></tr></table>';

//FORMULARIO DE INSERCI�N DE ACTIVIDADES
if($HorPt < 40) {
	echo'<form name="InsActividad" method="POST" action="prog_doc_inserta_pt.php">
	
	<table width="100%" align="center" border="1" cellspacing="0">
	<caption>INSERTAR ACTIVIDADES</caption>
	<tr>
		<td colspan="4">
		<p align="justify">
		<b>1-</b>Para ingresar una actividad, seleccionela de la lista desplegable "<span class="Estilo10">ACTIVIDADES</span>".
		<b>2-</b>Haga clic en el &iacute;tem "D&iacute;a" o en el bot&oacute;n frente al mismo, esto le permitir&aacute; ver la lista de valores y all&iacute; podr&aacute; seleccionar la informaci&oacute;n deseada.
		<b>3-</b>Repita el paso 2 para los &iacute;tem "Hora, Sede y Sal&oacute;n".
		<b>4-</b>Complete el formulario y haga clic en el bot&oacute;n "Grabar".
		Para ingresar una nueva actividad repita los pasos 1, 2, 3 y 4.</p>
		<p><center>PL (Planta),  VE (Vinculaci&oacute;n especial)</center></p>
		</td>
    	</tr>
	<tr bgcolor="#E4E5DB">
	  <td colspan="4" align="left">
	  <select style="width:98%" size="1" name="act">
		<option value="" selected class="Estilo6">ACTIVIDADES</option>';
		$i=0;
		while(isset($registro[$i][0]))
		{
			echo'<option value="'.$registro[$i][0].'">'.$result[$i][1].'</option>';
		$i++;
		}
	  print'</select>
	  </td>
    	</tr>
    	<tr bgcolor="#E4E5DB">
	  <td colspan="4" align="left">
	  <select style="width:98%" size="1" name="tipvin">
		<option value="" selected class="Estilo6">TIPO DE VINCULACI&Oacute;N</option>';
		$i=0;
		while(isset($registro1[$i][0]))
		{
			echo'<option value="'.$registro1[$i][0].'">'.$registro1[$i][1].'</option>';
		$i++;
		}
	  print'</select>
	  </td>
	</tr>
	<tr class="tr">
		<td align="center">D&iacute;a</td>
		<td align="center">Hora</td>
		<td align="center">Sede</td>
		<td align="center">Sal&oacute;n</td>
		</tr>';
	echo'<tr>
	<td align="center">
	<input name="dia" type="text" id="dia" value="" size="12" onClick="ListaValores(\'doc_lov_dia.php\',	this.name, sed.value, dia.value, hor.value, 240, 200, 450, 390)" readonly style="text-align:right; cursor:pointer">
	<input name="lv" type="button" '.$EstiloBoton.' onClick="ListaValores(\'doc_lov_dia.php\',	\'dia\', sed.value, dia.value, hor.value, 240, 200, 450, 390)"></td>
	<td align="center">
	<input name="hor" type="text" id="hor" value="" size="12" onClick="ListaValores(\'doc_lov_hora.php\', this.name, sed.value, dia.value, hor.value, 240, 200, 650, 390)" readonly style="text-align:right; cursor:pointer">
	<input name="lv" type="button" '.$EstiloBoton.' onClick="ListaValores(\'doc_lov_hora.php\',	\'hor\', sed.value, dia.value, hor.value, 240, 200, 650, 390)"></td>
	<td align="center">
	<input name="sed" type="text" id="sed" value="" size="12" onClick="ListaValores(\'doc_lov_sede.php\', this.name, sed.value, dia.value, hor.value, 340, 200, 650, 390)" readonly style="text-align:right; cursor:pointer">
	<input name="lv" type="button" '.$EstiloBoton.' onClick="ListaValores(\'doc_lov_sede.php\',	\'sed\', sed.value, dia.value, hor.value, 340, 200, 650, 390)"></td>
	<td align="center">
	<input name="sal" type="text" id="sal" value="" size="12" onClick="ListaValores(\'doc_lov_salon.php\', this.name, sed.value, dia.value, hor.value, 340, 200, 650, 390)" readonly style="text-align:right; cursor:pointer">
	<input name="lv" type="button" '.$EstiloBoton.' onClick="ListaValores(\'doc_lov_salon.php\', \'sal\', sed.value, dia.value, hor.value, 340, 200, 650, 390)"></td>
	</tr>
	<tr>
	<td colspan="4" align="center"><input type=submit name="actualizar" value="Grabar" style="cursor:pointer"></td></tr>
	</table></form>';
}
else{
print'<table width="100%" align="center" border="1" cellspacing="0">
	<tr><td align="center"><strong>DECRETOS, ACUERDOS Y RESOLUCIONES DEL R&Eacute;GIMEN DOCENTE</strong></td>
	</tr>
	<tr><td>
<p align="justify">
<strong>ART&Iacute;CULO 10</strong>.- Tiempo completo. El docente de tiempo completo est&aacute; obligado a dedicar a la universidad cuarenta (40) horas  semanales en las funciones propias de su cargo. Cualquier extensi&oacute;n adicional a su jornada semanal de trabajo se har&aacute; en t&eacute;rminos de la ley. <BR>
<BR>
<strong>ART&Iacute;CULO 11.</strong>- Medio tiempo. El docente de medio tiempo dedica a la universidad veinte (20) horas  semanales en las funciones propias de su cargo. Cualquier extensi&oacute;n adicional a su jornada semanal de trabajo se har&aacute; en t&eacute;rminos de la ley. <BR>
</p>
</td></tr></table><br>';
}

//TABLA DE CARGA LECTIVA
print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<caption>CARGA LECTIVA</caption>
  <tr class="tr">
    <td align="center">Asignatura</td>
    <td align="center">Tipo de vinculaci&oacute;n</td>
    <td align="center">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td align="center">D&iacute;a</td>
    <td align="center">Hora</td>
    <td align="center">Sede</td>
    <td align="center">Sal&oacute;n</td>
  </tr>';

	$registro2=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCarLec,"busqueda");
	$i=0;
	while(isset($registro2[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
		<td>'.UTF8_DECODE($registro2[$i][4]).'</td>
		<td>'.$registro2[$i][9].'</td>
		<td><b>'.$registro2[$i][10].'</b></td>
		<td>'.$registro2[$i][5].'</td>
		<td>'.$registro2[$i][6].'</td>
		<td align="center">'.$registro2[$i][7].'</td>
		<td align="center">'.$registro2[$i][8].'</td></tr>';
	$i++;
	}
echo '</table>';

echo '<br><center>';

echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB"><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS LECTIVAS</b></td><td align="center">.</td></tr>';

$contarTipVin = "SELECT count(*) FROM actipvin";
$registroTipVin=$conexion->ejecutarSQL($configuracion,$accesoOracle,$contarTipVin,"busqueda");
$totalTipVin=$registroTipVin[0][0];

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB"><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroLec[$k].'</td></td><td align="center"><b>'.$NroTip[$k].'</b></td></tr>';
	}
}
echo '</table>';
echo '</center><br>';



//TABLA DE GESTI�N DE ACTIVIDADES
echo'<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"><tr><td>';
if(isset($_REQUEST['error_login'])){
   $error=$_REQUEST['error_login'];
   echo"<br><center><font face='Tahoma' size='2' color='#FF0000'>$error_login_ms[$error]</font></center>";
}
echo'&nbsp;</td></tr></table>';

echo'<table width="98%" border="1" align="center" cellspacing="0" cellpadding="1">
<caption>GESTI&Oacute;N DE ACTIVIDADES</caption>
  <tr class="tr">
  	  <td align="center">Actividad</td>
  	  <td align="center">Tipo de vinculaci&oacute;n</td>
  	  <td align="center">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	  <td lign="center">D&iacute;a</td>
	  <td align="center">Hora</td>
	  <td align="center">Sede</td>
	  <td align="center">Sal&oacute;n</td>
      <td align="center">Borrar</td>
  </tr>';

	$registro3=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
	$i=0;
	while(isset($registro3[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'" style="font-size:11px">
		<td align="left">'.UTF8_DECODE($registro3[$i][3]).'</td>
		<td align="left">'.$registro3[$i][14].'</td>
		<td align="left"><b>'.$registro3[$i][15].'</b></td>
		<td align="center">'.$registro3[$i][4].'</td>
		<td align="left">'.$registro3[$i][5].'</td>
		<td align="left">'.$registro3[$i][6].'</td>
		<td align="right">'.$registro3[$i][7].'</td>
		<td align="center">
		<a href="prog_doc_borra_pt.php?&Ac='.$registro3[$i][12].'&Hr='.$registro3[$i][11].'" onMouseOver="link();return true;" onClick="link();return true;">'.$b_deltbl.'</a></td></tr>';
	$i++;
	}
$registro4=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryObs,"busqueda");	
echo '<tr><td colspan="8" bgcolor="#E4E5DB"><strong>Observaci&oacute;n a su plan de trabajo:</strong><div align="justify">'.$registro4[0][3].'</div></td></tr></table>';
echo '<br><center>';
echo '<table style="border-collapse:collapse;border-color:black;"  border="1">';
echo '<tr bgcolor="#E4E5DB"><td align="center"><b>TIPO DE VINCULACION</b </td><td align="center"><b>TOTAL HORAS ACTIVIDADES</b></td><td align="center"><b>TOTAL (LECTIVAS + ACTIVIDADES)</b></td><td align="center">.</td></tr>';

for($k=0;$k<$totalTipVin;$k++){
	if(!is_null($tipoVinculacion[$k])){
		echo '<tr bgcolor="#E4E5DB"><td> '.$tipoVinculacion[$k].'</td><td align="center"> '.$NroAct[$k].'</td><td align="center">'.((int)$NroAct[$k] + (int)$NroLec[$k]).'</td></td><td align="center"><b>'.$NroTip[$k].'</b></td></tr>';
	}
}

echo '</table>';
echo '</center><br>';

echo "<center><form action='doc_adm_pt.php' method='post' name='obspt' target='_self'>".$InsObs."</form></center>";

//INSERTAR OBSERVACIONES
if($_REQUEST['insobs'])
{
	$Qryobs = "SELECT 'S' as campo FROM ACDOCPLANTRABAJOBS, ACASPERI
			WHERE APE_ANO = DPO_APE_ANO
			AND APE_PER = DPO_APE_PER
			AND APE_ESTADO = 'A'
			AND DPO_DOC_NRO_IDEN = ".$_SESSION['usuario_login'];
			
	$row_obs=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryObs,"busqueda");
	
	if(is_array($row_obs))
	{
		$accion = 'prog_upd_obs_pt.php';
	}
	else
	{
		$accion = 'prog_ins_obs_pt.php';
	}
	
	print'<form action="'.$accion.'" method="post" name="observaciones">
	<div align="center">Digite las observaciones y haga clic en el bot&oacute;n "Grabar".<br>
	S&oacute;lo puede digitar <input type="text" name="contador" size="2" value="500" style="text-align:center" readonly>  caracteres.<br></div>
	<center><textarea name="texobs" cols="90" rows="8" id="texobs" onKeyDown="ConTex(this.form.texobs,this.form.contador);" onKeyUp="ConTex(this.form.texobs,this.form.contador);">'.$registro4[0][3].'</textarea><br>
	<input name="ins" type="submit" value="Grabar"></center></form>';
}
$print = "javascript:popUpWindow('print_rep_pt.php', 'yes', 0, 0, 780, 650)";
print'<div align="center"><input name="ppt" type="submit" value="Imprimir el Plan de Trabajo" class="button" style="width:470" onClick="'.$print.'"></div>';
?>
</BODY>
</HTML>