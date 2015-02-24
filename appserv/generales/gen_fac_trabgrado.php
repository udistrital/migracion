<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_cabezote("MODALIDAD DE TRABAJOS DE GRADO");
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
<table align="center" width="60%" border="1" >
<caption>MODALIDADES DE TRABAJOS DE GRADO POR FACULTAD</caption>
  <tr class="tr">
    <td align="center">C&oacyte;digo</td>
    <td align="center">Nombre</td>
  </tr>
<?php
$QryFac = "SELECT unique(cra_dep_cod), dep_nombre
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND cra_dep_cod NOT IN(0, 20,500)
	ORDER BY 1";

$RowFac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");

$TotEstActUD = 0;
$i=0;
while(isset($RowFac[$i][0]))
{
	print'<tr>
	<td align="right">'.$RowFac[$i][0].'</td>
	<td><a href="grado'.$RowFac[$i][0].'.pdf" target="principal">'.$RowFac[$i][1].'</a></td></tr>';
	
$i++;
}

if($_REQUEST['depcod'] == "") $_REQUEST['depcod'] = 23;

$depcod = $_REQUEST['depcod'];
require_once(dir_script.'NombreFacultad.php');
$RowFac=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");
$Facultad = $RowFac[0][1];
?>
</table>
<p>&nbsp;</p>
<table width="60%"  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
	<p align="justify">Haga clic en el nombre de la Facultad a la cual pertenece su Proyecto Curricular, para revisar la reglamentaci&oacute;n relacionada con las modalidades de trabajos de grado.</p>
	</td>
  </tr>
</table>
</body>
</html>