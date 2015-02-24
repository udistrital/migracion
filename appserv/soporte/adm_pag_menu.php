<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(80);
ob_start();
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 

$cripto=new encriptar();
//$indice="http://oasdes.udistrital.edu.co/weboffice/index.php?";
$indice=$configuracion["host"]."/weboffice/index.php?";
//$indice="http://10.20.0.39/webofficepro/index.php?";
$variable="pagina=adminCreacionUsuarios";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=80";
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceWeboffice=$indice.$variable;

$variable="pagina=adminCreacionUsuarios";
$variable.="&opcion=crearNuevoUsuario";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=80";
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceCrearNuevo=$indice.$variable;

//Actualiza la intensidad horaria
$variable="pagina=login";	
$variable.="&modulo=IntensidadHoraria";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=80";	
$variable.="&action=loginCondor";		
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable.="&parametro=@opcion=actualizaIntensidad@accion=1@hoja=1";
$variable=$cripto->codificar_url($variable,$configuracion);
$actualizacionIntensidadHoraria= $indice.$variable;	

 /*enlace solicitudes de usuario*/
$variable="pagina=admin_consultarSolicitudesUsuario";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=consultar";
$variable.="&tipoUser=80";
$variable.="&nivel=A";
$variable.="&modulo=soporte";
$variable.="&aplicacion=Condor";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceSolicitudesUsuarios=$indiceAcademico.$variable;

?>
<html>
<head>
<title>Admon</title>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</head>

<body class='menu'>

<? require_once('../usuarios/usuarios.php'); ?>

<p align="center"> 

<script>

		//Estos son los 3 niveles del men
		//top = Main menus
		//sub = Sub menus
		//sub2 = SubSub menus

		//makeMenu('TYPE','TEXT','LINK','TARGET', 'END (THE LAST MENU)')

		//Menu 0
		makeMenu('top','Admon. Usuarios')
			makeMenu('sub','Perfiles','adm_perfil_usuario.php','principal')
			makeMenu('sub','Cambiar clave','adm_update_geclaves.php','principal')
			makeMenu('sub','Bit&aacute;cora','<?PHP echo $enlaceWeboffice ?>','principal')
			makeMenu('sub','Solicitud Usuarios','<?echo $enlaceSolicitudesUsuarios;?>','principal')

		//Menu 1
		makeMenu('top','Intensidad Horaria')
			makeMenu('sub','Actualizaci&oacute;n Intensidad','<?echo $actualizacionIntensidadHoraria;?>','principal')
			//makeMenu('sub','Datos de docentes','coor_fra_datos_doc.php','principal')


		makeMenu('top','Creaci&oacute;n. Usuarios')
			makeMenu('sub','Consulta Usuario','<?echo $enlaceWeboffice?>','principal')
			makeMenu('sub','Usuarios Nuevos','<?echo $enlaceCrearNuevo?>','principal')
			makeMenu('sub','Solicitudes pendientes','adm_update_geclaves.php','principal')
			

		//Menu 3
		makeMenu('top','Salir')
		  makeMenu('sub','Contraer el Menú','adm_pag_menu.php','_self')
		  makeMenu('sub','Salir de Esta Página','../conexion/salir.php','_top')

		//Ejecucin del men
		onload=SlideMenuInit;
</script>

</body>
</html>
