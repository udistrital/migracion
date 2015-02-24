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
$RPrint='imprime_colilla_reingreso.php';

/*echo "<pre>";
var_dump($_POST);
echo "</pre><br>";*/


	$insAcasp="INSERT INTO mntac.acaspreingreso(ARE_APE_ANO,";
	$insAcasp.="ARE_APE_PER,";
	$insAcasp.="ARE_CRED,"; 
	$insAcasp.="ARE_TI_COD,";
	$insAcasp.="ARE_NRO_IDEN,"; 
	$insAcasp.="ARE_EST_COD,"; 
	$insAcasp.="ARE_CANCELO_SEM,"; 
	$insAcasp.="ARE_MOTIVO_RETIRO,"; 
	$insAcasp.="ARE_TELEFONO,"; 
	$insAcasp.="ARE_EMAIL,"; 
	$insAcasp.="ARE_CRA_CURSANDO,"; 
	$insAcasp.="ARE_CRA_TRANSFERENCIA,"; 
	$insAcasp.="ARE_ESTADO) ";
	$insAcasp.="VALUES(".$ano.","; 
	$insAcasp.=$per.","; 
	$insAcasp.=$cred.",";
	$insAcasp.=$_POST['TipoIns'].",";
	$insAcasp.="'".$_POST['DocActual']."',";
	$insAcasp.="'".$_POST['EstCod']."',";
	$insAcasp.="'".$_POST['CanSem']."',";
	$insAcasp.="'".$_POST['MotRetiro']."',";
	$insAcasp.="'".$_POST['tel']."',";
	$insAcasp.="'".$_POST['CtaCorreo']."',";
	$insAcasp.="'".$_POST['CraCod']."',";
	$insAcasp.="'".$_POST['TCraCod']."',";
	$insAcasp.="'".$est."')";

$inserta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$insAcasp,"");
$afectados=$conexion->totalAfectados($configuracion,$accesoOracle); //Esta linea es para verificar si se guardaron los registros en la base de datos;
	
if($afectados >= 1)
{
	header("Location: $RPrint");
}
else
{
	echo "La informaci&oacute;n suministrada NO pudo ser guardada en el sistema, revise que los datos suministrados en el formulario est&eacute;n correctos y vuelva a intentarlo.";
}

?>
