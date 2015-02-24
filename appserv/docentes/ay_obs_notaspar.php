<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");
  
$cod_consul = "SELECT NOB_COD, NOB_NOMBRE FROM ACNOTOBS WHERE NOB_COD IN(0,1,3,19,20) ORDER BY NOB_COD";
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

?>
<html>
<head>
<title>Ayuda</title>
<link href="../script/estilo_ay.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_jumpMenuGo(selName,targ,restore){ //v3.0
  var selObj = MM_findObj(selName); if (selObj) MM_jumpMenu(targ,selObj,restore);
}
//-->
</script>
</head>
<body>
<table width="90%" align="center" background="../img/fondo_ay.png">
  <tr>
    <td width="49" rowspan="3" class="td"><br>
    <img src="../img/ay.gif" width="30" height="30"></td>
    <td width="786" valign="middle"><span class="Estilo1"><br>
    &nbsp;&nbsp;&nbsp;
	<a href="doc_curso.php" target="principal">OBSERVACIONES DE NOTAS</a></span></td>
    <td width="39" valign="middle" align="center"><img src="../img/oas.gif" width="39" height="35"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr style="height:1" color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2">
	<?php
	echo '<table border="0" width="636" cellspacing="0" cellpadding="0"><tr>
	<td width="303">
	<p align="justify">Las notas deben ser digitadas sin punto decimal ni comas.    
	<p align="justify">Si la nota es 3.5, digite un n&uacute;mero entero 35.<br>  Si la nota es 0.8 digite solo la parte decimal 8. <br>
	<br>
	Digite 0 para las notas cualitativas, y en observaci&oacute;n digite 19 &oacute; 20, lo cual corresponde a "Aprobado" y "No Aprobado", respectivamente.
    	No oprima el bot&oacute;n &quot;<strong>Calcular Definitivas</strong>&quot;.</td>
	<td width="275" valign="top"><p align="justify">Antes de imprimir
	un reporte de las notas digitadas, haga clic en el bot&oacute;n &quot;<b>Grabar</b>&quot;.
	<p>
	<p align="justify"><strong>Para las notas cuantitativas</strong>: calcule las definitivas solo al
	final del semestre cuando se hayan digitado todas las notas parciales, haciendo clic en el bot&oacute;n &quot;<strong>Calcular Definitivas</strong>&quot;.</td>
	</tr></table>
		<table border="1" width="330" cellspacing="0" cellpadding="1">
	<caption>&nbsp;</caption>
	<tr>
		<td align="center" colspan="2" width="303"><span class="Estilo1">CAPTURA DE NOTAS PARCIALES</span></td>
	<tr><td width="104" align="center"><span class="Estilo1">C&oacute;digo</span></td>
	<td width="197" align="center"><span class="Estilo1">Tipo de Observaci&oacute;n</span></td></tr>';
	$i=0;
	while(isset($consulta[$i][0]))
	{
		echo'<tr><td width="3%" align="right">'.$consulta[$i][0].'</td>
		<td width="20%">'.$consulta[$i][1].'</td></tr>';
	$i++;
	}
?>
</table>

    </td>
  </tr>
</table>
<br>
<table width="90%" align="center" class="tb">
  <tr>
    <td width="75%" align="left" valign="middle">
     <? require_once('ay_doc_lis.php');?>
    </td>
    <td width="25%" align="right" valign="middle">
	<form name="form2">
	<input type="button" name="Submit" value="Cerrar" onClick="javascript:window.close();" class="button">
	</form></td>
  </tr>
</table>
</body>
</html>