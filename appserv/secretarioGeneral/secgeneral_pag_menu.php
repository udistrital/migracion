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
	fu_tipo_user(113);
        $conexion=new multiConexion();
        ob_start();
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";

          //Consjerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=113";
        $variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=secgeneral";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;    

            //Reporte interno de notas del estudiante
        $variable="pagina=reporte_interno";
    	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=113";
        $variable.="&modulo=secgeneral";
    	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
    	$enlaceAcademicoInterno=$indiceAcademico.$variable;        

//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=113;
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

<li class=""><a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsejeriaEstudiante ?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
</ul>
</li>

<li class="item5"><a href="#">Clave</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCambioPassword?>">Cambiar mi clave</a></li>
</ul>
</li>
<li class=""><a target="_top" href="../conexion/salir.php"><font color="red">Cerrar Sesi&oacute;n </font></a>
</li>
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
</script>

</body>
</html>
