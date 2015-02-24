<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


fu_tipo_user(30); 
fu_cabezote("ENVIO DE CORREOS EN GRUPO");
require_once(dir_script.'class_nombres.php');
$nombre = new Nombres;
$Asignatura = $nombre->rescataNombre($_REQUEST['as'],"NombreAsignatura");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
<body>
<FORM name="EmaiForm">
<table width="482" border="1" align="center" cellpadding="0" cellspacing="0">
    <caption><? print htmlentities($Asignatura).'   <span class="Estilo14">Grupo:</span> '.$_GET['gr'];?></caption>
<?php
require_once('msql_correos_grupo.php');
$row_curso=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_curso,"busqueda");
if(!is_array($row_curso))
{
die('<center><h3>No hay registros para esta consulta (rc).</h3></center>');
}

$nomdoc = $row_curso[0][2];
$emaildoc = $row_curso[0][3];

$i=0;
while(isset($row_curso[$i][0]))
{
    $nro=$i+1;
	print'<tr><td width="20" align="right">'.$nro.'</td>
   	<td width="20"><INPUT type="checkbox" value="'.$row_curso[$i][1].'"></td>
   	<td width="432">'.htmlentities($row_curso[$i][0]).'</td></tr>';
$i++;
}

?>
<tr><td width="482" colspan="3" align="justify">Si el n&uacute;mero de estudiantes no es igual al de la lista de clase, se debe a que hay estudiantes sin una cuenta de correo registrada.</td></tr>
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
