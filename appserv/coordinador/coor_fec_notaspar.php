<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once('../calendario/calendario.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
fu_cabezote("FECHAS DE NOTAS PARCIALES");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/BorraLink.js"></script>
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</head>
<body>

<?
$qry_cra = "SELECT cra_cod, cra_abrev, tra_nivel
	FROM accra, ACTIPCRA
	WHERE cra_emp_nro_iden = ".$_SESSION["usuario_login"]."
	AND cra_estado = 'A'
	AND cra_tip_cra=tra_cod
	ORDER BY cra_cod ASC";
$row_cra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cra,"busqueda");
//echo $row_cra[0][0];

if(!is_array($row_cra))
{
	echo "No existen Registros";
}

print '<form action="coor_fec_notaspar.php" method="post" name="LisCra" target="_self" id="LisCra">
   <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="tr"><td width="100%" align="center">Proyecto Curricular</td></tr>';
$i=0;
while(isset($row_cra[$i][0]))
{
	if($row_cra[$i][2]!='PREGRADO')
	{
		//if($i == 1)
		//{ 
			$c = $row_cra[$i][0];
			print '<tr><td align="center"><a href="coor_fec_notaspar.php?c='.$row_cra[$i][0].'">'.$row_cra[$i][1].'</a></td></tr>';
		//}
	}
	else
	{
		
			$c = $row_cra[$i][0];
			print '<tr><td align="center">'.$row_cra[$i][1].'</td></tr>';
		
	}
	$i++;
}
echo '</form>';
if(isset($_REQUEST['c']))
{
	$_SESSION['C'] = $_REQUEST['c'];
	require_once('coor_capfec_notaspar.php');
}
else
{       
        
	$_SESSION['C'] = $c;
	//require_once('coor_capfec_notaspar.php');
}
?>
</body>
</html>