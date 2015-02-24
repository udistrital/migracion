<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(20);

$redir = 'adm_principal.php';

$canclave = 'los_nuevos@est.ud.com';
$qrycanclave="UPDATE ";
$qrycanclave.="geclaves ";
$qrycanclave.="SET ";
$qrycanclave.="cla_clave ='".$canclave."' ";
$qrycanclave.="WHERE ";
$qrycanclave.="length(cla_clave) < 22 ";
	 
$qry_geclaves = "SELECT cla_codigo, cla_clave FROM geclaves WHERE LENGTH(cla_clave) < 22";

$rows = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_geclaves,"busqueda");

if(empty($rows))
{
	echo "<script>location.replace('$redir?error_login=28')</script>";
	exit;
}
$i=0;
$t=1;
while(isset($rows[$i][0]))
{
	$upd_clave="UPDATE ";
	$upd_clave.="geclaves ";
	$upd_clave.="SET ";
	$upd_clave.="cla_clave ='".sha1(md5($rows[$i][1]))."' ";
	$upd_clave.="WHERE ";
	$upd_clave.="cla_codigo ='".$rows[$i][0]."' ";
	
	$row_upd = $conexion->ejecutarSQL($configuracion,$accesoOracle,$upd_clave,"busqueda");
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
print'<br><br><br><br><br><div align="center"><table border="0" width="500">
	 <tr><td width="300" align="center"><b><font size="4" face="Tahoma" color="#FF0000">'.$msg.'</font></b></td>
	 </tr></table></div>';
?>
