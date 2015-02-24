<?PHP
require_once('conexion.php');
session_name($usuarios_sesion);
session_start();
if(!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
   session_destroy();
   die('<p align="center"><b><font color="#FF0000"><u>Sesi&oacute;n Cerrada!</u></font></b></p>');
   exit;
}
?>