<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/AdmLisEmail.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>
<?php
fu_cabezote("OBSERVACIONES POR PARTE DE LOS ESTUDIANTES");
print'<div align="center"><span class="Estilo6">EN EL PROCESO DE EVALUACI&Oacute;N DOCENTE</span></div>';

include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
   print'<div align="center"><span class="Estilo5">PROYECTO CURRICULAR: '.$NomCra->rescataNombre($_REQUEST['cracod']).'</span></div>';

   print'<table width="70%" border="0" align="center" cellpadding="2" cellspacing="0">
	 <caption>docentes con carga acad&eacute;mica en el proyecto curricular</caption>';
	
	$carrera = $_REQUEST['cracod'];
	$usuario = $_SESSION['usuario_login'];
	$nivel = $_SESSION["usuario_nivel"];
	require_once(dir_script.'NombreUsuario.php');
	
	//require_once(dir_script.'msql_correos_doc.php');
	$Qry_EmDoc = "SELECT DISTINCT(DOC_NOMBRE||' '||DOC_APELLIDO),DOC_EMAIL,DOC_NRO_IDEN
		FROM ACDOCENTE,ACCARGA,ACASPERI
		WHERE APE_ANO = CAR_APE_ANO
		AND APE_PER = CAR_APE_PER
		AND CAR_CRA_COD = $carrera
		AND CAR_ESTADO = 'A'
		AND APE_ESTADO IN ('A','P')
		AND DOC_NRO_IDEN = CAR_DOC_NRO_IDEN
		AND DOC_EMAIL IS NOT NULL
		AND DOC_ESTADO = 'A'";

	$row_EmDoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Qry_EmDoc,"busqueda");
	//if($row_EmDoc != 1) { die('<h3>No hay registros para esta consulta.</h3>'); exit; }
	//header("Location: ../err/err_sin_registros.php");
	$i=0;
	while(isset($row_EmDoc[$i][0]))
	{
		print'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
		<td width="6%" align="right">'.$i.'</td>
		<td width="13%" align="right">'.$row_EmDoc[$i][2].'</td>
		<td align="left"><a href="coor_doc_obsevaciones.php?HrefCCDoc='.$row_EmDoc[$i][2].'" target="_self"  onMouseOver="link();return true;" onClick="link();return true;">'.$row_EmDoc[$i][0].'</a></td></tr>';
	$i++;
	}
	
	print'<tr><td colspan="3" align="center" background="../img/td.gif">Si falta alg&uacute;n docente, se debe a que no tiene asignada una carga acad&eacute;mica.</td></tr>
	</table>
	<p></p>';
}
?>
</body>
</html>