<?php 
// header("Location: ../index.php?error_login=113");
// exit;


//if(strlen($_REQUEST['user'])==11 && (substr(date("H"),-2)>'00' && substr(date("H"),-2)<'24') )
if(strlen($_REQUEST['user'])==11 )

{

$a=substr(date("i"), -1);
if($a>8)
   {$b=($a-9);}
else{$b=($a+1);}
if($a>7)
   {$c=($a-8);}
else{$c=($a+2);}
if($a>6)
   {$d=($a-7);}
else{$d=($a+3);}

if(substr($_REQUEST['user'], -1)>$a)
	{$t=(substr($_REQUEST['user'], -1)-$d);}
else
	{$t=(substr($_REQUEST['user'], -1)-$a)+7;}

if(
substr($_REQUEST['user'], -1)<>$a 
AND
substr($_REQUEST['user'], -1)<>$b 
AND
substr($_REQUEST['user'], -1)<>$c
AND
substr($_REQUEST['user'], -1)<>$d 
)
 {
 header("Location: ../index.php?error_login=112&t=$t");
 exit;
 }

}


include_once('conexion.php');
require_once("../clase/funcionGeneral.class.php");

//lineas nuevas para desencriptar
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
     
    $cripto=new encriptar();
    $cripto->decodificar_url($_REQUEST['index'],$configuracion);


//require_once('cierra_bd.php');
  
$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];
$numero = $_POST['numero'];

if(!isset($_POST['user'])){
    $_POST['user']=$_REQUEST['user'];
    $_POST['pass']=$_REQUEST['pass'];
    //echo    "<br>P ".$_POST['pass'];
    
    }else{
        $_POST['pass']=strtolower($_POST['pass']);
    }

//echo "url=".$redir;
/*echo "usuario=".$_POST['user'];
echo "pass=".$_POST['pass'];*/


if($_SERVER['HTTP_REFERER'] == "")
{
   die('<p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}
elseif(empty($_POST['user']))
{
	   header("Location: $redir?error_login=4");
	   exit;
}
elseif(!is_numeric($_POST['user']))
{
   	   header("Location: $redir?error_login=4");
	   exit;
}
elseif(empty($_POST['pass']))
{
	   header("Location: $redir?error_login=3");
	   exit;
}
else
{
	 $cod_consul = "SELECT ";
	 $cod_consul.= "cla_codigo, ";
	 $cod_consul.= "cla_clave, ";
	 $cod_consul.= "cla_tipo_usu, ";
	 $cod_consul.= "cla_estado, ";
	 $cod_consul.= "cla_estado ";
	 $cod_consul.= "FROM ";
	 $cod_consul.= $configuracion["sql_tabla1"]." ";
	 $cod_consul.= "WHERE ";
	 $cod_consul.= "cla_codigo = ".$_POST['user']." ";
	 $cod_consul.= "ORDER BY 5";

         
}

//echo $cod_consul;

if(isset($_POST['user']) && isset($_POST['pass'])) 
{
	$conexion=new funcionGeneral();
	$registro=$conexion->ejecutarSQL($configuracion,$oci_conecta, $cod_consul,"busqueda");


	$user = stripslashes($_POST['user']);

      if($registro[0][3] == 'F'){
         header("Location: $redir?error_login=110");
         exit;
      }

      if(is_array($registro)){
	  $serverpassword = strtolower($numero.$registro[0][1]);
	  $userpassword = strtolower($_POST['pass']); 

	  if($userpassword != $serverpassword){
	    header("Location: $redir?error_login=3");
	  exit;
      }

      if($registro[0][3] <> 'A'){
         header("Location: $redir?error_login=7");
         exit;
      }
      if($registro[0][0]==$user && $serverpassword==$userpassword){
	  	
		 require_once('ins_usu.php');
                 //session_name($usuarios_sesion);
		      /*echo $cod_consul."<br>";
		      echo "<br>1".$registro[0][0];
		      echo "<br>2".$user;
		      echo "<br>3".$serverpassword;
		      echo "<br>4".$userpassword."<br>";*/
	     	ob_start();
	     	session_cache_limiter('nocache,private');
		$_SESSION["usuario_login"] = $registro[0][0];
		$_SESSION['usuario_password'] = $registro[0][1];
		$_SESSION["usuario_nivel"] = $registro[0][2];
				 
		 //Volver a llamar a conexion para cambiar el usuario de la clave

		 
		 unset($user);
		 unset($numero);
         	 unset($serverpassword);
		 unset($userpassword);
		 //cierra_bd($consulta,$oci_conecta);
         	 if($_SESSION["usuario_nivel"] == 16) header("Location: ../decano/decano.php");
		 if($_SESSION["usuario_nivel"] ==  4) header("Location: ../coordinador/coordinador.php");
		 if($_SESSION["usuario_nivel"] == 30) header("Location: ../docentes/docente.php");
		 
		 /*if ($_SESSION['carrera'] == '025')
		 {
		 echo "Su Coordinación no ha hecho la preinsicripcion";
		 }
		 else
		 {*/
		 if($_SESSION["usuario_nivel"] == 51){
			 // echo "entro";
			// echo "<script>location.replace(' ../estudiantes/estudiante.php')</script>";
			 header("Location: ../estudiantes/estudiante.php");

		 }
		 if($_SESSION["usuario_nivel"] == 52){
			  header("Location: ../estudianteCreditos/estudianteCreditos.php");
		 }
		 if($_SESSION["usuario_nivel"] == 20) header("Location: ../administracion/adm_index.php");
		 if($_SESSION["usuario_nivel"] == 24) header("Location: ../funcionario/funcionario.php");
		 if($_SESSION["usuario_nivel"] == 26) header("Location: ../proveedor/proveedor.php");	
		 if($_SESSION["usuario_nivel"] == 31) header("Location: ../rector/rector.php");
		 if($_SESSION["usuario_nivel"] == 32) header("Location: ../vicerrector/vicerrector.php");
		 if($_SESSION["usuario_nivel"] == 33) header("Location: ../registro/registro.php");			 
		 if($_SESSION["usuario_nivel"] == 34) header("Location: ../asesor/asesor.php");
		 if($_SESSION["usuario_nivel"] == 61) header("Location: ../asisVicerrectoria/asisVicerrectoria.php");		
		 if($_SESSION["usuario_nivel"] == 75) header("Location: ../admin_sga/admin_sga.php");
		 if($_SESSION["usuario_nivel"] == 80) header("Location: ../soporte/soporte.php");
                 if($_SESSION["usuario_nivel"] == 83) header("Location: ../secacademico/secacademico.php");
                 if($_SESSION["usuario_nivel"] == 28) header("Location: ../coordinadorcred/coordinadorcred.php");
                 if($_SESSION["usuario_nivel"] == 84) header("Location: ../desarrolloOAS/desarrolloOAS.php");
		 if($_SESSION["usuario_nivel"] == 87) {header("Location: ../moodle/moodle.php");}
		 if($_SESSION["usuario_nivel"] == 88) {header("Location: ../docencia/docencia.php");} 
	 
			//1023861508
			
		 	
      } 
      else{ header("Location: $redir?error_login=5"); } 
   }
   else{
   	if($oci_conecta){
		header("Location: $redir?error_login=4");
		exit;
	}
	else{
		header("Location: ../?error_login=109");
		exit;	
	}
	
   }
}
 ob_close();
?>
