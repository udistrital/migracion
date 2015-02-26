<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once('../administracion/class_cuenta_est.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_cabezote("ESTUDIANTES ACTIVOS");
?>
<html>
<head>
<title>oas</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>

<body>
<p></p>
<table align="center" width="600" border="1" >
<caption>TOTAL DE ESTUDIANTES ACTIVOS POR FACULTAD</caption>
  <tr class="tr">
    <td align="center">C&oacute;digo</td>
    <td align="center">Nombre</td>
    <td align="center">Total</td>
  </tr>
<?php
$QryFac = "SELECT DISTINCT(cra_dep_cod), dep_nombre,fua_tot_activos_fac(cra_dep_cod)
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND cra_dep_cod NOT IN(0,20,500)
	ORDER BY 1";

$RowFac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");

$TotEstActUD = 0;

$i=0;
while(isset($RowFac[$i][0]))
{
	print'<tr>
	<td align="right">'.$RowFac[$i][0].'</td>
	<td><a href="gen_est_abhl.php?depcod='.$RowFac[$i][0].'">'.$RowFac[$i][1].'</a></td>
	<td align="right">'.$RowFac[$i][2].'</td></tr>';
	$TotEstActUD = $TotEstActUD+$RowFac[$i][2];
	
$i++;
}
$_REQUEST['depcod']=(isset($_REQUEST['depcod'])?$_REQUEST['depcod']:23);

$depcod = $_REQUEST['depcod'];
require_once(dir_script.'NombreFacultad.php');
$RowFac=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");
$Facultad = $RowFac[0][1];
?>
<tr>
 <td align="right">&nbsp;</td>
 <td align="right"><B>TOTAL DE ESTUDIANTES ACTIVOS EN LA UNIVERSIDAD:</B></td>
 <td align="right"><b><? print $TotEstActUD;?></b></td>
 </tr>
</table>
<p></p>
<center><span class="Estilo5"><? print $Facultad;?><BR>TOTAL DE ESTUDIANTES ACTIVOS POR PROYECTO CURRICULAR</span></center>
<table width="600" border="1" align="center" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:bottom">
  <tr class="tr">
    <td align="center">C&oacute;digo</td>
    <td align="center">Proyecto Curricular</td>
    <td align="center">Total</td>
  </tr>
<?php
$QryCraFac = "SELECT cra_cod, cra_nombre, fua_tot_activos_cra(cra_cod)
	FROM gedep, accra
	WHERE dep_cod = ".(int)$_REQUEST['depcod']."
	AND dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND dep_estado = 'A'
	ORDER BY 2,1 ASC";

$RowCraFac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCraFac,"busqueda");

$TotThisFac = 0;
$i=0;
while(isset($RowCraFac[$i][0]))
{
	print'<tr onClick="this.className=\'raton_arr\'" onDblClick="this.className=\'raton_aba\'">
	<td align="right">'.$RowCraFac[$i][0].'</td>
	<td>'.$RowCraFac[$i][1].'</td>
	<td align="right">'.$RowCraFac[$i][2].'</td></tr>';
	$TotThisFac = $TotThisFac+$RowCraFac[$i][2];
$i++;
}
?>
<tr>
 <td align="right">&nbsp;</td>
 <td align="right"><B>TOTAL DE ESTUDIANTES ACTIVOS EN LA FACULTAD:</B></td>
 <td align="right"><b><? print $TotThisFac;?></b></td>
 </tr>
</table>
</body>
</html>