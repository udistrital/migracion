<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if(isset($_REQUEST['t'])){
   $QryAdm = "SELECT dep_cod, dep_nombre, cra_cod, cra_nombre
		FROM gedep, actipcra, accra
		WHERE dep_cod = cra_dep_cod
		and tra_nivel = '".$_REQUEST['t']."'
		and tra_cod = cra_tip_cra
		AND cra_estado = 'A'
		AND tra_estado = 'A'
		AND EXISTS(SELECT est_cod
		FROM acest
		WHERE accra.cra_cod = est_cra_cod
		AND est_estado_est IN ('A','B','H','L'))
		ORDER BY 2,1,4 asc";
   
	$RowAdm = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAdm,"busqueda");

	print'<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="2">
	<caption>'.$_REQUEST['t'].'</caption>
		<tr class="tr">
			<td align="center">C&oacute;digo</td>
			<td align="center">Proyecto Curricular</td>
			<td align="center">Facultad</td>
		</tr>';
		$i=0;
		$adm = 0;
		while(isset($RowAdm[$i][0]))
		{ 
			print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
				<td align="right">'.$RowAdm[$i][2].'</td>
				<td align="left" style="font-size:11px">'.$RowAdm[$i][3].'</td>
				<td align="left" style="font-size:11px">'.$RowAdm[$i][1].'</td>
			</tr>';
			$adm++;
			$i++;
		}
		print'<tr>
			<td align="center" colspan="3"><b>TOTAL '.$_REQUEST['t'].': '.$adm.'</b></td>
		</tr>
	</table><p></p>';
}
?>