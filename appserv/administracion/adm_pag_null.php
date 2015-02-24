<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_pie_pag.php');
?>
<html>
<head>
<link href="estilo_adm.css" rel="stylesheet" type="text/css">
<base target="_self">
</head>
<body>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'><center>$error_login_ms[$error]</center>";
}
?>
<br>
<br>
<br>
<br>
<br>
<br>
<?php fu_pie(); ?>
</body>
</html>
