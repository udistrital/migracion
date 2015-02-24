<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);
?>
<html>
<head>
<title></title>
</head>
<frameset rows="29%,31%,*" framespacing="0" border="0" frameborder="0">
  <frame src="encripta_clave.php" name="superior" scrolling="no" noresize>
  <frame src="lis_carrera.php" name="intermedio" scrolling="no" noresize>
  <frame src="lis_ciudad.php" name="inferior" scrolling="no" noresize>
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>