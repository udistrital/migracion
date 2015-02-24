<?PHP
/*
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');

require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');*/
/*

require_once(dir_conect.'valida_pag.php');
;*/
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
require_once("../clase/multiConexion.class.php");

fu_tipo_user(51);


$estcod = $_SESSION['usuario_login'];
//Funci�n que nos retorna S o N dependiendo si el estudiante ya tiene preinscripci�n.
$cod_consul= "SELECT ";
$cod_consul.= "mntac.fua_realizo_preins($estcod) ";
$cod_consul.= "FROM dual";
//Ejecuta la funci�n

	$conexion=new multiConexion();
	//$accesoOracle=$conexion->estableceConexion($configuracion,$_SESSION['usuario_nivel']);
	//$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle, $cod_consul,"busqueda");
    

//Valida si el estudiante ya tiene preinscripcion por parte del PROYECTO CURRICULAR


if($result=='N'){
	if($Nro == 1){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Dia == 30){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Mod == ""){
	$pmenu='est_pag_menu_uno.php';
	$pagpal = '../generales/cambiar_mi_clave.php';
	}
	else{
	$pmenu = 'est_pag_menu_preins.php';
	$pagpal = 'est_pag_principal.php';//aqui debo colocar la p�gina
	}
}
else{
	//echo $Nro."-".$Dia."-".$Mod."-";
	if($Nro == 1){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Dia == 30){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	}
	elseif($Mod == ""){
	    $pmenu='est_pag_menu_uno.php';
	    $pagpal = '../generales/cambiar_mi_clave.php';
	}
	else{
		require_once("../clase/encriptar.class.php");
		$cripto=new encriptar();
		
//		$indice="https://condor.udistrital.edu.co/weboffice/index.php?";
		
//		$variable="pagina=admin_datos_basicos";
//		$variable.="&usuario=".$_SESSION['usuario_login'];
		//$variable.="&action=loginCondor";
		//$variable.="&tipoUser=".$_SESSION['usuario_login'];
		//$variable.="&modulo=ActualizaDatos";
//		$variable.="&tiempo=".$_SESSION['usuario_login'];
//		$variable=$cripto->codificar_url($variable,$configuracion);
	/*$indice=$configuracion["raiz_sga"]."/index.php?";
	$variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
	$variable.="&usuario=".$_SESSION['usuario_login'];
	$variable.="&tipoUser=51";
	$variable.="&opcion=consultar";
	$variable.="&modulo=Estudiante";
	$variable.="&aplicacion=Condor";
	$variable=$cripto->codificar_url($variable,$configuracion);
		
	
	    $pmenu = 'est_pag_menu.php';
	    $pagpal = $indice.$variable;//aqui debo colocar la p�gina*/

            $pmenu='est_pag_menu.php';
	    $pagpal='est_fre_inscripcion.php';
	}
}
			
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Estudiantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>


<!--div style='position:absolute; width:1024px; height:630px'></div-->



<frameset class='cuerpoPrincipal' cols='*,1024,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE >

	 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>

		<frameset class='cuerpoPrincipal' rows='670,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE>

			 	 <frameset  rows="109,495,66"  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 NORESIZE  >
			
						<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="superior" src="../marcos/superior.php" NORESIZE>
			
					      

						<frameset cols="201,822" FRAMEBORDER='0' BORDER='0' FRAMESPACING='0' NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="menu" target="_self" src="<? print $pmenu; ?>" NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="principal" target="_self" src="<? print $pagpal; ?>" NORESIZE >
						</frameset>
					     
			

						<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="inferior" src="../marcos/inferior.php" NORESIZE>

				</frameset>

			 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>
		</frameset>
			
	 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>


</frameset>


<noframes><body>
</body></noframes>
</html>


 

