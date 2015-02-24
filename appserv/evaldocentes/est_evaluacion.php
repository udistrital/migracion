<?php 
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51); ?>
	
				<HTML>
				<HEAD>
				<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>
				<TITLE>Evaluaci&oacute;n Docente en l&iacute;nea</TITLE>
				</HEAD>
				  <frameset cols="22%,*"  frameborder=no framespacing=0 >
					<frameset rows="*,22%"  frameborder=1 framespacing=0 >
						<frame src='vu2_estudiante.php' name=fra_registro border='0'>
					   <frame src="pag_blanca.htm" name=fra_operaciones scrolling=no>
					</frameset> 
				    <frameset> 
						<frame src='pag_instrucciones_e.php' name=fra_formato border='0'>
					</frameset>
				<noframes></noframes>
				</HTML> <? 

?>
