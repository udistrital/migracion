<?PHP
require_once('dir_relativo.cfg'); 
require_once(dir_conect.'valida_pag.php'); 
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");

fu_tipo_user(20); 

fu_cabezote("REVISI&Oacute;N DE USUARIOS");
?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/KeyIntro.js"></script>
<title>Administraci&oacute;n</title>
</head>
<body>
<P></P>
<div align="center" class="Estilo11">REVISI&Oacute;N DE USUARIOS DE C&Oacute;NDOR</div>
<table width="100%" border="0" align="center">
  	<tr>
    		<td align="center">
		<form name="est" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="51" value="Estudiantes" class="button" style="width:140" title="Estudiantes por inactivar en Condor." <? print $evento_boton;?>>
		</form>
		</td>
		<td align="center">
		<form name="docpi" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="30" value="Docentes Sin Carga" class="button" style="width:140" title="Docentes sin asignacion academica" <? print $evento_boton;?>>
		</form></td>
		<td align="center">
		<form name="doci" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="301" value="Docentes Inactivos" class="button" style="width:140" title="Docentes Inactivos" <? print $evento_boton;?>>
		</form></td>
		<td align="center">
		<form name="fun" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="24" value="Funcionarios" class="button" style="width:140" title="Funcionarios inactivos" <? print $evento_boton;?>>
		</form></td>
		<td align="center">
		<form name="enw" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="999" value="Est. en W" class="button" style="width:140" title="Estudiantes en W" <? print $evento_boton;?>>
		</form></td>
	</tr>
	<tr>
		<td align="center">
		<form name="usunoest" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="888" value="Usuarios Inactivos" class="button" style="width:140" title="Usuarios en W diferentes a estudiante." <? print $evento_boton;?>>
		</form>
		</td>
		<td align="center">
		<form name="email" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="correo" value="Actualizar Correos" class="button" style="width:140" title="Actualiza el campo EOT_EMAIL con EOT_EMAIL_INS, cuando el primero es nulo." <? print $evento_boton;?>>
		</form>
		</td>
		<td align="center">
		<form name="email" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="Reingreso" value="Activar Reingreso" class="button" style="width:140" title="Acivar estudiantes de reintegro"  <? print $evento_boton;?>>
		</form>
		</td>
		<td align="center"><form name="usulog" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="log" value="Log de Conexion" class="button" style="width:140" title="Conexiones por usuario y fecha" <? print $evento_boton;?>>
		</form></td>
		<td align="center">
		<form name="egre" method="post" action="adm_usuarios_para_inactivar.php">
		<input type="submit" name="510" value="Egresados" class="button" style="width:140" title="Egresados por borrar de Condor" <? print $evento_boton;?>>
		</form>
		</td>
	</tr>
	<tr>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
</table>
<P></P>
<?PHP
if($_REQUEST['51'])
{
	require_once('adm_est_por_inactivar.php');
}
if($_REQUEST['510'])
{
	require_once('adm_egresados_por_borrar.php');
}
if($_REQUEST['30'])
{
	require_once('adm_doc_sin_carga_por_inactivar.php');
}
if($_REQUEST['301'])
{
	require_once('adm_doc_por_inactivar.php');
}
if($_REQUEST['24'])
{
	require_once('adm_fun_por_inactivar.php');
}
if($_REQUEST['999'])
{
	require_once('adm_est_enw.php');
}
if($_REQUEST['888'])
{
require_once('adm_usuarios_no_est.php');
}
if($_REQUEST['log'])
{
	require_once('adm_usuarios_log.php');
}
if($_REQUEST['correo'])
{
	require_once('adm_usuarios_actualiza_correo.php');
}
if($_REQUEST['Reingreso'])
{
	require_once('adm_estudiantes_de_reingreso.php');
}

if(isset($_REQUEST['error_login']))
{
	$error=$_REQUEST['error_login'];
	echo"<center><font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>
	<img src='../img/asterisco.gif'>$error_login_ms[$error]</font></center>";
}

if($_REQUEST['usulog'] == "" && $_REQUEST['tipo'] == "")
{
   print'<p></p>';
}
?>
</body>
</html>