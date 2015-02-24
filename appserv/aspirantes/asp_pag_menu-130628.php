<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/*Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario*/

/*********/


fu_tipo_user(50);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto=new encriptar();
	
	$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	//$indice="http://oas2.udistrital.edu.co/weboffice/index.php?";

	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=inscripcionGrado";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;
	
	
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=matriculaEstudiante";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceMatricula=$indice.$variable;
	
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=ActualizaDatos";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceActualizaDatos=$indice.$variable;


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
		//Variables de configuracin
		<? 
		$plan = '../palndeestudio/pe_'.$_SESSION["carrera"].'.pdf';
		if(!file_exists($plan))
		$plan = '../palndeestudio/sin_plan.pdf';
		?>		
		//Menu 0 
		makeMenu('top','Instructivo')
			makeMenu('sub','Instructivo Admisiones','../instructivo/index.php','principal')
			makeMenu('sub','Instructivo Reingreso','../instructivo/reingreso.php','principal')
			//makeMenu('sub','Instructivo Tranferencias','../instructivo/reingreso.php','principal')
			
		//Menu 1
		makeMenu('top','Inscripci&oacute;n')
			makeMenu('sub','Ingreso primer semestre','aceptar.php?form=acasp','principal')
			makeMenu('sub','Reingreso o transf. interna','aceptar.php?form=reingreso','principal')
			//makeMenu('sub','Transferencia externa','aceptar.php?form=transferencia','principal')
			
		//Menu 2
		makeMenu('top','Ver inscripci&oacute;n')
			makeMenu('sub','Consultar','../generales_asp/imprime_colilla_general.php?opcion=consultar','principal')
		
		//Menu 3
		makeMenu('top','Ver resultados')
			makeMenu('sub','Ver resultados','../resultados/aviso_resultados.php','principal')
					
		//Menu 3
		makeMenu('salir','Cerrar Sesi&oacute;n','../conexion/salir_asp.php','_top','end')
		
		//Ejecucin del men
		onload=SlideMenuInit;
</script>
</body>
</html>




