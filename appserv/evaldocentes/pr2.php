<? 
require_once('dir_relativo.cfg');
require_once('dir_eval.cfg');

require_once(dir_conect.'valida_pag.php');

include("funparamspag.php");

require_once(dir_eval.'conexion_ev06.php');

require_once(dir_conect.'fu_tipo_user.php');
include("vartextosfijos.php");
		
fu_tipo_user($_SESSION["usuario_nivel"]); 
//include "funerr.php"; 
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
</HEAD>
<script type="text/javascript" src="evdoc.js"> </script>
<script language="JavaScript" src="md5.js"></script>

<script language="JavaScript">
function fmtoeval() {
	kMD5();
	document.forms.item("trae_con").submit()
	}
function kMD5() {
	var k = document.forms["trae_con"].elements["cra"].value;
	document.forms["trae_con"].elements["cra"].value="";
	var k2 = calcMD5(k);
	document.forms["trae_con"].elements["vinculacion"].value = k2;
	//alert("k2 = " + k2);
}
</script>

<?
$resta = 555;
Response.$Expires = 20;
?>

<BODY bgcolor="c6c384">
<center>
<form name=trae_con method=post action="evaluacion.php"><br>
<table border = 0  width=80 align="left">
	<tr>
		<EM>Registre su ingreso</EM>
	</tr>
  <tr  align=left>
	 <td ><INPUT type="text" id=nivel name=nivel size=16></td>
     <td ></td><EM><label for="usuario">Vin</label></EM></STRONG></td>
  </tr> 
  <tr  align=left>
     <td><EM><label for="usuario">Usuario</label></EM></td>
  </tr>   
  <tr  align=right>
	 <td><INPUT id=text2 name=usuario size=16 style="BACKGROUND-COLOR: white"></td>
  </tr> 
  <tr  align=left>
     <td><EM><label for="usuario">C&oacute;digo</label></EM></td>
  </tr>   
  <tr  align=right>
	 <td><INPUT type="password" id=text3 name=cra size=16 style="BACKGROUND-COLOR: white"></td>
  </tr> 
  
 </table><br><br><br>
	<? //$hoy =date("Y-n-d H-i");
	//if ($hoy >= "2007-2-16 00-00"){
		//Fecha - Hora actual mayor
		//echo $hoy; }else{
		//Fecha - Hora actual menor
	//echo $hoy?>
     <INPUT type="hidden" value="1" id="vinculacion" name="vinculacion"> 
     <INPUT type="submit" value="Entrar" id=submit1 name=submit1 onclick="javascript:fmtoeval()"style="COLOR: blue; FONT-STYLE: italic; BACKGROUND-COLOR: Gold"  size=4> 
</center>
</form><br><br><br><br><br>
<form name="pags" method="post" action="pag_proceso_cerrado.php">

     <INPUT type="submit" value="Probar Página" id=submit2 name=submit2 style="COLOR: blue; FONT-STYLE: italic; BACKGROUND-COLOR: Gold"  size=4> 
</form>
</font></a><br><br> 

</BODY>
</HTML>

