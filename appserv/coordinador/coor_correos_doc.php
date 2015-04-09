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
?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/AdmLisEmail.js"></script>
</head>
<body>
<?php
fu_tipo_user(4);
fu_cabezote("CONTACTAR DOCENTES");
include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
   print'<div align="center"><span class="Estilo5">PROYECTO CURRICULAR: '.$NomCra->rescataNombre($_REQUEST['cracod'], "NombreCarrera").'</span></div>';
   //require_once('coor_adm_correos_doc.php');
   print'<FORM name="EmaiForm">
	<table width="482" border="1" align="center" cellpadding="0" cellspacing="0">
	 <caption>docentes con carga acad&aacute;mica en el proyecto curricular</caption>';
	
	$carrera = $_REQUEST['cracod'];
	$usuario = $_SESSION['usuario_login'];
	$nivel = $_SESSION["usuario_nivel"];
	require_once(dir_script.'NombreUsuario.php');
	
	require_once(dir_script.'msql_correos_doc.php');
	$row_EmDoc = $conexion->ejecutarSQL($configuracion,$accesoOracle,$Qry_EmDoc,"busqueda");
	//if($row_EmDoc != 1)  header("Location: ../err/err_sin_registros.php");
	
	$i=0;
	while(isset($row_EmDoc[$i][0]))
	{
		print'<tr><td width="20" align="right">'.$i.'</td>
		<td width="20"><INPUT type="checkbox" value="'.$row_EmDoc[$i][1].'"></td>
		<td width="432">'.$row_EmDoc[$i][0].'</td></tr>';
	$i++;
	}
	print'<tr><td width="482" colspan="3" align="justify">Si falta alg&uacute;n docente, se debe a que no tiene una cuenta de correo registrada en la base de datos o no tiene asignada una carga acad&eacute;mica.</td></tr>
	</table>
	
	</table>
	</FORM>
	<p></p>
	<FORM name="ButtonForm" method="post" action="../generales/frm_envia_correo_grupo.php" target="principal">
	  <table width="472" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		  <td align="center"><input onClick="set(1);" type="button" value="Marcar" title="Seleccionar todos los estudiantes"></td>
		  <td align="center"><input onClick="set(0);" type="button" value="Desmarcar" title="Borrar la selecci&oacute;n actual"></td>
		  <td align="center"><input onClick="set_invert();" type="button" value="Invertir" title="Invertir la selecci&oacute;n actual"></td>
		  <td align="center"><input onClick="Enviar(check());" type="button" value="Enviar Correos" title="Enviar correos a seleccionados"></td>
		</tr>
	  </table>
	  <input type="hidden" name="ctas" value="">
	  <input type="hidden" name="DocNombre" value="'.$Nombre.'">
	  <input type="hidden" name="DocCorreo" value="'.$Email.'">
	</FORM>';
}
?>
</body>
</html>