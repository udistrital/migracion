<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'msql_ano_per.php');
require_once('class_TotalPorActividad.php');
require_once('msql_doc_cuenta_act_pt.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(30);

require_once('valida_fecha_pt.php');

$usuario = $_SESSION['usuario_login'];

$url = explode("?",$_SERVER['HTTP_REFERER']);
$redir = $url[0];

if($_REQUEST['act']=="" || $_REQUEST['dia']=="" || $_REQUEST['hor']=="" || $_REQUEST['sed']=="" || $_REQUEST['sal']=="" || $_REQUEST['tipvin']==""){
   echo "<script>location.replace('$redir?error_login=29')</script>";
   exit;
}

//VALIDA PREPARACI�N DE CLASE
//$NroLec=Numero de horas de carga academica
//$PreClas=  Numero de horas registradas de preparacion de clase

$j=0;
if((int)$NroLec[(int)$_REQUEST['tipvin']] > 0 && $_REQUEST['act'] == 2 && $_REQUEST['tipvin'] <> ""){  //2 = preparacion de clase

   
  $TotAct = new TotalPorActividad;
  $PreClas = $TotAct->CuentaActividad($usuario, $_REQUEST['act'],$_REQUEST['tipvin']);
  $MitHorLec = $NroLec[(int)$_REQUEST['tipvin']]/2;

   if((int)$PreClas >= (int)$MitHorLec){
	echo "<script>location.replace('../err/err_prc.php')</script>";
	exit;

   }
}

//VALIDA INTENSIDAD DE LA ACTIVIDAD
$QryIntActividad = "SELECT DAC_INTENSIDAD FROM acdocactividad WHERE DAC_COD = '".$_REQUEST['act']."'";
$RowIntActividad=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryIntActividad,"busqueda");
$IntAct = $RowIntActividad[0][0];

$QryIntensidad="SELECT ";
$QryIntensidad.="COUNT(DPT_HORA) ";
$QryIntensidad.="FROM ";
$QryIntensidad.="acdocplantrabajo ";
$QryIntensidad.="WHERE ";
$QryIntensidad.="DPT_APE_ANO = $ano ";
$QryIntensidad.="AND ";
$QryIntensidad.="DPT_APE_PER = $per ";
$QryIntensidad.="AND ";
$QryIntensidad.="DPT_DOC_NRO_IDEN = $usuario ";
$QryIntensidad.="AND ";
$QryIntensidad.="DPT_DAC_COD ='".$_REQUEST['act']."' ";
$QryIntensidad.="AND ";
$QryIntensidad.="AND DPT_ESTADO = 'A' ";
$QryIntensidad.="AND ";
$QryIntensidad.="DPT_TVI_COD ='".$_REQUEST['tipvin']."'";

$RowIntensidad = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryIntensidad,"busqueda");
$TotHorAct = $RowIntensidad[0][0];

// $IntAct= Corresponde al numero de horas que se pueden registrar de cada actividad
// $TotHorAct = Numero de horas que el docente a registrado de la correspondiente actividad

if(($TotHorAct > 0) && ($IntAct == $TotHorAct)){ 
	echo "<script>location.replace('../err/err_".$IntAct."h.php')</script>";
	exit;
 }

//VALIDAR CRUCE CON CARGA LECTIVA
$QryCruCarLec="SELECT ";
$QryCruCarLec.="'S' ";
$QryCruCarLec.="FROM "; 
$QryCruCarLec.="v_accargalectiva ";
$QryCruCarLec.="WHERE ";
$QryCruCarLec.="cle_doc_nro_iden = $usuario ";
$QryCruCarLec.="AND ";
$QryCruCarLec.="cle_dia_nro ='".$_REQUEST['dia']."' "; 
$QryCruCarLec.="AND ";
$QryCruCarLec.="cle_hora ='".$_REQUEST['hor']."'";

$RowCruCarLec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCruCarLec,"busqueda");
$CruceCL = $RowCruCarLec[0][0];

if($CruceCL=="S"){ 
	echo "<script>location.replace('../err/err_crucarlec.php')</script>";
	exit;
 }

//VALIDA CRUCE DE LA ACTIVIDAD

$QryCruce = "SELECT ";
$QryCruce.="'S' ";
$QryCruce.="FROM ";
$QryCruce.="acdocplantrabajo ";
$QryCruce.="WHERE ";
$QryCruce.="DPT_APE_ANO = $ano ";
$QryCruce.="AND ";
$QryCruce.="DPT_APE_PER = $per ";
$QryCruce.="AND ";
$QryCruce.="DPT_DOC_NRO_IDEN = $usuario ";
$QryCruce.="AND ";
$QryCruce.="DPT_DIA_NRO ='".$_REQUEST['dia']."' ";
$QryCruce.="AND ";
$QryCruce.="DPT_HORA ='".$_REQUEST['hor']."'";

$RowFec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCruce,"busqueda");
$Cruce = $RowFec[0][0];

if($Cruce=="S"){ 
	echo "<script>location.replace('../err/err_cruactpt.php')</script>";
	exit; 
}

$QryFec = "SELECT sysdate FROM dual";

$RowFec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFec,"busqueda");
$fec = $RowFec[0][0];

$ins="INSERT INTO ";
$ins.="acdocplantrabajo ";
$ins.="(";
$ins.="DPT_APE_ANO, ";
$ins.="DPT_APE_PER, ";
$ins.="DPT_DOC_NRO_IDEN, ";
$ins.="DPT_DAC_COD, ";
$ins.="DPT_DIA_NRO, ";
$ins.="DPT_HORA, ";
$ins.="DPT_SED_COD, ";
$ins.="DPT_SAL_COD, ";
$ins.="DPT_FECHA, ";
$ins.="DPT_ESTADO, ";
$ins.="DPT_TVI_COD ";
$ins.=") ";
$ins.="VALUES ";
$ins.="( ";
$ins.=$ano.", ";
$ins.=$per.", ";
$ins.=$usuario.", ";
$ins.=$_REQUEST['act'].", ";
$ins.=$_REQUEST['dia'].", ";
$ins.=$_REQUEST['hor'].", ";
$ins.=$_REQUEST['sed'].", ";
$ins.=$_REQUEST['sal'].", ";
$ins.="'".$fec."', ";
$ins.="'A', ";
$ins.=$_REQUEST['tipvin']."";
$ins.=")";

$resultado=$conexion->ejecutarSQL($configuracion,$accesoOracle,$ins,"busqueda");

if(isset($resultado)){
echo "<script>location.replace('doc_adm_pt.php')</script>";
}
else
{	
echo "<script>location.replace('$redir?error_login=21')</script>";
}
?>