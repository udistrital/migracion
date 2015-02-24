<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once('valida_http_referer.php');
require_once(dir_conect.'cierra_bd.php');
require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiante_nuevo.php');
require_once(dir_script.'msql_ano_per.php');
include_once(dir_script.'class_nombres.php');
require_once(dir_conect.'fu_tipo_user.php');

fu_tipo_user(51);
$redir = 'est_msg_error.php';

$estcod = $_SESSION['usuario_login'];
$estcra = $_SESSION['carrera'];

$nom = new Nombres;
$nombre = $nom->NombreEstudiante($estcod);

$confec = OCIParse($oci_conecta, "SELECT SYSDATE FROM dual");
OCIExecute($confec) or die(Ora_ErrorCode());
$rows = OCIFetch($confec);
$fecha = OCIResult($confec, 1);
$estado = 'A';
$transaccion = 'CG';
OCIFreeCursor($confec);

$conhor = OCIParse($oci_conecta, "SELECT to_char(SYSDATE,'hh24:mi:ss') FROM dual");
OCIExecute($conhor) or die(Ora_ErrorCode());
$rows = OCIFetch($conhor);
$hora = OCIResult($conhor, 1);
OCIFreeCursor($conhor);

//Valida cambio de grupo
$QryAsiIns = OCIParse($oci_conecta, "SELECT 'S'
									   FROM acins,acasperi
									  WHERE ape_ano = ins_ano
									    AND ape_per = ins_per
									    AND ape_estado = 'A'
									    AND ins_est_cod = ".$_SESSION['usuario_login']."
									    AND ins_asi_cod = ".$_POST['asicod']);
OCIExecute($QryAsiIns) or die(ora_errorcode());
$RowAsiIns = OCIFetch($QryAsiIns);

$QryGrupo = OCIParse($oci_conecta, "SELECT 'S'
									  FROM v_acestmathorario
									 WHERE EMH_ASI_COD = ".$_POST['asicod']."
									   AND EMH_NRO = ".$_POST['asigru_nue']."
									   AND EHM_CRA_COD = ".$_SESSION['carrera']."
									   AND EMH_CUPO > 0");
OCIExecute($QryGrupo) or die(ora_errorcode());
$RowGrupo = OCIFetch($QryGrupo);
$grupo = $_POST['asicod'].'-'.$_POST['asigru_nue'];

if(OCIResult($QryAsiIns, 1) != 'S' || OCIResult($QryGrupo, 1) != 'S'){
   require_once(dir_conect.'inactiva_usuario.php');
   $ins = OCIParse($oci_conecta, "INSERT INTO accondorlog VALUES(:bclocodigo, :bclotipo, :bclourl, :bcloip, :bclofecha, :bcloestado, :bclotransaccion, :bclohora)");
	OCIBindByName($ins, ":bclocodigo", $_SESSION['usuario_login']);
	OCIBindByName($ins, ":bclotipo", $_SESSION['usuario_nivel']);
	OCIBindByName($ins, ":bclourl", $grupo);
	OCIBindByName($ins, ":bcloip", $_SERVER['REMOTE_ADDR']);
	OCIBindByName($ins, ":bclofecha", $fecha);
	OCIBindByName($ins, ":bcloestado", $estado);
	OCIBindByName($ins, ":bclotransaccion", $transaccion);
	OCIBindByName($ins, ":bclohora", $hora);
	OCIExecute($ins) or die(Ora_ErrorCode());
	OCICommit($oci_conecta);
   //------------------------------------------------
   OCIFreeCursor($QryAsiIns);
   OCIFreeCursor($QryGrupo);
   session_destroy();
   die("<center><h3><font color='#FF0000'>Señor(a): ".$nombre.", esta transacción no está permitida.</font><br>Su usuario fue bloqueado.</h3></center>");
   exit;
}
//Fin Valida cambio de grupo
if($_POST['estcod'] != $_SESSION['usuario_login']){
   require_once(dir_conect.'inactiva_usuario.php');
   $ins = OCIParse($oci_conecta, "INSERT INTO accondorlog VALUES(:bclocodigo, :bclotipo, :bclourl, :bcloip, :bclofecha, :bcloestado, :bclotransaccion, :bclohora)");
	OCIBindByName($ins, ":bclocodigo", $_SESSION['usuario_login']);
	OCIBindByName($ins, ":bclotipo", $_SESSION['usuario_nivel']);
	OCIBindByName($ins, ":bclourl", $_POST['estcod']);
	OCIBindByName($ins, ":bcloip", $_SERVER['REMOTE_ADDR']);
	OCIBindByName($ins, ":bclofecha", $fecha);
	OCIBindByName($ins, ":bcloestado", $estado);
	OCIBindByName($ins, ":bclotransaccion", $transaccion);
	OCIBindByName($ins, ":bclohora", $hora);
	OCIExecute($ins) or die(Ora_ErrorCode());
	OCICommit($oci_conecta);
   //------------------------------------------------
   OCIFreeCursor($QryAsiIns);
   OCIFreeCursor($QryGrupo);
   session_destroy();
   die("<center><h3><font color='#FF0000'>Señor(a): ".$nombre.", esta transacción no está permitida.</font><br>Su usuario fue bloqueado.</h3></center>");
   exit;
}
else{
	 $UpdAsi = OCIParse($oci_conecta, "BEGIN :ba := pck_pr_adicionescancelaciones.fua_control_cambio_grupo(:bano,:bper,:bestcod,:bestcra,:basicod,:bgrucan, :bgruadd); END;");
	 OCIBindByName($UpdAsi, ":bano", $ano);
	 OCIBindByName($UpdAsi, ":bper", $per);
	 OCIBindByName($UpdAsi, ":bestcod", $_SESSION['usuario_login']);
	 OCIBindByName($UpdAsi, ":bestcra", $_SESSION['carrera']);
	 OCIBindByName($UpdAsi, ":basicod", $_POST['asicod']);
	 OCIBindByName($UpdAsi, ":bgrucan", $_POST['asigru_ant']);
	 OCIBindByName($UpdAsi, ":bgruadd", $_POST['asigru_nue']);
	 OCIBindByName($UpdAsi, ":ba", $a,4);
	 OCIExecute($UpdAsi) or die(ora_errorcode());

	 cierra_bd($UpdAsi, $oci_conecta);
	 OCIFreeCursor($QryAsiIns);
     OCIFreeCursor($QryGrupo);
	 header("Location: $redir?error_login=$a");
}
?>