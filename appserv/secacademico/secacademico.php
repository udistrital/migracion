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

fu_tipo_user(83);


$estcod = $_SESSION['usuario_login'];
//Funci�n que nos retorna S o N dependiendo si el estudiante ya tiene preinscripci�n.
$cod_consul= "SELECT ";
$cod_consul.= "mntac.fua_realizo_preins($estcod) ";
$cod_consul.= "FROM dual";
//Ejecuta la funci�n

	$conexion=new multiConexion();
	//$accesoOracle=$conexion->estableceConexion($configuracion,$_SESSION['usuario_nivel']);
	//$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle, $cod_consul,"busqueda");
 
	$pmenu = 'secacademico_pag_menu.php';
	if($configuracion['activar_caduca_pwd']=='S')
		{ include_once("../clase/crypto/Encriptador.class.php");
		 $miCodificador=Encriptador::singleton();
		 $usuario = $_SESSION['usuario_login'];
		 $identificacion = $_SESSION['usuario_login'];
		 $indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
		 $tokenCondor = "condorSara2013!";
		 $tipo=83;
		 $tokenCondor = $miCodificador->codificar($tokenCondor);
		 $opcion="temasys=";
		 $variable="gestionPassword&pagina=validarActualizacion";
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
		{
			$pagpal = 'secacademico_pag_principal.php';//aqui debo colocar la p�gina
		}
	


			
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Sec.Academic</title>
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


 

