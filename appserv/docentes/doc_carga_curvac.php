<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<link href="../script/classic.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>
<?php
fu_cabezote("LISTAS DE CLASE Y CAPTURA DE NOTAS");   
$cedula = $_SESSION['usuario_login'];

if($per == 1){
   $anio=$ano-1;
   $peri = 4;
}
elseif($per == 3){
	   $anio=$ano;
	   $peri = 2;
}

$estado = 'A';//cambiar a V
require_once(dir_script.'msql_cargadoc.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

if(!isset($consulta))
{ 
	die('<h3>No tiene asignaci&oacute;n acad&eacute;mica en cursos de vacaciones.</h3>');
	//header("Location: ../err/err_sin_cargavac.php"); 
	exit;
}
?>
<div align="center"><span class="Estilo11">CURSOS DE VACACIONES</span><br><br>
    <table border="1" width="727" cellspacing="0" cellpadding="2">
  <tr>
	<td width="43%" align="right" height="10" colspan="4"><b>Per&iacute;odo Acad&eacute;mico:</b></td>
	<td width="20%" align="left" height="10"><? echo $anio.'-'.$peri; ?></td>
  </tr>
  <tr class="tr">
	<td width="76" align="center" height="10">C&oacute;digo</td>
    <td width="320" align="center" height="10">Asignatura</td>
	<td width="40" align="center" height="10">Grupo</td>
	<td width="52" align="center" height="10">Inscritos</td>
	<td width="213" align="center" height="10">Carrera</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr><td width="8%" align="right" height="10">
		<a href="doc_lisclase_curvac.php?as='.$consulta[$i][8].'&gr='.$consulta[$i][10].'&C='.$consulta[$i][4].'&cur='.$consulta[$i][12].'" onMouseOver="link();return true;" onClick="link();return true;" title="Lista de clase">'.$consulta[$i][8].'</a></td>
		<td width="25%" height="10"><a href="doc_dig_curvac.php?A='.$consulta[$i][8].'&G='.$consulta[$i][10].'&C='.$consulta[$i][4].'&cur='.$consulta[$i][12].'" onMouseOver="link();return true;" title="Digitar notas">
		'.$consulta[$i][9].'</a></td>
		<td width="5%" align="center" height="10">'.$consulta[$i][10].'</td>
		<td width="5%" align="center" height="10">'.$consulta[$i][11].'</td>
		<td width="20%" align="left" height="10"><span class="Estilo3">'.$consulta[$i][5].'</span></td>
	</tr>';
$i++;
}
?>
</table>
</div>
<p></p>
<TABLE width=608 border=0 align="center" cellPadding=5 cellspacing="0">
<TBODY><TR><TD width="598">
	<ul>
      <li class="PopItemStyle">&nbsp;Para ver el listado de estudiantes inscritos, haga clic en el c&oacute;digo de la asignatura.</li><br>
	  <li class="PopItemStyle">&nbsp;Para ver el plan de estudios de un estudiante, haga clic en el nombre.</li><br>
      <li class="PopItemStyle">&nbsp;Para digitar notas, haga clic en el nombre de la asignatura.</li>
    </ul>
  </TD></TR></TBODY></TABLE>
<br><br>
<?php
?>
</BODY>
</HTML>
