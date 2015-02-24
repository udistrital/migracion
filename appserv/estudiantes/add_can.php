<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
include_once(dir_script.'class_nombres.php');
require_once(dir_conect.'fu_tipo_user.php');
$nom = new Nombres;

fu_tipo_user(51);


		$conexion=new multiConexion();
		$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

		$estcod = $_SESSION['usuario_login'];
		
		$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
		$estcra = $registroCarrera[0][0];


		$QryAdd = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
				 TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		    	FROM accaleventos,acasperi
		  	 WHERE APE_ANO = ACE_ANIO
			 AND APE_PER = ACE_PERIODO
			 AND APE_ESTADO = 'A'
			 AND ACE_CRA_COD = ".$estcra ."
			 AND ACE_COD_EVENTO = 15";
			 
		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryAdd,"busqueda");

		$AddFecIni = $registro[0][0];
		$AddFecFin = $registro[0][1];

		$QryCan = "SELECT TO_CHAR(ACE_FEC_INI, 'dd/Mon/YYYY'),
				 TO_CHAR(ACE_FEC_FIN, 'dd/Mon/YYYY')
		    FROM accaleventos,acasperi
		   WHERE APE_ANO = ACE_ANIO
			 AND APE_PER = ACE_PERIODO
			 AND APE_ESTADO = 'A'
			 AND ACE_CRA_COD = ".$estcra."
			 AND ACE_COD_EVENTO = 16";

		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCan,"busqueda");
		
		$CanFecIni = $registro[0][0];
		$CanFecFin = $registro[0][1];

?>
<html>
<head>
<title>Proceso de Adici&oacute;n y Cancelaci&oacute;n</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	$CRA = $nom->rescataNombre($estcod,"NombreCarrera");
	
	if(!is_array($registro)){ die('<h3>El Proyecto Curricular:<br> '.$CRA.'.<br><br> No ha fijado fechas de adici&oacute;n ni cancelaci&oacute;n de asignaturas.<h3>'); exit; } 
?>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr class="td">
    <td colspan="2" align="center" class="Estilo5">Adici&oacute;n</td>
  </tr>
  <tr class="tr">
    <td width="50%" align="center">Fecha Inicial</td>
    <td width="50%" align="center">Fecha Final</td>
  </tr>
  <tr>
    <td align="center"><? print $AddFecIni; ?></td>
    <td align="center"><? print $AddFecFin; ?></td>
  </tr>
</table>
<p></p>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="2">
  <tr class="td">
    <td colspan="2" align="center" class="Estilo5">Cancelaci&oacute;n</td>
  </tr>
  <tr class="tr">
    <td width="50%" align="center">Fecha Inicial</td>
    <td width="50%" align="center">Fecha Final</td>
  </tr>
  <tr>
    <td align="center"><? print $CanFecIni; ?></td>
    <td align="center"><? print $CanFecFin; ?></td>
  </tr>
</table>
</body>
</html>
