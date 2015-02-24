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
	fu_tipo_user(114);
        $conexion=new multiConexion();
        ob_start();
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";

          //Consjerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=114";
        $variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=secretario";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;    

            //Reporte interno de notas del estudiante
        $variable="pagina=reporte_interno";
    	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=114";
        $variable.="&modulo=secretario";
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
	$tipo=114;
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

	//Generar certificado de estudio de estudiantes
	$variable="pagina=admin_certificadoEstudio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=114";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCertificadoEstudio=$indiceAcademico.$variable;

	//enlace historico de recibos de pagos
	$variable="pagina=admin_consultarHistoricoRecibos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=";
	$variable.="&tipoUser=114";
	$variable.="&nivel=A";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHistoricoRecibosPago=$indiceAcademico.$variable; 

	 //ocupacionde salones
	$variable="pagina=adminocupacionSalones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=114";
	$variable.="&nivel=A";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceOcupacion=$indiceAcademico.$variable;

	 //Consultar horario de estudiantes
	$variable="pagina=admin_consultarEstudianteHorarioCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=114";
	$variable.="&opcion=consultar";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultarHorario=$indiceAcademico.$variable;

	//Codificar estudiantes nuevos
	$variable="pagina=admin_codificarEstudiantesNuevos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=114";
	$variable.="&opcion=proyectos";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoCodificarEstudiante=$indiceAcademico.$variable;

	//enlce Homologaciones

	$variable="pagina=admin_homologaciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=114";
	$variable.="&opcion=crearTablaHomologacion";
	$variable.="&tipo_hom=normal";
	$variable.="&modulo=secretario";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCrearHomologaciones=$indiceAcademico.$variable;

	//Registro de estudiantes a grado
	$variable="pagina=admin_inscripcionGraduando";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=114";
	$variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=secretario";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceInscripcionGrados=$indiceAcademico.$variable;


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
<li class="subitem1"><a target="principal" href="../coordinador/coor_frm_datos_est.php?tipo=114">Datos B&aacute;sicos</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsejeriaEstudiante ?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceCertificadoEstudio ?>">Certificado de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceHistoricoRecibosPago;?>">Hist&oacute;rico Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoCodificarEstudiante?>">Codif. Estud. Nuevos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceInscripcionGrados;?>">Inscripci&oacute;n a grado</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_calendario_acad'];?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_cra_hor.php">Horarios Por Grupo</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Pensum</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCrearHomologaciones?>">Homologaciones</a></li>
</ul>
</li>


<li class=""><a href="#">Gesti&oacute;n de Horarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceOcupacion ?>">Ocupaci&oacute;n de salones</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Inscripciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoConsultarHorario?>">Horario Estudiantes</a></li>
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
