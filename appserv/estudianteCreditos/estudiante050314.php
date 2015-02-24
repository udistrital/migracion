<?PHP
/*
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');

require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');*/


require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
//require_once("../clase/multiConexion.class.php");

fu_tipo_user(52);



$estcod = $_SESSION['usuario_login'];



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
	    $pagpal = 'est_pag_principal.php';//aqui debo colocar la p�gina*/
	}
}
			
?>
<html>
<head>
<title>Estudiantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<?
	/*$result=$registro[0][0];
	echo "result".$result."<br>";*/
?>


<frameset class='cuerpoPrincipal' cols="*,1024,*" FRAMEBORDER="0" BORDER="0" FRAMESPACING="0"  NORESIZE  style="height: 630px;" >

	  <frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no" name="" target="" src="" NORESIZE>

	  <frameset  rows="109,495,46"  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 NORESIZE  >
			
				<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="superior" src="../generales/marcos/superior.php" NORESIZE>
			
			      

				<frameset cols="201,822" FRAMEBORDER=0 BORDER=0 FRAMESPACING=0 NORESIZE >
					<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="menu" target="_self" src="<? print $pmenu; ?>" NORESIZE >
					<frame  FRAMEBORDER=0 BORDER=0 FRAMESPACING=0  SCROLLING="auto"  name="principal" target="_self" src="<? print $pagpal; ?>" NORESIZE >
				</frameset>
			     
			

				<frame FRAMEBORDER=0 BORDER=0 FRAMESPACING=0   SCROLLING="no"  name="superior" src="../generales/marcos/inferior.php" NORESIZE>

		</frameset>

	  |<frame FRAMEBORDER="0" BORDER="0" FRAMESPACING="0"  SCROLLING="no" name="contenido" target="principal" src="" NORESIZE>

		<noframes>
			<body>
				<p>Esta p&aacute;gina usa marcos, pero su explorador no los admite.</p>
			</body>
		</noframes>
</frameset>





</html>
