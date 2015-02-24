<?
require_once('conexion.php');
session_name($usuarios_sesion);
session_start();
$logoutGoTo = "../index.php";
unset($_SESSION['autentificado']);
unset($_SESSION['usuario_login']);
unset($_SESSION['usuario_password']);
unset($_SESSION['u1']);
unset($_SESSION['c2']);
unset($_SESSION['b3']);
if($logoutGoTo != ""){
   session_destroy();
   session_unregister($_SESSION['autentificado']);
   session_unregister($_SESSION['usuario_login']);
   session_unregister($_SESSION['usuario_password']);
   session_unregister($_SESSION['u1']);
   session_unregister($_SESSION['c2']);
   session_unregister($_SESSION['b3']);
   OCILogOff($oci_conecta);
   header("Location: $logoutGoTo");
   exit;
}
?>