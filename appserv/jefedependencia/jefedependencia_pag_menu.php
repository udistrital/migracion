<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/*Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario*/

/*********/


fu_tipo_user(25);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto=new encriptar();
	
	$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	//$indice="http://oas2.udistrital.edu.co/weboffice/index.php?";

	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";	
	$variable.="&tipoUser=25";		
	$variable.="&modulo=inventariosoftware";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$invsoftware= $indice.$variable;



	


?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<link href="../marcos/apariencia.css" rel="stylesheet" type="text/css">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>

<body class='menu'>

<? require_once('../usuarios/usuarios.php'); ?>

<p align="center"> 



<script>
		makeMenu('top','Inventario')
			makeMenu('sub','Software','<?echo $invsoftware;?>','principal')


		makeMenu('top','Clave')
		  makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')


		makeMenu('top','Salir')
		  makeMenu('sub','Salir de Esta Pagina','../conexion/salir.php','_top')
		  makeMenu('sub','Contraer el Menu','rec_pag_menu.php','_self')

		//Ejecucin del men�
		onload=SlideMenuInit;
</script>




</body>
</html>
