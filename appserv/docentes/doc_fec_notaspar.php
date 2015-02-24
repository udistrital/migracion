<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Calendario de digitaci&oacute;n de notas parciales</TITLE>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/fecha.js"></script>
</HEAD>
<BODY>
<?php
$confechoy = "SELECT TO_CHAR(SYSDATE, 'YYYY-MM-DD') FROM dual";
$reg1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confechoy,"busqueda");
$fechoy = $reg1[0][0];

$cons_fec = "SELECT NPF_CRA_COD,
		TO_CHAR(NPF_IPAR1, 'YYYY-MM-DD'),
		TO_CHAR(NPF_FPAR1, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IPAR2, 'YYYY-MM-DD'),
		TO_CHAR(NPF_FPAR2, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IPAR3, 'YYYY-MM-DD'), 
		TO_CHAR(NPF_FPAR3, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IPAR4, 'YYYY-MM-DD'), 
		TO_CHAR(NPF_FPAR4, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IPAR5, 'YYYY-MM-DD'), 
		TO_CHAR(NPF_FPAR5, 'YYYY-MM-DD'),
		TO_CHAR(NPF_ILAB, 'YYYY-MM-DD'), 
		TO_CHAR(NPF_FLAB, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IEXA, 'YYYY-MM-DD'),  
		TO_CHAR(NPF_FEXA, 'YYYY-MM-DD'),
		TO_CHAR(NPF_IHAB, 'YYYY-MM-DD'),
		TO_CHAR(NPF_FHAB, 'YYYY-MM-DD')
		FROM acnotparfec
		WHERE NPF_CRA_COD =".$_SESSION["C"]."
		AND NPF_ESTADO = 'A'";
		
$reg2=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cons_fec,"busqueda");

if(($fechoy < $reg2[0][1]) || ($fechoy > $reg2[0][2]) || ($reg2[0][1] == " ") || ($reg2[0][2] == " "))
	$msgpar1 = '<span class="cerrado">CERRADO</span>';
else $msgpar1 = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][3]) || ($fechoy > $reg2[0][4]) || ($reg2[0][3] == " ") || ($reg2[0][4] == " "))
	$msgpar2 = '<span class="cerrado">CERRADO</span>';
else $msgpar2 = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][5]) || ($fechoy > $reg2[0][6]) || ($reg2[0][5] == " ") || ($reg2[0][6] == " "))
	$msgpar3 = '<span class="cerrado">CERRADO</span>';
else $msgpar3 = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][7]) || ($fechoy > $reg2[0][8]) || ($reg2[0][7] == " ") || ($reg2[0][8] == " "))
	$msgpar4 = '<span class="cerrado">CERRADO</span>';
else $msgpar4 = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][9]) || ($fechoy > $reg2[0][10]) || ($reg2[0][9] == " ") || ($reg2[0][10] == " "))
	$msgpar5 = '<span class="cerrado">CERRADO</span>';
else $msgpar5 = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][11]) || ($fechoy > $reg2[0][12]) || ($reg2[0][11] == " ") || ($reg2[0][12] == " "))
	$msglab = '<span class="cerrado">CERRADO</span>';
else $msglab = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][12]) || ($fechoy > $reg2[0][14]) || ($reg2[0][13] == " ") || ($reg2[0][14] == " "))
	$msgexa = '<span class="cerrado">CERRADO</span>';
else $msgexa = '<span class="abierto">ABIERTO</span>';

if(($fechoy < $reg2[0][15]) || ($fechoy > $reg2[0][16]) || ($reg2[0][15] == " ") || ($reg2[0][16] == " "))
	$msghab = '<span class="cerrado">CERRADO</span>';
else $msghab = '<span class="abierto">ABIERTO</span>';

?>
<table border="1" width="95%" align="center">
<caption>FECHAS DE DIGITACI&Oacute;N DE NOTAS PARCIALES</caption>
<tr><td width="100%" align="center" colspan="4"><SCRIPT>dia()</SCRIPT></td></tr>
<tr class="tr" bordercolorlight="#CCCCCC">
	<td width="76" align="center">PARCIALES</td>
    <td width="106" align="center">FECHA INICIAL</td>
    <td width="94" align="center">FECHA FINAL</td>
    <td width="100" align="center">PERMISOS</td>
</tr>
<?php
$i=0;
while(isset($reg2[$i][0]))
{
	echo'<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">P1</td>
	<td width="106" align="center">'.$reg2[$i][1].'</td>
	<td width="94" align="center">'.$reg2[$i][2].'</td>
	<td width="100" align="center">'.$msgpar1.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">P2</td>
	<td width="106" align="center">'.$reg2[$i][3].'</td>
	<td width="94" align="center">'.$reg2[$i][4].'</td>
	<td width="100" align="center">'.$msgpar2.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">P3</td>
	<td width="106" align="center">'.$reg2[$i][5].'</td>
	<td width="94" align="center">'.$reg2[$i][6].'</td>
	<td width="100" align="center">'.$msgpar3.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">P4</td>
	<td width="106" align="center">'.$reg2[$i][7].'</td>
	<td width="94" align="center">'.$reg2[$i][8].'</td>
	<td width="100" align="center">'.$msgpar4.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">P5</td>
	<td width="106" align="center">'.$reg2[$i][9].'</td>
	<td width="94" align="center">'.$reg2[$i][10].'</td>
	<td width="100" align="center">'.$msgpar5.'</td>
	</tr>
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">LAB</td>
	<td width="106" align="center">'.$reg2[$i][11].'</td>
	<td width="94" align="center">'.$reg2[$i][12].'</td>
	<td width="100" align="center">'.$msglab.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">EXA</td>
	<td width="106" align="center">'.$reg2[$i][13].'</td>
	<td width="94" align="center">'.$reg2[$i][14].'</td>
	<td width="100" align="center">'.$msgexa.'</td>
	</tr>
	
	<tr bordercolorlight="#CCCCCC">
	<td width="76" align="center">HAB</td>
	<td width="106" align="center">'.$reg2[$i][15].'</td>
	<td width="94" align="center">'.$reg2[$i][16].'</td>
	<td width="100" align="center">'.$msghab.'</td>
	</tr>';
$i++;
}
?>
</table>
</BODY>
</HTML>