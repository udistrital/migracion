<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
require_once("../clase/config.class.php");
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");

	$cripto=new encriptar();
	fu_tipo_user(16);
	$conexion=new multiConexion();
	ob_start();

         $indice=$configuracion["host"]."/weboffice/index.php?";
	//Consultar espacios academicos para plan de estudios
	//$indiceAcademico="http://oasdes.udistrital.edu.co/academicopro/index.php?";
        $indiceAcademico= $configuracion["raiz_sga"]."/index.php?";
	$variable="pagina=adminConsultarPlanEstudioDecano";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ver";
	$variable.="&tipoUser=16";
	$variable.="&modulo=decano";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsultarPlanEstudio=$indiceAcademico.$variable;

        //echo "<br>usuario decano ".$_SESSION['usuario_login'];

        $variable="pagina=adminUsuarios";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&opcion=mostrar";
        $variable.="&action=loginCondor";
        $variable.="&tipoUser=16";
        $variable.="&nivel=16";
        $variable.="&modulo=adminUsuario";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceUsuarios=$indice.$variable;

	//Consultar los Planes de Trabajo de los Docentes Preriodo actual
	$indicePlanTrabajo=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=adminPlanTrabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=16";
	$variable.="&nivel=A";
	$variable.="&modulo=controlPlanTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePlanTrabajo1=$indicePlanTrabajo.$variable;
      
	//Consulta la lista de Cursos Programados
	$indiceListaCursos=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=adminListaCursos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=16";
	$variable.="&nivel=A";
	$variable.="&modulo=listaCursos";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceListaCursos=$indiceListaCursos.$variable;

	//Control de notas
	$variable="pagina=adminConsultasCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&nivel=A";
	$variable.="&tipoConsulta=controlNotas";
	$variable.="&tipoUser=16";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceControlNotas=$indice.$variable;

	//Menú para ingresar a la página de docencia.
	$indiceDoc=$configuracion["host"]."/docencia/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=30";
	$variable.="&modulo=SubDecano";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocencia=$indiceDoc.$variable;

  	//ocupacionde salones
        $variable="pagina=adminocupacionSalones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
        $variable.="&opcion=inicio";
        $variable.="&tipoUser=16";
	$variable.="&nivel=A";
        $variable.="&modulo=Decano";
        $variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceOcupacion=$indiceAcademico.$variable;

        //Enlace para evaluación coordinadores docentes saraacademica
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $tipo=16;
        $indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
        $tokenCondor = "condorSara2013!";
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable="indexEvaluacion&pagina=decano";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=indexEvaluacion";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceEvaldocentes = $indiceSaraAcademica.$opcion.$variable;

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=facultad";
	$enlaceReporteFacultad=$configuracion["host"].$variable;

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=posgrado";
	$enlaceReportePosgrado=$configuracion["host"].$variable;


	 //Consejerias, consulta estado academico del estudiante e historia académica
	$variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=16";
	$variable.="&opcion=verEstudiante";
	$variable.="&modulo=Decano";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable; 

	 //Enlace para el cambio de contraseña
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
	$tokenCondor = "condorSara2013!";
	$tipo=16;
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="gestionPassword&pagina=decano";
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
<a href="#">Datos Personales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="dec_actualiza_dat.php">Actualizar</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Estudiantes Activos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Coordinadores</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="dec_frm_coordinadores.php">Coordinadores</a></li>
<li class="subitem1"><a target="principal" href="dec_cierre_semestre.php">Cierre Semestre</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlacePlanTrabajo1 ?>">Consultar Planes de Trabajo</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Evaluaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes ?>">Evaluaci&oacute;n Coordinador</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Control Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceControlNotas ?>">Notas digitadas</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Cursos Programados</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceListaCursos ?>">Disponibilidad de cupos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<?/*<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceDocencia ?>">Estado de cuenta</a></li>*/?>
<li class="subitem1"><a target="principal" href="<?echo $CalAcad?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="<?echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Estad&iacute;sticas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../estadistica/index_desercion.php">Deserci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/esta_tot_proyectos.php">Proyectos Curriculares</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Planes de Estudio</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsultarPlanEstudio?>">Consultar Plan de Estudio</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Gesti&oacute;n de Horarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceOcupacion ?>">Ocupaci&oacute;n de salones</a></li>
</ul>
</li>

<li class="item5"><a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceReporteFacultad; ?>">Facultad</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteProyecto; ?>">Pregrado</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReportePosgrado; ?>">Posgrado</a></li>
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
