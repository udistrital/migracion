<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);
?>
<html>
<head>
<title>Aspirantes por a&ntilde;o y per&iacute;odo</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
	var html;
	if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
		http=new XMLHttpRequest();
	}
	else  {// code for IE6, IE5
	    http=new ActiveXObject("Microsoft.XMLHTTP");
	}
	http.open('HEAD', selObj.options[selObj.selectedIndex].value, false);
	try{
	    http.send();
	    document.getElementById('error_mjs').innerHTML = '';
	    eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	    if (restore) selObj.selectedIndex=0;
	} catch (e) {
		document.getElementById('error_mjs').innerHTML = 'El archivo al que está intentando acceder no exite.';
	}
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
<?PHP 
fu_cabezote("ADMITIDOS POR A&Ntilde;O Y PER&Iacute;ODO"); 

$dir='../informes/radm_fac_cra_';

require_once('msql_nom_archivo.php');
$RowPartNom = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryPartNom,"busqueda");

print'<div align="center"><br><br><br>
  <span class="Estilo5">Seleccione el per&iacute;odo</span>
	<form name="form1">
	<select name="asp">';
		$i=0;
		while(isset($RowPartNom[$i][0]))
		{
			echo'<option value="'.$RowPartNom[$i][0].'">'.$RowPartNom[$i][1].'</option>\n';
		$i++;
		}
	print'</select>
	<input type="button" name="Button1" value="Consultar" onClick="MM_jumpMenuGo(\'asp\',\'self\',0)" style="cursor:pointer" title="Ejecutar la consulta">
	</form>
	<p>&nbsp;</p>
    <table width="478" border="0" align="center">
    <tr>
	  <td><h1 id="error_mjs"></h1></td>
      <td><p style="line-height: 100%" align="justify"> De acuerdo al per&iacute;odo seleccionado, se despliega la informaci&oacute;n del total de admitidos por Facultad y Carrera. Graficando el porcentaje de admitidos por carrera frente al total de la Facultad.</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>';
?>
</body>
</html>