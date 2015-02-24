<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(16);

$usuario = $_SESSION["usuario_login"];
$nivel = $_SESSION["usuario_nivel"];

require_once('msql_consulta_decanos.php');
$row_dec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_dec,"busqueda");
$depcod = $row_dec[0][0];
$depnom = $row_dec[0][1];

require_once('msql_consulta_coordinadores.php');
$row_coor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_coor,"busqueda");

?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body topmargin="0">

<table border="1" cellpadding="0" cellspacing="0" width="100%">
	<tr class="tr">
		<td width="100%" colspan="5" align="center">COORDINADORES DE LA <? echo $depnom?></td>
	</tr>
	<tr class="td">
		<td align="center">Cod.</td>
		<td align="center">Proyecto Curricular</td>
		<td align="center">Identificaci&oacute;n</td>
		<td align="center">Coordinador</td>
		<td align="center">Correo Electr&oacute;nico</td>
	</tr>
	<?
	$i=0;
	while(isset($row_coor[$i][0]))
	{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="right">'.$row_coor[$i][0].'</td>
		<td align="left">'.$row_coor[$i][1].'</td>
		<td align="right">'.$row_coor[$i][2].'</td>
		<td align="left">'.$row_coor[$i][3].'</td>
		<td align="left"><a href="dec_frm_contacto_coor.php?para='.$row_coor[$i][4].'" target="inferior" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo">'.$row_coor[$i][4].'</a></td>
	</tr>';
	$i++;
	}
	?>
</table>
</body>
</html>