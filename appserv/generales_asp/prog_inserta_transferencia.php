<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');

require_once(dir_general.'msql_ano_per.php');

require_once(dir_general.'valida_usuario_prog.php');
require_once(dir_general.'valida_http_referer.php');
require_once(dir_general.'valida_formulario_formulario.php');
require_once(dir_general.'valida_inscripcion.php');

require_once(dir_general.'msql_credencial.php');

$est = 'A';
$TPrint = dir_general.'imprime_colilla_transferencia.php';

	$insAcasp="INSERT INTO mntac.acasptransferencia(ATR_APE_ANO,";
	$insAcasp.="ATR_APE_PER,ATR_CRED,ATR_CRA_COD,ATR_TI_COD,ATR_UNIVERSIDAD_PROVIENE,ATR_CARRERA_PROVIENE,";
	$insAcasp.="ATR_SEMESTRE,ATR_MOTIVO,ATR_NACIONALIDAD,ATR_DEP_COD_NAC,ATR_MUN_COD_NAC,ATR_FECHA_NAC,";
	$insAcasp.="ATR_SEXO,ATR_ESTADO_CIVIL,ATR_EMAIL,ATR_NRO_IDEN_ACT,ATR_NRO_TIP_ACT,ATR_NRO_IDEN_ICFES,";
	$insAcasp.="ATR_NRO_TIP_ICFES,ATR_SNP,ATR_ESTRATO,ATR_DIRECCION,ATR_LOCALIDAD,ATR_TELEFONO,";
	$insAcasp.="ATR_LOCALIDAD_COLEGIO,ATR_OBSERVACION,ATR_ESTADO) ";
	$insAcasp.="VALUES(".$ano.","; 
	$insAcasp.="".$per.", ";
	$insAcasp.="".$cred.",";
	$insAcasp.="".$_POST['CraCodT'].",";
	$insAcasp.="".$_POST['TiCod'].",";
	$insAcasp.="to_char('".$_POST['UdPro']."'),";
	$insAcasp.="to_char('".$_POST['CraCur']."'),";
	$insAcasp.="'".$_POST['LastSem']."' ,";
	$insAcasp.="to_char('".$_POST['motivo']."'),";
	$insAcasp.="to_char('".$_POST['PaisNac']."'),"; 
	$insAcasp.="".$_POST['DptoNac'].","; 
	$insAcasp.="".$_POST['CiudadNac'].",";
	$insAcasp.="to_date('".$_POST['FechaNac']."','dd/mm/yyyy'),";
	$insAcasp.="to_char('".$_POST['Sexo']."'),"; 
	$insAcasp.="".$_POST['EstCivil'].",";
	$insAcasp.="to_char('".$_POST['CtaCorreo']."'),"; 
	$insAcasp.="".$_POST['DocActual'].","; 
	$insAcasp.="".$_POST['TipDocAct'].","; 
	$insAcasp.="".$_POST['DocIcfes'].", ";
	$insAcasp.="".$_POST['TipDocIcfes'].",";
	$insAcasp.="to_char('".$_POST['NroIcfes']."'),";
	$insAcasp.="".$_POST['StrRes'].",";
	$insAcasp.="to_char('".$_POST['dir']."'),";
	$insAcasp.="to_char('".$_POST['LocRes']."'),";
	$insAcasp.="to_char('".$_POST['tel']."'), ";
	$insAcasp.="to_char('".$_POST['LocCol']."'), "; 
	$insAcasp.="to_char('".$_POST['obs']."'),"; 
	$insAcasp.="to_char('".$est."'))";
	//echo $insAcasp;

	$inserta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$insAcasp,"");
	
$afectados=$conexion->totalAfectados($configuracion,$accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;

if($afectados >= 1)
{
	header("Location: $TPrint");
}
else
{
	echo "La informaci&oacute;n suministrada NO pudo ser guardada en el sistema, revise que los datos suministrados en el formulario est&eacute;n correctos y vuelva a intentarlo.";
}
?>
