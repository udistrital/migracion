<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4); 
?>
<HTML>
<HEAD><TITLE>Requisitos</TITLE>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
$cod_consul = "SELECT req_cod,asi_nombre,req_sem
		FROM ACREQ, ACASI 
		WHERE req_cod = asi_cod 
		AND req_cra_cod =".$_REQUEST['cracod']."
		AND req_asi_cod =".$_REQUEST['asicod']."
		AND req_estado = 'A'
		ORDER BY req_cod";
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

if(!is_array($consulta))
{
die('<center><h3>La asignatura no tiene requisitos.</h3></center>');
}

$asicod = $_REQUEST['asicod'];
require_once(dir_script.'NombreAsignatura.php');
ob_start();
?>
  <p>&nbsp;</p>
  <table width="95%" border="1" align="center" cellspacing="0" cellpadding="3">
  <caption><? echo '<span class="Estilo5">REQUISITOS DE: '.$_REQUEST['asicod'].' - '.$Asignatura; ?></span></caption>
  <tr class="tr">
  <td align="center">C&oacute;digo</td>
  <td align="center">Nombre de la Asignatura</td>
  <td align="center">Sem.</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right">'.$consulta[$i][0].'</td>
	<td align="left">'.$consulta[$i][1].'</td>
	<td align="left">'.$consulta[$i][2].'</td></tr>'; 
$i++;
}
?>
</table>
<p align="center"><input name="Bc" type="button" value="Cerrar" onClick="javascript:window.close()" style="width:120; cursor:pointer"></p>
</BODY>
</HTML>