<?
session_name($usuarios_sesion);
session_start();
unset($_SESSION['autentificado']);
unset($_SESSION['usuario_login']);
unset($_SESSION['usuario_password']);

session_destroy();
session_unregister($_SESSION['autentificado']);
session_unregister($_SESSION['usuario_login']);
session_unregister($_SESSION['usuario_password']);
//OCILogOff($oci_conecta);

?>