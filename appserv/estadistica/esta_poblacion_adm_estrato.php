<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<html>
<head>
<title>Aspirantes por a&ntilde;o y per&iacute;odo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?PHP 
$PoblacionAdm = "SELECT DECODE(trim(asp_estrato),99,'Sin',NULL, 'Nulos',asp_estrato),count(asp_estrato)
		FROM acasp
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		AND asp_admitido = 'A'
		GROUP BY asp_estrato
		ORDER BY asp_estrato";

$RowAdm = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAdm,"busqueda");

print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<tr class="tr"><td align="center">Estrato</td>
<td align="center">Poblaci&oacute;n</td></tr>';
$i=0;
while(isset($RowAdm[$i][0]))
{
	print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right" class="Estilo12">'.$RowAdm[$i][0].'</td>
	<td align="right">'.$RowAdm[$i][1].'</td></tr>';
	$cont = $cont + $RowAdm[$i][1];
$i++;
}
print'<tr><td align="right"><b>Total:</b></td>
<td align="right"><b>'.$cont.'</b></td></tr></table>';
?>
</body>
</html>