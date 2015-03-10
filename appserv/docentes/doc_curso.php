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
<script language="JavaScript" src="../script/BorraLink.js"></script> 
</HEAD>
<BODY>

<?php
fu_cabezote("LISTAS DE CLASE");
    
$cedula = $_SESSION['usuario_login'];
if (isset($_REQUEST['estado']))
{
    $estado = $_REQUEST['estado'];
}

require_once(dir_script.'msql_dig_notas.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");
$row = $consulta;
?><p>&nbsp;</p>
  <table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
  <tr>
	<td align="right" height="10" colspan="4">
    <b>Per&iacute;odo Acad&eacute;mico:</b></td>
	<td align="left"><? echo $ano.'-'.$per; ?></td>
  </tr>
  <tr class="tr">
	<td align="center" height="10">C&oacute;digo</td>
    <td align="center" height="10">Asignatura</td>
	<td align="center" height="10">Grupo</td>
	<td align="center" height="10">Inscritos</td>
	<td align="center" height="10">Carrera</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	//if($consulta[$i][12]=='PREGRADO' || $consulta[$i][12]=='EXTENSION')
	//{
		echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="left">
		<a href="doc_lis_clase.php?as='.$consulta[$i][8].'&gr='.$consulta[$i][13].'&C='.$consulta[$i][4].'" onMouseOver="link();return true;" onClick="link();return true;" title="Lista de clase">'.$consulta[$i][8].'</a></td>
		<td>'.htmlentities($consulta[$i][9]).'</td>
		<td align="center">'.$consulta[$i][10].'</td>
		<td align="center">'.$consulta[$i][11].'</td>
		<td align="left"><span class="Estilo3">'.$consulta[$i][5].'</span></td></tr>';
	//}
$i++;
}
?>
</table>&nbsp;</div>
<p></p>
<TABLE width=608 border=0 align="center" cellPadding=5 cellspacing="0">
<caption><br><br></caption>
<TBODY><TR><TD width="598">
	<ul>
      <li class = "PopItemStyle">&nbsp;Para ver el listado de estudiantes inscritos y generar un archivo  para Excel, haga clic en el c&oacute;digo de la asignatura. <strong>El  nombre del archivo ser&aacute; el de la asignatura y grupo</strong>.</li><br>
      <li class = "PopItemStyle">&nbsp;Para ver el plan de estudios de un estudiante, haga clic en el nombre.</li><br>
     
    </ul>
  </TD>
</TR></TBODY></TABLE>
<p>&nbsp; </p>
</BODY>
</HTML>
