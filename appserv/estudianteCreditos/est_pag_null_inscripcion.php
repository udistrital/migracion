<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

fu_tipo_user(51);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$qry_param = "SELECT ('Su Proyecto Curricular, le permite tomar como m&aacute;ximo ')|| api_maximo_asignaturas ||(' asignaturas, y pueden ser de ')|| API_NRO_SEMESTRES ||
	       	(' semestres ')|| DECODE(api_consecutivos,'S','consecutivos.','N','no consecutivos.') A,
		('Su Proyecto Curricular, ')|| DECODE(api_semestres_superiores,'S','si','N','no') ||
		(' le permite tomar asignaturas de semestres superiores al que est&aacute; cursando.') B,
		('Si usted tiene 3 o m&aacute;s asignaturas perdidas, su Proyecto Curricular ')|| DECODE(api_mas_asignaturas,'S','si','N','no') ||
		(' le permite tomar asignaturas adicionales a las asignaturas perdidas.') C
		FROM acasperi, acparins 
		WHERE api_cra_cod = (SELECT est_cra_cod FROM acest WHERE est_cod=".$_SESSION['usuario_login'].") 
		AND ape_ano = api_ape_ano
		AND ape_per = api_ape_per
		AND ape_estado = 'A'";

$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_param,"busqueda");

?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
</head>
<body style="margin:0">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td colspan="2" align="center" class="Estilo10">
	 <strong>El proceso de adici&oacute;n y cancelaci&oacute;n de asignaturas, depende de los par&aacute;metros de inscripci&oacute;n y la programaci&oacute;n de horarios 
	fijados por el PROYECTO CURRICULAR.</strong>
	<hr noshade class="hr">
	</td>
  </tr>
  <tr>
    <td align="center" valign="top">1.</td>
    <td><? echo $registro[0][0]; ?></td>
  </tr>
  <tr>
    <td align="center" valign="top">2.</td>
    <td><? echo $registro[0][1]; ?></td>
  </tr>
  <tr>
    <td align="center" valign="top">3.</td>
    <td><? echo $registro[0][2]; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top"><hr noshade class="hr"></td>
  </tr>
</table>

</body>
</html>
