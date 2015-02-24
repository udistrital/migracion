<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
require_once("../clase/config.class.php");
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$cripto=new encriptar();
	fu_tipo_user(4);
	$conexion=new multiConexion();
	ob_start();
	
	$indice=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&nivel=4";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;

	
	$enlaceWebofficepro=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&modulo=proyectoCurricular";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWebofficepro=$indice.$variable;

	
	//Espacios Academicos	
	
	$indiceAcademico=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?"; 
        
	$variable="pagina=adminEspacio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=mostrar";
	$variable.="&tipoUser=4";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoEspacio=$indiceAcademico.$variable;

	//Planes de estudios
		
	//$variable="pagina=adminMallas";
	$variable="pagina=ProyectoCurricular";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&opcion=ver";
	$variable.="&opcion=mostrar";
        $variable.="&tipoUser=4";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPlan=$indiceAcademico1.$variable;
	
	//Inscripcion ECAES
	$indiceECAES=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=admin_inscripcion_ECAES";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	//$variable.="&modulo=matriculaEstudiante";
	$variable.="&tipoUser=4";
        $variable.="&nivel=4";
	$variable.="&modulo=RegistroECAES";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceECAES=$indiceECAES.$variable;

	//Enlace Weboffice
        $indice=$configuracion["host"]."/weboffice/index.php?";
        /*
         * Enlace para la preinscripcion automatica
         */
        $variable="pagina=admin_preinscripcion";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&opcion=mostrar";
        $variable.="&action=loginCondor";
        $variable.="&tipoUser=4";
        $variable.="&modulo=Preinscripcion";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlacePreinscripcion=$indice.$variable;
        
        //Inscripción Posgrados
	$variable="pagina=admin_inscripcionCoordinadorPosgrado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=4";
        $variable.="&opcion=verProyectos";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAdiciones=$indiceAcademico1.$variable;
	

	//Duplicar la carga académica
	$indice=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=registroCargaAcademica";
	$variable.="&modulo=cargaAcademica";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";	
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=duplicaCarga@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDuplicarCarga= $indice.$variable;

	//Control Planes de Trabajo Docentes Periodo actual
	$indicePlanTrabajo=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=adminPlanTrabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=controlPlanTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePlanTrabajo1=$indiceECAES.$variable;

	//Control Planes de Trabajo Docentes proximo
	$indicePlanTrabajo=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=adminPlanTrabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&nivel=X";
	$variable.="&modulo=controlPlanTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlacePlanTrabajo2=$indiceECAES.$variable;

	
	//Consulta la lista de Cursos Programados
	$indiceListaCursos=$configuracion["host"]."/weboffice/index.php?";
	$variable="pagina=adminListaCursos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=listaCursos";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceListaCursos=$indiceListaCursos.$variable;

	//Registro de estudiantes que se acogen al acerdo 004/2011
	$variable="pagina=login";	
	$variable.="&modulo=listaCursos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";	
	$variable.="&action=loginCondor";		
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable.="&parametro=@opcion=registroAcuerdo@accion=1@hoja=1";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$acuerdo004= $indice.$variable;	

	//Gestión de Horarios
	$variable="pagina=adminConsultaHorarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHorarios3=$indiceAcademico.$variable;

   	//ocupacionde salones
        $variable="pagina=adminocupacionSalones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
        $variable.="&opcion=inicio";
        $variable.="&tipoUser=4";
	$variable.="&nivel=A";
        $variable.="&modulo=Coordinador";
        $variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceOcupacion=$indiceAcademico.$variable;

	//Control de notas
	$variable="pagina=adminConsultasCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&nivel=A";
	$variable.="&tipoConsulta=controlNotas";
	$variable.="&tipoUser=4";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceControlNotas=$indice.$variable;

	//Histórico de notas
	$variable="pagina=adminConsultasCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&nivel=A";
	$variable.="&tipoConsulta=historicoNotas";
	$variable.="&tipoUser=4";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHistoricoNotas=$indice.$variable;

	//Novedades de Notas
	$variable="pagina=adminNovedadesNotas";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&tipopagina=no_pagina";
	$variable.="&nivel=A";
	$variable.="&modulo=adminNovedadesNotas";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNovedadesNotas=$indice.$variable;

	//Consultar observaciones de evaluación docentes

	$variable="pagina=adminEvaldocentes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=consultarDocente";
	//$variable.="&tipoConsulta=historicoNotas";
	$variable.="&tipoUser=4";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceResultados=$indice.$variable;

	/*enlce Homologaciones*/

        $variable="pagina=admin_homologaciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=4";
        $variable.="&opcion=crearTablaHomologacion";
        $variable.="&tipo_hom=normal";
        $variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCrearHomologaciones=$indiceAcademico.$variable;

	/*enlace para cierre*/
	$variable="pagina=adminCierreSemestre";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCierre=$indiceAcademico.$variable;

	// Inscripcion automatica 
	$variable="pagina=admin_inscripcionAutomaticaCoordinador"; 
	$variable.="&usuario=".$_SESSION['usuario_login']; 
	$variable.="&tipoUser=4";
	 $variable.="&opcion=parametrosInscripcionAuto"; 
	$variable.="&modulo=Coordinador"; 
	$variable.="&aplicacion=Condor"; 
	$variable=$cripto->codificar_url($variable,$configuracion); 
	$enlaceInscripcionAuto=$indiceAcademico.$variable;

	//Consultar horario de estudiantes
	$variable="pagina=admin_consultarEstudianteHorarioCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&opcion=consultar";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultarHorario=$indiceAcademico.$variable;

	//Consjerias, consulta estado academico del estudiante e historia académica
	$variable="pagina=admin_consejeriaEstudianteSoporte";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&opcion=verEstudiante";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsejeriaEstudiante=$indiceAcademico.$variable; 

	 //inscripciones cursos intermedios

	$variable="pagina=admin_inscripcionCoordinadorCI";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&opcion=verProyectos";
	$variable.="&modulo=Coordinador";
	$variable.="&action=loginCondor";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoCursosIntermedios=$indiceAcademico.$variable;

	/* ESTE MENU SE MODIFICA PARA APUNTARLO AL NUEVO SISTEMA DE INFORMACION REALIZADO CON SARA */
        include_once('crypto/Encriptador.class.php');

        $miCodificador=Encriptador::singleton();

        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $modulo = 4;
        $indiceSara = $configuracion["host"]."/docenciaSara/index.php?";

	$tokenCondor = "condorSara2013";
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="admin_carga_docente&pagina=coordinador";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&modulo=".$modulo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=admin_carga_docente";
        $variable=$miCodificador->codificar($variable);
        $enlaceCargaPerActual = $indiceSara.$opcion.$variable;	

	//Generar certificado de estudio de estudiantes
	$variable="pagina=admin_certificadoEstudio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceCertificadoEstudio=$indiceAcademico.$variable;


	 /*enlace historico de recibos de pagos*/
	$variable="pagina=admin_consultarHistoricoRecibos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=AsistenteContabilidad";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHistoricoRecibosPago=$indiceAcademico.$variable;

	//enlace evaluacion docente	
	$indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
        $tokenCondor = "condorSara2013!";
        $tipo=4;
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="indexEvaluacion&pagina=coordinador";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=indexEvaluacion";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceEvaldocentes = $indiceSaraAcademica.$opcion.$variable;
        
        //Enlace consulta observaciones evaluacion docente	
	$indiceSaraAcademica = $configuracion["host"]."/saraacademica/index.php?";
        $tokenCondor = "condorSara2013!";
        $tipo=4;
        $tokenCondor = $miCodificador->codificar($tokenCondor);
        $opcion="temasys=";
        $variable.="indexEvaluacion&pagina=coordinador";                                                        
        $variable.="&usuario=".$usuario;
        $variable.="&tipo=".$tipo;
        $variable.="&token=".$tokenCondor;
        $variable.="&opcionPagina=observaciones";
        //$variable=$cripto->codificar_url($variable,$configuracion);
        $variable=$miCodificador->codificar($variable);
        $enlaceObservacionesEvaldocentes = $indiceSaraAcademica.$opcion.$variable;

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=proyecto";
	$enlaceReporteProyecto=$configuracion["host"].$variable; 

	 /*enlace sistema de administracion de reportes*/
	$variable="/reportes_udistrital/run.php?";
	$variable.="informes=posgrado";
	$enlaceReportePosgrado=$configuracion["host"].$variable; 

	 //Codificar estudiantes nuevos
	$variable="pagina=admin_codificarEstudiantesNuevos";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&opcion=proyectos";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoCodificarEstudiante=$indiceAcademico.$variable;
	
	//Recalcular estado a estudiantes
        $variable="pagina=admin_recalcularEstadoEstudiante";
        $variable.="&usuario=".$_SESSION['usuario_login'];        
        $variable.="&tipoUser=4";
        $variable.="&opcion=mostrarFormulario";
        $variable.="&modulo=Coordinador";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceAcademicoRecalcularEstado=$indiceAcademico.$variable;	

	//Enlace para cambio de Contraseñas
	$usuario = $_SESSION['usuario_login'];
	$identificacion = $_SESSION['usuario_login'];
	$indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
	$tokenCondor = "condorSara2013!";
	$tipo=4;
	$tokenCondor = $miCodificador->codificar($tokenCondor);
	$opcion="temasys=";
	$variable.="gestionPassword&pagina=coordinador";
	$variable.="&usuario=".$usuario;
	$variable.="&tipo=".$tipo;
	$variable.="&token=".$tokenCondor;
	$variable.="&opcionPagina=cambioPassword";
	//$variable=$cripto->codificar_url($variable,$configuracion);
	$variable=$miCodificador->codificar($variable);
	$enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;


        //Registro de estudiantes a grado
        $variable="pagina=admin_inscripcionGraduando";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=4";
        $variable.="&opcion=verEstudiante";
        $variable.="&aplicacion=Condor";
        $variable.="&modulo=secretario";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceInscripcionGrados=$indiceAcademico.$variable;

	$enlaceManCargaAcad=$configuracion['host_soporte']."/soporte/archivos/manual_carga_academica.pdf";
	$enlaceManListaDoc=$configuracion['host_soporte']."/soporte/archivos/MANUAL_listado_de_docentes .pdf";
	$enlaceManDatosBas=$configuracion['host_soporte']."/soporte/archivos/Manual_Cambio_de_Datos_Basicos_Perfil_Coordinador.pdf";
	$enlaceManInscGrado=$configuracion['host_soporte']."/soporte/archivos/Manual_Inscripcion_a_Grado_Perfil_Coordinador.pdf";
	$enlaceManNovNotas=$configuracion['host_soporte']."/soporte/archivos/manual_novedad_de_notas.pdf";
	$enlaceManHomo=$configuracion['host_soporte']."/soporte/archivos/manual_de_homologaciones.pdf";
	$enlaceManRecExt=$configuracion['host_soporte']."/soporte/archivos/manual_generacion_recibo_de_pago_extemporaneos.pdf";

	$variable="pagina=adminConsultarNotasParcialesNoCargadas";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=inicio";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasNoCierre=$indiceAcademico.$variable;

	$enlaceManRecTer=$configuracion['host_soporte']."/soporte/archivos/manual_recibos_de_pago_terminacion_de_materias.pdf";
	
	//Consultar recibos derechos pecuniarios
	$variable="pagina=admin_consultarRecibosPecuniariosFuncionario";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=4";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=Coordinador";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceReciboDerechosPecuniarios=$indiceAcademico.$variable;

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
<a href="#">Datos Personales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_actualiza_dat.php">Actualizar Datos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Gesti&oacute;n de Horarios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceHorarios3 ?>">Gestionar Horarios</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceOcupacion ?>">Ocupaci&oacute;n de salones</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Carga Acad&eacute;mica</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceCargaPerActual ?>">Gestionar Carga</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Docentes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_frm_docentes.php">Listado de Docentes</a></li>
<li class="subitem1"><a href="#" class="postmenu">Evaluaci&oacute;n</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceEvaldocentes?>">Evaluar Docentes</a></li>
<li class="subitem1"><a target="principal" href="coor_observaciones_doc.php">Observaciones Estudiantes</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceResultados ?>">Consulta observaciones</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceObservacionesEvaldocentes ?>">Consulta observaciones 2014-1 +</a></li>
</ul>
</li>
<li class="subitem1"><a href="#" class="postmenu">Plan de Trabajo</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlacePlanTrabajo1 ?>">Ver Periodo Actual</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlacePlanTrabajo2 ?>">Ver Periodo Pr&oacute;ximo</a></li>
<li class="subitem1"><a target="principal" href="../docentes/estdfocen.pdf">Estatuto Del Profesor</a></li>
<li class="subitem1"><a target="principal" href="../docentes/doc_circular003_pt.php">Circular 003</a></li>
<li class="subitem1"><a target="principal" href="../docentes/doc_circular008_pt.php">Circular 008</a></li>
</ul>
<li class="subitem1"><a target="principal" href="coor_correos_doc.php">Enviar Correo</a></li>
</li>
</ul>
</li>

<li class="item1">
<a href="#">Estudiantes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../generales/gen_est_abhl.php">Activos</a></li>
<li class="subitem1"><a target="principal" href="coor_frm_datos_est.php">Asignaturas Inscritas</a></li>
<li class="subitem1"><a target="principal" href="coor_est_activos.php">Con Asig. Inscritas</a></li>
<li class="subitem1"><a target="principal" href="coor_frm_datos_est.php">Datos B&aacute;sicos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceConsejeriaEstudiante;?>">Historia Acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceCertificadoEstudio;?>">Certificado de Estudio</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoCodificarEstudiante?>">Codif. Estud. Nuevos</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceInscripcionGrados;?>">Inscripci&oacute;n a grado</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoRecalcularEstado?>">Recalcular Reglamento</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Cursos Programa.</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceListaCursos ?>">Disponibilidad de cupos</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Control de Notas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_fec_notaspar.php">Fechas Notas Parciales</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceControlNotas ?>">Notas digitadas</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceHistoricoNotas ?>">Hist&oacute;rico de Notas</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceNovedadesNotas ?>">Novedades de Notas</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Preinscripci&oacute;n</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceInscripcionAuto; ?>">Preinscripci&oacute;n Automatica</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Servicios</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWeboffice ?>">Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceHistoricoRecibosPago;?>">Hist&oacute;rico Recibos de Pago</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceReciboDerechosPecuniarios;?>">Recibos derechos pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $acuerdo004 ?>">Acuerdo 004/2011</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceECAES ?>">SaberPro</a></li>
<li class="subitem1"><a target="principal" href="<?echo $CalAcad?>">Calendario Acad&eacute;mico</a></li>
<li class="subitem1"><a target="principal" href="<?echo $configuracion['host_derechos_pecuniarios'];?>">Derechos Pecuniarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/estaturo_est.pdf">Estatuto Estudiantil</a></li>
<li class="subitem1"><a target="principal" href="coor_cra_hor.php">Horarios Por Grupo</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_fac_trabgrado.php">Trabajos de Grado</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceWebofficepro ?>">Proyectos curriculares</a></li>
</ul>
</li>

<li class="item2">
<a href="#">Manuales</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManCargaAcad ?>">Manual de carga acad&eacute;mica</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManListaDoc ?>">Manual de listado de docentes</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManDatosBas ?>">Manual cambio de datos básicos</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManInscGrado ?>">Manual Inscripción a grado</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManNovNotas ?>">Manual Novedades de Notas</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManHomo ?>">Manual Homologaciones</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManRecExt ?>">Manual generación recibos de pago extemporaneos</a></li>
<li class="subitem1"><a target="principal" href="<?php echo $enlaceManRecTer ?>">Manual generación recibos de Terminación Materias</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Admon. Noticias</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_index_msg.php">Noticias</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Estad&iacute;sticas</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="../estadistica/index_desercion.php">Deserc&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="../estadistica/index_tot_empleados.php">Funcionarios</a></li>
<li class="subitem1"><a target="principal" href="../generales/gen_inscritos_por_facultad.php">Proceso Admisiones</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Pensum</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="coor_actualiza_pen.php">Consultar</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceCrearHomologaciones?>">Homologaciones</a></li>
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
<a href="#">Inscripciones</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoAdiciones?>">Adici&oacute;n y Cancelaci&oacute;n</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlacePreinscripcion ?>">Parametros Condor</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoConsultarHorario?>">Horario Estudiantes</a></li>
<li class="subitem1"><a target="principal" href="<?echo $enlaceAcademicoCursosIntermedios?>">Cursos Intermedios</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Cierre Semestre</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceCierre ?>">Cerrar Semestre</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceNotasNoCierre ?>">Cargar Notas</a></li>
</ul>
</li>

<li class="item5">
<a href="#">Reportes</a>
<ul class="submenus">
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReporteProyecto ?>">Reportes Pregrado</a></li>
<li class="subitem1"><a target="principal" href="<?PHP echo $enlaceReportePosgrado ?>">Reportes Posgrado</a></li>
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
