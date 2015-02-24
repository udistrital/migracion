<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
?>
<html>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<body>
<?php

fu_cabezote("ADMINISTRACI&Oacute;N DE DECANOS");
$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$b_edit ='<IMG SRC='.dir_img.'b_edit.png alt="Editar" border="0">';
$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Insertar" border="0">';
$b_browse='<IMG SRC='.dir_img.'b_browse.png alt="Consultar" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar" border="0">';

require_once('msql_consulta_dec.php');
$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$consulta,"busqueda");

echo'<div align="center">
<table border="0" width="620">
	<tr><td width="861" colspan="2" align="center">&nbsp;';
	if(isset($_REQUEST['error_login'])){ 
	$error=$_REQUEST['error_login']; 
	echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'> 
	<a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>"; 
	}
	echo'</td></tr></table><p>&nbsp;</p>
	
	<table border="1" width="95%" cellspacing="0" cellpadding="0">
		<tr class="tr">
			<td align="center">Documento</td>
			<td align="center">Nombre Del Decano</td>
			<td align="center">Facultad</td>
			<td align="center">Tipo</td>
			<td align="center">Est</td>
			<!-- <td align="center" colspan="2">Acci&oacute;n</td>-->
		</tr>';
	$i=0;
	while(isset($registro[$i][0]))
	{
		echo'<tr class="td">
		<td align="right">'.$registro[$i][0].'</td>
		<td align="left">'.$registro[$i][1].'</td>
		<td align="left">'.$registro[$i][2].'</td>
		<td align="center">'.$registro[$i][3].'</td>
		<td align="center">'.$registro[$i][4].'</td>
		<!-- <td width="27" align="center"><a href="adm_actualiza_dec.php?codigo='.$registro[$i][0].'">'.$b_edit.'</a></td> 
		<td width="27" align="center"><a href="msql_borra_geclaves.php?codigo='.$registro[$i][0].'&tipo='.$registro[$i][3].'">'.$b_deltbl.'</a></td>--> </tr>';
		$i++;
	}
echo'</table></div><br><br>';
?>
</body>
</html>