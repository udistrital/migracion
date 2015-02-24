<?PHP

//importación de archivos
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");

//validación tipo usuario
fu_tipo_user(105);
ob_start();

//configuración
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 

//Encripciòn de la página
$cripto=new encriptar();
$indice=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";

//Constante con los parámetros para la URL
define("CONSTANTE", "&usuario=".$_SESSION['usuario_login']."&tipoUser=105"."&tiempo=".$_SESSION['usuario_login']);

$variable = "pagina=adminEspacioFisico";
$variable.="&usuario=" . $_SESSION['usuario_login'];
$variable.="&tipoUser=105";
$variable.="&opcion=4";
$variable.="&nivel=1";
$variable.="&action=loginCondor";
$variable.="&modulo=Funcionario";
$variable.="&aplicacion=Condor";
$variable.="&tiempo=300";
$variable = $cripto->codificar_url($variable, $configuracion);
$enlaceFAC = $indice . $variable;

//Consultar ocupación salones
$variable="pagina=adminocupacionSalones";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=inicio";
$variable.="&tipoUser=105";
$variable.="&nivel=A";
$variable.="&modulo=Funcionario";
$variable.="&aplicacion=Condor";
$variable.="&tiempo=300";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceOcupacion=$indice.$variable;

$variable = "pagina=adminEspacioFisico";
$variable.="&action=loginCondor";
$variable.="&modulo=Funcionario";
$variable.="&opcion=105";
$variable.="&aplicacion=true";
$variable = $cripto->codificar_url($variable, $configuracion);
$enlaceEFA = $indice . $variable;


//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=105;
$tokenCondor = $miCodificador->codificar($tokenCondor);
$opcion="temasys=";
$variable.="gestionPassword&pagina=otrosCambioPassword";
$variable.="&usuario=".$usuario;
$variable.="&tipo=".$tipo;
$variable.="&token=".$tokenCondor;
$variable.="&opcionPagina=cambioPassword";
//$variable=$cripto->codificar_url($variable,$configuracion);
$variable=$miCodificador->codificar($variable);
$enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;
		
	
?>
<html>
<head>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body class='menu'>
<? require_once('../usuarios/usuarios.php'); ?>

<script src="../script/jquery.min.js"></script>
<link href="../estilo/menu.css" rel="stylesheet" type="text/css">

<ul class="menu">

<li class="item5">
<a href="#">Espacios F&iacute;sicos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceFAC;?>">Espacios F&iacute;sicos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceOcupacion?>">Ocupaci&oacute;n</a></li>
</ul>
</li>

<li class="item5"><a href="#">Clave</a>
 <ul class="submenus">
 <li class="subitem1"><a target="principal" href="<?echo $enlaceCambioPassword?>">Cambiar mi clave</a></li>
 </ul>
</li>

<li class=""><a target="_top" href="../conexion/salir.php"><font color="red">Cerrar Sesi&oacute;n </font></a>
</ul>

<!--initiate accordion-->
<script type="text/javascript">
$(function() {

var menu_ul = $('.menu .submenus'),
menu_a  = $('.menu a');
var clase;
var link;
menu_ul.hide();

menu_a.click(function(e) {
link=$(this).attr('href');
if(link=='#')
{
clase=$(this).attr('class');
menu_a.removeClass('active');
$(this).addClass('active');
if($(this).next().css('display') == 'none'){ 
$(this).next().slideDown('fast');    
}
else
{
$(this).next().slideUp('fast');
}

}
});

});
onload=SlideMenuInit;
</script>
</body>
</html>
