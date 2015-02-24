<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(34);
?>
<html>
<head>
<title>Docente Responsable</title>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

</head>
<body style="margin:0">
<?php
$QryDoc = "SELECT doc_nro_iden,
	((doc_nombre)||' '||(doc_apellido)),
	doc_telefono,
	doc_celular,
	doc_email
	FROM acdocente a
	WHERE doc_estado = 'A'
	AND EXISTS (SELECT car_doc_nro_iden
	FROM acasperi, accarga
	WHERE car_cur_asi_cod = ".$_REQUEST['a']."
	AND car_cur_nro = ".$_REQUEST['g']."
	AND a.doc_nro_iden = car_doc_nro_iden
	AND ape_ano = car_ape_ano
	AND ape_per = car_ape_per
	AND ape_estado = 'A'
	AND car_cra_cod = ".$_REQUEST['c']."
	AND car_estado = 'A')
	ORDER BY TRIM(doc_nombre) ASC";

$RowDoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryDoc,"busqueda");

include_once(dir_script.'class_nombres.php');
$NombreAsi = new Nombres;
?>
<div align="center"><h3><?php print $_REQUEST['a'].' - '.$NombreAsi->rescataNombre($_REQUEST['a']).' - GRUPO '.$_REQUEST['g']; ?></h3></div><p></p>
<table width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
<caption>docente responsable</caption>
  <tr class="tr">
    <td align="center">Identificaci&oacute;n</td>
    <td align="center">Nombre</td>
    <td align="center">Tel&eacute;fono</td>
    <td align="center">Celular</td>
    <td align="center">Correo Electr&oacute;nico</td>
  </tr>
<?php
$i=0;
while(isset($RowDoc[$i][0]))
{
	print'<tr>
	<td align="center">'.$RowDoc[$i][0].'</td>
	<td align="center">'.$RowDoc[$i][1].'</td>
	<td align="center">'.$RowDoc[$i][2].'</td>
	<td align="center">'.$RowDoc[$i][3].'</td>
	<td align="center">
	<a href="coor_form_contacto_doc.php?para='.$RowDoc[$i][4].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo">'.$RowDoc[$i][4].'</a></td>
	</tr>';
$i++;
}
?>
</table>
</body>
</html>