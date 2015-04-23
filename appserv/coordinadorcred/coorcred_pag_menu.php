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
	fu_tipo_user(28);
        $conexion=new multiConexion();
        $accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
        ob_start();
	$indice="http://oas2.udistrital.edu.co/weboffice/index.php?";
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
	$indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";
	//$indiceAcademico="http://10.20.0.70/academicosga/index.php?";
	//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;

	$indice1="http://oas2.udistrital.edu.co/weboffice/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=28";
	$variable.="&modulo=proyectoCurricular";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWebofficepro=$indice.$variable;

	
	//Espacios Academicos	

	$variable="pagina=adminEspacio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=mostrar";
	$variable.="&tipoUser=28";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoEspacio=$indiceAcademico.$variable;

	//Planes de estudios

	//$variable="pagina=adminPlanEstudios";
	$variable="pagina=adminProyectoCurricular";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&opcion=ver";
	$variable.="&opcion=mostrar";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPlan=$indiceAcademico1.$variable;

         //Preinscripciones

	$variable="pagina=realizarPreinscripcion";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=planestudios";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPreinscripcion=$indiceAcademico1.$variable;

        // Crear Horarios

	//$variable="pagina=adminPlanEstudios";
	$variable="pagina=generar_horarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&opcion=ver";
	$variable.="&opcion=nuevo";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHorarios=$indiceAcademico.$variable;

        // Copiar Horarios

	//$variable="pagina=adminPlanEstudios";
	$variable="pagina=copiar_horarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&opcion=ver";
	$variable.="&opcion=verProyecto";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCopiarHorarios=$indiceAcademico.$variable;

        // Equivalencias espacios academicos

	$variable="pagina=registroEquivalenciaEspacios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=nuevo";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceEquivalencias=$indiceAcademico.$variable;

        //Reportes certificados internos
        $variable="pagina=reporte_interno";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoInterno=$indiceAcademico1.$variable;

        //Ayuda SGA
        $variable="pagina=ayuda_sga";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=nuevo";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAyuda=$indiceAcademico.$variable;

        //Requisitos Espacios Academicos
        $variable="pagina=requisitos_espacio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=registrar";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoRequisitos=$indiceAcademico1.$variable;

        //Ver horarios de los espacios que pertenecen al proyecto
        $variable="pagina=adminEspaciosHorarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=horario";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoHorarioCarrera=$indiceAcademico1.$variable;

	//Ver los estudiantes con promedio menor y mayor a 3
        $variable="pagina=adminPromedioProyecto";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPromedioProyecto=$indiceAcademico1.$variable;

	//Configurar los planes de estudio
        $variable="pagina=adminConfigurarPlanEstudioCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConfigurarPlanEstudio=$indiceAcademico1.$variable;
        
	//Ver los estudiantes con promedio menor y mayor a 3
        $variable="pagina=adminConsultarPlanEstudioCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultarPlanEstudio=$indiceAcademico1.$variable;

	//Descargar Manuales de usuario
        $variable="pagina=adminManuales";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verCoordinador";
	$variable.="&tipoUser=28";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceManuales=$indiceAcademico1.$variable;

	//Cursos Intermedios
        //Se inactiva porque se hace desde el menu de Coordinador
        /*$variable="pagina=adminCursosIntermediosCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&tipoUser=28";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceCursosIntermedios=$indiceAcademico1.$variable;
        
        el enlace que estaba en el menu de inscripciones también se inactiva
        <li class="subitem1"><a target="principal" href="<? echo $enlaceCursosIntermedios ?>">Cursos Intermedios</a></li>*/


	//Adiciones y cancelaciones coordinadores
        $variable="pagina=adminInscripcionCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=28";
        $variable.="&opcion=verProyectos";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAdiciones=$indiceAcademico1.$variable;

	//Inscripcion Nuevos
        $variable="pagina=registroBloqueEstudiantes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=28";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoInscripcionNuevos=$indiceAcademico1.$variable;

        //Administrar Consejerias
        $variable="pagina=admin_consejerias";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=administrar";
	$variable.="&tipoUser=61";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceConsejerias=$indiceAcademico1.$variable;

	//Ver mensajes Consejerias
        $variable="pagina=admin_mensajeCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ver_mensajesRecibidos";
	$variable.="&tipoUser=28";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceMensajesConsejerias=$indiceAcademico1.$variable;

        //Espacios académicos inscritos por estudiantes inactivos
        $variable="pagina=adminConsultarInscripcionEstudiantesInactivos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&tipoUser=28";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceAsignaturasInscritas=$indiceAcademico1.$variable;

	$variable="pagina=admin_consultarPreinscritosDemandaPorEspacio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=28";
	$variable.="&opcion=reporteEspacios";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPreinsDemanda=$indiceAcademico1.$variable;// este indice es el  mismo de consejerias

	//Enlace para cambio de contraseña
	include_once("crypto/Encriptador.class.php");
	$miCodificador=Encriptador::singleton();
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
	$tokenCondor = "condorSara2013!";
	$tipo=28;
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="gestionPassword&pagina=coordinador";
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

<li class="item1">
<a href="#">Planes de Estudios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoConsultarPlanEstudio?>">Consultar Planes de Estudios</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoConfigurarPlanEstudio?>">Configurar Planes de Estudios</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Cursos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoHorarioCarrera ?>">Horarios</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Certificados Internos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Consejerias</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsejerias ?>">Administraci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceMensajesConsejerias ?>">Mensajes</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Inscripciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInscripcionNuevos?>">Inscripci&oacute;n Nuevos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoPreinsDemanda?>">Preins. por Demanda</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceAsignaturasInscritas?>">Estudiantes Inactivos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceManuales?>">Manuales</a></li>
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
