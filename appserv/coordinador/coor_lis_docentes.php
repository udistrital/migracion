<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(4);

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<script language="JavaScript" src="../script/fecha.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<body style="margin-top:0; margin-bottom:0">
<?PHP
fu_cabezote("INFORMACI&Oacute;N DE LOS DOCENTES");
$usuario = $_SESSION["usuario_login"];

include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
	$carrera = $_REQUEST['cracod'];
	
	require_once('msql_coor_doc_con_carga.php');
	$row_doc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_doc,"busqueda");
	$NombreCarrera = isset($NombreCarrera) ? $NombreCarrera:"";
	echo'<table align="center" border="1" width="90%" cellpadding="1" cellspacing="0">
	<caption><span class="Estilo5">PROYECTO CURRICULAR: '.$NomCra->rescataNombre($carrera, $NombreCarrera).'<br>
	LISTADO DE DOCENTES CON ASIGNACI&Oacute;N ACAD&Eacute;MICA PARA EL PER&Iacute;ODO '.$ano.'-'.$per.'
	</span></caption>
	<tr class="tr">
	<td align="center">Identificaci&oacute;n</td>
	<td align="center">Nombre</td>
	<td align="center">Tel&eacute;fono</td>
	<td align="center">Correo Electr&oacute;nico</td></tr>';
	$i=0;
	while(isset($row_doc[$i][0]))
	{
		echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="right">
		<a href="coor_actualiza_dat_doc.php?cedula='.$row_doc[$i][0].'" target="principal" onMouseOver="link();return true;" onClick="link();return true;" title="Datos del docente">'.$row_doc[$i][0].'</a></td>
		<td align="left">
		<a href="coor_doc_frm_carga.php?CD='.$row_doc[$i][0].'" target="inferior" onMouseOver="link();return true;" onClick="link();return true;" title="Ver asignaci&oacute;n acad&eacute;mica">'.$row_doc[$i][1].'</a></td>
		<td align="right">'.$row_doc[$i][2].'</td>
		<td align="left">
		<a href="coor_form_contacto_doc.php?para='.$row_doc[$i][3].'" target="inferior" onMouseOver="link();return true;" onClick="link();return true;" title="Enviar correo">'.$row_doc[$i][3].'</a></td></tr>';
	$i++;
	}
}

?>
</body>
</html>