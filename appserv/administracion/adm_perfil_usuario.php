<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(20);

fu_cabezote("PERFILES DE USUARIO");
$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

//Creación del nuevo perfil
if($_REQUEST['perf'])
{
	$tipo="SELECT 'S' FROM geclaves
	WHERE CLA_CODIGO  = ".$_SESSION['A']."
	AND CLA_TIPO_USU = ".$_REQUEST['perf'];
	
	$rowtipo=$conexion->ejecutarSQL($configuracion,$accesoOracle,$tipo,"busqueda");

	if($rowtipo[0][0]== 'S')
	{
		echo "<script>location.replace('$redir?error_login=23')</script>";	
		exit;
	}
   
	$est = 'A';
	$ins="INSERT ";
	$ins.="INTO ";
	$ins.="geclaves ";
	$ins.="( ";
	$ins.="CLA_CODIGO, ";
	$ins.="CLA_CLAVE, ";
	$ins.="CLA_TIPO_USU, ";
	$ins.="CLA_ESTADO";
	$ins.=")";
	$ins.="VALUES ";
	$ins.="( ";
	$ins.="'".$_SESSION['A']."', ";
	$ins.="'".$_SESSION['G']."', ";
	$ins.="'".$_REQUEST['perf']."', ";
	$ins.="'".$est."'";
	$ins.=")";
	$rowin=$conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");

   	$_REQUEST["usuario"] = $_SESSION['A'];
}
//Fin creación del nuevo perfil
?>
<html>
<head>
<title>Administraci&oacute;n</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="this.document.roll.usuario.focus()">
<form name="roll" method="post" action="<? $_SERVER['PHP_SELF'] ?>">
<table width="30%"  border="0" align="center">
  <tr>
    <td align="right">Usuario:</td>
    <td><input name="usuario" type="text" id="usuario"></td>
    <td><input type="submit" name="Submit" value="Consultar" class="button" <? print $evento_boton;?>></td>
  </tr>
</table>
</form>
<p></p><br><br>
<?PHP
//Consulta los perfiles del usuario
if($_REQUEST["usuario"] == "") $_REQUEST["usuario"] = $_REQUEST["u"];

if($_REQUEST["usuario"] != "")
{
	if(!is_numeric($_REQUEST['usuario']))
	{
		echo "<script>location.replace('$redir?error_login=4')</script>";
		exit;
	}
	$b_inactiva='<IMG SRC='.dir_img.'b_deltbl.png alt="Inactivar" border="0">';
	$b_activa='<IMG SRC='.dir_img.'s_okay.png alt="Activar" border="0">';
	
	require_once('msql_consulta_perfiles.php');
	$rowdatos=$conexion->ejecutarSQL($configuracion,$accesoOracle,$datos,"busqueda");

	$perfiles = "SELECT DISTINCT(CLA_TIPO_USU), USUTIPO_TIPO
			FROM GECLAVES,GEUSUTIPO
			WHERE USUTIPO_COD = CLA_TIPO_USU
			ORDER BY 2";
	
	$RowPer = $conexion->ejecutarSQL($configuracion,$accesoOracle,$perfiles,"busqueda");
	
	print'<form name="perfil" method="post" action="'.$_SERVER['PHP_SELF'].'">
	<table width="60%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="tr">
			<td align="center">Usuario</td>
			<td align="center">Perfil</td>
			<td align="center">Estado</td>
			<td align="center">Inactivar</td>
			<td align="center">Activar</td>
		</tr>';
		$i=0;
		while(isset($rowdatos[$i][0]))
		{
			print'<tr class="td">
				<td align="right">'.$rowdatos[$i][0].'</td>
				<td align="left">'.$rowdatos[$i][2].'</td>
				<td align="center">'.$rowdatos[$i][4].'</td>
				<td align="center"><a href="prog_activa_inactiva_perfiles.php?co='.$rowdatos[$i][0].'&ti='.$rowdatos[$i][3].'&es=I">'.$b_inactiva.'</a></td>
				<td align="center"><a href="prog_activa_inactiva_perfiles.php?co='.$rowdatos[$i][0].'&ti='.$rowdatos[$i][3].'&es=A">'.$b_activa.'</a></td>
			</tr>';
			$_SESSION['A'] = $rowdatos[$i][0];
			$_SESSION['G'] = $rowdatos[$i][1];
			$i++;
		}
	print'</table>';
		//Fin consulta perfiles del usuario
			
		//Lista de perfiles 
	print'<br><br><br><br><table width="30%"  border="1" align="center" cellpadding="0" cellspacing="0">
		<tr class="tr">
			<td align="center">Crear Nuevo Perfil</td>
		</tr>
		<tr class="td"><td align="center">
			<select name="perf">';
			$i=0;
			while(isset($RowPer[$i][0]))
			{
				print'<option value="'.$RowPer[$i][0].'">'.$RowPer[$i][1].'</option>';
				$i++;
			}
			print'</select></td>
		</tr>
			<tr class="td"><td align="center"><input type="submit" name="Submit" value="  Crear  " class="button" '.$evento_boton.'></td>
		</tr>
	</table></form>';
}
?>
</body>
</html>
