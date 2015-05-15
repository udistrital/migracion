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
		DECODE(BEN_TIPO_IDEN, 1,'CC', 2, 'TI', 3, 'CE', 4, 'OTROS'),
		FUA_NOMBRE_LUGAR(BEN_LUG_COD_IDEN),
		TO_CHAR(BEN_FECHA_NAC, 'DD-Mon-YYYY'),
		FUA_NOMBRE_LUGAR(BEN_LUG_COD_NAC),
		BEN_SEXO,
		DECODE(BEN_ESTADO_CIVIL, 1,'SOLTERO',2,'CASADO',3,'UNION LIBRE',4,'SEPARADO',5,'VIUDO'),
		DECODE(BEN_ACTIVIDAD, 1,'ESTUDIA', 2,'TRABAJA',3,'HOGAR',4,'OTROS'),
		DECODE(BEN_PARENTESCO, 1,'PADRE', 2,'MADRE',3,'HIJO',4,'HERMANO',5,'CONYUGE',6,'OTROS'),
		BEN_SUB_FAMILIAR,
		BEN_AUX_ESTUDIO,
		BEN_SERV_MEDICO,
		BEN_SEG_VIDA,
		TO_CHAR(BEN_DESDE, 'DD-Mon-YYYY'),
		TRUNC(MONTHS_BETWEEN(fua_fecha_sys, BEN_FECHA_NAC)/12),
		BEN_ESTADO
		FROM PEBEN
		WHERE BEN_EMP_COD = ".$_SESSION['fun_cod']."
		AND BEN_NRO_IDEN != $nroiden_fun
		AND BEN_ESTADO <> 'R'
		ORDER BY BEN_NOMBRE";
?>
