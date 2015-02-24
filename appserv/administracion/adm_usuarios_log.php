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

require_once('../calendario/calendario.php');

global $raiz;
$nombreformulario = "ususlog";
$nombrecampo1 = "feclog1";
$nombrecampo2 = "feclog2";

if(!isset($_REQUEST['usulog']) || !isset($_REQUEST['feclog1']) || !isset($_REQUEST['feclog2']))
{ 
	?>
		<html>
		<HEAD>
		<script language="JavaScript" type="text/javascript" src="../calendario/javascripts.js"></script>
		<script language="JavaScript" src="../script/SoloNumero.js"></script>
		<link href="estilo_adm.css" rel="stylesheet" type="text/css">
		</HEAD>
		
		<body onLoad="this.document.ususlog.usulog.focus()">
		<form action="adm_usuarios_log.php" method="post" name="ususlog" id="ususlog">
			<table width="33%"  border="1" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right">Usuario:</td>
					<td><input name="usulog" type="text" id="usulog" onKeypress="return SoloNumero(event)"></td>
				</tr>
				<tr>
					<td align="right">Rango de Fechas:</td>
					<td><? print'<input value="Inicial" name="feclog1" type="text" id="feclog1" title="dd/mm/aaaa" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo1.'\')"><br>
							<input value="Final" name="feclog2" type="text" id="feclog2" title="dd/mm/aaaa" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo2.'\')">'; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" name="Submit" value="Consultar" class="button"  <? print $evento_boton;?>>
					</td>
				</tr>
			</table>
		</form>
	<?PHP
}

else
{
	if($_REQUEST['usulog']=="" || $_REQUEST['feclog1']=="" || $_REQUEST['feclog2']=="")
	{
		header("Location: adm_principal.php");
	}
	$fecha1 = $_REQUEST['feclog1'];
	$fecha2 = $_REQUEST['feclog2'];
	
	if(!(ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $fecha1))||!(ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $fecha2))) 
	{
		echo "Por favor seleccione fechas";
	 
	}
	else
	{
	require_once('adm_usuarios_para_inactivar.php');
	$QryUlog="SELECT cnx_maquina,
			to_char(cnx_fecha,'dd-mm-yyyy'),
			cnx_hora,
			to_number(to_char(cnx_fecha,'yyyymmdd'))
			FROM geconexlog
			WHERE cnx_fecha BETWEEN '$fecha1' AND '$fecha2'
			AND cnx_usuario = ".$_REQUEST['usulog']."";
				
	$RowUlog = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUlog,"busqueda");
		
	if(is_array($RowUlog))
	{
		echo "Hay registros";
	}
	else
	{
		echo "No hay registros";
	}
	
	echo "<br>MMM".$RowUlog."<br>";
	echo "<br>NNN".$RowUlog[0][0]."<br>";
	
	print '<table width="300" border="1" align="center" cellpadding="2" cellspacing="0">
	<caption>LOG DEL USUARIO: '.$_REQUEST['usulog'].'<br>ENTRE EL "'.$_REQUEST['feclog1'].'"   Y EL   "'.$_REQUEST['feclog2'].'"</caption>
	<tr class="tr">
	  <td align="center">No.</td>
	  <td align="center">M&aacute;quina</td>
	  <td align="center">Fecha</td>
	  <td align="center">Hora</td>
	</tr>';
	
	$i = 0;
	while(isset($RowUlog[$i][0]))
	{
		print'<tr class="td">
			<td align="right">'.$i.'</td>
			<td align="right">'.$RowUlog[$i][0].'</td>
			<td align="center">'.$RowUlog[$i][1].'</td>
			<td align="center">'.$RowUlog[$i][2].'</td>
		</tr>';
		$i++;
	}
	echo "No entiendo por qué razon no funciona";
	print '</table><p></p>';
	}
}
?>
</body>
</html>