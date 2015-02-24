<?PHP
require_once('dir_relativo.cfg');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_pie_pag.php');
?>
<HTML>
<HEAD><TITLE>Estudiantes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
</HEAD>
<BODY>
<?
print'<br><br><br><br>';
if(isset($_GET['error_login'])){
   $error=$_GET['error_login'];
   echo"<center><font face='Verdana, Arial, Helvetica, sans-serif' size='2' color='#FF0000'>
		<img src='../img/asterisco.gif'>$error_login_ms[$error]</font><br><br>
		<form method='POST' action='est_fre_asi_ins.php' target='principal'>
  		  <input type='submit' value='Refrescar' name='ref'>
		</form></center>
		<br><br>";
}
fu_pie();
?>
</BODY>
</HTML>