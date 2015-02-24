<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once("../clase/config.class.php");
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");
$cripto=new encriptar();
fu_tipo_user(88);
$conexion=new multiConexion();
ob_start();

//$indice="http://oasdes.udistrital.edu.co/weboffice/index.php?";
//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";

$indiceAcademico=$configuracion["host"]."/academicopro/index.php?";
$indice1=$configuracion["host"]."/docencia/index.php?";

$variable="pagina=login";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=88";
$variable.="&modulo=SubDocente";
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceWeboffice=$indice1.$variable;

$indice2=$configuracion["host"]."/weboffice/index.php?";
$variable="pagina=login";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=88";
$variable.="&modulo=adminEvaldocentes";
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable.="&parametro=@opcion=seleccionPeriodo@accion=1@hoja=1";;
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceResultados=$indice2.$variable;

        /*enlce registro documentos vinculacion especial*/
        $variable="pagina=registroDocumentosVinculacion";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&action=loginCondor";
        $variable.="&opcion=inicio";
        $variable.="&tipoUser=88";
        $variable.="&nivel=A";
        $variable.="&modulo=vinculacion";
        $variable.="&aplicacion=Condor";
        $variable.="&tiempo=300";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceRegistroDocumentoVinculacion=$indiceAcademico.$variable;

	//enlace administrados Evaluación Docente
	include_once("crypto/Encriptador.class.php");
	$miCodificador=Encriptador::singleton();
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$tipo=88;
	$indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
	$tokenCondor = "condorSara2013!";
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="indexEvaldocentes&pagina=docencia";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=indexEvaldocentes";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceEvaldocentes = $indiceSaraAcademica.$opcion.$variable;

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=docencia";
	$enlaceReporteDocencia=$configuracion["host"].$variable;




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
<a href="#">Admon. Docencia</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Administraci&oacute;n</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Evaluaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes ?>">Administrador</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceResultados ?>">Result. Observaciones Est.</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Vinculaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceRegistroDocumentoVinculacion ?>">Documentos</a></li>
</ul>
</li>

<li class=""><a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceReporteDocencia; ?>">Ver Reportes</a></li>
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
