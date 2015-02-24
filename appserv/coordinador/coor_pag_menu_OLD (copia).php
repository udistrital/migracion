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
	
	//Enlcae registro Carga Academica 
	$variable="pagina=registroCargaAcademica";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&action=loginCondor";
        $variable.="&tipoUser=4";
        $variable.="&nivel=A";
        $variable.="&modulo=cargaAcademica";
	$variable.="&tiempo=300";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceCargaPerActual=$indice.$variable;

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

	        //Enlace usuarios
        $variable="pagina=adminUsuarios";
        $variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&opcion=mostrar";
        $variable.="&action=loginCondor";
        $variable.="&tipoUser=4";
        $variable.="&nivel=4";
        $variable.="&modulo=adminUsuario";
        $variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceUsuarios=$indice.$variable;

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
	$variable="pagina=adminHorarios";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=4";
	$variable.="&nivel=A";
	$variable.="&modulo=gestionHorarios";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceHorarios=$indice.$variable;

	//Control de notas
	$variable="pagina=adminConsultasCoordinador";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
	$variable.="&nivel=A";
	$variable.="&tipoUser=4";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceControlNotas=$indice.$variable;
	
?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<link href="../marcos/apariencia.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>

</head>

<body class="menu">

<? require_once('../usuarios/usuarios.php'); ?>

<script>
	//Menu 0 
	makeMenu('top','Datos Personales')
		makeMenu('sub','Actualizar Datos','coor_actualiza_dat.php','principal')
	//Menu1
	makeMenu('top','Gesti&oacute;n de Horarios')
		makeMenu('sub','Gestionar Horarios','<?PHP echo $enlaceHorarios ?>','principal')
	//Menu 2
	makeMenu('top','Carga Acad&eacute;mica')
		makeMenu('sub','Gestionar Carga','<?PHP echo $enlaceCargaPerActual ?>','principal')
	//Menu 3
	makeMenu('top','Docentes')
		makeMenu('sub','Listado de Docentes','coor_frm_docentes.php','principal')
		//makeMenu('sub','Evaluaci&oacute;n','../ev06/evaluacion.php','principal')
		makeMenu('sub','Evaluaci&oacute;n')
			makeMenu('sub2','Evaluar Docentes','../err/valida_evadoc.php','principal')
			makeMenu('sub2','Obser. de Estudiantes','coor_observaciones_doc.php','principal')
			makeMenu('sub2','Resultados','../informes/resultados_uni_prom_20113.pdf','principal')
		makeMenu('sub','Enviar Correo','coor_correos_doc.php','principal')
		makeMenu('sub','Plan de Trabajo','','')
			makeMenu('sub2','Ver Periodo Actual','<?PHP echo $enlacePlanTrabajo1 ?>','principal')
			makeMenu('sub2','Ver Periodo Pr&oacute;ximo','<?PHP echo $enlacePlanTrabajo2 ?>','principal')
			makeMenu('sub2','Estatuto Del Profesor','../docentes/estdfocen.pdf','principal')
			makeMenu('sub2','Circular 003','../docentes/doc_circular003_pt.php','principal')
			makeMenu('sub2','Circular 008','../docentes/doc_circular008_pt.php','principal')

	//Menu 4
	makeMenu('top','Estudiantes')
		makeMenu('sub','Activos','../generales/gen_est_abhl.php','principal')
		makeMenu('sub','Asignaturas Inscritas','coor_frm_datos_est.php','principal')
		makeMenu('sub','Con Asig. Inscritas','coor_est_activos.php','principal')
		makeMenu('sub','Datos B&aacute;sicos','coor_frm_datos_est.php','principal')
	  
	  
	//Menu 5
	makeMenu('top','Cursos Programa.')
		//makeMenu('sub','Disponibilidad Cupos','coor_lis_asignaturas.php','principal')
		makeMenu('sub','Disponibilidad de cupos','<?PHP echo $enlaceListaCursos ?>','principal')    

	//Menu 6
	makeMenu('top','Control de Notas')
		makeMenu('sub','Fec. Notas Parciales','coor_fec_notaspar.php','principal')
		makeMenu('sub','Notas digitadas','<?PHP echo $enlaceControlNotas ?>','principal')

	//Menu 7
	makeMenu('top','Preinscripci&oacute;n')
		makeMenu('sub','Preinscripci&oacute;n','<?PHP echo $enlacePreinscripcion ?>','principal')

	//Menu 8
	makeMenu('top','Servicios')
		makeMenu('sub','Recibos de Pago','<?PHP echo $enlaceWeboffice ?>','principal')
		makeMenu('sub','Acuerdo 004/2011','<?PHP echo $acuerdo004 ?>','principal')  
		makeMenu('sub','ECAES','<?PHP echo $enlaceECAES ?>','principal')
		makeMenu('sub','Calendario Acad&eacute;mico','<?echo $CalAcad?>','principal')
		makeMenu('sub','Derechos Pecuniarios ','http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=279&Itemid=116','principal')
		makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
		makeMenu('sub','Horarios Por Grupo','coor_cra_hor.php','principal')
		makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
		makeMenu('sub','Proyectos curriculares','<?PHP echo $enlaceWebofficepro ?>','principal')
	  
	//Menu 9
	makeMenu('top','Admon. Noticias')
		makeMenu('sub','Noticias','coor_index_msg.php','principal')
	  
	//Menu 10
	makeMenu('top','Estad&iacute;sticas')
		makeMenu('sub','Deserc&oacute;n','../estadistica/index_desercion.php','principal')
		makeMenu('sub','Funcionarios','../estadistica/index_tot_empleados.php','principal')
		makeMenu('sub','Proceso Admisiones','../generales/gen_inscritos_por_facultad.php','principal')
	  
	//Menu 11
	makeMenu('top','Pensum')
		makeMenu('sub','Actualizaci&oacute;n','coor_actualiza_pen.php','principal')
	
	//Menu 12
	makeMenu('top','Inscripciones')
		makeMenu('sub','Adici&oacute;n y Cancelaci&oacute;n','<?echo $enlaceAcademicoAdiciones?>','principal')

	//menu 13
	makeMenu('top','Usuarios')
		  makeMenu('sub','Gesti&oacute;n de usuarios','<?PHP echo $enlaceUsuarios?>','principal')

	//Menu 14
	makeMenu('top','Clave')
		makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

	
	//Menu 15
	makeMenu('top','Salir')
		makeMenu('sub','Salir de Esta P&aacute;gina','../conexion/salir.php','_top')

	//Ejecucin del men
	onload=SlideMenuInit;
</script>

</body>
</html>
