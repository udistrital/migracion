<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'mensaje_error.inc.php');
require_once(dir_script."evnto_boton.php");
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);
fu_cabezote("CORRECCI&Oacute;N DE DOCUMENTO DE IDENTIDAD");
?>
<html>
<head>
<title>Correcci&oacute;n de documento</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/KeyIntro.js"></script>
<script language="JavaScript" src="../script/SigReg.js"></script>

</head>
<body onLoad="this.document.cRcc.doc_incorrecto.focus();">
<br><br><br><br><br><br><br>
<form name="cRcc" method="post" action="adm_corrige_cedula.php">
	<table width="450" border="0" align="center" cellpadding="3" cellspacing="0" class="fondoTab">
	<caption>CORRECCI&Oacute;N DEL DOCUMENTO DE IDENTIDAD DE LOS DOCENTES</caption>
		<tr>
			<td align="center" class="Estilo1">DOCUMENTO INCORRECTO</td>
			<td align="center" class="Estilo1">DOCUMENTO CORRECTO</td>
		</tr>
		<tr>
			<td align="center"><span class="Estilo4">*</span><input name="doc_incorrecto" type="text" id="doc_incorrecto" autocomplete="off" onkeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false; SigReg(event, 'cRcc','doc_correcto')"></td>
			<td align="center"><span class="Estilo4">*</span><input name="doc_correcto" type="text" id="doc_correcto" autocomplete="off" onkeypress="check_enter_key(event,document.getElementById('cRcc')); if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="Submit" value="Corregir Documento" class="button" <? print $evento_boton; ?>>
				<input name="uRl" type="hidden" value="1">
			<p><?php
			
			if(isset($_REQUEST['error_login']))
			{
				$error=$_REQUEST['error_login'];
				echo"<span class='Estilo1'>$error_login_ms[$error]</span>";
			}
			
			?></p></td>
		</tr>
	</table>
</form>
<?PHP

if(isset($_REQUEST['uRl']) && $_REQUEST['uRl']==1)
{
	if($_REQUEST['doc_incorrecto'] == "" || $_REQUEST['doc_correcto'] == "")
	{
		print'<center class="Estilo11">No hay registros para esta consulta.</center><br><br><br><br><br><br><br>';
		exit;
	}
	
	require_once(dir_script.'class_nombres.php');
	$nom = new Nombres;
	
	$codigo=$_REQUEST['doc_incorrecto'];
	$DNombre = $nom->rescataNombre($_REQUEST['doc_incorrecto'],'NombreDocente');
	
	if($DNombre == 'Sin nombre')
	{
		$NombreDoc = '<span class="Estilo4">'.$DNombre.'</span>';
		$botonSi = '<input type="submit" name="Submit" value=" Si " disabled>';
		$msj = 'No existe un docente con ese "<span class="Estilo5">documento incorrecto</span>"';
	}
	else
	{
		$botonSi = '<input type="submit" name="Submit" value=" Si " class="button" '.$evento_boton.' enabled>';
		$NombreDoc = '<b>'.$DNombre.'</b>';
		$msj='';
	}
		print'<br><br><br><table width="400" border="1" align="center" style="border-collapse:collapse">
	<caption>'.$msj.'</caption>
	<tr><td width="200" align="right" class="Estilo5">Nombre del Docente:<br>Documento actual:<br>Dcoumento a ingresar:</td>
	<td width="200" align="left">'.$NombreDoc.'<br>'.$_REQUEST['doc_incorrecto'].'<br>'.$_REQUEST['doc_correcto'].'</td></tr>
	<tr>
	<td colspan="2" align="center" class="Estilo2">EST&Aacute; SEGURO DE HACER EL CAMBIO.</span></td>
	</tr>
	<tr>
	<td width="200" align="center">
		<form name="formSi" method="post" action="prg_cambia_cedula.php">'.$botonSi.'
		<input name="CedInCor" type="hidden" value="'.$_REQUEST['doc_incorrecto'].'">
		<input name="CedCor" type="hidden" value="'.$_REQUEST['doc_correcto'].'">
	</td></form>
	<td width="200" align="center">
		<form name="formNo" method="post" action="adm_corrige_cedula.php">
	<input type="submit" name="Submit" value="No" class="button" '.$evento_boton.'>
	</td></form>
	</tr>
	</table>';
}
?> 
</body>
</html>