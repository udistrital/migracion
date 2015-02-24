<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_pie_pag.php');
fu_tipo_user(4);
?>
<html>
<head>
<base target="_self">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<br><br><br><br><br><br><br>
<p align="center"><span class="Estilo5">HAGA CLIC EN EL NOMBRE DE LA CARRERA
<br>PARA VER EL LISTADO DE LOS DOCENTES</span><br></p>
<br><br><br><br><br><br><br><br><br><br>
<? fu_pie(); ?>
</body>
</html>