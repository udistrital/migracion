<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'Fecha_Hora.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD><TITLE>Notas Parciales</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY>

<?php
fu_cabezote("- LISTA DE CLASE -");

$docnroiden = trim($_REQUEST['CD']);
if($_REQUEST['as'] != ""){
   $_SESSION['carrera'] = $_REQUEST['C'];
   $_SESSION["A"] = $_REQUEST['as'];
   $_SESSION["G"] = $_REQUEST['cur'];
   $_SESSION["C"] = $_REQUEST['cur'];
   $as = $_REQUEST['as'];
   $gr = $_REQUEST['cur'];

}

$estado = 'A';
require_once('msql_notaspar_doc.php');
$regist = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");

//---------------------------
echo'<p>&nbsp;</p>
<table align="center" border="1" width="90%" cellspacing="2" cellpadding="0">
  <tr><td align="right">'.$regist[0][4].'</td>
  <td><b>'.$regist[0][5].'</b></td>
  <td align="center"><b>Grupo</b></td>
  <td align="center"><b>Inscritos</b></td>
  <td align="center"><b>Periodo</b></td>
  </tr>
  <tr>
  <td align="right">'.$regist[0][0].'</td>
  <td align="left"><b>'.$regist[0][1].'</b></td>
  <td align="center">'.$regist[0][6].' ('.$regist[0][34].')'.'</td>		
  <td align="center">'.$regist[0][32].'</td>
  <td align="center">'.$regist[0][2].'-'.$regist[0][3].'</td>
  </tr></table>
  <p></p>
  <table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
    <tr class="td">
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="10" align="center">PORCENTAJES DE NOTAS</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="tr">
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td align="center">%1</td>
	  <td align="center">%2</td>
	  <td align="center">%3</td>
	  <td align="center">%4</td>
	  <td align="center">%5</td>
	  <td align="center">%6</td>
	  <td align="center">LAB</td>
	  <td align="center">EXA</td>
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
    </tr>
	 <tr>
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td width="24" align="center">'.$regist[0][11].'</td>
	  <td width="25" align="center">'.$regist[0][13].'</td>
	  <td width="25" align="center">'.$regist[0][15].'</td>
	  <td width="25" align="center">'.$regist[0][17].'</td>
	  <td width="25" align="center">'.$regist[0][19].'</td>
	  <td width="25" align="center">'.$regist[0][21].'</td>
	  <td width="25" align="center">'.$regist[0][27].'</td>
	  <td width="25" align="center">'.$regist[0][23].'</td>
	  <td width="26" align="center">'.$regist[0][25].'</td>
	  <td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>
	  <td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>
	  <td width="27" align="center"><font color="#ffffff" size="2" face="Tahoma">.</td>
	 

    </tr>';
?>
  
    <tr class="td">
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="10" align="center">NOTAS PARCIALES</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="tr">
	  <td align="center">No.</td>
      <td align="center">C&oacute;digo</td>
      <td align="center">Nombre</td>
      <td align="center">1</td>
      <td align="center">2</td>
      <td align="center">3</td>
      <td align="center">4</td>
      <td align="center">5</td>
      <td align="center">6</td>
      <td align="center">LAB</td>
      <td align="center">EXA</td>
      <td align="center">HAB</td>
      <td align="center">ACU</td>
      <td align="center">DEF</td>
    </tr>
<?php
$i=0;
$No=$i+1;
while(isset($regist[$i][0]))
{
	echo'<tr>
	<td align="center">'.$No.'</td>
	<td align="right">'.$regist[$i][7].'</td>
	<td>
	<a href="coor_est_semaforo.php?estcod='.$regist[$i][7].'" onMouseOver="link();return true;" onClick="link();return true;" title="Plan de estudio">'.$regist[$i][8].'</a></td>
	<td width="24" align="center">'.$regist[$i][10].'</td>
	<td width="25" align="center">'.$regist[$i][12].'</td>
	<td width="25" align="center">'.$regist[$i][14].'</td>
	<td width="25" align="center">'.$regist[$i][16].'</td>
	<td width="25" align="center">'.$regist[$i][18].'</td>
	<td width="25" align="center">'.$regist[$i][20].'</td>
	<td width="25" align="center">'.$regist[$i][24].'</td>
	<td width="25" align="center">'.$regist[$i][22].'</td>
	<td width="26" align="center">'.$regist[$i][26].'</td>
	<td width="27" align="center">'.$regist[$i][31].'</td>
	<td width="27" align="center">'.$regist[$i][28].'</td>
	<td>&nbsp;</td></tr>';
$i++;
$No++;
}
?>
<tr><td colspan="12" align="right" style="font-size:9px">Dise&ntilde;&oacute; Oficina Asesora de Sistemas</td></tr>
</table>
<p></p>
<?php 
$print = "javascript:popUpWindow('print_lis_clase.php?as=$as&gr=$gr&CD=$docnroiden', 'yes', 0, 0, 790, 650)";
echo'<center><br><input type="submit" value="Imprimir Listado" onClick="'.$print.'" style="cursor:pointer"></center>';
?>
</BODY>
</HTML>