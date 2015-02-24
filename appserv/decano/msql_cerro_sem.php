<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
//require_once('valida_fecha_pt.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();

$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$qry_cerro = "select fua_cerro_sem($cracod) from dual";

$row_cerro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_cerro,"busqueda");

$retorno = $row_cerro[0][0];

if($retorno == 'S')
{
	$resul='<span class="Estilo12"><b>CERR&Oacute; SEMESTRE</b></span>';
}
elseif($retorno == 'N')
{
	$resul='<span class="Estilo10"><b>NO HA CERRADO</b></span>';
}
?>