<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
fu_print_cabezote("NOTAS DEFINITIVAS CURSO DE VACACIONES");
$estado = 'V';

require_once(dir_script.'msql_notasdef.php');

$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

  echo'<div align="center"><table border="0" width="90%" cellspacing="1" cellpadding="2" style="font-family: Tahoma; font-size: 10 pt">
       <tr><td width="10%" align="right"><B>Asignatura:</B></td>
		   <td width="40%"><B><U>'.$consulta[0][1].'</U></B></td>
		   <td width="8%" align="right"><B>Grupo:</B></td>
		   <td width="5%" align="right">'.$consulta[0][2].'</td>
		   <td width="8%" align="right"><B>Semestre:</B></td>
		   <td width="6%" align="right">'.$consulta[0][9].'</td></tr>
		   
  <tr><td width="10%" align="right"><B>Carrera:</B></td>
		   <td width="40%">'.$consulta[0][4].'</td>
		   <td width="8%" align="right"><B>Cupo:</B></td>
		   <td width="5%" align="right">'.$consulta[0][10].'</td>
		   <td width="8%" align="right"><B>Inscritos:</B></td>
		   <td width="6%" align="right">'.$consulta[0][11].'</td></tr>
		   
  <tr><td width="10%" align="right"><B>Docente:</B></td>
		   <td width="40%"><B><U>'.$consulta[0][6].'</B></U></B></td>
		   <td width="8%" align="right"><B>A&ntilde;o:</B></td>
		   <td width="5%" align="right">'.$consulta[0][7].'</td>
		   <td width="8%" align="right"><B>Per&iacute;odo:</B></td>
		   <td width="6%" align="right">'.$consulta[0][8].'</td></tr></table></div>';
?>
  <div align="center"><table border="1" width="90%" cellspacing="0" cellpadding="2">
  <tr>
    <td width="2%" align="center"><font face="Tahoma" size="1"><b>Nro.</b></font></td>
	<td width="8%" align="center"><font face="Tahoma" size="2"><b>C&oacute;digo</b></font></td>
    <td width="50%" align="center"><font face="Tahoma" size="2"><b>Apellidos y Nombres</b></font></td>
	<td width="3%" align="center"><font face="Tahoma" size="2"><b>Nota</b></font></td>
	<td width="3%" align="center"><font face="Tahoma" size="2"><b>Obs</b></font></td>
  </tr>

<?php
$nro=1;
$i=0;
while(isset($consulta[$i][1]))
{
	echo'<tr>
		<td width="2%" align="right"><font face="Tahoma" size="2">'.$nro.'</font></td>
		<td width="8%" align="right"><font size="2" face="Tahoma">'.$consulta[$i][12].'</font></td>
		<td width="40%"align="left"><font size="2" face="Tahoma">'.$consulta[$i][13].'</font></td>
		<td width="3%" align="right"><font size="2" face="Tahoma">'.$consulta[$i][14].'</font></td>
		<td width="3%" align="right"><font size="2" face="Tahoma">'.$consulta[$i][15].'</font></td>
	</tr>';
	$nro++;
	$i++;
}
?>
<tr><td colspan="5" align="right" style="font-size:9px">Dise&ntilde;&oacute;: Oficina Asesora de Sistemas</td></tr>
</table>
</div>
<div align="center">
<br><br><br>
<table border="0" width="90%" cellspacing="1"><tr>
  <td width="25%">
    <p align="center">---------------------------</td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%">
    <p align="center">---------------------------</td></tr>
  <tr>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Firma del Docente</font></td>
  <td width="25%"></td>
  <td width="25%"></td>
  <td width="25%"><p align="center"><font size="2" face="Tahoma">Recibido</font></td></tr>
</table>
</div>
</BODY>
</HTML>