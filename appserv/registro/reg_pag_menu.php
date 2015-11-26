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
fu_tipo_user(33);
$conexion=new multiConexion();
ob_start();

$indice=$configuracion["host"]."/weboffice/index.php?";
$indiceAcademico=$configuracion["host"]."/academicopro/index.php?";

	//Enlace a recibos de pago de aspirantes admitidos.
	$variable="pagina=imprimirFacturaAdm";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=33";
	$variable.="&nivel=P";
	$variable.="&modulo=recibosAdmisiones";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice1=$indice.$variable;

	//Enlace a recibos de pago de aspirantes admitidos.
	$variable="pagina=imprimirFacturaAdm";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=33";
	$variable.="&nivel=X";
	$variable.="&modulo=recibosAdmisiones";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice2=$indice.$variable;

	//Enlace a consultas varias
	$variable="pagina=adminConsultasAdmisiones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=consultaDatosAspirantes";
	$variable.="&tipoUser=33";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsultasAspirantes=$indice.$variable;

	//Recibos de pago admisiones
	$variable="pagina=imprimirFacturaAdm";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=33";
	$variable.="&nivel=P";
	$variable.="&modulo=recibosAdmisiones";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=adminFechasInsRes@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdministracion=$indice.$variable;

	//Recibos de pago admisiones
	$variable="pagina=adminAdmisiones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=32";
	$variable.="&modulo=admisiones";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=presentacion@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminin=$indice.$variable;
      
        //Consejerias, consulta estado academico del estudiante e historia académica
	$variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=33";
	$variable.="&opcion=verEstudiante";
	$variable.="&modulo=admisiones";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;
        
        //Enlace para el módulo de administración
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $indiceSaraLaverna = $configuracion["host_adm_asp"]."/admisiones/index.php?";
        $tokenCondor = "condorSara2013!";
        $tipo=33;
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="indexAdminAdmisiones&pagina=adminAdmisiones";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=indexAdminAdmisiones";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceAdministracionAdmisiones=$indiceSaraLaverna.$opcion.$variable;
        
        //Enlace para el cambio de contraseña
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $indiceSaraLaverna = $configuracion["host_adm_pwd"]."/index.php?";
        $tokenCondor = "condorSara2013!";
        $tipo=33;
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="gestionPassword&pagina=otrosCambioPassword";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=cambioPassword";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceCambioPassword=$indiceSaraLaverna.$opcion.$variable;

        //Consultar recibos derechos pecuniarios
        $variable="pagina=admin_consultarRecibosPecuniariosFuncionario";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=33";
        $variable.="&aplicacion=Condor";
        $variable.="&modulo=admisiones";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceReciboDerechosPecuniarios=$indiceAcademico.$variable;

    //Reporte interno de notas del estudiante
        $variable="pagina=reporte_interno";
    	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=33";
        $variable.="&modulo=soporte";
    	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
    	$enlaceAcademicoInterno=$indiceAcademico.$variable;

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
<a href="#">Administrador</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceAdministracionAdmisiones;?>">Administrador</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Procesos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="https://occired1.bancodeoccidente.com.co/bancacorporativa/">Bajar Pagos</a></li>
<li class="subitem1"><a target="principal" href="http://bari.icfes.gov.co/resultados/sniee_ind_res_ies.htm">Bajar ICFES</a></li>
<li class="subitem1"><a target="principal" href="reg_encripta_claves_de_aspirantes.php">Encriptar Claves</a></li>
</ul>
</li>


<li class="item1">
<a href="#">Recibos de Pagos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice1 ?>">Periodo Actual</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice2 ?>">Periodo Próximo</a></li>
</ul>
</li>


<li class="item1">
<a href="#">Consultas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceConsultasAspirantes ?>">Datos de aspirantes</a></li>
<li class="subitem1"><a target="principal" href="reg_consulta_referencia.php">Ref.Bancaria</a></li>
<li class="subitem1"><a target="principal" href="reg_snp_acaspw.php">SNP Aspirantes</a></li>
<li class="subitem1"><a target="principal" href="reg_snp_acasptransferencia.php">SNP Transferencia</a></li>
</ul>
</li>


<li class="item1">
<a href="#">Aspirantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_estrato.php">Poblaci&oacute;n por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_sexo.php">Poblaci&oacute;n por Sexo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_asp_localidad.php">Poblaci&oacute;n por Loc.</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Admitidos</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="reg_codif_anoper.php">Codificados</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_estrato.php">Poblaci&oacute;n por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_sexo.php">Poblaci&oacute;n por Sexo</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_localidad.php">Poblaci&oacute;n por Loc.</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Activos</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fra_cuenta_est.php">Con Asignaturas Ins.</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_activa_estrato.php">Activos por Estrato</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_activa_sexo.php">Activos por Sexo</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
</ul>
</li>


<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $CalAcad?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="../generales/derechos_pecuniarios.php">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceReciboDerechosPecuniarios;?>">Recibos derechos pecuniarios</a></li>
</ul>
</li>


<li class="item5">
<a href="#">Estad&iacute;sticas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="reg_uso_diario.php">Accesos Diarios</a></li>
<li class="subitem1"><a target="principal" href="reg_uso_inscripcion.php">Accesos Mensuales</a></li>
<li class="subitem1"><a target="principal" href="reg_asp_anoper.php">Aspirantes</a></li>
<li class="subitem1"><a target="principal" href="reg_adm_anoper.php">Admitidos</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_desercion.php">Deserci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_tot_empleados.php">Funcionarios</a></li>
<li class="subitem1"><a target="principal" href="reg_inscritos_por_facultad.php">Inscritos por Facultad</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_poblacion_adm_sexo.php">Inscritos por Carrera</a></li>
<li class="subitem1"><a target="principal" href="reg_relacion_consignaciones.php">Valores Consignados</a></li>
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
