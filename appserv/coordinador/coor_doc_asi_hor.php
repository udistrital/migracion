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
//LLAMADO DE: coor_doc_carga.php
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<hr>
<?php 
require_once(dir_script.'msql_doc_asi_hor.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

if($_REQUEST['asicod']) $asicod = $_REQUEST['asicod'];
include(dir_script.'class_nombres.php');
$nombre = new Nombres;
$Asignatura = $nombre->rescataNombre($asicod,"NombreCarrera");
?>
  <table border="0" width="90%" align="center" cellpadding="0" cellspacing="0">
  <caption><?php echo $Asignatura.' - '.$_REQUEST['curso']; ?></caption>
  <tr class="tr">
	<td align="center">D&iacute;a</td>
    <td align="center">Hora</td>
    <td align="center">Sal&oacute;n</td>
	<td align="center">Sede</td>
	<td align="center">Edificio</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="center">'.$consulta[$i][0].'</td> 
	<td align="center">'.$consulta[$i][1].'-'.$consulta[$i][2].'</td> 
	<td align="center">'.$consulta[$i][3].'</td>
	<td align="center">'.$consulta[$i][4].'</td>
	<td align="center">'.$consulta[$i][5].'</td></tr>';
$i++;
}
?>
</BODY>
</HTML>