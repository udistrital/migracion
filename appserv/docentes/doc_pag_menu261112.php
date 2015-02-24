<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../generales/gen_link.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once("../clase/config.class.php");
include_once("../clase/multiConexion.class.php");
require_once("../clase/encriptar.class.php");

	$esta_configuracion=new config();
	$configuracion=$esta_configuracion->variable("../");
        $cripto=new encriptar();
	fu_tipo_user(30);
        $conexion=new multiConexion();
        ob_start();
	$indiceAcademico= $configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
        $indiceAcademico1=$configuracion["raiz_sga"]."/index.php?";

        //Consejerias
	$variable="pagina=admin_consejeriasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=verProyectos";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAcademicoConsejerias=$indiceAcademico1.$variable;
	
	//Dig NOTAS
	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=PREGRADO";
	$variable.="&tipoUser=30";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesPregrado=$indiceDoc.$variable;
	
	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=POSGRADO";
	$variable.="&tipoUser=30";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesPosgrado=$indiceDoc.$variable;

	$indiceDoc=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_notasDocente";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=notasPerAnterior";
	$variable.="&nivel=ANTERIOR";
	$variable.="&tipoUser=30";
	$variable.="&aplicacion=Condor";
	$variable.="&modulo=docentes";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceNotasDocentesAnterior=$indiceDoc.$variable;

	/*$variable="pagina=adminConsultasAdmisiones";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=consultaDatosAspirantes";
	$variable.="&tipoUser=33";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceConsultasAspirantes=$indice.$variable;*/
	
	$indicePlanDocActual=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_plan_trabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=A";
	$variable.="&tipoUser=30";
	$variable.="&modulo=planTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocentesPlanTrabajoActual=$indicePlanDocActual.$variable;

	
	$indicePlanDocProximo=$configuracion["host"]."/weboffice/index.php?";
	
	$variable="pagina=registro_plan_trabajo";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&nivel=X";
	$variable.="&tipoUser=30";
	$variable.="&modulo=planTrabajo";
	$variable.="&tiempo=300";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocentesPlanTrabajoProximo=$indicePlanDocProximo.$variable;
	
	/*$indice=$configuracion["host"].$configuracion["raiz_sga"]."/index.php?";
	$indice1=$configuracion["raiz_sga"]."/index.php?";
	//$indice="http://oasdes.udistrital.edu.co/weboffice/webofficepro/index.php?";
	//$indice="http://10.20.0.39/webofficepro/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=84";
	$variable.="&modulo=AdminBlogdev";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceWeboffice=$indice.$variable;*/
      
	//Menú para ingresar a la página de docencia.
	$indiceDoc=$configuracion["host"]."/docencia/index.php?";
	$variable="pagina=login";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&action=loginCondor";
	$variable.="&tipoUser=30";
	$variable.="&modulo=Docencia";
	$variable.="&tiempo=".$_SESSION['usuario_login'];
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceDocencia=$indiceDoc.$variable;

	//Biblioteca
	$variable="pagina=admin_biblioteca";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&opcion=adminBiblioteca";
        $variable.="&tipoUser=30";
        $variable.="&modulo=Docente";        
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
	$enlaceAdminBiblioteca=$indiceAcademico.$variable;
?>
<html>
<head>
<link href="../script/estilo_menu.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/clicder.js"></script>
<script language="JavaScript" src="../script/SlideMenu.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/BorraLink.js"></script>
<link href="../marcos/apariencia.css" rel="stylesheet" type="text/css">
</head>

<body class="menu">
<p align="center"> 


<? require_once('../usuarios/usuarios.php'); ?>
<script>

makeMenu('top','Datos Personales')
	makeMenu('sub','Actualizar','doc_actualiza_dat.php','principal')

//Menu 1	
makeMenu('top','Plan de Trabajo')
	makeMenu('sub','Registrar Periodo Actual!','<?echo $enlaceDocentesPlanTrabajoActual?>','principal')
	makeMenu('sub','Registrar Periodo Pr&oacute;ximo!','<?echo $enlaceDocentesPlanTrabajoProximo?>','principal')
	//makeMenu('sub','Gestionar','doc_adm_pt.php','principal')
	makeMenu('sub','Reglamentaci&oacute;n','','')
	makeMenu('sub2','Estatuto Del Profesor','est_doc.pdf','principal')
	makeMenu('sub2','Circular 003','doc_circular003_pt.php','principal')
	makeMenu('sub2','Circular 008','doc_circular008_pt.php','principal')

//Menu 2
makeMenu('top','Asignaci&oacute;n Acad.')
	makeMenu('sub','Asignaturas','doc_fre_carga.php','principal')
	
//Menu 3
makeMenu('top','Consejerias')
	 makeMenu('sub','Consejerias','<? echo $enlaceAcademicoConsejerias ?>','principal')

//Menu 4    
makeMenu('top','Auto Evaluaci&oacute;n')
  makeMenu('sub','Auto Evaluaci&oacute;n','../err/valida_evadoc.php','principal')
  //makeMenu('sub','Auto Evaluaci�n','../ev06/evaluacion.php','principal')
  //makeMenu('sub','Obs. Eva. Actual','doc_fre_observaciones.php','principal')
  makeMenu('sub','Observaciones de Est.','doc_obsevaciones.php','principal')
  makeMenu('sub','Resultados','../informes/resultados_uni_prom_20113.pdf','principal')

//Menu 5
makeMenu('top','Captura de Notas')
  makeMenu('sub','Lista de clase','doc_curso.php','principal')
  makeMenu('sub','Captura notas Pregrado','<?echo $enlaceNotasDocentesPregrado?>','principal')
  makeMenu('sub','Captura notas Posgrado','<?echo $enlaceNotasDocentesPosgrado?>','principal')
  //makeMenu('sub','Posgrado','doc_curso_posgrado.php','principal')
  makeMenu('sub','Vacacionales','doc_carga_curvac.php','principal')
 // makeMenu('sub','Posgrados Per.Ant.','doc_carga_pos.php','principal')
  makeMenu('sub','<center><b>REPORTES</b></center>','','')
  //makeMenu('sub','Per&iacute;odo Anterior','','')
  makeMenu('sub','Notas per. Anterior','<?echo $enlaceNotasDocentesAnterior?>','principal')

//Menu 6
makeMenu('top','Servicios')
  makeMenu('sub','Estado de cuenta','<?PHP echo $enlaceDocencia ?>','principal')  
  //makeMenu('sub','Accesos a C&oacute;ndor','../generales/gen_uso_condor.php','principal')
  makeMenu('sub','Calendario Acad&eacute;mico','<?echo $CalAcad?>','principal')
  makeMenu('sub','Contactar Docentes','doc_contacta_doc.php','principal')
  makeMenu('sub','Derechos Pecuniarios','http://sgral.udistrital.edu.co/sgral/index.php?option=com_content&task=view&id=279&Itemid=116','principal')
  makeMenu('sub','Estatuto Estudiantil','../generales/estaturo_est.pdf','principal')
  makeMenu('sub','Estudiantes Activos','../generales/gen_est_abhl.php','principal')
  makeMenu('sub','Trabajos de Grado','../generales/gen_fac_trabgrado.php','principal')
  
	//Menu 5
	makeMenu('top','Biblioteca')
	makeMenu('sub','Bases de Datos','<?echo $enlaceAdminBiblioteca?>','principal')

//Menu 7
makeMenu('top','Clave')
makeMenu('sub','Cambiar mi Clave','../generales/cambiar_mi_clave.php','principal')

//Menu 8
makeMenu('salir','Cerrar Sesi&oacute;n','../conexion/salir.php','_top','end')

//Ejecucin del men
onload=SlideMenuInit;
</script>

</body>
</html>
