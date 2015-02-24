<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

fu_tipo_user(84);

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$cripto=new encriptar();

//$indice="http://oasdes.udistrital.edu.co/weboffice/index.php?";
$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
//$indice="http://10.20.0.39/webofficepro/index.php?";
$variable="pagina=login";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=84";
$variable.="&modulo=AdminBlogdev";
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceWeboffice=$indice.$variable;
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

<li class="item1">
<a href="#">Bit&aacute;cora</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Bit&aacute;cora</a></li>
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