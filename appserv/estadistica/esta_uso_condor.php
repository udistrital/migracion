<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
?>
<HTML>
<HEAD>
<TITLE>Estadisticas</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
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
  var selObj = MM_findObj(selName); 
  if(selObj) MM_jumpMenu(targ,selObj,restore);
}
//-->
</script>
</HEAD>
<BODY>
<?php
fu_cabezote("ESTADISTICAS DE INGRESO A CONDOR");
$dir='../informes/';
require_once('msql_uso_condor.php');

$RowUsoCondor = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryUsoCondor,"busqueda");

print'<div align="center"><br><br><br>
  <span class="Estilo5">Seleccione el mes</span>
	<form name="form1">
		<select name="MesCod">';
			$i=0;
			while(isset($RowUsoCondor[$i][0]))
			{
				echo'<option value="'.$RowUsoCondor[$i][1].'">'.$RowUsoCondor[$i][0].'</option>\n';
				$i++;
			}
		print'</select>
		<input type="button" name="Button1" value="Consultar" onClick="MM_jumpMenuGo(\'MesCod\',\'self\',0)" style="cursor:pointer" title="Ejecutar la consulta">
	</form>
  <p>&nbsp;</p>

</div>';
?>
</BODY>
</HTML>