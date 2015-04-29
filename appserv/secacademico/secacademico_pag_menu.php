<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 

$cripto=new encriptar();
fu_tipo_user(83);
$conexion=new multiConexion();
ob_start();
	
	//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	//$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
		$indice=$configuracion["host"]."/weboffice/index.php?";
        $indiceAcademico=$configuracion["host"]."/academicopro/index.php?";

	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";	
	$variable.="&tipoUser=83";		
	$variable.="&modulo=adminInsertaAprobacionExt";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$aprobacionext= $indice.$variable;


	$variable="pagina=login";
	$variable.="&modulo=adminInscritoGrado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";	
	$variable.="&tipoUser=83";	
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=listaCompleta@accion=1@hoja=1";			
	$variable=$cripto->codificar_url($variable,$configuracion);
	$total_grados= $indice.$variable;	


	$variable="pagina=login";	
	$variable.="&modulo=adminInscritoGrado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=83";	
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=listadoTotalProyecto@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$proy_grados= $indice.$variable;	


	$variable="pagina=login";	
	$variable.="&modulo=inscribirCoordinacion";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];	
	$variable.="&tipoUser=83";
	$variable.="&parametro=@opcion=nuevo@sinCodigo=1@xajax=datos_basicos@xajax=pais|region|paisFormacion|regionFormacion@xajax_file=inscripcion";		
	$variable=$cripto->codificar_url($variable,$configuracion);
	$ins_est_grados= $indice.$variable;		
      
	//Promedio Egresados
	$variable="pagina=login";	
	$variable.="&modulo=adminInscritoGrado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=83";	
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=promedioEgresados@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$promedio_egresados= $indice.$variable;	
        
        /*enlce registro documentos*/
        $variable="pagina=registroDocumentosVinculacion";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&action=loginCondor";
        $variable.="&opcion=inicio";
        $variable.="&tipoUser=83";
        $variable.="&nivel=A";
        $variable.="&modulo=secacademico";
        $variable.="&aplicacion=Condor";
        $variable.="&tiempo=300";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceRegistroDocumentoVinculacion=$indiceAcademico.$variable;
	
	/*enlce sabana de notas*/
	$variable="pagina=admin_reporteSabanaDeNotas";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=mostrar";
	$variable.="&tipoUser=83";
	$variable.="&nivel=A";
	$variable.="&modulo=reporte_interno";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceSabanaDeNotas=$indiceAcademico.$variable;


          //Consjerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=83";
        $variable.="&opcion=verEstudiante";
        $variable.="&modulo=secacademico";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;    

	/*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=secacademica";
	$enlaceReporteSecAcademica=$configuracion["host"].$variable;



	/*enlace copia acta*/
        $variable="pagina=admin_generarActa";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&action=loginCondor";
        $variable.="&opcion=copiaActaGrado";
        $variable.="&tipoUser=83";
        $variable.="&nivel=A";
        $variable.="&modulo=secacademico";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceGenerarCopiaActa=$indiceAcademico.$variable;

        /*enlace acta grado*/
        $variable="pagina=admin_generarActa";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&action=loginCondor";
        $variable.="&opcion=actaGrado";
        $variable.="&tipoUser=83";
        $variable.="&nivel=A";
        $variable.="&modulo=secacademico";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceGenerarActaGrado=$indiceAcademico.$variable;

                
         //Registro de estudiantes a grado
        $variable="pagina=admin_inscripcionGraduando";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=83";
        $variable.="&opcion=verEstudiante";
        $variable.="&aplicacion=Condor";
        $variable.="&modulo=secretario";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceInscripcionGrados=$indiceAcademico.$variable;    
	
//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=83;
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

 //Reporte interno de notas del estudiante
$variable="pagina=reporte_interno";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&opcion=ingresar";
$variable.="&tipoUser=83";
$variable.="&modulo=secretario";
$variable.="&aplicacion=Condor";

$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceAcademicoInterno=$indiceAcademico.$variable;


// Enlaces Manuales
$enlaceManInscGrado=$configuracion['host_soporte']."/soporte/archivos/Manual_Inscripcion_a_Grado_Perfil_de_Secretaria_Academica.pdf";

 $variable="pagina=admin_cargarDatosEgresado";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=83";
$variable.="&opcion=verFormCarga";
$variable.="&aplicacion=Condor";
$variable.="&modulo=secacademico";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceCargarArchivoGrados=$indiceAcademico.$variable;

	 /*enlace sistema de administracion de reportes pregrado*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes postgrado
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=posgrado";
	$enlaceReportePosgrado=$configuracion["host"].$variable;


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

<li class="item3">
<a href="#">Recibos de pago</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $aprobacionext;?>">Aprobar Extemporaneo</a></li>
</ul>
</li>

<li class="item2">
<a href="#">Gesti&oacute;n de Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceSabanaDeNotas;?>">Sabana de notas</a></li>
</ul>
</li>
<li class="item1">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Estudiantes Activos</a></li>
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>

</ul>
</li>
<li class="item5">
<a href="#">Grados</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $total_grados;?>">Listado total inscritos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $proy_grados;?>">Inscritos por proyecto</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceInscripcionGrados;?>">Inscribir estudiante</a></li>
<?/*?><li class="subitem1"><a target="principal" href="<?echo $enlaceCargarArchivoGrados;?>">Cargar archivo</a></li><?*/?>
</ul>
</li>

<li class="item1">
<a href="#">Egresados</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $promedio_egresados;?>">Promedio Egresados</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceGenerarActaGrado;?>">Generar acta</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceGenerarCopiaActa;?>">Generar copia acta</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Vinculaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceRegistroDocumentoVinculacion ?>">Documentos</a></li>
</ul>
</li>

<li class="item2">
<a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteSecAcademica ?>">Ver Reportes</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteProyecto; ?>">Reportes Pregrado</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReportePosgrado; ?>">Reportes Posgrado</a></li>
</ul>
</li>

<li class="item2">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManInscGrado ?>">Manual Inscripción a grado</a></li>
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
