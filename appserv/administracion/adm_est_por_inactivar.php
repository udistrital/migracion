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
$estpi = "SELECT COUNT(est_cod), est_estado_est,estado_descripcion 
	FROM acest,geclaves, acestado
	WHERE cla_codigo = est_cod
	AND cla_tipo_usu = 51
	AND cla_estado = 'A'
	AND estado_cod = est_estado_est
	GROUP BY est_estado_est,estado_descripcion
	ORDER BY 2";

$Rowestpi = $conexion->ejecutarSQL($configuracion,$accesoOracle,$estpi,"busqueda");

if(!is_array($Rowcursor))
{
	$accion = "";
}
else
{
	$accion = "prog_usu_por_inactivar.php";
}

print'<table width="50%" border="1" align="center" cellpadding="2" cellspacing="0">
<caption>ESTUDIANTES ACTIVOS EN CONDOR</caption>
<tr class="tr">
  <td align="center">No.</td>
  <td align="center">Estado</td>
  <td align="center">Descripci&oacute;n del Estado Acad&eacute;mico</td>
</tr>';

$i=0;
while(isset($Rowestpi[$i][0]))
{
	print'<tr class="td" onClick="this.className=\'raton_arr\'" onDblClick="this.className=\'raton_aba\'">
		<td align="right">'.$Rowestpi[$i][0].'</td>
		<td align="center">'.$Rowestpi[$i][1].'</td>
		<td align="left">'.$Rowestpi[$i][2].'</td>
	</tr>';
   $i++;
}
print'</table><p></p>
<div align="center">Inactivar los estudiantes en estados diferentes a: (A,B,H,L).</div>
<form name="form1" method="post" action="prog_inactivar_est_por_estado.php">
<table width="98%" border=".5" align="center" cellpadding="0" cellspacing="0">
	<tr class="tr">
		<td width="52%" align="center">Inactivar por estados</td>
		<td width="48%" align="center">Inactivar todos</td>
	</tr>
	<tr>
		<td align="center">
		C<input name="EstadoEst" type="radio" value="C" style="cursor:pointer" title="C">
		D<input name="EstadoEst" type="radio" value="D" style="cursor:pointer" title="D">
 		E<input name="EstadoEst" type="radio" value="E" style="cursor:pointer" title="E">
 		F<input name="EstadoEst" type="radio" value="F" style="cursor:pointer" title="F">
 		G<input name="EstadoEst" type="radio" value="G" style="cursor:pointer" title="G">
 		I<input name="EstadoEst" type="radio" value="I" style="cursor:pointer" title="I">
 		J<input name="EstadoEst" type="radio" value="J" style="cursor:pointer" title="J">
 		K<input name="EstadoEst" type="radio" value="K" style="cursor:pointer" title="K">
		<br>
 		M<input name="EstadoEst" type="radio" value="M" style="cursor:pointer" title="M">
 		N<input name="EstadoEst" type="radio" value="N" style="cursor:pointer" title="N">
 		P<input name="EstadoEst" type="radio" value="P" style="cursor:pointer" title="P">
 		R<input name="EstadoEst" type="radio" value="R" style="cursor:pointer" title="R">
 		S<input name="EstadoEst" type="radio" value="S" style="cursor:pointer" title="S">
 		T<input name="EstadoEst" type="radio" value="T" style="cursor:pointer" title="T">
 		V<input name="EstadoEst" type="radio" value="V" style="cursor:pointer" title="V">
 		X<input name="EstadoEst" type="radio" value="X" style="cursor:pointer" title="X">
 		Z<input name="EstadoEst" type="radio" value="Z" style="cursor:pointer" title="Z">
		</td>
		<td align="center"><font color=#ff0000>Inactivar solo despu&eacute;s del cierre de oficializaci&oacute;n de estudiantes.</font></td>
	</tr>
	<tr>
		<td align="center"><br><input type="submit" name="Submit" value="Inactivar en C&oacute;ndor" class="button" '.$evento_boton.'title="Inactivar los del estado seleccionado"></form></td>
		<td align="center">
		<form name="form1" method="post" action="'.$accion.'"><br>
		<input type="submit" name="Submit" value="Inactivar en C&oacute;ndor" class="button" '.$evento_boton.' title="Inactivarlos a todos"></form></td>
	</tr>
</table>';
?>