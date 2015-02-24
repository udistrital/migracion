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
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
</head>

<body class="menu">
<? require_once('../usuarios/usuarios.php'); ?>
<script>
//Menu 0
makeMenu('top','Admon. Usuarios')
	makeMenu('sub','Usuario Activos','adm_usu_activos.php','principal')
	makeMenu('sub','Usuario Nuevo','adm_insert_geclaves.php','principal')
	makeMenu('sub','Perfiles','adm_perfil_usuario.php','principal')
	makeMenu('sub','Cambiar clave','<?echo $enlaceCambioPassword?>','principal')
	makeMenu('sub','Log Adici&oacute;n y Can.','adm_consulta_addcan.php','principal')
	makeMenu('sub','Revisi&oacute;n','adm_usuarios_para_inactivar.php','principal')
	makeMenu('sub','Estudiantes nuevos','adm_usuarios_nuevos_est.php','principal')
	makeMenu('sub','Docentes nuevos','adm_usuarios_nuevos_doc.php','principal')
	makeMenu('sub','Funcionarios nuevos','adm_usuarios_nuevos_emp.php','principal')
	makeMenu('sub','Encriptar claves','adm_encripta_claves_de_geclaves.php','principal')
	makeMenu('sub','Encriptar Aspirantes','adm_encripta_claves_de_aspirantes.php','principal')
	//makeMenu('sub','Datos de estudiantes','adm_fra_datos_est.php','principal')

//Menu 2
makeMenu('top','Intensidad Horaria')
	makeMenu('sub','Actualizaci&oacute;n Intensidad','<?echo $actualizacionIntensidadHoraria;?>','principal')
	makeMenu('sub','Prein. Demanda','<?echo $monitoreoPreinDemanda;?>','principal')
	makeMenu('sub','Intensidad Lote','<?echo $enlaceIntensidadHorariaLote;?>','principal')
	makeMenu('sub','Actualizar Intensidad Egresado','<?echo $enlaceIntensidadHorariaEgresado;?>','principal')
	//makeMenu('sub','Datos de docentes','coor_fra_datos_doc.php','principal')


//Menu 3
makeMenu('top','Docentes')
	makeMenu('sub','Corregir c&eacute;dulas','adm_corrige_cedula.php','principal')
	makeMenu('sub','Decanos','adm_decanos.php','principal')
	makeMenu('sub','Coordinadores','adm_fra_facultades.php','principal')
	makeMenu('sub','Docentes','adm_docentes.php','principal')
	//makeMenu('sub','Datos de docentes','coor_fra_datos_doc.php','principal')

//Menu 4    
makeMenu('top','Recibos')
	makeMenu('sub','Enviar recibos','<?echo $enlaceGeneraArchivosRecibos;?>','principal')

//Menu 5    
makeMenu('top','Servicios')
	//makeMenu('sub','Accesos a C&oacute;ndor','../generales/gen_uso_condor.php','principal')
	makeMenu('sub','Con asig. inscritas','adm_fra_cuenta_est.php','principal')
	makeMenu('sub','Estudiantes activos','../generales/gen_est_abhl.php','principal')
	makeMenu('sub','Encriptar caracteres','adm_fra_datos.php','principal')
	makeMenu('sub','Informaci&oacute;n PHP','phpinfo.php','principal')
	makeMenu('sub','Fechas Eventos','<?echo $enlaceFechasCalendario;?>','principal')

//Menu 6
makeMenu('top','Modelo Riesgo')
	makeMenu('sub','Ejecutar Proceso','<?echo $enlaceModelosBienestar;?>','principal')

//Menu 7
makeMenu('top','Salir')
	makeMenu('sub','Contraer el Men&uacute;','adm_pag_menu.php','_self')
	makeMenu('sub','Salir de Esta P&aacute;gina','../conexion/salir.php','_top')

//Ejecucin del men
onload=SlideMenuInit;
</script>
</body>
</html>
