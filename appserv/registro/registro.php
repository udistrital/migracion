<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
//require_once(dir_script.'mensaje_error.inc.php');

fu_tipo_user(33);

//echo "mmm".$Nro;

if($Nro == 1){
   $pmenu='reg_pag_menu_uno.php';
   $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Dia == 30){
       $pmenu='reg_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Mod == ""){
       $pmenu='reg_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
else{

     $pmenu = 'reg_pag_menu.php';
	if($configuracion['activar_caduca_pwd']=='S')
		{ include_once("../clase/crypto/Encriptador.class.php");
		 $miCodificador=Encriptador::singleton();
		 $usuario = $_SESSION['usuario_login'];
		 $identificacion = $_SESSION['usuario_login'];
		 $indiceSaraPassword = $configuracion["host_adm_pwd"]."/index.php?";
		 $tokenCondor = "condorSara2013!";
		 $tipo=33;
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
			$pagpal = 'reg_pag_principal.php';
		}
     
}

?>
<html>
<head>
<title>Admisiones y Registro</title>
</head>

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


<noframes>
</html>
