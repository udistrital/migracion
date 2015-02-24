<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_general.'msql_ano_per.php');
include_once(dir_general.'class_nombres.php');
require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_inscripcion.php');

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($per==1) $peri ='PRIMER';
if($per==3) $peri ='SEGUNDO';

$nom = new Nombres;
$CraNom = $nom->rescataNombre($_POST['CraCodT'],"NombreCarrera");
$Dpto = $nom->rescataNombre($_POST['DptoNac'],"NombreDepartamento");
$Mun = $nom->rescataNombre($_POST['CiudadNac'],"NombreMunicipio");
$Medio = $nom->rescataNombre($_POST['MedPub'],"MediPublicidad");
$LRes = $nom->rescataNombre($_POST['LocRes'],"NombreLocalidad");
$Estrato = $nom->rescataNombre($_POST['StrRes'],"NombreEstrato");
$LCol = $nom->rescataNombre($_POST['LocCol'],"NombreLocalidad");
$TiCodigo = $nom->rescataNombre($_POST['TiCod'],"NombreTipoIns");




if($_POST['Sexo']=='M'){ $PregEnvio = '¿EST&Aacute; SEGURO DE GRABAR ESTA INFORMACI&Oacute;N?'; $TSexo = 'MASCULINO'; }
if($_POST['Sexo']=='F'){ $PregEnvio = '¿EST&Aacute; SEGURA DE GRABAR ESTA INFORMACI&Oacute;N?'; $TSexo = 'FEMENINO'; }

if($_POST['SePresentaPor']==1) $NroVeces = 'PRIMERA VEZ';
if($_POST['SePresentaPor']==2) $NroVeces = 'SEGUNDA VEZ';
if($_POST['SePresentaPor']==3) $NroVeces = 'TERCERA O M&Aacute;S VECES';

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

if($_POST['TipDocAct']==1) $TDocAct = 'C&Eacute;DULA DE CIUDADAN&Iacute;A';
if($_POST['TipDocAct']==2) $TDocAct = 'TARJETA DE IDENTIDAD';
if($_POST['TipDocAct']==3) $TDocAct = 'C&Eacute;DULA DE EXTRANJER&Iacute;A';

if($_POST['TipDocIcfes']==1) $TDocIcfes = 'C&Eacute;DULA DE CIUDADAN&Iacute;A';
if($_POST['TipDocIcfes']==2) $TDocIcfes = 'TARJETA DE IDENTIDAD';
if($_POST['TipDocIcfes']==3) $TDocIcfes = 'C&Eacute;DULA DE EXTRANJER&Iacute;A';

if($_POST['TipoColegio']=='O') $TipoCol = 'OFICIAL';
if($_POST['TipoColegio']=='P') $TipoCol = 'PRIVADO';

$TInsertar = dir_general.'prog_inserta_transferencia.php';
?>
<html>
<head>
<title>Aspirantes</title>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<?



print'<p align="center" class="Estilo6">FORMULARIO DE TRANSFERENCIA EXTERNA<br>'.$periodo.'</p>';
?>
<table width="99%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr><td>
  <fieldset style="padding:10">
  <p align="center" class="error">Revise cuidadosamente la informaci&oacute;n consignada. Haga clic en el bot&oacute;n &quot;<strong>Si</strong>&quot; para grabar. Haga clic en el bot&oacute;n &quot;<strong>No</strong>&quot; para corregir.</p>
  <form name="datasp" method="post" action="<? print $TInsertar; ?>">
<table width="98%" border="1" align="center" cellpadding="2" cellspacing="3" style="border-collapse:collapse">
    <tr>
      <td width="486" align="right">Carrera a la que se transfiere:</td>
      <td colspan="3">
	  <? print $_POST['CraCodT'].' '.$CraNom; ?>
	  <input name="CraCodT" type="hidden" value="<? print $_POST['CraCodT'];?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Tipo de inscripci&oacute;n: </td>
      <td colspan="3"><? print $TiCodigo; ?>
        <input name="TiCod" type="hidden" value="<? print $_POST['TiCod'];?>"></td>
    </tr>
    <tr>
      <td align="right">Universidad de donde viene:</td>
      <td colspan="3"><? print $_POST['UdPro']; ?>
	  <input name="UdPro" type="hidden" value="<? print $_POST['UdPro']; ?>">
	  </td>
    </tr>
    <tr>
      <td align="right">Carrera que venia cursando:</td>
      <td colspan="3"><? print $_POST['CraCur']; ?>
	  <input name="CraCur" type="hidden" value="<? print $_POST['CraCur'];?>"></td>
    </tr>
    <tr>
      <td align="right">&Uacute;ltimo semestre cursado:</td>
      <td colspan="3"><? print $_POST['LastSem'].' '.$LastSem; ?>
	  <input name="LastSem" type="hidden" value="<? print $_POST['LastSem']; ?>"></td>
    </tr>
    <tr>
      <td align="right">Motivo de la transferencia:</td>
      <td colspan="3">
	  <? print $_POST['motivo']; ?>
	  <input name="motivo" type="hidden" value="<? print $_POST['motivo']; ?>">
	  </td>
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
	  </td>
    </tr>
  </table>
      <p align="justify">Los datos consignados en el formulario ser&aacute;n guardados bajo la gravedad del juramento, y en el momento de grabar y enviar la informaci&oacute;n equivale a la firma de la inscripci&oacute;n.</p>
  
  <p align="center">
    <span class="error">
	<? print $PregEnvio;?></span><BR>    
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
