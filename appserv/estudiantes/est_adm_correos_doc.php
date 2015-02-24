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

fu_tipo_user(51); 
fu_cabezote("ENVIO DE CORREOS A DOCENTES");
?>
<html>
<head>
<title>Envio de corres en grupo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script> 
<script language="JavaScript" src="../script/AdmLisEmail.js"></script>
</head>
<body>
<FORM name="EmaiForm">
<table width="482" border="1" align="center" cellpadding="0" cellspacing="0">
 <caption>docentes con carga acad&eacute;mica en su proyecto curricular</caption>
<?php
$usuario = $_SESSION['usuario_login'];
$nivel = $_SESSION["usuario_nivel"];

$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod,est_nombre,eot_email,eot_email_ins FROM acest, acestotr WHERE est_cod=$usuario AND est_cod=eot_cod ","busqueda");
$carrera = $registroCarrera[0][0];

require_once(dir_script.'NombreUsuario.php');
require_once(dir_script.'msql_correos_doc.php');
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$Qry_EmDoc,"busqueda");

//if($row_EmDoc != 1) { header("Location: ../err/err_sin_registros.php"); exit; }
//if($row_EmDoc != 1) { die('<h3>No hay registros para esta consulta.</h3>'); exit; }

$i=0;
while(isset($registro[$i][0]))
{
	print'<tr><td width="20" align="right">'.$i.'</td>
		<td width="20"><INPUT type="checkbox" value="'.$registro[$i][1].'"></td>
		<td width="432">'.$registro[$i][0].'</td>
	</tr>';
	$i++;
}
?>
<tr><td width="482" colspan="3" align="justify">Si falta alg&uacute;n docente, se debe a que no tiene una cuenta de correo registrada en la base de datos o no tiene asignada una carga acad&eacute;mica.</td></tr>
</table>

</table>
</FORM>
<p></p>
<FORM name="ButtonForm" method="post" action="../generales/frm_envia_correo_grupo.php" target="principal">
  <?
  $Nombre = $registroCarrera[0][1];
  $Email = $registroCarrera[0][2].";".$registroCarrera[0][3];
  ?>"
  <table width="472" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center"><input onClick="set(1);" type="button" value="Marcar" title="Seleccionar todos los estudiantes"></td>
      <td align="center"><input onClick="set(0);" type="button" value="Desmarcar" title="Borrar la seleccion actual"></td>
      <td align="center"><input onClick="set_invert();" type="button" value="Invertir" title="Invertir la seleccion actual"></td>
      	<td> <input type="hidden" name="ctas" value=""></td>
  	<td><input type="hidden" name="DocNombre" id="DocNombre" value="<? echo $Nombre; ?>"></td>
  	<td><input type="hidden" name="DocCorreo" id="DocCorreo" value="<? echo $Email; ?>"></td>
      <td align="center"><input onClick="Enviar(check());" type="button" value="Enviar Correos" title="Enviar correos a seleccionados"></td>
    </tr>
  </table>
</FORM>
</body>
</html>
<?php
?>