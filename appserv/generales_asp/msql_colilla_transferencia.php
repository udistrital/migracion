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
   
$QryCTransferencia="SELECT (DECODE(atr_ape_per,1,'PRIMER PERIODO',3,'SEGUNDO PERIODO')||' '||atr_ape_ano) periodo,
											   LPAD(atr_cred,5,0) credencial,
											   cra_nombre carrera,
											   ti_nombre tip_ins,
											   atr_universidad_proviene uni_proviene,
											   atr_carrera_proviene cra_proviene,
											   atr_semestre sem_proviene,
											   atr_nacionalidad nacionalidad,
											   dep_nombre departamento,
											   mun_nombre municipio,
											   TO_CHAR(atr_fecha_nac,'DD/MON/YYYY') fechanac,
											   DECODE(atr_sexo,'M','MASCULINO','F','FEMENINO') sexo,
											   DECODE(atr_estado_civil,1,'SOLTERO',2,'CASADO',3,'OTRO') estadocivil,
											   atr_direccion direccion,
											   loc_nombre localidad,
											   atr_estrato estrato,
											   atr_telefono telefono,
											   atr_email email,
											   (DECODE(atr_nro_tip_act,1,'CC',2,'TI',3,'CE')||' '||atr_nro_iden_act) iden_act,
											   (DECODE(atr_nro_tip_icfes,1,'CC',2,'TI',3,'CE')||' '||atr_nro_iden_icfes) iden_icfes,
											   atr_snp snp,
											   (SELECT loc_nombre
												FROM mntac.aclocalidad
												WHERE mntac.acasptransferencia.atr_ape_ano = loc_ape_ano
													  AND mntac.acasptransferencia.atr_ape_per = loc_ape_per
													  AND TO_NUMBER(mntac.acasptransferencia.atr_localidad_colegio) = loc_nro) loc_colegio,
											   TO_CHAR(SYSDATE,'DD/MON/YYYY') fecha_impresion,
											   TRUNC(((TO_NUMBER(TO_CHAR(SYSDATE,'YYYYMMDD'))-TO_NUMBER(TO_CHAR(atr_fecha_nac,'YYYYMMDD')))/atr_cred)+cra_cod) seguridad
											FROM mntac.acasperiadm, 
											   mntac.acrecbanasp, 
											   accra, 
											   mntge.gedepartamento,
											   mntge.gemunicipio,
											   mntac.acasptransferencia,
											   mntac.aclocalidad,
											   mntac.actipins
											WHERE ape_ano = rba_ape_ano
											   AND ape_per = rba_ape_per
											   AND ape_estado = 'X'
											   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
											   AND rba_clave = '".$_SESSION["usuario_password"]."'
											   AND rba_ape_ano = atr_ape_ano 
											   AND rba_ape_per = atr_ape_per 
											   AND rba_asp_cred = atr_cred 
											   AND cra_cod = atr_cra_cod
											   AND ti_cod = atr_ti_cod
											   AND dep_cod (+) = atr_dep_cod_nac
											   AND mun_cod (+) = atr_mun_cod_nac
											   AND loc_ape_ano = atr_ape_ano
											   AND loc_ape_per = atr_ape_per
											   AND loc_nro = TO_NUMBER(atr_localidad)"; 

$RowCTransferencia=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCTransferencia,"busqueda");
?>
