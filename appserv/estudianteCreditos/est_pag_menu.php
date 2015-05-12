<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/*Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario*/

/*********/


fu_tipo_user(52);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto=new encriptar();


        //$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
        //$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
	$indice=$configuracion["host"]."/weboffice/index.php?";
        $indiceAcademico=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        //$indice="http://10.20.0.39/webofficepro/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=inscripcionGrado";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;


	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=matriculaEstudiante";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceMatricula=$indice.$variable;

	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=51";
	$variable.="&modulo=ActualizaDatos";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceActualizaDatos=$indice.$variable;

	$variable="pagina=consultarCalificaciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&action=consultarCalificaciones";
	$variable.="&opcion=loginCondor";
	$variable.="&tipoUser=52";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademico=$indiceAcademico.$variable;

        $variable="pagina=reporte_interno";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=generar";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoInterno=$indiceAcademico1.$variable;

                //Solicitudes de certificado
        $variable="pagina=adminSolicitudCertificado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
        //$variable.="&opcion=".$_SESSION['usuario_login'];
        $variable.="&opcion=52";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoSolicitud=$indiceAcademico.$variable;

          //Adiciones y cancelaciones estudiantes
        $variable="pagina=admin_inicioInscripcionEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=52";
        $variable.="&opcion=creditos";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAdiciones=$indiceAcademico1.$variable;


        //Ver horarios de cada estudiante
        $variable="pagina=adminHorarioEstudiantes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verHorario";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoHorarioEstudiante=$indiceAcademico1.$variable;

	//Consultar plan de estudio
        $variable="pagina=adminConsultarPlanEstudioEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=mostrar";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultaPlanEstudio=$indiceAcademico1.$variable;

	
	//Descargar Manuales de usuario
        $variable="pagina=adminManuales";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verEstudiante";
	$variable.="&tipoUser=52";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceManuales=$indiceAcademico1.$variable;

	//Normatividad
        $variable="pagina=adminNormatividadEstudiantes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=principal";
	$variable.="&tipoUser=52";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceNormatividad=$indiceAcademico1.$variable;

        //Consejerias
	$variable="pagina=admin_mensajeEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verMensajesRecibidos";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsejerias=$indiceAcademico1.$variable;
	
	//Biblioteca
	$variable="pagina=admin_biblioteca";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=adminBiblioteca";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";        
	$variable.="&aplicacion=Condor";
    
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminBiblioteca=$indiceAcademico1.$variable;
	
	$variable="pagina=admin_inicioPreinscripcionDemandaEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=52";
	$variable.="&opcion=consultar";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPreinscripcionDemanda=$indiceAcademico1.$variable;// este indice es el

//Enlace para evaluación Docente
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $tipo = 52;
        $indiceSaraAcademica = $configuracion["host_evaluadoc"]."/saraacademica/index.php?";
        $tokenCondor = "condorSara2013!";
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="indexEvaluacion&pagina=estudiantes";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=indexEvaluacion";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceEvaldocentes = $indiceSaraAcademica.$opcion.$variable;

	//Enlace para el cambio de contraseña
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
	$tokenCondor = "condorSara2013!";
	$tipo=52;
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="gestionPassword&pagina=estudiantes";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=cambioPassword";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;
//Enlaces Manuales
$enlaceManEvalDoc=$configuracion['host_soporte']."/soporte/archivos/manual_evaluacion_docente.pdf";

          //Consejerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriasConsultaEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=52";
        $variable.="&opcion=verEstudiante";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;
?>
<html>
<head>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<link href="../marcos/apariencia.css" rel="stylesheet" type="text/css">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

</head>
<body class='menu'>
<? require_once('../usuarios/usuarios.php'); ?>
<p align="center">

<script src="../script/jquery.min.js"></script>
<link href="../estilo/menu.css" rel="stylesheet" type="text/css">

<script>
		var message = "";
		function clickIE(){
		if (document.all){
			(message);
			return false;
		}
		}
		function clickNS(e){
		if (document.layers || (document.getElementById && !document.all)){
			if (e.which == 2 || e.which == 3 ){
			(message);
			return false;
			}
		}
		}
		if (document.layers){
		document.captureEvents(Event.MOUSEDOWN);
		document.onmousedown = clickNS;
		} else {
		document.onmouseup = clickNS;
		document.oncontextmenu = clickIE;
		}
		document.oncontextmenu = new Function("return false")
		onload=SlideMenuInit;
</script>

<ul class="menu">

<li class="item1">
<a href="#">Datos Personales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceActualizaDatos ?>">Actualizar datos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Asignaturas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoAdiciones?>">Adicionar y Cancelar</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoHorarioEstudiante?>">Horarios</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoPreinscripcionDemanda?>">Preins. por Demanda</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="est_notaspar.php">Parciales</a></li>
<li class="subitem1"><a target="principal" href="../estudiantes/est_notas_curvac.php">Vacacionales</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoInterno?>">Certificado Interno</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="est_adm_correos_doc.php">Contactar Docentes</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes?>">Evaluaci&oacute;n docentes</a></li>
<li class="subitem1"><a target="principal" href="https://portalws.udistrital.edu.co/soporte/archivos/manual_evaluacion_docente .pdf">Manual evaluación docente</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoConsejerias?>">Consejerias</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceMatricula ?>">Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Inscripci&oacute;n a Grado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $CalAcad?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceNormatividad?>">Normatividad</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceAcademicoConsultaPlanEstudio ?>">Plan de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceConsejeriaEstudiante ?>">Historia Acad&eacute;mica</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceManuales?>">Manuales</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManEvalDoc ?>">Manual evaluación docente</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Biblioteca</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAdminBiblioteca?>">Bases de Datos</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Clave</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceCambioPassword?>">Cambiar mi Clave</a></li>
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
