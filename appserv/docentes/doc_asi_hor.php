<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
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
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY topmargin="0">
<?php 
require_once(dir_script.'msql_doc_asi_hor.php');
$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

$asicod = $_REQUEST['asicod'];
$NombreAsignatura= "SELECT trim(asi_nombre) 
		FROM acasi 
		WHERE asi_cod = $asicod
		AND asi_estado = 'A'";
$rowasi=$conexion->ejecutarSQL($configuracion,$accesoOracle,$NombreAsignatura,"busqueda");
$Asignatura = $rowasi[0][0];
?>
  <table width="690" align="center" border="0" cellspacing="0" cellpadding="2">
  <caption><?php echo $Asignatura; ?></caption>
  <tr class="tr">
	<td width="5%" align="center">Dia</td>
    <td width="4%" align="center">Hora</td>
    <td width="4%" align="center">Sal&oacute;n</td>
	<td width="4%" align="center">Sede</td>
	<td width="4%" align="center">Edificio</td>
  </tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
	echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td width="5%" align="center">'.$consulta[$i][0].'</td>
     	<td width="4%" align="center">'.$consulta[$i][1].'-'.$consulta[$i][2].'</td>
	<td width="4%" align="center">'.$consulta[$i][3].'</td>
	<td width="4%" align="center">'.$consulta[$i][4].'</td>
	<td width="4%" align="center">'.$consulta[$i][5].'</td></tr>'; 
$i++;
}
?>
</table>
</div>
</BODY>
</HTML>