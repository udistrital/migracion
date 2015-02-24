<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/*Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario*/

/*********/


fu_tipo_user(51);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto=new encriptar();
	
	//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$indice=$configuracion["host"]."/weboffice/index.php?";
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
	$variable.="&tipopagina=no_pagina";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceActualizaDatos=$indice.$variable;

        //Consejerias
	$variable="pagina=admin_mensajeEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verMensajesRecibidos";
        $variable.="&tipoUser=51";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsejerias=$indiceAcademico1.$variable;

	//Biblioteca
	$variable="pagina=admin_biblioteca";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=adminBiblioteca";
        $variable.="&tipoUser=51";
        $variable.="&modulo=Estudiante";        
	$variable.="&aplicacion=Condor";
    
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminBiblioteca=$indiceAcademico1.$variable;
	
	$variable="pagina=admin_inicioPreinscripcionDemandaEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=51";
	$variable.="&opcion=consultar";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	//$enlaceAcademicoPreinscripcionDemanda=$indicePruebas.$variable;
	$enlaceAcademicoPreinscripcionDemanda=$indiceAcademico1.$variable;// este indice es el
 
	//adiciones y cancelaciones
	$variable="pagina=admin_inicioInscripcionEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=51";
	$variable.="&opcion=horas";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoInscripcion=$indiceAcademico1.$variable;// este indice es el

	/*enlace*/ //Generación Horario Nuevo
	$variable="pagina=adminHorarioEstudiantesHoras";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=51";
	$variable.="&opcion=verHorario";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoHorarioInscripcion=$indiceAcademico1.$variable;


	//Enlace para evaluación Docente
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $tipo = 51;
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
	$tipo=51;
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

	$estcod=$_SESSION['usuario_login'];
	$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
	$carrera = $registroCarrera[0][0];
	$plan = '../palndeestudio/pe_'.$carrera.'.pdf';
	if(!file_exists($plan))
	{
		$plan = '../palndeestudio/sin_plan.pdf';
	}

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
<? //require_once('../usuarios/usuarios.php'); ?>
<p align="center">

<script src="../script/jquery.min.js"></script>
<link href="../estilo/menu.css" rel="stylesheet" type="text/css">

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
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoHorarioInscripcion ?>">Inscritas</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoInscripcion?>">Adicionar y Cancelar</a></li>
<li class="subitem1"><a target="principal" href="est_lis_asignaturas.php">Cursos Programados</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoPreinscripcionDemanda?>">Preins. por Demanda</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="est_notaspar.php">Parciales</a></li>
<li class="subitem1"><a target="principal" href="est_notas_curvac.php">Vacacionales</a></li>
<li class="subitem1"><a target="principal" href="est_notas.php">Hist&oacute;rico</a></li>
<li class="subitem1"><a target="principal" href="est_semaforo.php">Plan de Estudio</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="est_adm_correos_doc.php">Contactar Docentes</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes?>">Evaluaci&oacute;n docentes</a></li>
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
<li class="subitem1"><a target="principal" href="<?  echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="<? print $plan; ?>">Plan de Estudio</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
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
