<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
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
$CraNom = $nom->NombreCarrera($_POST['CraCod']);
$Dpto = $nom->NombreDepartamento($_POST['DptoNac']);
$Mun = $nom->NombreMunicipio($_POST['CiudadNac']);
$Medio = $nom->MediPublicidad($_POST['MedPub']);
$LRes = $nom->NombreLocalidad($_POST['LocRes']);
$Estrato = $nom->NombreEstrato($_POST['StrRes']);
$LCol = $nom->NombreLocalidad($_POST['LocCol']);

if($_POST['Sexo']=='M'){ $PregEnvio = '¿ESTÁ SEGURO DE GRABAR ESTA INFORMACIÓN?'; $TSexo = 'MASCULINO'; }
if($_POST['Sexo']=='F'){ $PregEnvio = '¿ESTÁ SEGURA DE GRABAR ESTA INFORMACIÓN?'; $TSexo = 'FEMENINO'; }

if($_POST['SePresentaPor']==1) $NroVeces = 'PRIMERA VEZ';
if($_POST['SePresentaPor']==2) $NroVeces = 'SEGUNDA VEZ';
if($_POST['SePresentaPor']==3) $NroVeces = 'TERCERA O MÁS VECES';

if($_POST['TipoIns']==1) $TipoIns = 'NORMAL';
if($_POST['TipoIns']==21) $TipoIns = 'NEGRITUDES';
if($_POST['TipoIns']==4) $TipoIns = 'INDIGENAS';
if($_POST['TipoIns']==22) $TipoIns = 'DESPLAZADOS';
if($_POST['TipoIns']==23) $TipoIns = 'MEJOR BACHILLER DE COLEGIO OFICIAL EN BOGOTA';

if($_POST['EstCivil']==1 && $_POST['Sexo']=='M') $EstadoCivil = 'SOLTERO';
if($_POST['EstCivil']==1 && $_POST['Sexo']=='F') $EstadoCivil = 'SOLTERA';
if($_POST['EstCivil']==2 && $_POST['Sexo']=='M') $EstadoCivil = 'CASADO';
if($_POST['EstCivil']==2 && $_POST['Sexo']=='F') $EstadoCivil = 'CASADA';
if($_POST['EstCivil']==3) $EstadoCivil = 'OTRO';

if($_POST['TipDocAct']==1) $TDocAct = 'CÉDULA DE CIUDADANÍA';
if($_POST['TipDocAct']==2) $TDocAct = 'TARJETA DE IDENTIDAD';
if($_POST['TipDocAct']==3) $TDocAct = 'CÉDULA DE EXTRANJERÍA';

if($_POST['TipDocIcfes']==1) $TDocIcfes = 'CÉDULA DE CIUDADANÍA';
if($_POST['TipDocIcfes']==2) $TDocIcfes = 'TARJETA DE IDENTIDAD';
if($_POST['TipDocIcfes']==3) $TDocIcfes = 'CÉDULA DE EXTRANJERÍA';

if($_POST['TipoColegio']=='O') $TipoCol = 'OFICIAL';
if($_POST['TipoColegio']=='P') $TipoCol = 'PRIVADO';

$Ainsertar = dir_general.'prog_inserta_acasp.php';
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../general/asp_estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?
require_once(dir_general.'cabezote.php');

print'<p align="center" class="Estilo6">FORMULARIO DE INSCRIPCI&Oacute;N PARA INGRESO<br>'.$periodo.'</p>';
?>
<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <p align="center" class="error">Revise cuidadosamente la información consignada. Haga clic en el botón &quot;<strong>Si</strong>&quot; para grabar. Haga clic en el bot&oacute;n &quot;<strong>No</strong>&quot; para corregir.</p>
  <form name="datasp" method="post" action="<? print $Ainsertar;?>">
<table width="98%" border="1" align="center" cellpadding="2" cellspacing="3" style="border-collapse:collapse">
    <tr>
      <td width="486" align="right">Por que medio se enter&oacute; de la Universidad Distrital:</td>
      <td colspan="3">
	  <? print $_POST['MedPub'].' '.$Medio; ?>
	  <input name="MedPub" type="hidden" value="<? print $_POST['MedPub'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Se presenta a la Universidad por:</td>
      <td colspan="3"><? print $_POST['SePresentaPor'].' '.$NroVeces; ?>
	  <input name="SePresentaPor" type="hidden" value="<? print $_POST['SePresentaPor']; ?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Carrera en la que se inscribe:</td>
      <td colspan="3"><? print $_POST['CraCod'].' '.$CraNom; ?>
	  <input name="CraCod" type="hidden" value="<? print $_POST['CraCod'];?>"></td>
    </tr>
    <tr>
      <td align="right">Tipo de inscripci&oacute;n:</td>
      <td colspan="3"><? print $_POST['TipoIns'].' '.$TipoIns; ?>
	  <input name="TipoIns" type="hidden" value="<? print $_POST['TipoIns']; ?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td colspan="4" align="center" class="Estilo9">LUGAR Y FECHA DE NACIMIENTO</td>
    </tr>
    <tr>
      <td align="right">Pais:</td>
      <td colspan="3"><? print $_POST['PaisNac'] ?>
	  <input name="PaisNac" type="hidden" value="<? print $_POST['PaisNac'];?>"></td>
    </tr>
    <tr>
      <td align="right">Departamento:</td>
      <td colspan="3" align="left"><? print $_POST['DptoNac'].' '.$Dpto; ?>
	  <input name="DptoNac" type="hidden" value="<? print $_POST['DptoNac'];?>"></td>
    </tr>
    <tr>
      <td align="right">Municipio:</td>
      <td colspan="3">
	  <? print $_POST['CiudadNac'].' '.$Mun; ?>
	  <input name="CiudadNac" type="hidden" value="<? print $_POST['CiudadNac'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Fecha:</td>
      <td colspan="3"><? print $_POST['FechaNac']; ?>
	  <input name="FechaNac" type="hidden" value="<? print $_POST['FechaNac'];?>"></td>
    </tr>
    <tr>
      <td align="right">Sexo:</td>
      <td colspan="3"><? print $_POST['Sexo'].' '.$TSexo; ?>
	  <input name="Sexo" type="hidden" value="<? print $_POST['Sexo'];?>"></td>
    </tr>
    <tr>
      <td align="right">Estado civil:</td>
      <td colspan="3"><? print $_POST['EstCivil'].' '.$EstadoCivil; ?>
	  <input name="EstCivil" type="hidden" value="<? print $_POST['EstCivil'];?>"></td>
    </tr>
    <tr>
      <td align="right">Direcci&oacute;n:</td>
      <td colspan="3">
	  <? print $_POST['dir'] ?>
	  <input name="dir" type="hidden" value="<? print $_POST['dir'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Localidad de residencia:</td>
      <td colspan="3">
	  <? print $_POST['LocRes'].' '. $LRes; ?>
	  <input name="LocRes" type="hidden" value="<? print $_POST['LocRes']?>">
	  
	  </td>
    </tr>
    <tr>
      <td align="right">Estrato de residencia:</td>
      <td colspan="3">
	  <? print $Estrato; ?>
	  <input name="StrRes" type="hidden" value="<? print $_POST['StrRes'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Tel&eacute;fono:</td>
      <td colspan="3">
	  <? print $_POST['tel'] ?>
	  <input name="tel" type="hidden" value="<? print $_POST['tel'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Correo electr&oacute;nico: </td>
      <td colspan="3">
	  <? print $_POST['CtaCorreo'] ?>
	  <input name="CtaCorreo" type="hidden" value="<? print $_POST['CtaCorreo'];?>">
	  </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td colspan="4" align="center" class="Estilo9">DOCUMENTO DE IDENTIDAD</td>
    </tr>
    <tr>
      <td align="right">Documento actual: </td>
      <td width="119" align="left"><? print $_POST['DocActual']; ?>
	  <input name="DocActual" type="hidden" value="<? print $_POST['DocActual'];?>"></td>
      <td align="right">Tipo:</td>
      <td width="278"><? print $_POST['TipDocAct'].' '.$TDocAct; ?>
	  <input name="TipDocAct" type="hidden" value="<? print $_POST['TipDocAct'];?>"></td>
    </tr>
    <tr>
      <td align="right">Documento de identidad con el que present&oacute; el ICFES: </td>
      <td align="left"><? print $_POST['DocIcfes']; ?>
	  <input name="DocIcfes" type="hidden" value="<? print $_POST['DocIcfes'];?>"></td>
      <td align="right">Tipo:</td>
      <td><? print $_POST['TipDocIcfes'].' '.$TDocIcfes; ?>
	  <input name="TipDocIcfes" type="hidden" value="<? print $_POST['TipDocIcfes'];?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td colspan="4" align="center"><span class="Estilo9">REGISTRO ICFES</span></td>
    </tr>
    <tr>
      <td align="right">N&uacute;mero del registro del icfes:</td>
      <td colspan="3"><? print $_POST['TipoIcfes'].''.$_POST['NroIcfes']; ?>
        <input name="NroIcfes" type="hidden" value="<? print $_POST['TipoIcfes'].$_POST['NroIcfes'];?>"></td>
    </tr>
    <tr>
      <td align="right">Localidad del colegio donde culmin&oacute; el grado 11:</td>
      <td colspan="3" align="left">
	  <? print $_POST['LocCol'].' '.$LCol ?>
	  <input name="LocCol" type="hidden" value="<? print $_POST['LocCol'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Observaciones:</td>
      <td colspan="3" align="left">
	  <? print $_POST['obs']; ?>
        <input name="obs" type="hidden" value="<? print $_POST['obs'];?>">
		<input name="est" type="hidden" value="A">
	  </td>
    </tr>
  </table>
  <p align="justify">Los datos consignados en el formulario serán guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la información equivale a la firma de la inscripción.</p>
  
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
<? 
fu_pie();
?>
</body>
</html>