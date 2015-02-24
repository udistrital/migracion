<?php
 //require_once('../conexion/conexion.php');

function cierra_bd($cursor, $Logoff){
  OCIFreeCursor($cursor);
  OCILogOff($Logoff);
}
?>