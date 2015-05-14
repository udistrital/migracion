<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(24);
?>
<HTML>
<HEAD>
<TITLE>Funcionarios</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</HEAD>
<BODY>
<?php
fu_cabezote("CURSOS");
$funcod = $_SESSION['usuario_login'];
$QryCod = "select emp_cod from peemp where emp_nro_iden = '".$_SESSION['usuario_login']."'";

$RowCod = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCod,"busqueda");
$_SESSION["fun_cod"]=$RowCod[0][0];

require_once('msql_cursos_fun.php');
$Rowcursos = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursos,"busqueda");
echo '<br>';print_r($Rowcursos);echo '<br>';
if(!is_array($Rowcursos))
{
	header("Location: ../err/err_sin_registros.php");
	exit;
}

echo'<p>&nbsp;</p>
  <table border="1" width="97%" align="center" '. $EstiloTab .'>
  <caption>CURSOS DE ACTUALIZACI&Oacute;N</caption>
	<tr class="tr">
		<td align="center">No.</td>
		<td align="center">Curso</td>
		<td align="center">Instituci&oacute;n</td>
		<td align="center">Nro. Horas</td>
		<td align="center">Desde</td>
		<td walign="center">Hasta</td>
	</tr>';
$i=1;
while(isset($Rowcursos[$i][0]))
{
	echo'<tr>
		<td align="left">'.$i.'</td>
		<td align="left">'.$Rowcursos[$i][0].'</td>
		<td align="left">'.$Rowcursos[$i][1].'</td>
		<td align="center">'.$Rowcursos[$i][2].'</td>
		<td align="center">'.$Rowcursos[$i][3].'</td>
		<td align="center">'.$Rowcursos[$i][4].'</td>
	</tr>';
$i++;
}
?>
</table></div><BR>
<? 
require_once('inconsistencia.php');
?>
</BODY>
</HTML>