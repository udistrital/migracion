<?PHP
session_name($usuarios_sesion);
session_start();
if($_SERVER['HTTP_REFERER'] == ""){
   session_destroy();
   die('<p>&nbsp;</p><p align="center"><b><font color="#FF0000"><u>Acceso incorrecto!</u></font></b></p>');
   exit;
}
?>