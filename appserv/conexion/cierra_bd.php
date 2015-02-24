<?php
function cierra_bd($cursor, $Logoff){
  //echo $cursor."--".$Loggoff;
  @OCILogOff($Logoff);
  OCIFreeCursor($cursor);
}
?>