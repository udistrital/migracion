<?PHP
require_once('dir_relativo.cfg');
require_once('conexion.php');
require_once('cierra_bd.php');

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];
$numero = $_POST['numero'];

if($_SERVER['HTTP_REFERER'] == ""){
   die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}

elseif(empty($_POST['pass'])){
   header("Location: $redir?error_login=3");
   exit;
}
else{
	  $cod_consul = ("SELECT rba_nro_iden, rba_clave, rba_valor, trunc(smi_valor*0.10)-150, rba_ref_pago
					    FROM mntac.acasperiadm, mntac.acrecbanasp, mntac.acsalmin
					   WHERE ape_ano = rba_ape_ano
						 AND ape_per = rba_ape_per
						 AND '".strtolower($_POST['numero'])."'||rba_clave = '".strtolower($_POST['pass'])."'
						 AND ape_estado = 'X'
						 AND smi_estado = 'A'");
}
if(isset($_POST['pass'])) {
   $consulta = OCIParse($oci_conecta, $cod_consul);
   OCIExecute($consulta) or die(ora_errorcode());
   $row = OCIFetch($consulta);

   if($row != 0){
      	  $serverpassword = strtolower($numero.OCIResult($consulta, 2));
	  $userpassword = strtolower($_POST['pass']);
	  
	if(OCIResult($consulta, 3) < OCIResult($consulta, 4)){
         header("Location: ../err/err_valor.php?c=".OCIResult($consulta, 3)."&f=".OCIResult($consulta, 4));
         exit;
      }
	
      if($userpassword != $serverpassword){
         header("Location: $redir?error_login=3");
         exit;
      }
      if($serverpassword == $userpassword){
         session_name($usuarios_sesion);
		 session_start();
         session_cache_limiter('nocache,private');
	     $_SESSION["usuario_login"] = OCIResult($consulta, 1);
		 $_SESSION["usuario_password"] = OCIResult($consulta, 2);
		 require_once('ins_usu.php');
		 unset($numero);
         unset($serverpassword);
		 unset($userpassword);
		 cierra_bd($consulta,$oci_conecta);
		 header("Location: ../aspirantes/aspirantes.php");
      } 
      else{ header("Location: $redir?error_login=5"); } 
   }
   else{
	    header("Location: $redir?error_login=4");
        exit;
   }
}
?>
