
<?php

require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');

require_once(dir_conect.'valida_pag.php');

include("funparamspag.php");

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
		
fu_tipo_user($_SESSION["usuario_nivel"]); 
					//$servidor = $_SERVER["HTTP_REFERER"];	
				$k = @$_POST["vinculacion"];$cif = md5("1604");//.$ks-date("d")-date("n")); 
//echo $_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -Us_post- ".$_POST["usuario"]." nivel ".$_POST["nivel"];

			if (strtolower($k)==$cif){$_SESSION["usuario_login"] = @$_POST["usuario"];$_SESSION["usuario_nivel"] = @$_POST["nivel"];
				$_SESSION["pr2"] =0;// con '0': Omitir verificación del calendario
				//print "Ingreso pr2 Ok";
//echo $_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -usuario pr2: ".$_POST["usuario"]." nivel ".$_POST["nivel"];
			
			}else{unset($_SESSION["usuario_nivel"]);unset($_SESSION["usuario_login"]);}
		
		
	//		echo '<br>'.$_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -- ".$_POST["usuario"]." nivel ".$_POST["nivel"];
?>
				<HTML>
				<HEAD>
				<META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>
				<TITLE>Evaluaci&oacute;n Docente en l&iacute;nea</TITLE>
				</HEAD>
				  <frameset cols="22%,*"  frameborder=no framespacing=0 >
					<frameset rows="*,22%"  frameborder=1 framespacing=0 >
						<frame src=' <?php
							if ($_SESSION["usuario_nivel"] == 51) { 	
								echo "vu2_estudiante.php";
							}elseif ($_SESSION["usuario_nivel"] == 30) { 	
								echo "vu2_docente.php";
							}elseif ($_SESSION["usuario_nivel"] == 4)  { 
								echo "vu2_coordinador.php";
							}elseif ($_SESSION["usuario_nivel"] == 16) { 	
								echo "vu2_decano.php" ;
							}elseif ($_SESSION["usuario_nivel"] == 19) {
								echo "validcoordev.php";
							}else {
								echo 'pag_blanca.htm';
							} ?>' name=fra_registro border='0'>
					   <frame src="pag_blanca.htm" name=fra_operaciones scrolling=no>
					</frameset> 
				    <frameset> 
						<frame src=' <?php 
							if ($_SESSION["usuario_nivel"] == 51) { 	
								echo "pag_instrucciones_e.php";
							}elseif ($_SESSION["usuario_nivel"] == 30) { 	
								echo "pag_instrucciones_d.php";
							}elseif ($_SESSION["usuario_nivel"] == 4)  { 
								echo "pag_instrucciones_c.php";
							}elseif ($_SESSION["usuario_nivel"] == 16) { 	
								echo "pag_instrucciones_f.php" ;
							}elseif ($_SESSION["usuario_nivel"] == 99) {
								echo 'pag_instrucciones_cev.php';
							}else {
								echo 'pag_bienvenidos.php' ;
							} ?>' name=fra_formato border='0'>
				  </frameset>
				<noframes></noframes>
				</HTML> <? 
							
?>
