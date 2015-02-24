<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(33);

$redir = 'reg_pag_principal.php';

$qry_acrecbanasp = "SELECT RBA_NRO_IDEN, trim(RBA_REF_PAGO) FROM acrecbanasp WHERE RBA_CLAVE IS NULL";

$row_acrecbanasp = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_acrecbanasp,"busqueda");

if(empty($row_acrecbanasp))
{
	echo "<script>location.replace('$redir?error_login=28')</script>";	
	exit;
}
$i = 0;
$t = 1;
while(isset($row_acrecbanasp[$i][0]))
{
	$upd_acrecbanasp="UPDATE ";
	$upd_acrecbanasp.="acrecbanasp ";
	$upd_acrecbanasp.="SET ";
	$upd_acrecbanasp.="rba_clave ='".md5($row_acrecbanasp[$i][1])."' ";
	$upd_acrecbanasp.="WHERE ";
	$upd_acrecbanasp.="rba_nro_iden ='".$row_acrecbanasp[$i][0]."' ";
	$upd_acrecbanasp.="AND ";
	$upd_acrecbanasp.="RBA_REF_PAGO ='".$row_acrecbanasp[$i][1]."' ";
	$upd_acrecbanasp.="AND ";
	$upd_acrecbanasp.="rba_clave IS NULL";
	$row_upd = $conexion->ejecutarSQL($configuracion,$accesoOracle,$upd_acrecbanasp,"busqueda");
	$tot = $tot+$t;
$i++;
}
$cont = $tot;
if(isset($row_upd))
{
	$msg = $cont.' Registros Encriptados.';
}
else
{
	$msg = $cont.' Registro Encriptado.';
}

print'<h3 align="center" style="color:#FF0000; background-color:#E6E6DE">'.$msg.'</h3>';
?>