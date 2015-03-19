<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'Fecha_Hora.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
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
<script language="JavaScript" src="../script/ventana.js"></script>
</HEAD>
<BODY topmargin="0" leftmargin="0">

<?php
fu_cabezote("LISTA DE CLASE");
if($_REQUEST['as'] != ""){
   $_SESSION['carrera'] = $_REQUEST['C'];
}

$estado = 'V';
require_once(dir_script.'msql_lisclase.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

$as=$consulta[0][0];
$gr=$consulta[0][2];

echo'<br><br><br><div align="center"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td width="8%" align="right">'.$hor.'</td>
		<td width="40%" align="center">'.$fec.'</td>
		<td width="5%" align="right">&nbsp;</td>
		<td width="7%" align="right">&nbsp;</td>
		<td width="10%" align="right"><b>Per&iacute;odo:</b></td>
		<td width="7%" align="right">'.$consulta[0][8].'-'.$consulta[0][9].'</td>
	</tr>
	<tr>
		<td width="8%" align="right"><B>Asignatura:</B></td>
		<td width="40%" align="left">'.$consulta[0][1].'</td>
		<td width="5%" align="right"><B>Grupo:</B></td>
		<td width="7%" align="center">'.$consulta[0][2].'</td>
		<td width="10%" align="right"><B>Semestre:</B></td>
		<td width="7%" align="right">'.$consulta[0][3].'</td>
	</tr>
	<tr>
		<td width="8%" align="right"><B>Carrera:</B></td>
		<td width="40%" align="left">'.$consulta[0][5].'</td>
		<td width="5%" align="right"><B>Cupo:</B></td>
		<td width="7%" align="center">'.$consulta[0][6].'</td>
		<td width="10%" align="right"><B>Inscritos:</B></td>
		<td width="7%" align="right">'.$consulta[0][7].'</td>
	</tr>
</table>
</center></div><p></p>';
?>
<div align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="1" style="border-collapse: collapse" bordercolor="#FFFFFF">
	<tr>
		<td width="2%" align="center"><b>Nro.</b></td>
		<td width="8%" align="center"><b>C&oacute;digo</b></td>
		<td width="60%" align="center"><b>Apellidos y Nombres</b></td>
		<td width="5%" align="center"><b>Est</b></td>
		<td width="15%" align="center">&nbsp;</td>
	</tr>
<?php
$i=1;
while(isset($consulta[$i][0]))
{
	echo'<tr>
		<td width="5%" align="right">'.$i.'</td>
		<td width="15%" align="right">'.$consulta[$i][11].'</td>
		<td width="60%" align="left">
		 <a href="doc_est_semaforo.php?estcod='.$consulta[$i][11].'" onMouseOver="link();return true;" onClick="link();return true;" title="Plan de estudio">'.$consulta[$i][12].'</a></td>
		<td width="5%" align="center">'.$consulta[$i][13].'</td>
		<td width="25%" align="center">
			<table border="1" width="300" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#FFFFFF">
				<tr>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
					<td width="5%" style="border: .8 dotted #000000">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>';
	$i++;
}
?>
</table>
</div>
<?php 
$print = "javascript:popUpWindow('print_lis_clase_curvac.php?as=$as&gr=$gr', 'yes', 0, 0, 790, 650)";
echo'<center><br><input type="submit" value="Imprimir Listado" onClick="'.$print.'"></center>';
?>
</BODY>
</HTML>
