<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once('msql_ano_per.php');
require_once('valida_usuario_prog.php');
require_once('valida_http_referer.php');
require_once('valida_formulario_formulario.php');
require_once('valida_inscripcion.php');
require_once('msql_credencial.php');

$est='A';
$APrint=dir_general.'imprime_colilla_acasp.php';
$insAcasp="INSERT INTO mntac.acaspw(ASP_APE_ANO,"; 
$insAcasp.="ASP_APE_PER,";  
$insAcasp.="ASP_CRED, "; 
$insAcasp.="ASP_MED_COD, "; 
$insAcasp.="ASP_VECES,";  
$insAcasp.="ASP_CRA_COD,"; 
$insAcasp.="ASP_TI_COD,";  
$insAcasp.="ASP_NACIONALIDAD,"; 
$insAcasp.="ASP_DEP_COD_NAC,"; 
$insAcasp.="ASP_MUN_COD_NAC,"; 
$insAcasp.="ASP_FECHA_NAC,"; 
$insAcasp.="ASP_SEXO,"; 
$insAcasp.="ASP_ESTADO_CIVIL, "; 
$insAcasp.="ASP_EMAIL,"; 
$insAcasp.="ASP_NRO_IDEN_ACT, "; 
$insAcasp.="ASP_NRO_TIP_ACT,";  
$insAcasp.="ASP_NRO_IDEN_ICFES,";  
$insAcasp.="ASP_NRO_TIP_ICFES,"; 
$insAcasp.="ASP_SNP,"; 
$insAcasp.="ASP_ESTRATO,";
$insAcasp.="ASP_ESTRATO_COSTEA,";  
$insAcasp.="ASP_DIRECCION,";  
$insAcasp.="ASP_LOCALIDAD,";
$insAcasp.="ASP_TELEFONO,";  
$insAcasp.="ASP_LOCALIDAD_COLEGIO,";
$insAcasp.="ASP_TIPO_COLEGIO,";
$insAcasp.="ASP_TIPO_DISCAP,";
$insAcasp.="ASP_VALIDA_BTO,"; 
$insAcasp.="ASP_OBSERVACION,";
$insAcasp.="ASP_SEM_TRANSCURRIDOS,";
$insAcasp.="ASP_ESTADO)"; 
$insAcasp.="VALUES(".$ano.",";  
$insAcasp.="".$per.",";  
$insAcasp.="".$cred.","; 
$insAcasp.="".$_POST['MedPub'].",";  
$insAcasp.="".$_POST['SePresentaPor'].",";  
$insAcasp.="".$_POST['CraCod'].", "; 
$insAcasp.="".$_POST['TipoIns'].",";  
$insAcasp.="'".$_POST['PaisNac']."',";  
$insAcasp.="".$_POST['DptoNac'].",";  
$insAcasp.="".$_POST['CiudadNac'].",";  
$insAcasp.="to_date('".$_POST['FechaNac']."','dd/mm/yyyy'),"; 
$insAcasp.="'".$_POST['Sexo']."',";  
$insAcasp.="".$_POST['EstCivil'].",";  
$insAcasp.="'".$_POST['CtaCorreo']."',";  
$insAcasp.="".$_POST['DocActual'].",";  
$insAcasp.="".$_POST['TipDocAct'].",";  
$insAcasp.="".$_POST['DocIcfes'].", "; 
$insAcasp.="".$_POST['TipDocIcfes'].","; 
$insAcasp.="'".$_POST['NroIcfes']."',"; 
$insAcasp.="".$_POST['StrRes'].",";
$insAcasp.="".$_POST['StrResCost'].",";
$insAcasp.="'".$_POST['dir']."',";  
$insAcasp.="'".$_POST['LocRes']."',";
$insAcasp.="'".$_POST['tel']."',";  
$insAcasp.="'".$_POST['LocCol']."',";
$insAcasp.="'".$_POST['TipCol']."',";
$insAcasp.="'".$_POST['TipDis']."',";
$insAcasp.="'".$_POST['valido']."',";
$insAcasp.="'".$_POST['obs']."',";
$insAcasp.="'".$_POST['semestresTranscurridos']."',";
$insAcasp.="'".$est."')";

$inserta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$insAcasp,"");
//echo $insAcasp; exit;
$afectados=$conexion->totalAfectados($configuracion,$accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;
	
if($afectados >= 1)
{
	header("Location: $APrint");
}
else
{
	echo "La informaci&oacute;n suministrada NO pudo ser guardada en el sistema, revise que los datos suministrados en el formulario est&eacute;n correctos y vuelva a intentarlo.";
}

?>
