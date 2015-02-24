<?PHP
session_name($usuarios_sesion);
session_start();

$QryCReingreso = OCIParse($oci_conecta, "SELECT (DECODE(are_ape_per,1,'PRIMER PERIODO',3,'SEGUNDO PERIODO')||' '||are_ape_ano) periodo
, LPAD(are_cred,5,0) credencial
, ti_nombre inscripcion
, are_nro_iden identificacion
, are_est_cod codigo
,DECODE(are_cancelo_sem,'S','SI','N','NO') can_semestre
, are_motivo_retiro mot_retiro
, are_telefono telefono
, are_email email
, (SELECT cra_nombre 
	FROM mntac.accra 
	WHERE cra_cod = mntac.acaspreingreso.are_cra_cursando) cra_cursando
, (SELECT cra_nombre
  FROM mntac.accra
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
  AND ti_cod = are_ti_cod");

//echo $QryCReingreso;
OCIExecute($QryCReingreso) or die(Ora_ErrorCode());
$RowCReingreso = OCIFetch($QryCReingreso);
?>