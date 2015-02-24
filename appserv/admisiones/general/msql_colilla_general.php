<?PHP
require('../conexion/conexion.php');
$QryCred =OCIParse($oci_conecta, "SELECT rba_asp_cred 
   FROM acrecbanasp 
   WHERE rba_nro_iden = ".$_SESSION["usuario_login"]."
	AND rba_clave = '".$_SESSION["usuario_password"]."'");

OCIExecute($QryCred, OCI_DEFAULT) or die(Ora_ErrorCode());
$RowCred = OCIFetch($QryCred);
$credencial = OCIResult($QryCred,1);
OCIFreeCursor($QryCred);
//echo $credencial;

$QryColillaGen_2 = "SELECT ape_ano anio,
ape_per periodo,
asp_cred credencial,
cra_cod codigo,
 cra_nombre carrera,
ti_cod tip_ins,
ti_nombre inscripcion,
 asp_apellido apellidos,
 asp_nombre nombres,
 asp_nro_iden identificacion,
 DECODE(asp_sexo,'F','FEMENINO','M','MASCULINO') sexo,
 DECODE(asp_ser_militar,'S','SI','N','NO') serv_militar,
 loc_nombre localidad,
 str_nro estrato,
 med_nombre medio_de_publicidad,
 asp_snp snp,
 DECODE(asp_tip_icfes,'N','NUEVO','A','ANTIGUO',asp_tip_icfes) tipo_icfes,
 asp_cie_soc ciencias_sociales,
 asp_bio biologia,
 asp_qui quimica,
 asp_fis fisica,
 asp_soc sociales,
asp_apt_verbal apt_verbal,
 asp_esp_y_lit esp_y_lit,
 asp_apt_mat apt_mat,
 asp_con_mat con_mat,
 asp_fil filosofia,
 asp_his historia,
 asp_geo geografia,
 asp_idioma idioma,
 ele_nombre electiva
FROM mntac.acasperiadm,
 mntac.aclocalidad,
 mntac.acestrato,
 mntac.acmedio,
 mntac.accra,
 mntac.actipins,
 mntac.acelect,
 mntac.acasp
WHERE ape_estado = 'X'
 AND asp_cred =$credencial
 AND ape_ano = asp_ape_ano
 AND ape_per = asp_ape_per
 AND med_cod = NVL(asp_med_cod,7)
 AND cra_cod = asp_cra_cod
 AND ti_cod = asp_ti_cod
 AND asp_localidad = loc_nro
 AND ape_ano = loc_ape_ano
 AND ape_per = loc_ape_per
 AND loc_estado = 'A'
 AND asp_estrato = str_nro
 AND ape_ano = str_ape_ano
 AND ape_per = str_ape_per
 AND ele_cod = asp_electiva";
//echo $QryColillaGen_2;


$QryColillaGen = OCIParse($oci_conecta,$QryColillaGen_2);
OCIExecute($QryColillaGen, OCI_DEFAULT) or die(Ora_ErrorCode());
$RowColillaGen = OCIFetch($QryColillaGen);
if(empty($RowColillaGen)) header("Location: ../err/err_consulta_inscripcion.php");
?>

