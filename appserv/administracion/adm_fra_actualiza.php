<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'fu_pie_pag.php');
require_once("../calendario/calendario.php");
require_once(dir_script.'val-email.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(20);
?>
<html>
<head>
<title>Pagina nueva 2</title>
</head>
<frameset rows="22%,*" framespacing="0" border="0" frameborder="0">
  <frame name="superior" src="adm_consulta_est.php" scrolling="no">
  <frame name="inferior" src="adm_pag_null.php" scrolling="no">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
</html>