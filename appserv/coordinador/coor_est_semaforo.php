<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'class_tiempo_carga.php'); 
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if (isset($_REQUEST['tipo'])){
if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}else{
    fu_tipo_user(4);
    $tipo=4; 
}}
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY>

<?php
if($_REQUEST['estcod'] != "") $estcod = $_REQUEST['estcod'];
if($_REQUEST['estcod'] != "") $estcod = $_REQUEST['estcod'];
$print = "javascript:popUpWindow('print_est_semaforo.php?estcod=$estcod', 'yes', 0, 0, 850, 650)";

require_once(dir_script.'msql_semaforo.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

$carrera = $consulta[0][3];
$_SESSION['carrera'] = $consulta[0][3];

echo'<p></p><table border="0" width="95%" align="center" cellspacing="0" cellpadding="2">
	 <caption>PLAN DE ESTUDIO<caption>
	 <tr><td width="13%" align="right">'.$consulta[0][0].'</td>
	 <td width="44%" align="left"><strong>'.$consulta[0][1].'</strong></td>
	 <td width="27%" align="right">Documento de Identidad: </td>
	 <td width="16%" align="left">'.$consulta[0][2].'</td></tr><tr> 
	 <td width="13%" align="right">'.$consulta[0][3].'</td>
	 <td width="44%" align="left"><strong>'.$consulta[0][4].'</strong></td>
	 <td width="27%" align="right">Promedio: </td>
	 <td width="16%" align="left">'.$consulta[0][5].'</td></tr>
	 <td width="13%" align="right"></td>
	 <td width="44%" align="left"></td>
	 <td width="27%" align="right">Pensum: </td>
	 <td width="16%" align="left">'.$consulta[0][6].'</td></tr></table>';
?>
  <div align="center">
  <table border="0" width="95%" cellspacing="0" cellpadding="2">
  <tr class="tr">
	<td align="center">C&oacute;digo</td>
	<td align="center">Asignatura</td>
	<td align="center">Sem</td>
	<td align="center">Nota</td>
	<td align="center">Observaci&oacute;n</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right">
	<a href="#" onClick="javascript:popUpWindow(\'coor_est_requisito_asig.php?asicod='.$consulta[$i][7].'&cracod='.$carrera.'\', \'yes\', 100, 100, 600, 300)" onMouseOver="link();return true;" onClick="link();return true;" title="Requisitos de la asignatura">'.$consulta[$i][7].'</a></td>
	<td>'.$consulta[$i][8].'</td>
	<td align="center">'.$consulta[$i][9].'</td>
	<td align="center">'.$consulta[$i][10].'</td>
	<td align="laft"><span class="Estilo3">'.$consulta[$i][11].'</span></td></tr>';
$i++;
}
?>
</table>
<?PHP
$tiempo = new getmicrotime; 
echo $tiempo->vertiempo();
echo'<br><input type="submit" value="Imprimir Plan de Estudio" onClick="'.$print.'" style="cursor:pointer">';
?>
</div>
</BODY>
</HTML>