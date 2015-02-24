<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);

require_once('msql_egresados_por_borrar.php');
$RowEstE = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEstE,"busqueda");

if(!is_array($RowEstE))
{
	$accion = "";
}
else
{
	$accion = "prog_egresados_por_inactivar.php";
}

print'<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
<caption>EGRESADOS CON USUARIO EN C&Oacute;NDOR</caption>
	<tr class="tr">
		<td align="center">No.</td>
		<td align="center">C&oacute;digo</td>
		<td align="center">Nombre</td>
		<td align="center">Proyecto Curricular</td>
		<td align="center">Est. E.</td>
		<td align="center">C&oacute;ndor</td>
	</tr>';
$i=0;
while(isset($RowEstE[$i][0]))
{
	if($RowEstE[$i][3] == "A")
	{
		$estadoE = '<font color="#FF0000"><b>'.$RowEstE[$i][3].'</b></font>';
	}
	else
	{
	$estadoE = $RowEstE[$i][3];
	}
   
	print'<tr class="td" onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td align="right">'.$i.'</td>
		<td align="right">'.$RowEstE[$i][0].'</td>
		<td align="left">'.$RowEstE[$i][1].'</td>
		<td align="left">'.$RowEstE[$i][4].'</td>
		<td align="center">'.$RowEstE[$i][2].'</td>
		<td align="center">'.$estadoE.'</td></tr>';
	$i++;
}
print'</table><p></p>
<form name="form1" method="post" action="'.$accion.'">
  <div align="center">
    <input type="submit" name="Submit" value="Inactivar en C&oacute;ndor" class="button" '.$evento_boton.'>
	<input name="tipo" type="hidden" value="51">
  </div>
</form>';
?>
</body>
</html>