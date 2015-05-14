<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

$docusu = "SELECT EMP_NRO_IDEN FROM mntpe.PEEMP WHERE EMP_COD = ".$_SESSION['fun_cod']." AND EMP_ESTADO != 'R'";

$rowusu = $conexion->ejecutarSQL($configuracion,$accesoOracle,$docusu,"busqueda");

$nroiden_fun = $rowusu[0][0];

$beneficiarios = "SELECT BEN_NOMBRE,
		BEN_NRO_IDEN,
		(CASE WHEN BEN_TIPO_IDEN =  1 THEN 'CC' WHEN BEN_TIPO_IDEN =  2 THEN  'TI' WHEN BEN_TIPO_IDEN =  3 THEN  'CE' WHEN BEN_TIPO_IDEN =  4 THEN  'OTROS' END),
		fua_nombre_lugar(BEN_LUG_COD_IDEN),
		TO_CHAR(BEN_FECHA_NAC, 'DD-Mon-YYYY'),
		fua_nombre_lugar(BEN_LUG_COD_NAC),
		BEN_SEXO,
		(CASE WHEN BEN_ESTADO_CIVIL =  1 THEN 'SOLTERO' WHEN BEN_ESTADO_CIVIL = 2 THEN 'CASADO' WHEN BEN_ESTADO_CIVIL = 3 THEN 'UNION LIBRE' WHEN BEN_ESTADO_CIVIL = 4 THEN 'SEPARADO' WHEN BEN_ESTADO_CIVIL = 5 THEN 'VIUDO' END),
		(CASE WHEN BEN_ACTIVIDAD =  1 THEN 'ESTUDIA' WHEN BEN_ACTIVIDAD =  2 THEN 'TRABAJA' WHEN BEN_ACTIVIDAD = 3 THEN 'HOGAR' WHEN BEN_ACTIVIDAD = 4 THEN 'OTROS' END),
		(CASE WHEN BEN_PARENTESCO =  1 THEN 'PADRE' WHEN BEN_PARENTESCO =  2 THEN 'MADRE' WHEN BEN_PARENTESCO = 3 THEN 'HIJO' WHEN BEN_PARENTESCO = 4 THEN 'HERMANO' WHEN BEN_PARENTESCO = 5 THEN 'CONYUGE' WHEN BEN_PARENTESCO = 6 THEN 'OTROS' END),
		BEN_SUB_FAMILIAR,
		BEN_AUX_ESTUDIO,
		BEN_SERV_MEDICO,
		BEN_SEG_VIDA,
		TO_CHAR(BEN_DESDE, 'DD-Mon-YYYY'),
		date_part('year',age(fua_fecha_sys(), BEN_FECHA_NAC)),
		BEN_ESTADO
		FROM PEBEN
		WHERE BEN_EMP_COD = ".$_SESSION['fun_cod']."
		AND BEN_NRO_IDEN != $nroiden_fun
		AND BEN_ESTADO <> 'R'
		ORDER BY BEN_NOMBRE";
?>
