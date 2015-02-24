<html>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<body onLoad="this.document.encripta.A_Encriptar.focus();">
<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
$redir = 'encripta_clave.php';

if(!isset($_REQUEST['A_Encriptar'])){
   echo' <div align="center">
   <table border="0" width="100%" cellspacing="0" cellpadding="0">
   <caption>ENCRIPTAR UNA CADENA DE CARACTERES</caption>
   <tr><td width="100%" align="center">
   <FORM  method="post" ACTION="encripta_clave.php" name="encripta">
   <span class="Estilo11">*</span><input name="A_Encriptar" type="text" value="" size="15">
   <INPUT TYPE="Submit" VALUE="Encriptar" class="button" '.$evento_boton.'></FORM></center>';
}
else{
	 if($_REQUEST['A_Encriptar']=="") 
	    die("<br><br><br><center><span class='estilo11'><a OnMouseOver='history.go(-1)'>No hay registros para esta consulta.<br><br>Regresar</a></span></center>");
	    
	 //$password = des_encrypt($_POST['A_Encriptar'], $_POST['A_Encriptar']);
	 $password = md5($_REQUEST['A_Encriptar']);
	 echo'<div align="center"><center><table border="0" width="450" cellspacing="5" cellpadding="0">
     <tr><td width="100%" align="center" valign="middle">'.$_REQUEST['A_Encriptar'].'</td></tr>
     <tr><td width="100%" align="center" valign="middle">'.$password.'</td></tr>
     <tr><td width="100%" align="center" valign="middle">
	 <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000FF">
	 <a OnMouseOver="history.go(-1)">Limpiar</a></font></td></tr>
     </table></center></div>';
}
if(isset($_REQUEST['error_login'])){
   $error=$_REQUEST['error_login'];
   echo'<font face="Verdana" size="2" color="#FF0000"><a OnMouseOver="history.go(-1)">'.$error_login_ms[$error].'</a></font>';
} 
?>
</td></tr></table>
</div>
</body>