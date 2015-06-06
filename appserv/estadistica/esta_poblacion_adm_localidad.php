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
$PoblacionAdmLoc = "SELECT loc_nombre, COUNT(asp_localidad),TO_NUMBER(loc_nro,'999999')
				FROM mntac.acasp, mntac.aclocalidad
				WHERE asp_ape_ano = $Anio
				AND asp_ape_per = $Peri
				AND loc_ape_ano = asp_ape_ano
				AND loc_ape_per = asp_ape_per
				AND loc_nro = asp_localidad
				AND asp_admitido = 'A'
				GROUP BY loc_nombre,TO_NUMBER(loc_nro,'999999')
				ORDER BY TO_NUMBER(loc_nro,'999999')";

$RowAdmLoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAdmLoc,"busqueda");

print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
	<tr class="tr">
		<td align="center">C&oacute;d</td>
		<td align="center">Localidad</td>
		<td align="center">Pob.</td></tr>';
		$i=0;
		while(isset($RowAdmLoc[$i][0]))
		{
			print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
			<td align="right" class="Estilo12">'.$RowAdmLoc[$i][2].'</td>
			<td align="left" class="Estilo3">'.$RowAdmLoc[$i][0].'</td>
			<td align="right" class="Estilo3">'.$RowAdmLoc[$i][1].'</td></tr>';
			$cont = $cont + $RowAdmLoc[$i][1];
		$i++;
		}
		print'<tr><td align="right" colspan="2"><b>Total:</b></td>
		<td align="right"><b>'.$cont.'</b></td>
	</tr>
</table>';
?>
</body>
</html>