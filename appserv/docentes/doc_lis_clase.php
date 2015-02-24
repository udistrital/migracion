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

fu_tipo_user(30);
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
fu_cabezote("LISTA DE CLASE");
if($_REQUEST['as'] != ""){
   $_SESSION['carrera'] = $_REQUEST['C'];
   $_SESSION["A"] = $_REQUEST['as'];
   $_SESSION["G"] = $_REQUEST['gr'];
}

$docnroiden = $_SESSION['usuario_login'];
$estado = 'A';
require_once(dir_script.'msql_notaspar_doc.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$row = $consulta;
$as = $consulta[0][4];
$gr = $consulta[0][6];
//---------------------------
echo'<p>&nbsp;</p>

<div align="center">
[<a href="prog_crea_archivo_lisclase.php?as='.$consulta[0][4].'&gr='.$consulta[0][6].'&C='.$consulta[0][31].'&cur='.$consulta[0][33].'" title="Crear archivo para Excel">Generar Archivo de Excel</a>]
</div>

<p></p>
<table align="center" border="1" width="90%" cellspacing="2" cellpadding="0">
  <tr><td align="right">'.$consulta[0][4].'</td>
  <td><b>'.$consulta[0][5].'</b></td>
  <td align="center"><b>Grupo</b></td>
  <td align="center"><b>Inscritos</b></td>
  <td align="center"><b>Periodo</b></td>
  </tr>
  <tr>
  <td align="right">'.$consulta[0][0].'</td>
  <td align="left"><b>'.$consulta[0][1].'</b></td>
  <td align="center">'.$consulta[0][6].'</td>
  <td align="center">'.$consulta[0][30].'</td>
  <td align="center">'.$consulta[0][2].'-'.$consulta[0][3].'</td>
  </tr></table>
  <p></p>
  <table width="90%" border="1" align="center" cellpadding="2" cellspacing="0">
    <tr class="td">
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="8" align="center">PORCENTAJES DE NOTAS</td>
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
      <td align="center">LAB</td>
      <td align="center">EXA</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
	<tr>
	  <td>&nbsp;</td>
      <td>'.$fec.'</td>
      <td>'.$hor.'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>';
?>
  
    <tr class="td">
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="8" align="center">NOTAS PARCIALES</td>
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
      <td align="center">LAB</td>
      <td align="center">EXA</td>
      <td align="center">HAB</td>
      <td align="center">DEF</td>
    </tr>
<?php
$i=0;
$cont=1;
while(isset($consulta[$i][0]))
{
	echo'<tr>
	<td align="center">'.$cont.'</td>
	<td align="right">'.$consulta[$i][7].'</td>
	<td>
	<a href="doc_est_semaforo.php?estcod='.$consulta[$i][7].'" title="Plan de estudio">'.$consulta[$i][8].'</a></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td></tr>';
$cont++;
$i++;
}
?>
<tr><td colspan="12" align="right" style="font-size:9px">Dise&ntilde;&oacute; Oficina Asesora de Sistemas</td></tr>
</table>
<p></p>
<?php 
$print = "javascript:popUpWindow('print_lis_clase.php?as=$as&gr=$gr', 'yes', 0, 0, 790, 650)";
echo'<center><br><input type="submit" value="Imprimir Listado" onClick="'.$print.'" style="cursor:pointer"></center>';
?>
</BODY>
</HTML>
