<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
//establece conexion para mysql
$accesoMY=$conexion->estableceConexion('cambio_claveMY');
fu_tipo_user(105); 

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($_REQUEST['codigo'] == "")
{
	header("Location: $redir?error_login=17");
	exit;
}
else
{
	if($_REQUEST['nc'] != $_REQUEST['rnc'])
	{
		header("Location: $redir?error_login=12");
		exit;
	}
	else
	{
		if($_REQUEST['nc'] == "" || $_REQUEST['rnc'] == "")
		{
			header("Location: $redir?error_login=3");
			exit;
		}
		else
		{
			$encriptaclave = md5($_REQUEST['nc']);	 
			$qry="UPDATE ";
			$qry.="geclaves ";
			$qry.="SET ";
			$qry.="cla_clave ='".$encriptaclave."' ";
			$qry.="WHERE ";
			$qry.="cla_codigo ='".$_REQUEST['codigo']."'";
			$rowqry=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
			
			if(isset($rowqry))
			{       if(isset($accesoMY)){$resultado2 =$conexion->ejecutarSQL($configuracion,$accesoMY,$qry,"busqueda");} 
				echo "<script>location.replace('$redir?error_login=13')</script>";
			}
		}
	
	}
}


?>