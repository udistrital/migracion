<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(16);

fu_cabezote("CONTROL CIERRE DE SEMESTRE");
$usuario = $_SESSION["usuario_login"];
$nivel = $_SESSION["usuario_nivel"];

require_once('msql_consulta_decanos.php');
$row_dec=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_dec,"busqueda");
$depcod = $row_dec[0][0];
$depnom = $row_dec[0][1];

require_once('msql_control_cierre.php');
$row_cierre=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cierre,"busqueda");

?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<BR><BR>
<table border="1" align="center" cellspacing="0" cellpadding="1" width="100%" >
	<tr class="tr">
		<td width="60" align="center">C&oacute;digo</td>
		<td width="450" align="center">Proyecto Curricular</td>
		<td width="398" align="center">Coordinador</td>
		<td width="209" align="center">Estado</td>
	</tr>
	<?php
	$i=0;
	while(isset($row_cierre[$i][0]))
	{
		if($row_cierre[$i][0] != "")
		{
			$cracod = $row_cierre[$i][2];
			require_once('msql_cerro_sem.php');
			
			print'<tr>
			<td width="68" align="right">'.$row_cierre[$i][2].'</td>
				<td width="511">'.$row_cierre[$i][3].'</td>
				<td width="398">'.$row_cierre[$i][4].'</td>
				<td width="140">'.$resul.'</td>
			</tr>';
		}
		else
		{
		print'<tr><td colspan="4" align="center" class="Estilo12"><center>TODOS LOS PROYECTOS CURRICULARES DE LA '.$depnom.'<BR>CERRARON SEMESTRE.</td></tr>';
		}
		$i++;
	}
?>
</table>
</body>
</html>