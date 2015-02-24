
<?php 
//session_register($nuevousuario="newuser");
//require_once("bloqueasesion.php"); //Pendiente probar-----***************************
session_name($usuarios_sesion="Condorizado");// Igual al valor establecido en esta variable en la página 'interno.cfg'
session_register($usuarios_sesion="Condorizado");
//***session_start();
    //session_cache_limiter('nocache,private');
//$ks=date("Y-n-d H-i");
$servidor = $_SERVER["HTTP_REFERER"];
//$serv1 = "http://localhost/temp/estudiantes/est_pag_menu.php";
$serv2 = "http://localhost/ev06/pr2.php";
//$serv2 = "http://condor.udistrital.edu.co:7777/desa/ev063/pr2.php";

$serv9 = "http://oas11/ev06/pr2.php";
$serv9 = "http://localhost/ev073/pr2.php";
$serv10 = "http://10.20.0.6/appserv/ev073/pr2.php";
		//-----------     serv_b --------------
$serv1b = "http://condor.udistrital.edu.co/appdesa/ev073/pr2.php";
$serv7b = "http://condor.udistrital.edu.co/appdesa/ev073/pr2.php";
$serv2b = "http://condor.udistrital.edu.co:7777/appdesa/estudiantes/est_pag_menu.php";
$serv3b = "http://condor.udistrital.edu.co/appdesa/docentes/doc_pag_menu.php";
$serv4b = "http://condor.udistrital.edu.co/appdesa/estudiantes/est_pag_menu.php";
$serv5b = "http://condor.udistrital.edu.co/appdesa/coordinador/coor_pag_menu.php";
$serv6b = "http://condor.udistrital.edu.co/appdesa/decano/dec_pag_menu.php";
$serv6b = "http://condor.udistrital.edu.co/appdesa/funcionario/fun_pag_menu.php";

if ($servidor == $serv9 || $servidor == $serv2 || $servidor == $serv1b || $servidor == $serv10 || $servidor == $serv6b ) {
//--
			$k = @$_POST["vinculacion"];$cif = md5("1604");//.$ks-date("d")-date("n")); 
//echo $_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -- ".$_POST["usuario"]." nivel ".$_POST["nivel"];
			
			if (strtolower($k)==$cif){$_SESSION["usuario_login"] = @$_POST["usuario"];$_SESSION["usuario_nivel"] = @$_POST["nivel"];
				$_SESSION["pr2"] =0;// con '0': Omitir verificación del calendario
				session_name($usuarios_sesion="Autentificado");
				session_register($usuarios_sesion="Autentificado");session_start();
				//print "Ingreso pr2 Ok";
//echo $_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -usuario pr2: ".$_POST["usuario"]." nivel ".$_POST["nivel"];
			}else{unset($_SESSION["usuario_nivel"]);unset($_SESSION["usuario_login"]);}
		
		}?>
	<? $hoy =date("Y-n-d H-i");
	if ($hoy >= "2008-8-18 12-00" && $hoy < "2008-2-28 00-00"){?>

			<HTML>
			<HEAD>
			
			<TITLE></TITLE>
			<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
			
			<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
			
			<link href="../script/estilo.css" rel="stylesheet" type="text/css">
			
			</HEAD>
			<BODY >
			<CENTER>
			<font size = 6><EM>Evaluaci&oacute;n Docente</EM></font><BR><FONT size=5><EM>Periodo Acad&eacute;mico 2008-1</EM><BR>
			</FONT><BR><BR><BR><BR><BR><BR><EM><FONT size=4>verificar fechas programadas en pag evaluacion</FONT></EM><BR><BR><BR><BR>
			
			
			<font size = 3>         </font><EM><FONT 
			size=6><STRONG></STRONG></FONT></EM></CENTER>
			<P>&nbsp;</P>
			<CENTER><BR>
			
			
			
			<font size = 5><EM>-- Evaluaci&oacute;n en L&iacute;nea --</EM></font><BR><BR><BR><BR></CENTER>
			
			
			
			</BODY>
			</HTML>
	<? }else{
			//echo $_SESSION["usuario_login"]." <------usuario"." vin ".$_SESSION["usuario_nivel"]." -- ".$_POST["usuario"]." nivel ".$_POST["nivel"];
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
       }				
/*//-- Con semáforo				
} else {
	   header('Location: http://condor.udistrital.edu.co/oas/ev06/index.php');
}
*/ //--------
?>
