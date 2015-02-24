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

require_once('msql_doc_por_inactivar.php');

$Rowcursor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cursor,"busqueda");

if(isset($Rowcursor))
{
	$accion = "";
}
else
{
	$accion = "prog_usu_por_inactivar.php";
}
print'<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
<caption>DOCENTES INACTIVOS EN LA UD</caption>
<tr class="tr">
  <td align="center">No.</td>
  <td align="center">C&oacute;digo</td>
  <td align="center">Nombre</td>
  <td align="center">Est. D.</td>
  <td align="center">C&oacute;ndor</td>
</tr>';
$i=0;
$cont=$i+1;
while(isset($Rowcursor[$i][0]))
{
	print'<tr class="td">
		<td align="right">'.$cont.'</td>
		<td align="right">'.$Rowcursor[$i][0].'</td>
		<td align="left">'.$Rowcursor[$i][1].'</td>
		<td align="center">'.$Rowcursor[$i][2].'</td>
		<td align="center">'.$Rowcursor[$i][3].'</td>
	</tr>';
	$i++;
}
print'</table><p></p>
<form name="form1" method="post" action="'.$accion.'">
  <div align="center">
    <input type="submit" name="Submit" value="Inactivar en C&oacute;ndor" class="button" '.$evento_boton.'>
	<input name="tipo" type="hidden" value="301">
  </div>
</form>';
?>