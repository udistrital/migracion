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
		//Variables de configuracin
		<? 
		$estcod=$_SESSION['usuario_login'];
		$registroCarrera=$conexion->ejecutarSQL($configuracion,$accesoOracle,"SELECT est_cra_cod FROM acest WHERE est_cod=$estcod ","busqueda");
		$carrera = $registroCarrera[0][0];
		$plan = '../palndeestudio/pe_'.$carrera.'.pdf';
		if(!file_exists($plan))
		{
			$plan = '../palndeestudio/sin_plan.pdf';
		}
		?>		
		//Menu 0 
		makeMenu('top','Datos Personales')
			makeMenu('sub','Actualizar datos','<?PHP echo $enlaceActualizaDatos ?>','principal')
		
		//Menu 1
		makeMenu('top','Asignaturas')
			////makeMenu('sub','Inscritas','est_fre_asi_ins.php','principal')
			makeMenu('sub','Inscritas','<?echo $enlaceAcademicoHorarioInscripcion ?>','principal')
			makeMenu('sub','Adicionar y Cancelar','<?echo $enlaceAcademicoInscripcion?>','principal')
			//makeMenu('sub','Adicionar y Cancelar','est_fre_inscripcion.php','principal')
			//makeMenu('sub','Adicionar Electivas','est_fre_inscripcion_electivas.php','principal')
			makeMenu('sub','Vacacionales','est_asi_ins_curvac.php','principal')
			//makeMenu('sub','Horarios','est_fre_horarios.php','principal')
			makeMenu('sub','Cursos Programados','est_lis_asignaturas.php','principal')
			makeMenu('sub','Preins. por Demanda','<?echo $enlaceAcademicoPreinscripcionDemanda?>','principal')

		
		//Menu 2
		makeMenu('top','Notas')
		makeMenu('sub','Parciales','est_notaspar.php','principal')
		makeMenu('sub','Vacacionales','est_notas_curvac.php','principal')
		makeMenu('sub','Hist&oacute;rico','est_notas.php','principal')
		makeMenu('sub','Plan de Estudio','est_semaforo.php','principal')
		
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
		makeMenu('sub','Derechos Pecuniarios','https://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=313&Itemid=135','principal')
		makeMenu('sub','Plan de Estudios','<? print $plan; ?>','principal')
		makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
		  
		//Menu 5
		makeMenu('top','Biblioteca')
		makeMenu('sub','Bases de Datos','<?echo $enlaceAdminBiblioteca?>','principal')
		
		//Menu 5
		makeMenu('top','Clave')
		makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')
		
		//Menu 6
		makeMenu('salir','Cerrar Sesi&oacute;n','../conexion/salir.php','_top','end')
		
		//Ejecucin del men
		onload=SlideMenuInit;
</script>

</body>
</html>
