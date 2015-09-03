<?PHP

	require_once('dir_relativo.cfg');
	require_once(dir_conect.'valida_pag.php');
	require_once('../generales/gen_link.php');
	require_once(dir_conect.'fu_tipo_user.php');
	fu_tipo_user(80);
	ob_start();
	require_once("../clase/config.class.php");
	require_once("../clase/encriptar.class.php");
	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$cripto=new encriptar();
	//$indice="http://oasdes.udistrital.edu.co/weboffice/index.php?";
	$indice=$configuracion["host"]."/weboffice/index.php?";
        $indiceAcademico=$configuracion["raiz_sga"]."/index.php?";
	//$indice="http://10.20.0.39/webofficepro/index.php?";
	$variable="pagina=adminCreacionUsuarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;

	$variable="pagina=adminCreacionUsuarios";
	$variable.="&opcion=crearNuevoUsuario";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCrearNuevo=$indice.$variable;

	//Actualiza la intensidad horaria
	$variable="pagina=login";	
	$variable.="&modulo=IntensidadHoraria";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";	
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=actualizaIntensidad@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$actualizacionIntensidadHoraria= $indice.$variable;	
	
	$variable="pagina=adminReportes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=80";
	$variable.="&parametro=@tipoUser=80";
	$variable.="&nivel=A";
	$variable.="&modulo=adminReportes";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceReportes=$indice.$variable;
        
        //Consultar inscripciones de estudiantes
        $variable="pagina=admin_inscripcionEstudianteSoporte";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=80";
        $variable.="&opcion=consultar";
        $variable.="&modulo=Coordinador";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceAcademicoAdiciones=$indiceAcademico.$variable;
        
          //Consjerias, consulta estado academico del estudiante e historia académica
        $variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=80";
        $variable.="&opcion=verEstudiante";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=soporte";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable;    

	//Procesar y cargar datos de estudiantes para inscripcciones
	$variable="pagina=admin_inicioSoporteInscripciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&opcion=verProyectos";	
	$variable.="&modulo=soporte";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePrecargaInscripciones=$indiceAcademico.$variable;   

	//consultar datos de estudiantes para preinscripcciones
	$variable="pagina=admin_preinscripcionEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&opcion=consultar";
	$variable.="&modulo=soporte";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePreinscripciones=$indiceAcademico.$variable; 

	//Actualizar datos de preinscripciones por demanda
	$variable="pagina=registro_actualizarPreinscripcionesSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&opcion=facultad";
	$variable.="&modulo=soporte";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceActualizarPreinscripciones=$indiceAcademico.$variable;
	
	
	//Homologaciones pendientes
	$variable="pagina=admin_homologacionesPendientesPorProyecto";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&opcion=realizarHomologacionProyectoPendientes";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=soporte";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHomologacionesPendientes=$indiceAcademico.$variable;

	//Enlace para generar recibos de pago de matricula.
	$indiceRecibos = $configuracion["host"]."/weboffice/index.php?";
	$variable ="pagina=oas_inicio";
	//$variable.="&parametro=@opcion=presentacion";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceRecibos=$indiceRecibos.$variable;

   	//ocupacionde salones
        $variable="pagina=adminocupacionSalones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
        $variable.="&opcion=inicio";
        $variable.="&tipoUser=80";
	$variable.="&nivel=A";
        $variable.="&modulo=soporte";
        $variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceOcupacion=$indiceAcademico.$variable;


	//Inscripción automática 
	$variable="pagina=admin_rankingPreinsDemanda";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=80";
	$variable.="&opcion=ranking";
	$variable.="&aplicacion=Condor";
        $variable.="&modulo=soporte";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceinscripcionAutomatica=$indiceAcademico.$variable;

	//Enlace para consultar intensidad horaria
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
	$variable="pagina=adminConsultaHorariosSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=80";
	$variable.="&nivel=A";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHorarios3=$indiceAcademico.$variable;
	
	//enlce registro documentos
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

    //Reporte interno de notas del estudiante
        $variable="pagina=reporte_interno";
    	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ingresar";
        $variable.="&tipoUser=80";
        $variable.="&modulo=soporte";
    	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
    	$enlaceAcademicoInterno=$indiceAcademico.$variable;

    //enlace historico de recibos de pagos
	$variable="pagina=admin_consultarHistoricoRecibos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=";
	$variable.="&tipoUser=109";
	$variable.="&nivel=A";
	$variable.="&modulo=AsistenteContabilidad";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHistoricoRecibosPago=$indiceAcademico.$variable; 


 //enlace solicitudes de usuario
	$variable="pagina=admin_consultarSolicitudesUsuario";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=consultar";
	$variable.="&tipoUser=80";
	$variable.="&nivel=A";
	$variable.="&modulo=soporte";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceSolicitudesUsuarios=$indiceAcademico.$variable; 

	 //enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=soporte";
	$enlaceReporteSoporte=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=facultad";
	$enlaceReporteFacultad=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=bienestar";
	$enlaceReporteBienestar=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=viceacademica";
	$enlaceReporteViceAcedemica=$configuracion["host"].$variable;
	
	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=secacademica";
	$enlaceReporteSecAcademica=$configuracion["host"].$variable;

	 //enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=viceacademica";
	$enlaceReporteViceAcademica=$configuracion["host"].$variable;
	
	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=laboratorio";
	$enlaceReporteLaboratorios=$configuracion["host"].$variable; 

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=ilud";
	$enlaceReporteILUD=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=ceri";
	$enlaceReporteCERI=$configuracion["host"].$variable;

	//enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=posgrado";
	$enlaceReportePosgrado=$configuracion["host"].$variable;
    
    //enlace sistema de administracion de reportes
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=tesoreria";
	$enlaceReporteTesoreria=$configuracion["host"].$variable;

        /*enlace sistema de administracion de reportes*/
        $variable="/reportes_udistrital/run.php?";
        $variable.="informes=docencia";
        $enlaceReporteDocencia=$configuracion["host"].$variable; 


//Recalcular estado a estudiantes
        $variable="pagina=admin_recalcularEstadoEstudiante";
        $variable.="&usuario=".$_SESSION['usuario_login'];        
        $variable.="&tipoUser=80";
        $variable.="&opcion=mostrarFormulario";
        $variable.="&modulo=soporte";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceAcademicoRecalcularEstado=$indiceAcademico.$variable;	

        //enlace generar recibo de pago
        $variable="pagina=admin_generarReciboPago";
        $variable.="&usuario=".$_SESSION['usuario_login'];        
        $variable.="&tipoUser=80";
        $variable.="&opcion=nuevo";
        $variable.="&modulo=soporte";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceGenerarReciboPago=$indiceAcademico.$variable;	

         //enlace cambiar estado recibo de pago
        $variable="pagina=admin_cambiarEstadoRecibo";
        $variable.="&usuario=".$_SESSION['usuario_login'];        
        $variable.="&tipoUser=80";
        $variable.="&opcion=";
        $variable.="&modulo=soporte";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceCambiarEstadoReciboPago=$indiceAcademico.$variable;	

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

//Enlace para cambio de contraseña
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraLaverna = $configuracion["host_adm_pwd"]."/laverna/index.php?";
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
$enlaceCambioPassword=$indiceSaraLaverna.$opcion.$variable;

//Enlace consulta de certificado de ingresos y retenciones
include_once("crypto/Encriptador.class.php");
$miCodificador=Encriptador::singleton();
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$indiceSaraLaverna = $configuracion["host"]."/saraadministrativa/index.php?";
$tokenCondor = "s4r44dm1n1str4t1v4C0nd0r2014!";
$tipo=80;
$tokenCondor = $miCodificador->codificar($tokenCondor);
$opcion="temasys=";
$variable.="gestionPassword&pagina=certificaciones";                                                        
$variable.="&usuario=".$usuario;
$variable.="&tipo=".$tipo;
$variable.="&token=".$tokenCondor;
$variable.="&opcionPagina=consultaCertIngRet";
//$variable=$cripto->codificar_url($variable,$configuracion);
$variable=$miCodificador->codificar($variable);
$enlaceCertificadosIngRet=$indiceSaraLaverna.$opcion.$variable;

//Enlace validacion usuarios activos

//echo file_exists ($_SERVER['DOCUMENT_ROOT']."/appserv/soporte/crypto_serviciosacademicos/EncriptadorServiciosAcademicos.class.php").PHP_EOL;
//$filecontents = file_get_contents(dirname(__FILE__)."/crypto_serviciosacademicos/EncriptadorServiciosAcademicos.class.php");
//var_dump( $filecontents);
//echo PHP_EOL;
//echo dirname(__FILE__)."/crypto_serviciosacademicos/EncriptadorServiciosAcademicos.class.php";

set_include_path(dirname(__FILE__));

//echo get_include_path().PHP_EOL;

require $_SERVER['DOCUMENT_ROOT']."/appserv/soporte/crypto_serviciosacademicos/EncriptadorServiciosAcademicos.class.php";

//echo $estaVar;
//echo "aqui".PHP_EOL;
//var_dump( class_exists (EncriptadorServiciosAcademicos ));

$miCodificadorServiciosAcademicos= EncriptadorServiciosAcademicos::singleton();

//var_dump($miCodificadorServiciosAcademicos);
//exit;
//
//exit;
//$miCodificadorServiciosAcademicos=Encriptador::singleton();


$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$modulo = '80';

$indiceSara = $configuracion["host"]."/serviciosacademicos/index.php?";

$tokenCondor = "condorSara2014";
$tokenCondor = $miCodificadorServiciosAcademicos->codificar($tokenCondor);
$opcion = 'datos=';
$variable.="&rol=soporte";
$variable.="&pagina=index";
$variable.="&usuario=".$usuario;
$variable.="&opcionPagina=validarEstudiantesActivos";
$variable.="&modulo=".$modulo;
$variable.="&token=".$tokenCondor;
//$variable=$cripto->codificar_url($variable,$configuracion);
$variable=$miCodificadorServiciosAcademicos->codificar($variable);
$enlaceValidarEstudiantes = $indiceSara.$opcion.$variable;

//Enlace Mantis
$usuario = $_SESSION['usuario_login'];
$identificacion = $_SESSION['usuario_login'];
$opcion = 'datos=';
$variable="&rol=soporte";
$variable.="&pagina=index";
$variable.="&usuario=".$usuario;
$variable.="&opcionPagina=mantis";
$variable.="&modulo=".$modulo;
$variable.="&token=".$tokenCondor;
$variable=$miCodificadorServiciosAcademicos->codificar($variable);
$enlaceMantis = $indiceSara.$opcion.$variable;

 $variable="pagina=admin_cargarDatosEgresado";
$variable.="&usuario=".$_SESSION['usuario_login'];
$variable.="&action=loginCondor";
$variable.="&tipoUser=80";
$variable.="&opcion=verFormCarga";
$variable.="&aplicacion=Condor";
$variable.="&modulo=soporte";
$variable=$cripto->codificar_url($variable,$configuracion);
$enlaceCargarArchivoGrados=$indiceAcademico.$variable;



	//Generar recibos especiales
	$variable="pagina=admin_recibosEspecialesArchivo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=80";
	$variable.="&opcion=verFormCarga";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=soporte";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceGenerarRecibosEspeciales=$indiceAcademico.$variable;
        
//Actualizar datos estudiantes acuerdo 004
        $variable="pagina=registro_actualizarDatos004";
        $variable.="&usuario=".$_SESSION['usuario_login'];        
        $variable.="&tipoUser=80";
        $variable.="&opcion=seleccionarOpcion";
        $variable.="&modulo=soporte";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceAcademicoActualizar004=$indiceAcademico.$variable;	

        

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
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlacePreinscripciones;?>">Consultar Preins</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoInterno ?>">Certificados Internos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceHistoricoRecibosPago;?>">Hist&oacute;rico Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoRecalcularEstado?>">Recalcular Reglamento</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceValidarEstudiantes?>">Validar Estudiantes Activos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoActualizar004?>">Actualizar datos est. 004</a></li>

</ul>
</li>

<li class="item1">
<a href="#">Admon. Usuarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceCambioPassword ?>">Cambiar clave</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Bit&aacute;cora</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceSolicitudesUsuarios;?>">Solicitud Usuarios</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Atención Usuarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceMantis;?>">Reporte Atención Usuarios</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Intensidad Horaria</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $actualizacionIntensidadHoraria;?>">Actualizaci&oacute;n Intensidad</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Homologaciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceHomologacionesPendientes;?>">Hom. pendientes</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReportes?>">Consultar Reportes</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceAcademicoAdiciones?>">Inscr. Estudiantes</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Grados</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCargarArchivoGrados;?>">Cargar archivo</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Vinculaci&oacute;n Docente</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceRegistroDocumentoVinculacion ?>">Documentos</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Inscripciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlacePrecargaInscripciones;?>">Procesar y cargar datos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceActualizarPreinscripciones;?>">Actualizar Preins.</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceinscripcionAutomatica;?>">inscripción Automática</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Recibos de pago</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceRecibos;?>">Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceGenerarReciboPago;?>">Generar Recibo de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceCambiarEstadoReciboPago;?>">Cambiar estado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceGenerarRecibosEspeciales;?>">Recibos especiales</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Gesti&oacute;n de Horarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceOcupacion ?>">Ocupaci&oacute;n de salones</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceHorarios3 ?>">Admin Horarios</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Gesti&oacute;n Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteSoporte ?>">Soporte</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteProyecto ?>">Proyecto Curricular</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteFacultad ?>">Facultad</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteBienestar ?>">Bienestar</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteViceAcademica; ?>">Vicerrector&iacute;a Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteSecAcademica; ?>">Secretaria Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteLaboratorios; ?>">Laboratorios</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteILUD ?>">ILUD</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReportePosgrado ?>">Posgrados</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteCERI ?>">Rel. Interinstitucionales</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteTesoreria ?>">Tesorer&iacute;a </a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteDocencia?>">Docencia</a></li>
</ul>
</li>

<li class="item1">
<a href="#">Modelo Riesgo</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceModelosBienestar?>">Ejecutar Proceso</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Cert. Ingresos y Ret.</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceCertificadosIngRet?>">Certificados</a></li>
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
