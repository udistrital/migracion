<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('../calendario/calendario.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


if(!$_REQUEST['tipo']){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}elseif($_REQUEST['tipo']==4){
    fu_tipo_user(4);
    $tipo=4; 
}
?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../calendario/javascripts.js"></script>
</head>
<body>
<?php
fu_cabezote("ADMINISTRACI&Oacute;N DE NOTICIAS");

global $raiz;
$nombreformulario = "dat";
$nombrecampo1 = "cmefecini";
$nombrecampo2 = "cmefecfin";

$b_edit ='<IMG SRC='.dir_img.'b_edit.png alt="Editar Noticia" border="0">';
$b_insrow ='<IMG SRC='.dir_img.'b_insrow.png alt="Publicar Nueva Noticia" border="0">';
$b_browse='<IMG SRC='.dir_img.'b_browse.png alt="Consultar Noticias Publicadas" border="0">';
$b_deltbl='<IMG SRC='.dir_img.'b_deltbl.png alt="Borrar Noticia" border="0">';
$b_home='<IMG SRC='.dir_img.'b_home.png alt="Administraci&oacute;n de Noticias" border="0">';

//Edita la noticia de acuerdo al parametro codigo
if($_REQUEST['edit']){
   $qry_msg = "SELECT CME_CODIGO, 
		CME_CRA_COD, 
		CME_AUTOR, 
		CME_TITULO, 
		CME_FECHA_INI,
		CME_FECHA_FIN,
		CME_MENSAJE
		FROM accoormensaje
		WHERE CME_CODIGO = ".$_REQUEST['edit']."
		AND CME_CRA_COD =".$_REQUEST['cracod'];
   $rows_msg = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_msg,"busqueda");

   if(!is_array($rows_msg))
   {
   	echo "<script>location.replace('../err/err_sin_registros.php')</script>";
   	exit;
   }
   $i=0;
	while(isset($rows_msg[$i][0]))
	{
		echo'<p></p><form name="dat" method="post" action="prog_update_msg.php">
		<table width="100%" border="1" align="center" cellspacing="0" cellpadding="0">
		<tr class="tr"><td width="604" align="center" colspan="2">Actualizaci&oacute;n de Noticias</td></tr>
		<tr><td width="604" align="right">Id:</td>
		<td width="547"><input name="cmecod" type="text" id="cmecod" value="'.$rows_msg[$i][0].'" size="71" readonly></td></tr>
		<tr><td width="604" align="right">Autor:</td>
		<td width="547"><input name="cmeautor" type="text" id="cmeautor" value="'.$rows_msg[$i][2].'" size="71" maxlength="50"><input name="cmecracod" type="hidden" id="cmecracod" value="'.$rows_msg[$i][1].'" size="5" readonly></td></tr>
		<tr><td width="604" align="right">Fecha Inicial:</td>
		<td width="547"><input name="cmefecini" type="text" id="cmefecini" value="'.$rows_msg[$i][4].'" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo1.'\')" readonly>DD/MM/AAAA</td></tr>
		<tr><td width="604" align="right">Fecha Final:</td>
		<td width="547"><input name="cmefecfin" type="text" id="cmefecfin" value="'.$rows_msg[$i][5].'" size="12" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo2.'\')" readonly>DD/MM/AAAA</td></tr>   
		<tr><td width="604" align="right">T&iacute;tulo:</td>
		<td width="547"><input name="cmetitulo" type="text" id="cmetitulo" value="'.$rows_msg[$i][3].'" size="71" maxlength="50"></td></tr>
		<tr><td width="604" align="center">&nbsp;</td>
		<td width="547">
		<textarea name="cmemsg" cols="85" rows="12" id="cmemsg">'.$rows_msg[$i][6].'</textarea>
		<br></td>
		</tr>
		<tr>
		<td colspan="2" align="center">
		<table width="604" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td width="200" align="center"><a href="coor_admin_msg.php">'.$b_home.'</a>
		</td>
		<td width="201" align="center"><input type=submit name="update" value="Actualizar Noticia"></td>
		<td width="201" align="center"><a href="coor_index_msg.php">'.$b_browse.'</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table></form>';
	$i++;
	}
}

// Principal
if(empty($_REQIEST['update']) && empty($_REQUEST['del']) && empty($_REQUEST['edit']))
{
   require_once('msql_consulta_msg.php');
   $rows_msg = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_msg,"busqueda");
   //if($rows_msg != 1) header("Location: ../err/err_sin_registros.php");
   echo'<p>&nbsp;</p>
   <table width="100%" border="1" align="center" cellspacing="0" cellPadding="2">';
    if(!is_array($rows_msg))
    {
	print'<tr>
        <td align="center" width="796" colspan="9"><span class="Estilo10">No hay registros para esta consulta.</span></td>
	</tr>';
    }
   echo'<tr class="tr">
        <td align="center" width="20">id</td>
	<td align="center" width="305">Proyecto Curricular</td>
	<td align="center" width="81">Noticia Para</td>
        <td align="center" width="81">Fecha</td>
        <td align="center" width="246">T&iacute;tulo del Mensaje</td>
        <td align="center" width="117" colspan="4">Acci&oacute;n</td></tr>';
        $i=0;
	while(isset($rows_msg[$i][0]))
	{
		if($rows_msg[$i][9] == 51) $notipara = 'Estudiantes';
		echo'<tr><td align="center" width="20">'.$rows_msg[$i][0].'</td>
		<td align="left" width="305">'.$rows_msg[$i][2].'</td>
		<td align="center" width="81">'.$notipara.'</td>
		<td align="center" width="81">'.$rows_msg[$i][5].'</td>
		<td align="left" width="246">'.$rows_msg[$i][4].'</td>
		<td align="center" width="25"><a href="coor_admin_msg.php?edit='.$rows_msg[$i][0].'&cracod='.$rows_msg[$i][1].'">'.$b_edit.'</a></td>
		<td align="center" width="25"><a href="prog_borra_msg.php?del='.$rows_msg[$i][0].'&cracod='.$rows_msg[$i][1].'">'.$b_deltbl.'</a></td>
		<td align="center" width="25"><a href="coor_forma_msg.php">'.$b_insrow.'</a></td>
		<td align="center" width="25"><a href="coor_index_msg.php">'.$b_browse.'</a></td></tr>';
	$i++;
	}
echo'</table>';
}
?>
</table>
</div>
</body>
</html>