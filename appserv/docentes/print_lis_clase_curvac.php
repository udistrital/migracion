<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_print_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Lista de clase</TITLE>
</HEAD>
<BODY topmargin="0" leftmargin="0">
<CENTER>
<?php
fu_print_cabezote("LISTA DE CLASE");

$estado = 'V';
require_once(dir_script.'msql_lisclase.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

echo'<br><div align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td width="8%" align="right">'.$hor.'</td>
		<td width="40%" align="center">'.$fec.'</td>
		<td width="5%" align="right">&nbsp;</td>
		<td width="7%" align="right">&nbsp;</td>
		<td width="10%" align="right"><font face="Tahoma" size="2"><b>Per&iacute;odo:</b></font></td>
		<td width="7%" align="right"><font face="Tahoma" size="2">'.$consulta[0][8].'-'.$consulta[0][9].'</font></td>
	</tr>
	<tr>
		<td width="8%" align="right"><font face="Tahoma" size="2"><B>Asignatura:</B></font></td>
		<td width="40%" align="left"><font face="Tahoma" size="2">'.$consulta[0][1].'</font></td>
		<td width="5%" align="right"><font face="Tahoma" size="2"><B>Grupo:</B></font></td>
		<td width="7%" align="center"><font face="Tahoma" size="2">'.$consulta[0][2].'</font></td>
		<td width="10%" align="right"><font face="Tahoma" size="2"><B>Semestre:</B></font></td>
		<td width="7%" align="right"><font face="Tahoma" size="2">'.$consulta[0][14].'</font></td>
	</tr>
	<tr>
		<td width="8%" align="right"><font face="Tahoma" size="2"><B>Carrera:</B></font></td>
		<td width="40%" align="left"><font face="Tahoma" size="2">'.$consulta[0][5].'</font></td>
		<td width="5%" align="right"><font face="Tahoma" size="2"><B>Cupo:</B></font></td>
		<td width="7%" align="center"><font face="Tahoma" size="2">'.$consulta[0][6].'</font></td>
		<td width="10%" align="right"><font face="Tahoma" size="2"><B>Inscritos:</B></font></td>
		<td width="7%" align="right"><font face="Tahoma" size="2">'.$consulta[0][7].'</font></td>
	</tr>
</table>
</center></div>';
?>
<div align="center">
<table border="1" width="100%" cellspacing="0" cellpadding="1" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td width="2%" align="center"><font face="Tahoma" size="2"><b>Nro.</b></font></td>
		<td width="8%" align="center"><font face="Tahoma" size="2"><b>C&oacute;digo</b></font></td>
		<td width="60%" align="center"><font face="Tahoma" size="2"><b>Apellidos y Nombres</b></font></td>
		<td width="5%" align="center"><font face="Tahoma" size="2"><b>Est</b></font></td>
		<td width="15%" align="center">&nbsp;</td>
	</tr>
<?php
$i=0;
while(isset($consulta[$i][0]))
{
        $j=$i+1;
	echo'<tr>
		<td width="5%" align="right"><font face="Tahoma" size="2">'.$j.'</font></td>
		<td width="15%" align="right"><font face="Tahoma" size="2">'.$consulta[$i][11].'</font></td>
		<td width="60%" align="left"><font face="Tahoma" size="2">'.$consulta[$i][12].'</font></td>
		<td width="5%" align="center"><font face="Tahoma" size="2">'.$consulta[$i][13].'</font></td>
		<td width="25%" align="center">
			<table border="1" width="300" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#111111">
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
</div><br>
</BODY>
</HTML>