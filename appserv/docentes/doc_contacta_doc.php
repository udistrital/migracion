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

fu_tipo_user(30);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Envio de corres en grupo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script> 
<script language="JavaScript" src="../script/AdmLisEmail.js"></script>
</head>
<body style="margin-top:0">
<?php
fu_cabezote("ENVIO DE CORREOS A DOCENTES");

require_once('msql_cra_carga.php');
$row_CraCarga=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Qry_CraCarga,"busqueda");
if(!is_array($row_CraCarga))
{
die('<h3>No hay registros para esta consulta.</h3>');
exit;
} 
//header("Location: ../err/err_sin_carga.php");
else
{
$carrera = $row_CraCarga[0][0];
}
echo'<div align="center"><form name="LIS_CRA" method="POST" action="'.$_SERVER['PHP_SELF'].'">
<select size="1" name="cracod" style="font-size: 10pt; font-family: Tahoma">
<option value="" selected>Seleccione el Proyecto Curricular, Haga clic en Consultar.</option>\n';
$i=0;
while(isset($row_CraCarga[$i][0]))
{
	echo'<option value="'.$row_CraCarga[$i][0].'">'.$row_CraCarga[$i][0].'--'.$row_CraCarga[$i][1].'</option>\n';
$i++;
}
echo'</select><input type="submit" value="Consultar" name="B1"></form></div>';
$_REQUEST['cracod']=(isset($_REQUEST['cracod'])?$_REQUEST['cracod']:'');
$_POST['cracod']=(isset($_POST['cracod'])?$_POST['cracod']:'');
if(!$_REQUEST['cracod']) $_REQUEST['cracod']=$carrera;

if($_POST['cracod']){
    $carrera=$_POST['cracod'];
	require_once(dir_script.'msql_correos_doc.php');
	$row_EmDoc=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Qry_EmDoc,"busqueda");
	if(!is_array($row_EmDoc))
	{
	header("Location: ../err/err_sin_carga.php");
	}
	print'<FORM name="EmaiForm">
	<table width="482" border="1" align="center" cellpadding="0" cellspacing="0">
 	<caption>DOCENTES CON CARGA ACAD&Eacute;MICA EN EL PROYECTO CURRICULAR</caption>';
	$i=0;
        $l=1;
	while(isset($row_EmDoc[$i][0]))
	{
		if($row_EmDoc[$i][2]==$_SESSION['usuario_login'])
		{
			$nomdoc = $row_EmDoc[$i][0];
			$emaildoc = $row_EmDoc[$i][1];
		}
	   print'<tr><td width="20" align="right">'.$l.'</td>
	   <td width="20"><INPUT type="checkbox" value="'.$row_EmDoc[$i][1].'"></td>
	   <td width="432">'.$row_EmDoc[$i][0].'</td></tr>';
	$i++;
        $l++;
	}
	?>
	<tr>
	  <td width="482" colspan="3" align="justify">Si alg&uacute;n docente no aparece en la lista, se debe a que no tiene carga acad&eacute;mica registrada o no tiene una cuenta de correos.</td>
	</tr>
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
	  <input type="hidden" name="DocNombre" value="<? echo $nomdoc; ?>">
	  <input type="hidden" name="DocCorreo" value="<? echo $emaildoc; ?>">
	</FORM>
	</body>
	</html>
	<?php 
}
?>