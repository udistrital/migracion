<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
require_once('valida_adicion.php');

?>
<html>
<head>
</head>
<frameset framespacing="0" border="0" rows="40%,*" frameborder="0">
  <frame name="superior" src="est_listado_asig.php" scrolling="auto">
  <frame name="inferior" src="est_pag_null_inscripcion.php" scrolling="auto">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>
