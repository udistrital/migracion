<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
ob_start();
?>
<HTML>
<HEAD>
<TITLE>Estadisticas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<?php
$PoblacionAspLoc = "SELECT loc_nombre, COUNT(asp_localidad),to_number(trim(loc_nro))
		FROM acasp, mntac.aclocalidad
		WHERE asp_ape_ano = $Anio
		AND asp_ape_per = $Peri
		AND loc_ape_ano = asp_ape_ano
		AND loc_ape_per = asp_ape_per
		AND loc_nro = asp_localidad
		GROUP BY loc_nombre,to_number(trim(loc_nro))
		ORDER BY to_number(trim(loc_nro))";

$RowAspLoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$PoblacionAspLoc,"busqueda");

print'<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
<tr class="tr"><td align="center">C&oacute;d.</td>
<td align="center">Localidad</td>
<td align="center">Pob.</td></tr>';
$i=0;
while(isset($RowAspLoc[$i][0]))
{
   print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
   <td align="right" class="Estilo12">'.$RowAspLoc[$i][2].'</td>
   <td align="left" class="Estilo3">'.$RowAspLoc[$i][0].'</td>
   <td align="right" class="Estilo3">'.$RowAspLoc[$i][1].'</td></tr>';
   $cont = $cont + $RowAspLoc[$i][1];
   $i++;
}
print'<tr><td align="right" colspan="2"><b>Total:</b></td>
<td align="right"><b>'.$cont.'</b></td></tr></table>';
?>
</BODY>
</HTML>