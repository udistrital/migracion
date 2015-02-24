<?PHP

require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(109);
ob_start();
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 
$cripto=new encriptar();
$indiceAcademico=$configuracion["raiz_sga"]."/index.php?";

	require_once('../usuarios/usuarios.php'); 
        $variable="pagina=admin_consultarHistoricoRecibos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=";
	$variable.="&tipoUser=109";
	$variable.="&nivel=A";
	$variable.="&modulo=AsistenteContabilidad";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceHistoricoRecibosPago=$indiceAcademico.$variable;

/*enlace sistema de administracion de reportes*/

$variable="/reportes_udistrital/run.php?";
$variable.="informes=tesoreria";
$enlaceReporteTesoreria=$configuracion["host"].$variable;

//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraLaverna = $configuracion["host_adm_pwd"]."/lamasu/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=34;
$tokenCondor = $miCodificador->codificar($tokenCondor);
$opcion="temasys=";
$variable.="gestionPassword&pagina=otrosCambioPassword";                                                        
$variable.="&usuario=".$usuario;
$variable.="&tipo=".$tipo;
$variable.="&token=".$tokenCondor;
$variable.="&opcionPagina=cambioPassword";
//$variable=$cripto->codificar_url($variable,$configuracion);
$variable=$miCodificador->codificar($variable);
$enlaceCambioPassword=$indiceSaraLaverna.$opcion.$variable;

//Enlace ICETEX
include_once("crypto/Encriptador.class.php");
$miCodificadorServiciosAcademicos=Encriptador::singleton();

$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$modulo = '109';

$indiceSara = $configuracion["host"]."/serviciosacademicos/index.php?";
$tokenCondor = "condorSara2014";
$tokenCondor = $miCodificadorServiciosAcademicos->codificar($tokenCondor);
$opcion = 'datos=';
$variable="&rol=asistenteContabilidad";
$variable.="&pagina=index";
$variable.="&usuario=".$usuario;
$variable.="&opcionPagina=icetex";
$variable.="&modulo=".$modulo;
$variable.="&token=".$tokenCondor;
$variable=$miCodificadorServiciosAcademicos->codificar($variable);
$enlaceIcetex = $indiceSara.$opcion.$variable;

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
<a href="#">Recibos de pago</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?php echo $enlaceHistoricoRecibosPago;?>">Actualizar Datos </a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceIcetex?>">Crédito Matricula</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Clave</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCambioPassword?>">Cambiar mi clave</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?php echo $enlaceReporteTesoreria ?>">Ver Reportes</a></li>
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
