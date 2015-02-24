<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

/*Retorna $oci_conecta que es el acceso a la base de datos dependiendo del usuario*/

/*********/


fu_tipo_user(52);


$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


$cripto=new encriptar();


        //$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
        //$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
	$indice=$configuracion["host"]."/weboffice/index.php?";
        $indiceAcademico=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        //$indice="http://10.20.0.39/webofficepro/index.php?";
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
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceActualizaDatos=$indice.$variable;

	$variable="pagina=consultarCalificaciones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	//$variable.="&action=consultarCalificaciones";
	$variable.="&opcion=loginCondor";
	$variable.="&tipoUser=52";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademico=$indiceAcademico.$variable;

        $variable="pagina=reporte_interno";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&opcion=generar";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoInterno=$indiceAcademico1.$variable;

                //Solicitudes de certificado
        $variable="pagina=adminSolicitudCertificado";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
        //$variable.="&opcion=".$_SESSION['usuario_login'];
        $variable.="&opcion=52";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoSolicitud=$indiceAcademico.$variable;

          //Adiciones y cancelaciones estudiantes
        $variable="pagina=admin_inicioInscripcionEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
        $variable.="&tipoUser=52";
        $variable.="&opcion=creditos";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoAdiciones=$indiceAcademico1.$variable;


        //Ver horarios de cada estudiante
        $variable="pagina=adminHorarioEstudiantes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verHorario";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoHorarioEstudiante=$indiceAcademico1.$variable;

	//Consultar plan de estudio
        $variable="pagina=adminConsultarPlanEstudioEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=mostrar";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsultaPlanEstudio=$indiceAcademico1.$variable;

	
	//Descargar Manuales de usuario
        $variable="pagina=adminManuales";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verEstudiante";
	$variable.="&tipoUser=52";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceManuales=$indiceAcademico1.$variable;

	//Normatividad
        $variable="pagina=adminNormatividadEstudiantes";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=principal";
	$variable.="&tipoUser=52";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";


	$variable=$cripto->codificar_url($variable,$configuracion);
        $enlaceNormatividad=$indiceAcademico1.$variable;

        //Consejerias
	$variable="pagina=admin_mensajeEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verMensajesRecibidos";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";

	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsejerias=$indiceAcademico1.$variable;
	
	//Biblioteca
	$variable="pagina=admin_biblioteca";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=adminBiblioteca";
        $variable.="&tipoUser=52";
        $variable.="&modulo=Estudiante";        
	$variable.="&aplicacion=Condor";
    
        $variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminBiblioteca=$indiceAcademico1.$variable;
	
	$indicePruebas="http://10.20.0.38/academicosga/index.php?";
	//$variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
	$variable="pagina=admin_inicioPreinscripcionDemandaEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=52";
	$variable.="&opcion=consultar";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	//$enlaceAcademicoPreinscripcionDemanda=$indicePruebas.$variable; 
	$enlaceAcademicoPreinscripcionDemanda=$indiceAcademico1.$variable;// este indice es el

//Enlace para evaluación Docente
        include_once("crypto/Encriptador.class.php");
        $miCodificador=Encriptador::singleton();
        $usuario = $_SESSION['usuario_login'];
        $identificacion = $_SESSION['usuario_login'];
        $tipo = 52;
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



?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<link href="../marcos/apariencia.css" rel="stylesheet" type="text/css">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">

<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
</head>

<body class='menu'>

<? require_once('../usuarios/usuarios.php'); ?>

<p align="center"> 



<script>
		var message = "";
		function clickIE(){
		if (document.all){
			(message);
			return false;
		}
		}
		function clickNS(e){
		if (document.layers || (document.getElementById && !document.all)){
			if (e.which == 2 || e.which == 3 ){
			(message);
			return false;
			}
		}
		}
		if (document.layers){
		document.captureEvents(Event.MOUSEDOWN);
		document.onmousedown = clickNS;
		} else {
		document.onmouseup = clickNS;
		document.oncontextmenu = clickIE;
		}
		document.oncontextmenu = new Function("return false")

		//Variables de configuracin
		<?
		$plan = '../palndeestudio/pe_'.$_SESSION["carrera"].'.pdf';
		if(!file_exists($plan))
		$plan = '../palndeestudio/sin_plan.pdf';
		?>
		//Menu 0
		makeMenu('top','Datos Personales')
			makeMenu('sub','Actualizar datos','<?PHP echo $enlaceActualizaDatos ?>','principal')


		//Menu 1
		makeMenu('top','Asignaturas')
//			makeMenu('sub','Inscritas','est_fre_asi_ins.php','principal')
			makeMenu('sub','Adicionar y Cancelar','<?echo $enlaceAcademicoAdiciones?>','principal')
			//makeMenu('sub','Adicionar Electivas','est_fre_inscripcion_electivas.php','principal')
//			makeMenu('sub','Vacacionales','est_asi_ins_curvac.php','principal')
			makeMenu('sub','Horarios','<?echo $enlaceAcademicoHorarioEstudiante?>','principal')
			makeMenu('sub','Preins. por Demanda','<?echo $enlaceAcademicoPreinscripcionDemanda?>','principal')

		//Menu 2
		makeMenu('top','Notas')
		makeMenu('sub','Parciales','est_notaspar.php','principal')
		makeMenu('sub','Vacacionales','../estudiantes/est_notas_curvac.php','principal')
//		makeMenu('sub','Hist&oacute;rico','est_notas.php','principal')
		makeMenu('sub','Certificado Interno','<?echo $enlaceAcademicoInterno?>','principal')
	//	makeMenu('sub','Plan de Estudio','est_semaforo.php','principal')
	//	makeMenu('sub','Notas Cr&eacute;ditos','<?echo $enlaceAcademico?>','principal')

		//Menu 3
		makeMenu('top','Docentes')
		makeMenu('sub','Contactar Docentes','est_adm_correos_doc.php','principal')
		makeMenu('sub','Consejerias','<?echo $enlaceAcademicoConsejerias?>','principal')
		makeMenu('sub','Evaluaci&oacute;n docentes','<?PHP echo $enlaceEvaldocentes?>','principal')

		//Menu 4
		makeMenu('top','Servicios')
		makeMenu('sub','Recibos de Pago','<?PHP echo $enlaceMatricula ?>','principal')
		makeMenu('sub','Inscripci&oacute;n a Grado','<?PHP echo $enlaceWeboffice ?>','principal')
		makeMenu('sub','Calendario Acad&eacute;mico','<?echo $CalAcad?>','principal')
		makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
		makeMenu('sub','Normatividad','<?echo $enlaceNormatividad?>','principal')
		makeMenu('sub','Derechos Pecuniarios','https://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=313&Itemid=135','principal')
	//	makeMenu('sub','Plan de Estudios','<? print $plan; ?>','principal')
		makeMenu('sub','Plan de Estudio','<?PHP echo $enlaceAcademicoConsultaPlanEstudio ?>','principal')
	//	makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
		
		//Menu 5
		makeMenu('top','Manuales')
                makeMenu('sub','Manuales','<?echo $enlaceManuales?>','principal')
  
		//Menu 5
		makeMenu('top','Biblioteca')
		makeMenu('sub','Bases de Datos','<?echo $enlaceAdminBiblioteca?>','principal')
		
		//Menu 6
		makeMenu('top','Clave')
		makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

		//Menu 7
		makeMenu('salir','Cerrar Sesi&oacute;n','../conexion/salir.php','_top','end')

		//Ejecucin del men
		onload=SlideMenuInit;
</script>




<?php



?>
</body>
</html>
