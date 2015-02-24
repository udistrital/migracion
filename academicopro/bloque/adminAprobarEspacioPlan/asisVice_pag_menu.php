<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once('../generales/gen_link.php');
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../"); 

	$cripto=new encriptar();
	fu_tipo_user(61);
	ob_start();
	//$indice="http://oas2.udistrital.edu.co/weboffice/index.php?";
	$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;

	$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=61";
	$variable.="&modulo=proyectoCurricular";
	$variable.="&tiempo=".$_SESSION['usuario_login'];

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWebofficepro=$indice.$variable;


        //Aprobacion de espacios academicos para plan de estudios
	$indiceAcademico="http://oasdes.udistrital.edu.co/academicopro/index.php?";
	//$indiceAcademico="http://oasdes.udistrital.edu.co/academicondor/index.php?";
	$variable="pagina=adminAprobarEspacioPlan";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=ver";
	$variable.="&tipoUser=61";
	$variable.="&modulo=AsistenteVicerrectoria";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        
        $enlaceApobacionEspacios=$indiceAcademico.$variable;

	/*
	//Espacios Academicos	
	$indiceAcademico="http://oasdes.udistrital.edu.co/academicondor/index.php?";
	$variable="pagina=adminEspacio";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=mostrar";
	$variable.="&tipoUser=4";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoEspacio=$indiceAcademico.$variable;

	//Planes de estudios
	$indiceAcademico="http://oasdes.udistrital.edu.co/academicondor/index.php?";
	//$variable="pagina=adminMallas";
	$variable="pagina=ProyectoCurricular";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&opcion=ver";
	$variable.="&opcion=mostrar";
        $variable.="&tipoUser=4";
	$variable.="&modulo=Coordinador";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoPlan=$indiceAcademico.$variable;*/

?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<link href="../generales/marcos/apariencia.css" rel="stylesheet" type="text/css">
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
		
		makeMenu('top','Planes de Estudio')
		  //makeMenu('sub','Datos B&aacute;sicos','coor_frm_datos_doc.php','principal')
		  makeMenu('sub','Aprobar Espacios Académicos','<?echo $enlaceApobacionEspacios?>','principal')
		//Menu 1
		/*makeMenu('top','Docentes')
		  //makeMenu('sub','Datos B&aacute;sicos','coor_frm_datos_doc.php','principal')
		  makeMenu('sub','Listado de Docentes','coor_frm_docentes.php','principal')
		  //makeMenu('sub','Evaluaci&oacute;n','../ev06/evaluacion.php','principal')
		  makeMenu('sub','Evaluaci&oacute;n')
		  	makeMenu('sub2','Evaluar Docentes','../err/valida_evadoc.php','principal')
			makeMenu('sub2','Obser. de Estudiantes','coor_observaciones_doc.php','principal')
			makeMenu('sub2','Resultados','../informes/rresultados_uni_prom_20061.pdf','principal')
		  
		  makeMenu('sub','Enviar Correo','coor_correos_doc.php','principal')
		  makeMenu('sub','Plan de Trabajo','','')
		  	makeMenu('sub2','Ver Plan de Trabajo','coor_doc_digito_pt.php','principal')
			makeMenu('sub2','Estatuto Del Profesor','../docentes/estdfocen.pdf','principal')
			makeMenu('sub2','Circular 003','../docentes/doc_circular003_pt.php','principal')
			makeMenu('sub2','Circular 008','../docentes/doc_circular008_pt.php','principal')

		//Menu 2
		makeMenu('top','Estudiantes')
		  makeMenu('sub','Activos','../generales/gen_est_abhl.php','principal')
		  makeMenu('sub','Asignaturas Inscritas','coor_frm_datos_est.php','principal')
		  makeMenu('sub','Con Asig. Inscritas','coor_est_activos.php','principal')
		  makeMenu('sub','Datos B&aacute;sicos','coor_frm_datos_est.php','principal')
		  //makeMenu('sub','Enviar Correo','coor_frm_emaii_est.php','principal')
		  
		//Menu 3
		makeMenu('top','Cursos Programa.')
		  makeMenu('sub','Disponibilidad Cupos','coor_lis_asignaturas.php','principal')

		//Menu 4
		makeMenu('top','Control de Notas')
		  makeMenu('sub','Fec. Notas Parciales','coor_fec_notaspar.php','principal')
		  makeMenu('sub','Notas Digitadas','coor_control_notas.php','principal')
		  makeMenu('sub','Notas Cr&eacute;ditos','<?echo $enlaceAcademico?>','principal')

		//Menu 5
		makeMenu('top','Servicios')
		  makeMenu('sub','Recibos de Pago','<?PHP echo $enlaceWeboffice ?>','principal')		
		  //makeMenu('sub','Accesos a C&oacute;ndor','../generales/gen_uso_condor.php','principal')
		  makeMenu('sub','Calendario Acad&eacute;mico','<?echo $CalAcad?>','principal')
		  makeMenu('sub','Derechos Pecuniarios ','http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=184&Itemid=1','principal')
		  makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
		  makeMenu('sub','Horarios Por Grupo','coor_cra_hor.php','principal')
		  makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
		  makeMenu('sub','Proyectos curriculares','<?PHP echo $enlaceWebofficepro ?>','principal')
		  
		//Menu 6
		makeMenu('top','Admon. Noticias')
		  makeMenu('sub','Noticias','coor_index_msg.php','principal')
		  
		//Menu 7
		makeMenu('top','Estad&iacute;sticas')
		  makeMenu('sub','Deserc&oacute;n','../estadistica/index_desercion.php','principal')
		  makeMenu('sub','Funcionarios','../estadistica/index_tot_empleados.php','principal')
		  makeMenu('sub','Proceso Admisiones','../generales/gen_inscritos_por_facultad.php','principal')
		  
		//Menu 8
		makeMenu('top','Pensum')
		  makeMenu('sub','Actualizaci&oacute;n','coor_actualiza_pen.php','principal')
		
		//Menu 9
		makeMenu('top','Cr&eacute;ditos')
		makeMenu('sub','Espacios Acad&eacute;micos','<?echo $enlaceAcademicoEspacio?>','principal')
		makeMenu('sub','Planes de Estudios','<?echo $enlaceAcademicoPlan?>','principal')
  */
		
		//Menu 10
		makeMenu('top','Clave')
		  makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

		
		//Menu 11
		makeMenu('top','Salir')
		  makeMenu('sub','Contraer el Men&uacute;','coor_pag_menu.php','_self')
		  makeMenu('sub','Salir de Esta P&aacute;gina','../conexion/salir.php','_top')

		//Ejecucin del men
		onload=SlideMenuInit;
</script>

</body>
</html>
