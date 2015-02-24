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
fu_tipo_user(20);
$conexion=new multiConexion();
ob_start();

$indice=$configuracion["host"]."/weboffice/index.php?";
//Intensidad Horaria
$variable="pagina=login";	
$variable.="&modulo=IntensidadHoraria";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=20";	
$variable.="&action=loginCondor";		
$variable.="&tiempo=".$_SESSION['usuario_login'];
$variable.="&parametro=@opcion=actualizaIntensidad@accion=1@hoja=1";
$variable=$cripto->codificar_url($variable,$configuracion);
$actualizacionIntensidadHoraria= $indice.$variable;

//$indice=$configuracion["host"]."/weboffice/index.php?";
$indicePreins=$configuracion["host"]."/academicosga/index.php?";
//Intensidad Horaria
$variable="pagina=admin_consultarPreinscripciones";	
$variable.="&opcion=consultar";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=20";	
$variable.="&aplicativo=loginCondor";		
//$variable.="&tiempo=".$_SESSION['usuario_login'];
//$variable.="&parametro=@opcion=actualizaIntensidad@accion=1@hoja=1";
$variable=$cripto->codificar_url($variable,$configuracion);
$monitoreoPreinDemanda= $indicePreins.$variable;


$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
$variable="pagina=adminConsultaRecibos";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&opcion=consultaProyectos";
//$variable.="&nivel=ANTERIOR";
$variable.="&tipoUser=30";
$variable.="&aplicacion=Condor";
$variable.="&nivel=A";
//$variable.="&modulo=docentes";
$variable.="&tiempo=300";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceGeneraArchivosRecibos=$indiceDoc.$variable;

//Enlace para consultar intensidad horaria
$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
$variable="pagina=adminIntensidadHorariaLote";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&tipoUser=30";
$variable.="&modulo=adminIntensidadHorariaLote";
$variable.="&aplicacion=Condor";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceIntensidadHorariaLote=$indice.$variable;


 /*enlace calcular modelos bienestar*/
$variable="pagina=registro_calculoModelosBienestar";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=";
$variable.="&tipoUser=80";
$variable.="&nivel=A";
$variable.="&aplicacion=Condor";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceModelosBienestar=$indiceAcademico.$variable; 

 /*enlace intensidad Horaria Egresados*/
$variable="pagina=registro_actualizarIntensidadHorariaEgresado";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=consultar";
$variable.="&tipoUser=20";
$variable.="&nivel=A";
$variable.="&aplicacion=Condor";
$variable.="&modulo=soporte";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceIntensidadHorariaEgresado=$indiceAcademico.$variable; 

 /*enlace fechas calendario eventos*/
$variable="pagina=admin_consultarCalendarioEventos";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&opcion=";
$variable.="&tipoUser=20";
$variable.="&nivel=A";
$variable.="&aplicacion=Condor";
$variable.="&modulo=admin_sga";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceFechasCalendario=$indiceAcademico.$variable; 

//Enlace para cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=20;
$tokenCondor = $miCodificador->codificar($tokenCondor);
$opcion="temasys=";
$variable.="gestionPassword&pagina=soporte";
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
<title>Admon</title>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>

<? require_once('../usuarios/usuarios.php'); ?>

<script src="../script/jquery.min.js"></script>
<link href="../estilo/menu.css" rel="stylesheet" type="text/css">

<ul class="menu">

<li class="item2">
<a href="#">Admon. Usuarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="adm_usu_activos.php">Usuario Activos</a></li>
<li class="subitem1"><a target="principal" href="adm_insert_geclaves.php">Usuario Nuevo</a></li>
<li class="subitem1"><a target="principal" href="adm_perfil_usuario.php">Perfiles</a></li>
<li class="subitem1"><a target="principal" href="adm_consulta_addcan.php">Log Adici&oacute;n y Can.</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceCambioPassword?>">Cambiar clave</a></li>
<li class="subitem1"><a target="principal" href="adm_usuarios_para_inactivar.php">Revisi&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="adm_usuarios_nuevos_est.php">Estudiantes nuevos</a></li>
<li class="subitem1"><a target="principal" href="adm_usuarios_nuevos_doc.php">Docentes nuevos</a></li>
<li class="subitem1"><a target="principal" href="adm_usuarios_nuevos_emp.php">Funcionarios nuevos</a></li>
<li class="subitem1"><a target="principal" href="adm_encripta_claves_de_geclaves.php">Encriptar claves</a></li>
<li class="subitem1"><a target="principal" href="adm_encripta_claves_de_aspirantes.php">Encriptar Aspirantes</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Intensidad Horaria</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $actualizacionIntensidadHoraria;?>">Actualizaci&oacute;n Intensidad</a></li>
<li class="subitem1"><a target="principal" href="<?echo $monitoreoPreinDemanda;?>">Prein. Demanda</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceIntensidadHorariaLote;?>">Intensidad Lote</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceIntensidadHorariaEgresado;?>">Actualizar Intensidad Egresado</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="adm_corrige_cedula.php">Corregir c&eacute;dulas</a></li>
<li class="subitem1"><a target="principal" href="adm_decanos.php">Decanos</a></li>
<li class="subitem1"><a target="principal" href="adm_fra_facultades.php">Coordinadores</a></li>
<li class="subitem1"><a target="principal" href="adm_docentes.php">Docentes</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="adm_fra_cuenta_est.php">Con asig. inscritas</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Estudiantes activos</a></li>
<li class="subitem1"><a target="principal" href="adm_fra_datos.php">Encriptar caracteres</a></li>
<li class="subitem1"><a target="principal" href="<? echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceFechasCalendario;?>">Fechas Eventos</a></li>
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
