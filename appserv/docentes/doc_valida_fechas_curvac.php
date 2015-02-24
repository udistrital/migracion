<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);
$conpervac = "SELECT ape_ano, ape_per FROM acasperi WHERE ape_estado = 'V'";
$rowconpervac = $conexion->ejecutarSQL($configuracion,$accesoOracle,$conpervac,"busqueda");
$ano = $rowconpervac[0][0];
$per = $rowconpervac[0][1];

$confechoy = "SELECT TO_NUMBER(TO_CHAR(SYSDATE, 'YYYYMMDD')) FROM dual";
$rowconfechoy = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confechoy,"busqueda");
$fechahoy = $rowconfechoy[0][0];

$confechas = "SELECT TO_NUMBER(TO_CHAR(ACE_FEC_INI, 'YYYYMMDD')), TO_NUMBER(TO_CHAR(ACE_FEC_FIN, 'YYYYMMDD')),TO_CHAR(ACE_FEC_FIN, 'dd-Mon-yyyy')
	FROM accaleventos
	WHERE ace_cra_cod =".$_SESSION["C"]."
	AND ace_anio = $ano
	AND ace_periodo = $per
	AND ace_cod_evento = 52";
//echo $confechas;
$rowconfechas = $conexion->ejecutarSQL($configuracion,$accesoOracle,$confechas,"busqueda");
$fecini = $rowconfechas[0][0];
$fecfin = $rowconfechas[0][1];
$fecha = $rowconfechas[0][2];

if($fechahoy < $fecini || $fechahoy > $fecfin)
{
	$msg = '<h3><B>EL PROCESO DE DIGITACI&Oacute;N DE NOTAS PARA CURSOS VACACIONALES SE ENCUENTRA CERRADO</h3>';
	$btn_grabar = '';
	$sbgc = "style='background-color: #F0F0E1'";
}
else
{
	 $msg = "";
	 $btn_grabar = '<input type=submit name="upd" value="Grabar">';
	 $sbgc = "style='background-color: #FFFFFF'";
}
?>