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


$QryCAcasp="SELECT (DECODE(asp_ape_per,1,'PRIMER PERIODO',3,'SEGUNDO PERIODO')||' '||asp_ape_ano) periodo,
   LPAD(asp_cred,5,0) credencial,
   cra_cod,
   cra_nombre carrera,
   ti_nombre inscripcion,
   asp_nacionalidad nacionalidad,
   dep_nombre departamento,
   mun_nombre municipio,
   TO_CHAR(asp_fecha_nac,'DD/MON/YYYY') fechanac,
   decode(asp_sexo, 'M','MASCULINO', 'F','FEMENIMO') sexo,
   DECODE(asp_estado_civil,1,'SOLTERO',2,'CASADO',3,'OTRO') estadocivil,
   asp_direccion direccion,
   loc_nombre localidad,
   asp_estrato estrato,
   asp_telefono telefono,
   asp_email email,
   (DECODE(asp_nro_tip_act,1,'CC',2,'TI',3,'CE')||' '||asp_nro_iden_act) iden_act,
   (DECODE(asp_nro_tip_icfes,1,'CC',2,'TI',3,'CE')||' '||asp_nro_iden_icfes) iden_icfes,
   asp_snp snp,
   (SELECT loc_nombre
    FROM mntac.aclocalidad
    WHERE mntac.acasperiadm.ape_ano = loc_ape_ano
          AND mntac.acasperiadm.ape_per = loc_ape_per
          AND TO_NUMBER(mntac.acaspw.asp_localidad_colegio) = loc_nro) loc_colegio,
   DECODE(ASP_TIPO_COLEGIO,'O','OFICIAL','P','PRIVADO') caracter_colegio,
   ASP_TIPO_DISCAP,
   DECODE(ASP_VALIDA_BTO,'si','SI','no','NO') valido_bachiller,
   TO_CHAR(SYSDATE,'DD/MON/YYYY') fecha_impresion,
   TRUNC(((TO_NUMBER(TO_CHAR(SYSDATE,'YYYYMMDD'))-TO_NUMBER(TO_CHAR(asp_fecha_nac,'YYYYMMDD')))/asp_cred)+cra_cod) seguridad,
   discap_code,
   discap_descr,
   asp_sem_transcurridos,
   asp_estrato_costea estratoCostea 
FROM mntac.acasperiadm, 
   mntac.acrecbanasp, 
   accra, 
   mntac.actipins,
   mntge.gedepartamento,
   mntge.gemunicipio,
   mntac.acaspw,
   mntac.aclocalidad,
   gediscapacidad
WHERE ape_ano = rba_ape_ano
   AND ape_per = rba_ape_per
   AND ape_estado = 'X'
   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
   AND rba_clave = '".$_SESSION["usuario_password"]."'
   AND rba_ape_ano = asp_ape_ano 
   AND rba_ape_per = asp_ape_per 
   AND rba_asp_cred = asp_cred 
   AND cra_cod = asp_cra_cod
   AND ti_cod = asp_ti_cod
   AND dep_cod = asp_dep_cod_nac
   AND mun_cod = asp_mun_cod_nac
   AND loc_ape_ano = asp_ape_ano
   AND loc_ape_per = asp_ape_per
   AND discap_code = ASP_TIPO_DISCAP
   AND loc_nro = TO_NUMBER(asp_localidad)"; 

$RowCAcasp=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCAcasp,"busqueda");

?>
