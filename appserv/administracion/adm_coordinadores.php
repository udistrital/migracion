<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
?>
<html>
<head>
<title>Facultades</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

$b_edit ='<IMG SRC='.dir_img.'b_edit.png alt="Editar" border="0">';
$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Insertar" border="0">';
$b_browse='<IMG SRC='.dir_img.'b_browse.png alt="Consultar" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar" border="0">';

if($_REQUEST['depcod'] == "") $depcod = 23;
else $depcod = $_REQUEST['depcod'];

$QryFac = "SELECT unique(cra_dep_cod), dep_nombre
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_dep_cod = $depcod";

$RowFac = $registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFac,"busqueda");

$qry_coor = "SELECT cra_cod, cra_abrev, doc_nro_iden,
	(LTRIM(doc_nombre)||' '||LTRIM(doc_apellido)),doc_email
	FROM gedep, accra, acdocente
	WHERE dep_cod = $depcod
	AND dep_cod = cra_dep_cod
	AND cra_estado = 'A'
	AND cra_emp_nro_iden = doc_nro_iden
	AND doc_estado = 'A'
	ORDER BY 1 ASC";
	
$Row_coor = $registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_coor,"busqueda");
	
echo'<table border="0" width="90%" cellspacing="0" cellpadding="0" align="center">
<tr><td colspan="2" align="center"><span class="Estilo5">COORDINADORES:</span> <B>'.$Facultad.'</B></td></tr></table>

<table border="0" width="90%"><tr><td width="684" colspan="2" align="center">';
if(isset($_REQUEST['error_login'])){ 
$error=$_REQUEST['error_login']; 
echo"<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'> 
<a OnMouseOver='history.go(-1)'>$error_login_ms[$error]</a></font>"; 
}
echo'</td></tr></table>

<table width="760" border="1" cellspacing="0" cellpadding="0" align="center">
	<tr class="tr" align="center">
		<td>Documento</td>
		<td>Nombre</td>
		<td>C&oacute;d</td>
		<td>Proyecto Curricular</td>
		<!-- <td colspan="2">Acci&oacute;n</td> --></tr>';
		$i=0;
		while(isset($Row_coor[$i][0]))
		{
			echo'<tr class="td">
			<td align="right">'.$Row_coor[$i][2].'</td>
			<td align="left">'.$Row_coor[$i][3].'</td>
			<td align="right">'.$Row_coor[$i][0].'</td>
			<td align="left">'.$Row_coor[$i][1].'</td>
			<!-- <td align="center"><a href="adm_actualiza_coor.php?codigo='.$Row_coor[$i][2].'">'.$b_edit.'</a></td>
			<td align="center"><a href="msql_borra_geclaves.php?codigo='.$Row_coor[$i][2].'&tipo=4">'.$b_deltbl.'</a></td> --> </tr>';
			$i++;
		}
echo'</table><br>';
?>
</body>
</html>