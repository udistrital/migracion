<?PHP
/*
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');

require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');*/
/*

require_once(dir_conect.'valida_pag.php');
;*/
error_reporting(E_ALL);
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

		$pmenu = 'est_pag_menu.php';
		if($configuracion['activar_caduca_pwd']=='S')
			{	
			    include_once("../clase/crypto/Encriptador.class.php");
			    $miCodificador=Encriptador::singleton();
			    $usuario = $_SESSION['usuario_login'];
			    $identificacion = $_SESSION['usuario_login'];
			    $indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
			    $tokenCondor = "condorSara2013!";
			    $tipo=51;
			    $tokenCondor = $miCodificador->codificar($tokenCondor);
			    $opcion="temasys="; 	
			    $variable.="gestionPassword&pagina=validarActualizacion";                                                 
			    $variable.="&usuario=".$usuario;
			    $variable.="&tipo=".$tipo;
			    $variable.="&token=".$tokenCondor;
			    $variable.="&opcionPagina=validaActualizacion";
			    //$variable=$cripto->codificar_url($variable,$configuracion);
			    $variable=$miCodificador->codificar($variable);
			    $enlaceCambioPassword=$indiceSaraPassword.$opcion.$variable;
			    $pagpal=$indiceSaraPassword.$opcion.$variable;
			   }  
		else
			{   require_once("../clase/encriptar.class.php");
			    $cripto=new encriptar();
			    $indice=$configuracion["host"]."/weboffice/index.php?";
			    $variable1="pagina=admin_datos_basicos";
			    $variable1.="&usuario=".$_SESSION['usuario_login'];
			    //$variable.="&action=loginCondor";
			    //$variable.="&tipoUser=".$_SESSION['usuario_login'];
			    //$variable.="&modulo=ActualizaDatos";
			    $variable1.="&tiempo=".$_SESSION['usuario_login'];
			    $variable1=$cripto->codificar_url($variable1,$configuracion);
			    $pagpal = $indice.$variable1;//aqui debo colocar la p�gina*/
			}
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



<frameset class='cuerpoPrincipal' cols='*,90%,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE >

	 	<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>

		<frameset class='cuerpoPrincipal' rows='100%,*' FRAMEBORDER='0' BORDER='0' FRAMESPACING='0'  NORESIZE>

			 	 <frameset  rows="101,*,66"  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 NORESIZE  >
			
						<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="superior" src="../marcos/superior.php" NORESIZE>
			
					      

						<frameset cols="201,822" FRAMEBORDER='0' BORDER='0' FRAMESPACING='0' NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="menu" target="_self" src="<? print $pmenu; ?>" NORESIZE >
							<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="principal" target="_self" src="<? print $pagpal;?>" NORESIZE >
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


 

