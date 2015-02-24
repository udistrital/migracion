<?
//require_once('conexion.php');
require_once("../clase/config.class.php");
require_once("../clase/encriptar.class.php");
$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../"); 
$cripto=new encriptar();
session_name($configuracion["usuarios_sesion"]);
session_start();
$var='usuario='.$_SESSION['usuario_login'];
$url=$configuracion['host_logueo'].$configuracion['site']."/clase/logout.class.php";
$var=$cripto->codificar_url($var, $configuracion);
echo "<script type='text/javascript'>
        window.location='$url?$var';
      </script>";
exit;

?>
