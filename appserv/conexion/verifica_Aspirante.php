<?PHP			//36783051
include_once('conexion.php');
require_once("../clase/funcionGeneral.class.php");


$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];
$numero = $_POST['numero'];
//echo $redir;

//echo $_POST['numero'].'-'.$_POST['pass'].'<br>';

if($_SERVER['HTTP_REFERER'] == ""){
   die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}

elseif(empty($_POST['pass'])){
   header("Location: $redir?error_login=3");
   exit;
}
else{
	  $cod_consul = ("SELECT rba_nro_iden, rba_clave, rba_valor, trunc(smiasp_valor*0.10), rba_ref_pago
					    FROM mntac.acasperiadm, mntac.acrecbanasp, mntac.acsalminasp
					   WHERE ape_ano = rba_ape_ano
						 AND ape_per = rba_ape_per
						 AND '".strtolower($_POST['numero'])."'||rba_clave = '".strtolower($_POST['pass'])."'
						 AND ape_estado = 'X'
						 AND smiasp_estado = 'A'");
	//echo  $cod_consul."<br>"; exit;			
}
if(isset($_POST['pass'])) {

	$conexion=new funcionGeneral();
	$registro=$conexion->ejecutarSQL($configuracion,$oci_conecta, $cod_consul,"busqueda");
		//var_dump($registro);

   if(is_array($registro)){
   
	  $serverpassword = strtolower($numero.$registro[0][1]);
	  $userpassword = strtolower($_POST['pass']); 
	  
	  if($registro[0][2] < $registro[0][3]){
	echo "1";
         header("Location: ../aspirantes/err/err_valor.php?c=".$registro[0][2]."&f=".$registro[0][3]);
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
         
	     	 $_SESSION["usuario_login"]=$registro[0][0];
		 $_SESSION["usuario_password"]=$registro[0][1];
		 $_SESSION["usuario_referencia"]=$registro[0][4];
		 $_SESSION["usuario_nivel"]=50;
		 
		 require_once('ins_usu.php');
		 require_once('ins_usu_adm.php');
		 //echo $ins_usu_adm."<br>";
		 //echo $ins_usu."<br>";
		 
		 unset($numero);
         	 unset($serverpassword);
		 unset($userpassword);

		 header("Location: ../aspirantes/aspirantes.php");
      } 
      else{ 

      	header("Location: $redir?error_login=5");
      } 
   }
   else{

	    header("Location: $redir?error_login=4");
        exit;
   }
}
?>
  
