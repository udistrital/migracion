<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'NumeroVisitas.php');
fu_tipo_user(34);

ob_start();
if($Nro == 1){
   $pmenu='assr_pag_menu_uno.php';
   $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Dia == 30){
       $pmenu='assr_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
elseif($Mod == ""){
       $pmenu='assr_pag_menu_uno.php';
       $pagpal = '../generales/cambiar_mi_clave.php';
}
else{
     $pmenu = 'assr_pag_menu.php';
     $pagpal = 'assr_pag_principal.php';
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Asesor</title>
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





