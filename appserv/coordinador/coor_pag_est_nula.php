<?php
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');
?>
<html>
<head>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<base target="_self">
</head>
<body>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
if(isset($_REQUEST['error_login'])){
   $error=$_REQUEST['error_login'];
   echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'><center>$error_login_ms[$error]</font></center>";
}
?>
</body>
</html>