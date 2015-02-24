<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_general.'msql_ano_per.php');
require_once(dir_general.'asp_pie_pagAdm.php');
include_once(dir_general.'class_nombres.php');
require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion.php');

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($per==1) $peri ='PRIMER';
if($per==3) $peri ='SEGUNDO';

$nom = new Nombres;
$CraNom = $nom->rescataNombre($_POST['CraCod'],"NombreCarrera");
$TCraNom = $nom->rescataNombre($_POST['TCraCod'],"NombreCarrera");
$TipoIns = $nom->rescataNombre($_POST['TipoIns'],"NombreTipoIns");

if($_POST['CanSem'] == 'S') $CSem = 'SI';
if($_POST['CanSem'] == 'N') $CSem = 'NO';

$PregEnvio = '&iquest;EST&Aacute; SEGURO(A) DE GRABAR ESTA INFORMACI&Oacute;N?';

if($_POST['TipoIns'] == 25) {
   $Transfiere = '<tr><td align="right">Carrera que ven&iacute;a cursando:</td>
   <td>'.$_POST['CraCod'].' '.$CraNom.'<input name="CraCod" type="hidden" value="'.$_POST['CraCod'].'"></td></tr>
   <tr><td align="right">Carrera a la que se transfiere:</td>
   <td align="left">'.$_POST['TCraCod'].' '.$TCraNom.'<input name="TCraCod" type="hidden" value="'.$_POST['TCraCod'].'"></td></tr>';
}

$RInsertar = dir_general.'prog_inserta_reingreso.php';
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?

	print'<p align="center" class="Estilo6">FORMULARIO DE REINGRESO O TRANSFERENCIA INTERNA<br>'.$periodo.'</p>';
	?>
	<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
	<tr><td>
	<fieldset style="padding:10">
	<p align="center" class="error">Revise cuidadosamente la informaci&oacute;n consignada. Haga clic en el bot&oacute;n &quot;<strong>Si</strong>&quot; para grabar. Haga clic en el bot&oacute;n &quot;<strong>No</strong>&quot; para corregir.</p>
	<form name="datasp" method="post" action="<? print $RInsertar; ?>">
	<table width="98%" border="1" align="center" cellpadding="2" cellspacing="3" style="border-collapse:collapse">
	    <tr>
	      <td width="50%" align="right"><B>Seleccione el tipo de inscripci&oacute;n:</B></td>
	      <td width="50%">
		  <? print $_POST['TipoIns'].' '.$TipoIns; ?>
		  <input name="TipoIns" type="hidden" value="<? print $_POST['TipoIns'];?>">
		  </td>
	    </tr>
	    <tr>
	      <td align="right">Documento de identidad:</td>
	      <td><? print $_POST['DocActual']; ?>
		  <input name="DocActual" NombreEstudiantetype="hidden" value="<? print $_POST['DocActual']; ?>">
		  </td>
	    </tr>
	    <tr>
	      <td align="right">C&oacute;digo de estudiante en la Universidad Distrital:</td>
	      <td><? print $_POST['EstCod']; ?>
		  <input name="EstCod" type="hidden" value="<? print $_POST['EstCod'];?>"></td>
	    </tr>
	    <tr>
	      <td align="right">Cancel&oacute; semestre:</td>
	      <td><? print $CSem; ?>
		  <input name="CanSem" type="hidden" value="<? print $_POST['CanSem']; ?>"></td>
	    </tr>
	    <tr>
	      <td align="right">Motivo del retiro: </td>
	      <td>
		  <? print $_POST['MotRetiro']; ?>
		  <input name="MotRetiro" type="hidden" value="<? print $_POST['MotRetiro']; ?>">
		  </td>
	    </tr>
	    <tr>
	      <td align="right">Tel&eacute;fono:</td>
	      <td colspan="3" align="left">
		  <? print $_POST['tel']; ?>
		  <input name="tel" type="hidden" value="<? print $_POST['tel']; ?>">
		  </td>
	    </tr>
	    <tr>
	      <td align="right">Correo electr&oacute;nico:</td>
	      <td>
		  <? print $_POST['CtaCorreo']; ?>
		  <input name="CtaCorreo" type="hidden" value="<? print $_POST['CtaCorreo']; ?>">
		  </td>
	    </tr>
	    <? print $Transfiere; ?>
	  </table>
	  <p align="justify">Los datos consignados en el formulario ser&aacute;n guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la informaci&oacute;n equivale a la firma de la inscripci&oacute;n.</p>
	  
	  <p align="center">
	    <span class="error"><? print $PregEnvio;?></span><BR>    
	    <input type="submit" value="Si" style="width:80;cursor:pointer">&nbsp;<input type="button" value="No" onClick='history.go(-1)' style="width:80;cursor:pointer">
	  </p>
	</form>
	</fieldset>
	</td></tr>
	</table>
	</fieldset>
	<p></p>

</body>
</html>
