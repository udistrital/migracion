<?PHP
//LLAMADO DE doc_adm_correos.php
/*$qry_curso = "SELECT est_nombre,
		eot_email,
		doc_nombre||' '||doc_apellido,
		doc_email
		FROM acasperi,acins,acest,acestotr,accarga,acdocente
		WHERE ape_ano = ins_ano
		AND ape_per = ins_per
		AND ape_estado = 'A'
		AND ins_asi_cod = ".$_GET['as']."
		AND ins_gr = ".$_GET['gr']."
		AND ins_cra_cod = ".$_GET['C']."
		AND CAR_APE_ANO = INS_ANO
		AND CAR_APE_PER = INS_PER
		AND CAR_CRA_COD = ins_cra_cod
		AND CAR_CUR_ASI_COD = INS_ASI_COD
		AND CAR_CUR_NRO = INS_GR
		AND doc_nro_iden = CAR_DOC_NRO_IDEN
		AND doc_nro_iden = ".$_GET['cc']."
		AND doc_estado = 'A'
		AND est_cod = ins_est_cod
		AND est_cod = eot_cod
		AND eot_email IS NOT NULL
		ORDER BY ins_est_cod";*/

$qry_curso = "SELECT distinct est_nombre,
		eot_email,
		doc_nombre||' '||doc_apellido,
		doc_email,
                ins_est_cod
		FROM acasperi,acins,acest,acestotr,accargas,accursos,achorarios,acdocente
		WHERE ape_ano = ins_ano
		AND ape_per = ins_per
		AND ape_estado = 'A'
		AND ins_asi_cod = ".$_GET['as']."
		AND ins_gr = ".$_GET['cur']."
		AND CUR_APE_ANO = INS_ANO
		AND CUR_APE_PER = INS_PER
		AND CUR_ASI_COD = INS_ASI_COD
		AND cur_id = ins_gr
		AND doc_nro_iden = car_doc_nro
		AND doc_nro_iden = ".$_GET['cc']."
		AND doc_estado = 'A'
		AND est_cod = ins_est_cod
		AND est_cod = eot_cod
		AND eot_email IS NOT NULL
		AND car_hor_id = hor_id
		AND hor_id_curso=cur_id
		ORDER BY ins_est_cod";
?>