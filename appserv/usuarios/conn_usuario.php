<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');

require_once('qry_usu.php');

  if($_GET['u'] == 1){
	  require_once('conexion_otr.php');
      header("Location: ../admisiones/admisiones.php");
   }
  if($_GET['u'] == 4){
	  require_once('conexion_otr.php');
      header("Location: ../coordinador/coordinador.php");
   }
   elseif($_GET['u'] == 28){
		  require_once('conexion_otr.php');
          header("Location: ../coordinadorcred/coordinadorcred.php");
   }
   elseif($_GET['u'] == 16){
		  require_once('conexion_otr.php');
          header("Location: ../decano/decano.php");
   }
   elseif($_GET['u'] == 20){
		  require_once('conexion_otr.php');
          header("Location: ../administracion/adm_index.php");
   }
   elseif($_GET['u'] == 24){
		  require_once('conexion_otr.php');
          header("Location: ../funcionario/funcionario.php");
   }
   elseif($_GET['u'] == 25){
		  require_once('conexion_otr.php');
          header("Location: ../jefedependencia/jefedependencia.php");
   }   
   elseif($_GET['u'] == 30){
		  require_once('conexion_otr.php');
		  header("Location: ../docentes/docente.php");
   }
   elseif($_GET['u'] == 31){
		  require_once('conexion_otr.php');
		  header("Location: ../rector/rector.php");
   }
   elseif($_GET['u'] == 32){
		  require_once('conexion_otr.php');
		  header("Location: ../vicerrector/vicerrector.php");
   }
   elseif($_GET['u'] == 33){
		  require_once('conexion_otr.php');
		  header("Location: ../registro/registro.php");
   }
   elseif($_GET['u'] == 34){
		  require_once('conexion_otr.php');
		  header("Location: ../asesor/asesor.php");
   }
   elseif($_GET['u'] == 37){
		  require_once('conexion_otr.php');
		  header("Location: ../jurado_votacion/juradovotacion.php");
   }   
   elseif($_GET['u'] == 51){
		  require_once('conexion_otr.php');
		  header("Location: ../estudiantes/estudiante.php");
   }
   elseif($_GET['u'] == 50){
		  require_once('conexion_otr.php');
		  header("Location: ../aspirantes/aspirantes.php");
   }
  elseif($_GET['u'] == 80){
		  require_once('conexion_otr.php');
		  header("Location: ../soporte/soporte.php");
   }
  elseif($_GET['u'] == 87){
		  require_once('conexion_otr.php');
		  header("Location: ../moodle/moodle.php");
   }
  elseif($_GET['u'] == 84){
		  require_once('conexion_otr.php');
		  header("Location: ../desarrolloOAS/desarrolloOAS.php");
   }
  elseif($_GET['u'] == 61){
		  require_once('conexion_otr.php');
		 header("Location: ../asisVicerrectoria/asisVicerrectoria.php");
   }
  elseif($_GET['u'] == 75){
		  require_once('conexion_otr.php');
		  header("Location: ../admin_sga/admin_sga.php");
   }
  elseif($_GET['u'] == 83){
		  require_once('conexion_otr.php');
		  header("Location: ../secacademico/secacademico.php");
   }
  elseif($_GET['u'] == 88){
		  require_once('conexion_otr.php');
		  header("Location: ../docencia/docencia.php");
   }  
  elseif($_GET['u'] == 7){
		  require_once('conexion_otr.php');
		  header("Location: ../adminevaldoc/adminevaldoc.php");
   }
  elseif($_GET['u'] == 104){
		  require_once('conexion_otr.php');
		  header("Location: ../aspu/aspu.php");
   }
  elseif($_GET['u'] == 105){
		  require_once('conexion_otr.php');
		  header("Location: ../funcionario_planeacion/funcionario_planeacion.php");
   }
  elseif($_GET['u'] == 109){
	require_once('conexion_otr.php');
	header("Location: ../asistenteContabilidad/asistenteCont.php");
  } 	  
  elseif($_GET['u'] == 68){
	require_once('conexion_otr.php');
	header("Location: ../bienestarInstitucional/bienestar.php");
  }
  elseif($_GET['u'] == 110){
	require_once('conexion_otr.php');
	header("Location: ../asistenteProyecto/asistente.php");
  }
  elseif($_GET['u'] == 111){
	require_once('conexion_otr.php');
	header("Location: ../asistenteDecanatura/asistente.php");
  }
  elseif($_GET['u'] == 112){
	require_once('conexion_otr.php');
	header("Location: ../asistenteSecretaria/asistente.php");
  }
  elseif($_GET['u'] == 113){
	require_once('conexion_otr.php');
	header("Location: ../secretarioGeneral/secgeneral.php");
  } 
 elseif($_GET['u'] == 114){
	require_once('conexion_otr.php');
	header("Location: ../secretarioProyecto/secretario.php");
 }
 elseif($_GET['u'] == 115){
	require_once('conexion_otr.php');
	header("Location: ../secretarioDecanatura/secretario.php");
 }
 elseif($_GET['u'] == 116){
	require_once('conexion_otr.php');
	header("Location: ../secretarioSecretaria/secretario.php");
 }
 elseif($_GET['u'] == 117){
	require_once('conexion_otr.php');
	header("Location: ../asistenteRelInterinstitucionales/asistenteRelInterinstitucionales.php");
 }
 elseif($_GET['u'] == 118){
	require_once('conexion_otr.php');
	header("Location: ../laboratorios/laboratorios.php");
 }
 elseif($_GET['u'] == 119){
	require_once('conexion_otr.php');
	header("Location: ../asistenteILUD/asistenteILUD.php");
 }
 elseif($_GET['u'] == 120){
	require_once('conexion_otr.php');
	header("Location: ../consultor/consultor.php");
 }
 elseif($_GET['u'] == 121){
	require_once('conexion_otr.php');
	header("Location: ../egresado/egresado.php");
 }
 elseif($_GET['u'] == 122){
	require_once('conexion_otr.php');
	header("Location: ../asistenteTesoreria/asistenteTesoreria.php");
 }

?> 
