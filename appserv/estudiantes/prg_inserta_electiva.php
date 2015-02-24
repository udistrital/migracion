<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'conexion.php');
require_once(dir_conect.'cierra_bd.php');
require_once('valida_adicion.php');
require_once('valida_estudiante_activo.php');
require_once('valida_estudiantes_nuevos.php');
require_once(dir_script.'msql_ano_per.php');
include_once(dir_script.'class_nombres.php');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(51);

$redir = 'est_error_electivas.php';
$estcod = $_SESSION['usuario_login'];
$estcra = $_SESSION['carrera'];

$nom = new Nombres;
$nombre = $nom->NombreEstudiante($estcod);

//valida requisitos
$asicod = OCIParse($oci_conecta, "SELECT 'S'
  									FROM v_acestmatelectivas,acest
 								   WHERE emi_est_cod = ".$_SESSION['usuario_login']."
								     AND emi_est_cod = est_cod
									 AND emi_asi_cod = ".$_POST['asicod']."
									 AND emi_nro_sem = ".$_POST['asisem']."
									 AND emi_pen_nro = ".$_POST['pensum']."
									 AND est_estado_est IN('A','B')");
OCIExecute($asicod) or die(ora_errorcode());
$rowc = OCIFetch($asicod);

if($rowc != 1){
   //require_once('est_inactiva_est.php');
   require_once(dir_conect.'inactiva_usuario.php');
   OCIFreeCursor($asicod);
   session_destroy();
   die("<center><h3><font color='#FF0000'>Señor(a): ".$nombre.", esta transacción no está permitida.</font><br>Su usuario fue bloqueado.</h3></center>");
   exit; 
}
//fin valida requisitos

if($_POST['estcod'] != $_SESSION['usuario_login']){
   require_once(dir_conect.'inactiva_usuario.php');
   OCIFreeCursor($asicod);
   session_destroy();
   die("<center><h3><font color='#FF0000'>Señor(a): ".$nombre.", esta transacción no está permitida.</font><br>Su usuario fue bloqueado.</h3></center>");
   exit;
}
else{
	 $AddAsi = OCIParse($oci_conecta, "BEGIN :ba := pck_pr_adicionescancelaciones.fua_control_adicion(:bano,:bper,:bestcod,:bestcra,:basicod,:basigru,:basisem,:bestpen); END;");
	 OCIBindByName($AddAsi, ":bano", $ano);
	 OCIBindByName($AddAsi, ":bper", $per);
	 OCIBindByName($AddAsi, ":bestcod", $_SESSION['usuario_login']);
	 OCIBindByName($AddAsi, ":bestcra", $_SESSION['carrera']);
	 OCIBindByName($AddAsi, ":basicod", $_POST['asicod']);
	 OCIBindByName($AddAsi, ":basigru", $_POST['asigru']);
	 OCIBindByName($AddAsi, ":basisem", $_POST['asisem']);
	 OCIBindByName($AddAsi, ":bestpen", $_POST['pensum']);
	 OCIBindByName($AddAsi, ":ba", $a,4);
	 OCIExecute($AddAsi) or die(ora_errorcode());

	 cierra_bd($AddAsi, $oci_conecta);
	 OCIFreeCursor($asicod);
	 header("Location: $redir?error_login=$a");
}
?>