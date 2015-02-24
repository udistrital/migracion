<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$cripto=new encriptar();
	fu_tipo_user(61);
	ob_start();
	$indice="https://condor.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;

	$indice="https://condor.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=61";
	$variable.="&modulo=proyectoCurricular";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWebofficepro=$indice.$variable;


        //Aprobacion de espacios academicos para plan de estudios
	    $indiceAcademico=$configuracion["raiz_sga"]."/index.php?";
	    $variable="pagina=adminAprobarEspacioPlan";
	    $variable.="&usuario=".$_SESSION['usuario_login'];
	    $variable.="&opcion=ver";
	    $variable.="&tipoUser=61";
	    $variable.="&modulo=AsistenteVicerrectoria";
	    $variable.="&aplicacion=Condor";
	    $variable=$cripto->codificar_url($variable,$configuracion);
        
        $enlaceApobacionEspacios=$indiceAcademico.$variable;

	//Consultar espacios academicos para plan de estudios
	$variable="pagina=adminConsultarPlanEstudioAsisVice";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ver";
	$variable.="&tipoUser=61";
	$variable.="&modulo=AsistenteVicerrectoria";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsultarPlanEstudio=$indiceAcademico.$variable;

	//Descargar Manuales de usuario
        $variable="pagina=adminManuales";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verAsisVice";
	$variable.="&tipoUser=61";
	$variable.="&modulo=AsistenteVicerrectoria";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceManuales=$indiceAcademico.$variable;

        $indice=$configuracion["host"]."/weboffice/index.php?";
        $variable="pagina=adminNotasOas";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&opcion=consultaNotas";
        $variable.="&tipoUser=61";
        $variable.="&modulo=loginCondor";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsultaNotas=$indice.$variable;

        $variable="pagina=adminNotasOas";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&opcion=consultaNotasAsignatura";
        $variable.="&tipoUser=61";
        $variable.="&modulo=loginCondor";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsultaNotasAsignatura=$indice.$variable;

	//Plan de estudios horas
	$indiceAcademico=$configuracion["raiz_sga"]."/index.php?";
	$variable="pagina=admin_consultarPlanEstudiosHoras";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=consultar";
	$variable.="&tipoUser=61";
	$variable.="&modulo=AsistenteVicerrectoria";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePlanEstudiosHoras=$indiceAcademico.$variable;

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=soporte";
	$enlaceReporteSoporte=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=facultad";
	$enlaceReporteFacultad=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=bienestar";
	$enlaceReporteBienestar=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=viceacademica";
	$enlaceReporteViceAcademica=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=secacademica";
	$enlaceReporteSecAcademica=$configuracion["host"].$variable;

//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=61;
$tokenCondor = $miCodificador->codificar($tokenCondor);
$opcion="temasys=";
$variable.="gestionPassword&pagina=otrosCambioPassword";
$variable.="&usuario=".$usuario;
$variable.="&tipo=".$tipo;
$variable.="&token=".$tokenCondor;
$variable.="&opcionPagina=cambioPassword";
$variable=$miCodificador->codificar($variable);
$enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;


 /*enlace fechas Novedades de notas*/
$variable="pagina=admin_consultarFechasNovedadNotas";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=";
$variable.="&tipoUser=61";
$variable.="&nivel=A";
$variable.="&aplicacion=Condor";
$variable.="&modulo=admin_sga";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceFechasNovedadesNota=$indiceAcademico.$variable;

//Consejerias, consulta estado academico del estudiante e historia académica
$variable="pagina=admin_consejeriaEstudianteSoporte";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=61";
$variable.="&opcion=verEstudiante";
$variable.="&aplicacion=Condor";
$variable.="&modulo=soporte";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceConsejeriaEstudiante=$indiceAcademico.$variable; 


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
<?/*
<li class="item1">
<a href="#">Datos Personales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_actualiza_dat.php">Actualizar Datos</a></li>
</ul>
</li>*/?>

<li class="item5">
<a href="#">Planes de Estudio</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsultarPlanEstudio?>">Consultar Plan de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceApobacionEspacios?>">Config Planes de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlacePlanEstudiosHoras?>">P. de Estudios Horas</a></li>
</ul>

</li>
<li class="item5">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
</ul>
</li>
<li class="item5">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceManuales?>">Manuales</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Moodle</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsultaNotas?>">Importar Notas a C&oacute;ndor</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsultaNotasAsignatura?>">Importar Notas por Asig.</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Gesti&oacute;n Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteProyecto ?>">Proyecto Curricular</a></li>
<li class="subitem1"><a target="principal"  href="<?PHP echo $enlaceReporteFacultad ?>">Facultad</a></li>
<li class="subitem1"><a target="principal"  href="<?PHP echo $enlaceReporteBienestar ?>">Bienestar</a></li>
<li class="subitem1"><a target="principal"  href="<?PHP echo $enlaceReporteSecAcademica; ?>">Secretaria Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal"  href="<?PHP echo $enlaceReporteViceAcademica; ?>">Vicerrector&iacute;a Acad&eacute;mica</a></li>
</ul>
</li>
<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceFechasNovedadesNota?>">Fechas eventos</a></li>
</ul>
</li>
<li class="item5">
<a href="#">Clave</a>
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
