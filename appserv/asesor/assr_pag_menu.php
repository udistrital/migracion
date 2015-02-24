<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
fu_tipo_user(34);
ob_start();
?>

<html>
<head>
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>
<body class='menu'>

<? require_once('../usuarios/usuarios.php');

    $cripto=new encriptar();

 $indice=$configuracion["host"]."/weboffice/index.php?";

//Enlace consulta proveedores
 $variable="pagina=adminConsultarProveedor";
 $variable.="&usuario=".$_SESSION['usuario_login'];
 $variable.="&opcion=consultarProveedor";
 $variable.="&tipoUser=34";
 $variable.="&aplicacion=Condor";
 $variable=$cripto->codificar_url($variable,$configuracion);
 $enlaceConsultaProveedores=$indice.$variable;

//Enlace consulta datos básicos estudiantes
 $variable="pagina=adminConsultasAsesor";
 $variable.="&usuario=".$_SESSION['usuario_login'];
 $variable.="&opcion=consultaDatosEstudiantes";
 $variable.="&tipoUser=34";
 $variable.="&aplicacion=Condor";
 $variable=$cripto->codificar_url($variable,$configuracion);
 $enlaceDatosEstudiantes=$indice.$variable;

//Reporte planes de trabajo docentes
$variable="pagina=adminReportes";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=4";
$variable.="&parametro=@tipoUser=4@opcReporte=9@tipopagina=no_pagina";
//$variable.="&nivel=A";
$variable.="&modulo=adminReportes";
$variable.="&aplicacion=Condor";
$variable.="&tipopagina=no_pagina";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceReportesPlanesTrabajo=$indice.$variable; 

//Consultar los Planes de Trabajo de los Docentes Preriodo actual
$variable="pagina=login";
$variable.="&modulo=controlPlanTrabajo";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=34";
$variable.="&nivel=A";
$variable.="&parametro=@opcion=listaFacultades@accion=1@hoja=1";;
$variable=$cripto->codificar_url($variable,$configuracion);
$enlacePlanTrabajo1=$indice.$variable;

//Enlace para el cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
$tokenCondor = "condorSara2013!";
$tipo=34;
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

//Enlace para consultar planes de estudios de creditos
$indiceAcademico= $configuracion["raiz_sga"]."/index.php?";
$variable="pagina=adminConsultarPlanEstudioDecano";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&opcion=ver";
$variable.="&tipoUser=16";
$variable.="&modulo=decano";
$variable.="&aplicacion=Condor";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceConsultarPlanEstudio=$indiceAcademico.$variable;


?>

<script src="../script/jquery.min.js"></script>
<link href="../estilo/menu.css" rel="stylesheet" type="text/css">

<ul class="menu">

<li class="item5">
<a href="#">Control de Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../asesor/coor_control_notas.php">Notas digitadas</a></li>
</ul>
</li>

<li class="">
<a href="#">Aspirantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../generales/gen_inscritos_por_facultad.php">Proceso Actual</a></li>
<li class="subitem1"><a target="principal" href="assr_asp_anoper.php">Por A&ntilde;o y Per&iacute;odo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_estrato.php">Poblaci&oacute;n por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_sexo.php">Poblaci&oacute;n por Sexo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_localidad.php ">Poblaci&oacute;n por Localidad.</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Admitidos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="assr_adm_anoper.php">Por A&ntilde;o y Per&iacute;odo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_estrato.php">Poblaci&oacute;n por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_sexo.php">Poblaci&oacute;n por Sexo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_localidad.php">Poblaci&oacute;n por Loc.</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Est. Codificados</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="assr_codif_anoper.php">Por A&ntilde;o y Per&iacute;odo</a></li>
</ul>
</li>


<li class="item5">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceDatosEstudiantes;?>">Datos b&aacute;sicos</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Activos</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fra_cuenta_est.php">Con Asignaturas Inscritas</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_activa_estrato.php">Activos por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_activa_sexo.php">Poblaci&oacute;n por Sexo</a></li>
</ul>
</li>


<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_doc_digito_pt.php">Informaci&oacute;n Docentes</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlacePlanTrabajo1?>">Ver Planes de trabajo</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceReportesPlanesTrabajo?>">Consolidado Plan trabajo</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Proveedores</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsultaProveedores;?>">Consulta de Proveedores</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $CalAcad?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="<?echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsultarPlanEstudio?>">Consultar Planes de Estudios</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Estad&iacute;sticas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../estadistica/esta_uso_condor.php">Accesos a C&oacute;ndor</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_desercion.php">Deserci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_tot_empleados.php">Funcionarios</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/esta_tot_proyectos.php">Proy. Curriculares</a></li>
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
