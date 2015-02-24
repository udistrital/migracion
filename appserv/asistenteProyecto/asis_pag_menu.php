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
	fu_tipo_user(110);
        $conexion=new multiConexion();
        ob_start();
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";

          //Consjerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=110";
        $variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=asistente";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;    

            //Reporte interno de notas del estudiante
        $variable="pagina=reporte_interno";
    	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=110";
        $variable.="&modulo=asistente";
    	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
    	$enlaceAcademicoInterno=$indiceAcademico.$variable;        

         //Generar certificado de estudio de estudiantes
	$variable="pagina=admin_certificadoEstudio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCertificadoEstudio=$indiceAcademico.$variable;

	//Gestión de Horarios
	$variable="pagina=adminConsultaHorarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=110";
	$variable.="&nivel=A";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHorarios3=$indiceAcademico.$variable;

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable; 

	//Enlace para el cambio de contraseña
	include_once("crypto/Encriptador.class.php");
	$miCodificador=Encriptador::singleton();
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
	$tokenCondor = "condorSara2013!";
	$tipo=110;
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


	//Inscripción Posgrados
	$variable="pagina=admin_inscripcionCoordinadorPosgrado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=verProyectos";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAdiciones=$indiceAcademico1.$variable;

	//enlace historico de recibos de pagos
	$variable="pagina=admin_consultarHistoricoRecibos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=";
	$variable.="&tipoUser=110";
	$variable.="&nivel=A";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHistoricoRecibosPago=$indiceAcademico.$variable; 

 	//ocupacionde salones
	$variable="pagina=adminocupacionSalones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=110";
	$variable.="&nivel=A";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceOcupacion=$indiceAcademico.$variable;

 	//Consultar horario de estudiantes
	$variable="pagina=admin_consultarEstudianteHorarioCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=consultar";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultarHorario=$indiceAcademico.$variable; 

	//Codificar estudiantes nuevos
	$variable="pagina=admin_codificarEstudiantesNuevos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=proyectos";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoCodificarEstudiante=$indiceAcademico.$variable;

 	//enlce Homologaciones

	$variable="pagina=admin_homologaciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=crearTablaHomologacion";
	$variable.="&tipo_hom=normal";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCrearHomologaciones=$indiceAcademico.$variable;

	//Registro de estudiantes a grado
	$variable="pagina=admin_inscripcionGraduando";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=asistente";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceInscripcionGrados=$indiceAcademico.$variable;

	//inscripciones cursos intermedios
	$variable="pagina=admin_inscripcionCoordinadorCI";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=verProyectos";
	$variable.="&modulo=asistente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoCursosIntermedios=$indiceAcademico.$variable;
	
	//enlace servicios de recibos de pago
	$indiceWeboffice=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&nivel=110";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indiceWeboffice.$variable;

	//Recalcular estado a estudiantes
	$variable="pagina=admin_recalcularEstadoEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=110";
	$variable.="&opcion=mostrarFormulario";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoRecalcularEstado=$indiceAcademico.$variable;

	//Inscripcion ECAES
	$indiceECAES=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=admin_inscripcion_ECAES";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	//$variable.="&modulo=matriculaEstudiante";
	$variable.="&tipoUser=110";
	$variable.="&modulo=RegistroECAES";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&nivel=110";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceECAES=$indiceECAES.$variable;



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
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Activos</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_frm_datos_est.php">Asignaturas Inscritas</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_est_activos.php">Con Asig. Inscritas</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_frm_datos_est.php?tipo=110">Datos B&aacute;sicos</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceConsejeriaEstudiante ?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceCertificadoEstudio ?>">Certificados de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoAdiciones?>">Adici&oacute;n y Cancelaci&oacute;n</a></li>	  
<li class="subitem1"><a target="principal" href="<?echo $enlaceHistoricoRecibosPago;?>">Hist&oacute;rico Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoCodificarEstudiante?>">Codif. Estud. Nuevos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceInscripcionGrados;?>">Inscripci&oacute;n a grado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoRecalcularEstado?>">Recalcular Reglamento</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../coordinador/coor_frm_docentes.php">Listado de Docentes</a></li>
<li class="subitem1"><a href="#" class="postmenu">Plan de Trabajo</a>
	<ul class="submenus">
	<li class="subitem1"><a target="principal" href="../docentes/estdfocen.pdf">Estatuto Del Profesor</a></li>
	<li class="subitem1"><a target="principal" href="../docentes/doc_circular003_pt.php">Circular 003</a></li>
	<li class="subitem1"><a target="principal" href="../docentes/doc_circular008_pt.php">Circular 008</a></li>
	</ul>
</li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceECAES ?>">SaberPro</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_calendario_acad'];?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_cra_hor.php">Horarios Por Grupo</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
</ul>
</li>


<li class="item5">
<a href="#">Admon. Noticias</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../coordinador/coor_index_msg.php">Noticias</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Estad&iacute;sticas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../estadistica/index_desercion.php">Deserci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_tot_empleados.php">Funcionarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_inscritos_por_facultad.php">Proceso Admisiones</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Pensum</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCrearHomologaciones?>">Homologaciones</a></li>
<li class="subitem1"><a target="principal" href="../coordinador/coor_actualiza_pen.php">Consultar</a></li>
</ul>
</li>

<li class=""><a href="#">Gesti&oacute;n de Horarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceHorarios3 ?>">Gestionar Horarios</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceOcupacion ?>">Ocupaci&oacute;n de salones</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Inscripciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoConsultarHorario?>">Horario Estudiantes</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoCursosIntermedios?>">Cursos Intermedios</a></li>
</ul>
</li>

<li class=""><a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceReporteProyecto; ?>">Ver Reportes</a></li>
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
