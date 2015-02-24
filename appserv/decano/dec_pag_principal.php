<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(16);
?>
<html>
<head>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body>

<?php
$usuario = $_SESSION['usuario_login'];

require_once('msql_consulta_decanos.php');

$wow_dec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_dec,"busqueda");

$depcod = $wow_dec[0][0];
$depnom = $wow_dec[0][1];

require_once(dir_script.'NumeroVisitas.php');

echo'<div align="center"><br><br><br>
	<table border="0" width="500" cellpadding="0">
		<tr>
			<td width="200" align="left" height="9" colspan="2"><span class="Estilo5">'.$depnom.'</span></td>
			<td width="300" align="right" height="9" colspan="2"><span class="Estilo7">Visita No. '.$Nro.' de '.$Tot.' desde 28-Jun-2006</span></td>
		</tr>
	</table>
	<p></p>
	<table border="0" width="500" cellpadding="0">
		<tr>
			<td width="100%" align="center" height="9" colspan="2">
			<hr noshade class="hr">
			</td>
		</tr>
		<tr>
			<td width="67%" height="200" background="../img/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
			<p align="justify" style="line-height: 100%">Si tiene m&aacute;s de un tipo de usuario como: (Decano, Coordinador &oacute; Docente),
			haga clic en el usuario deseado, en la lista &quot;<span class="Estilo5">Cambiar a Usuario</span>&quot;.<br><br>
			Si cambia su correo electr&oacute;nico, no olvide actualizarlo en la p&aacute;gina de 
			actualizaci&oacute;n de datos, haciendo clic en el men&uacute; &quot;Datos Personales&quot;.             
		
			<p style="line-height: 100%" align="justify">Cuando 
			actualice alguna informaci&oacute;n, no olvide grabar. La forma segura de salir de esta p&aacute;gina, 
			es haciendo 
			clic en el hiperv&iacute;nculo &quot;<a href="../conexion/salir.php" target="_top" title="Salida segura"><strong>Salir</strong></a>&quot;. 
			<p style="line-height: 100%" align="justify">De 
			esta forma nos aseguramos que otras personas no puedan manipular sus 
			datos.
			</td>
		</tr>
		<tr>
			<td width="100%" align="center" height="1">
			<hr noshade class="hr">
			</td>
		</tr>
		
	</table>
</div>';
?>
</body>
</html>