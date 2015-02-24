<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");
require_once(dir_conect.'fu_tipo_user.php');

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(50);



$QryCReingreso="SELECT (DECODE(are_ape_per,1,'PRIMER PERIODO',3,'SEGUNDO PERIODO')||' '||are_ape_ano) periodo
, LPAD(are_cred,5,0) credencial
, ti_nombre inscripcion
, are_nro_iden identificacion
, are_est_cod codigo
,DECODE(are_cancelo_sem,'S','SI','N','NO') can_semestre
, are_motivo_retiro mot_retiro
, are_telefono telefono
, are_email email
, (SELECT cra_nombre 
	FROM accra 
	WHERE cra_cod = mntac.acaspreingreso.are_cra_cursando) cra_cursando
, (SELECT cra_nombre
  FROM accra
 WHERE cra_cod = mntac.acaspreingreso.are_cra_transferencia) cra_transfiere
, TO_CHAR(SYSDATE,'DD/MON/YYYY') fecha_impresion
, TRUNC(((TO_NUMBER(TO_CHAR(SYSDATE,'YYYYMMDD'))/are_cred+are_nro_iden))) seguridad
FROM mntac.acasperiadm
	,mntac.acrecbanasp
	,mntac.acaspreingreso
	,mntac.actipins
WHERE ape_ano = rba_ape_ano
  AND ape_per = rba_ape_per
  AND ape_estado = 'X'
  AND rba_nro_iden = ".$_SESSION["usuario_login"]."
  AND rba_clave = '".$_SESSION["usuario_password"]."'
  AND rba_ape_ano = are_ape_ano
  AND rba_ape_per = are_ape_per
  AND rba_asp_cred = are_cred
  AND ti_cod = are_ti_cod";

//echo $QryCReingreso;

$RowCReingreso=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCReingreso,"busqueda");
//var_dump($RowCReingreso);
?>
