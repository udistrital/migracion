<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('valida_http_referer.php');
require_once('valida_preinscripcion.php');
require_once('valida_cancelacion.php');

?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<frameset framespacing="0" border="0" rows="55%,*" frameborder="0">
  <frame name="superior" src="est_inscripcion.php" scrolling="auto">
  <frame name="inferior" src="est_pag_null_inscripcion.php" scrolling="auto">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>
