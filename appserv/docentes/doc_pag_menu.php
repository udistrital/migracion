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
	fu_tipo_user(30);
        $conexion=new multiConexion();
        ob_start();
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";

        //Consejerias
	$variable="pagina=admin_consejeriasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsejerias=$indiceAcademico1.$variable;
	
	//Dig NOTAS
	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=PREGRADO";
	$variable.="&tipoUser=30";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesPregrado=$indiceDoc.$variable;
	
	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=POSGRADO";
	$variable.="&tipoUser=30";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesPosgrado=$indiceDoc.$variable;

	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=notasPerAnterior";
	$variable.="&nivel=ANTERIOR";
	$variable.="&tipoUser=30";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesAnterior=$indiceDoc.$variable;

	/*$variable="pagina=adminConsultasAdmisiones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=consultaDatosAspirantes";
	$variable.="&tipoUser=33";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsultasAspirantes=$indice.$variable;*/
	
	$indicePlanDocActual=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_plan_trabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=A";
	$variable.="&tipoUser=30";
	$variable.="&modulo=planTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocentesPlanTrabajoActual=$indicePlanDocActual.$variable;

	
	$indicePlanDocProximo=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_plan_trabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=X";
	$variable.="&tipoUser=30";
	$variable.="&modulo=planTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocentesPlanTrabajoProximo=$indicePlanDocProximo.$variable;
	
	/*$indice=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
	$indice1=$configuracion["raiz_sga"]."/index.php?";
	//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	//$indice="http://10.20.0.39/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=84";
	$variable.="&modulo=AdminBlogdev";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;*/
      
	//Menú para ingresar a la página de docencia.
	$indiceDoc=$configuracion["host"]."/docencia/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=30";
	$variable.="&modulo=Docencia";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocencia=$indiceDoc.$variable;
        
	//Biblioteca
	$variable="pagina=admin_biblioteca";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=adminBiblioteca";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";        
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminBiblioteca=$indiceAcademico.$variable;

	/*enlce consulta docuemntos*/
	$variable="pagina=adminDocumentosVinculacion";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=30";
	$variable.="&nivel=A";
	$variable.="&modulo=Docente";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocumentoVinculacion=$indiceAcademico.$variable;

	//Enlace para evaluación Docente
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $tipo=30;
        $indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
        $tokenCondor = "condorSara2013!";
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable="indexEvaluacion&pagina=docentes";                                                        
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
	$tipo=30;
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="gestionPassword&pagina=docentes";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=cambioPassword";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;

//Enlace para ver la lista de clase y enviar correo electónico a estudiantes.
	include_once("crypto/Encriptador.class.php");
	$miCodificador=Encriptador::singleton();
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$tipo=30;
	$indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
	$tokenCondor = "condorSara2013!";
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable="indexEvaluacion&pagina=docentes";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=listaClase";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceListaClase = $indiceSaraAcademica.$opcion.$variable;

//Enlace para evaluación Docente
	 include_once("crypto/Encriptador.class.php");
	$miCodificador=Encriptador::singleton();
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$tipo=30;
	$indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
	$tokenCondor = "condorSara2013!";
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable="indexEvaluacion&pagina=docentes";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=resultadosEvaluacion";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceResultadosEvaldocentes = $indiceSaraAcademica.$opcion.$variable;


// enlaces manuales

$enlaceManIngNotas=$configuracion['host_soporte']."/soporte/archivos/manual_ingreso_de_notas_docentes.pdf";


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

<li class="item1"><a href="#">Datos Personales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="doc_actualiza_dat.php">Actualizar Datos </a></li>
</ul>
</li>

<li class="item2"><a href="#">Plan de trabajo</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceDocentesPlanTrabajoActual?>">Registrar Periodo Actual</a></li>
<li class="subitem1"><a target="principal"  href="<?echo $enlaceDocentesPlanTrabajoProximo?>">Registrar Periodo Pr&oacute;ximo</a></li>
<li class="subitem1"><a href="#" class="postmenu">Reglamentaci&oacute;n</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="est_doc.pdf">Estatuto Del Profesor</a></li>
<li class="subitem1"><a target="principal" href="doc_circular003_pt.php">Circular 003</a></li>
<li class="subitem1"><a target="principal" href="doc_circular008_pt.php">Circular 008</a></li>
</ul>
</ul>
</li>
</li>

<li class=""><a href="#">Asignaci&oacute;n Acad.</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="doc_fre_carga.php">Asignaturas </a></li>
</ul>
</li>

<li class=""><a href="#">Consejerias </a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<? echo $enlaceAcademicoConsejerias ?>">Consejerias </a></li>
</ul>
</li>

<li class="item5"><a href="#">Auto Evaluaci&oacute;n </a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes?>">Auto Evaluaci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="doc_obsevaciones.php">Observaciones de Est.(ant. 2013-3)</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceResultadosEvaldocentes?>">Observaciones.</a></li>

</ul>
</li>

<li class="item5"><a href="#">Captura de Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="doc_curso.php">Lista de clase</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceNotasDocentesPregrado?>">Captura notas Pregrado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceNotasDocentesPosgrado?>">Captura notas Posgrado</a></li>
<li class="subitem1"><a target="principal" href="doc_carga_curvac.php">Vacacionales</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceNotasDocentesAnterior?>">Notas per. Anterior</a></li>
</ul>
</li>

<li class="item5"><a href="#">Servicios</a>
<ul class="submenus">
<?php /* ?><li class="subitem1"><a target="principal" href="<?PHP echo $enlaceDocencia ?>">Estado de cuenta</a></li><?php */ ?>
<li class="subitem1"><a target="principal" href="<?echo $enlaceListaClase?>">Envío de correos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $configuracion['host_calendario_acad'];?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="doc_contacta_doc.php">Contactar docentes</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Estudiantes Activos</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de grado</a></li>

</ul>
</li>

<li class="item5"><a href="#">Vinculaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceDocumentoVinculacion ?>">Documentos </a></li>
</ul>
</li>

<li class="item5"><a href="#">Biblioteca</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAdminBiblioteca?>">Base de datos</a></li>
</ul>
</li>
<li class="item2">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManIngNotas ?>">Manual de ingreso de notas</a>
</li>
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

<!--initiate accordion-->
<script type="text/javascript">

//Ejecucin del men
onload=SlideMenuInit;
</script>

</body>
</html>
