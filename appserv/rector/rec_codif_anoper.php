<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'fu_pie_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(31);
?>
<html>
<head>
<title>Aspirantes por año y período</title>
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
  var selObj = MM_findObj(selName); if (selObj) MM_jumpMenu(targ,selObj,restore);
}
//-->
</script>
</head>

<body>
<?PHP 
fu_cabezote("CODIFICADOS POR AÑO Y PERÍODO");

$dir='../informes/rcodif_fac_cra_';
require_once('msql_nom_archivo.php');

print'<div align="center"><br><br><br>
  <span class=\'Estilo5\'>Seleccione el per&iacute;odo</span>
  <form name="form1">
    <select name="asp">';
		do{
		   //if(OCIResult($QryPartNom,2) != $ano.'-'.$per) 
		      echo'<option value="'.OCIResult($QryPartNom,1).'">'.OCIResult($QryPartNom,2).'</option>\n';
	    }while(OCIFetch($QryPartNom));
	    cierra_bd($QryPartNom,$oci_conecta);
    print'</select>
    <input type="button" name="Button1" value="Consultar" onClick="MM_jumpMenuGo(\'asp\',\'self\',0)" style="cursor:pointer" title="Ejecutar la consulta">
  </form>
  <p>&nbsp;</p>
    <table width="478" border="0" align="center">
    <tr>
      <td><p style="line-height: 100%" align="justify"> De acuerdo al per&iacute;odo seleccionado, se despliega la informaci&oacute;n del total de estudiantes codificados por Facultad y Carrera. Graficando el porcentaje de admitidos por carrera frente al total de la Facultad.</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div><br><br><br><br><br><br><br><br><br>';
fu_pie(); ?>
</body>
</html>