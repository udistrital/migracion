<?php
$cod_consul = "SELECT EST_COD,
		EST_NOMBRE,
		EST_NRO_IDEN,
		CRA_COD,
		CRA_NOMBRE,
		TRUNC(FA_PROMEDIO_NOTA(EST_COD),2),
		ASI_COD,
		ASI_NOMBRE,
		INS_GR,
		DOC_NRO_IDEN,
		(LTRIM(RTRIM(DOC_NOMBRE))||'
	      '||LTRIM(RTRIM(DOC_APELLIDO))),
		DOC_EMAIL
		FROM ACCRA, ACEST,ACINS,
		ACASI,ACASPERI,ACCARGA,ACDOCENTE
		WHERE CRA_COD = EST_CRA_COD
                                     AND EST_ESTADO_EST IN ('V','J')
                                     AND EST_COD     = INS_EST_COD
                                     AND EST_CRA_COD = INS_CRA_COD

                                     AND ASI_COD     = INS_ASI_COD

                                     AND APE_ANO     = INS_ANO
                                     AND APE_PER     = INS_PER
                                     AND APE_ESTADO  = 'V'
                                     AND EST_COD = ".$_SESSION['usuario_login']."

                                     AND INS_ANO     = CAR_APE_ANO(+)
                                     AND INS_PER     = CAR_APE_PER(+)
                                     AND INS_CRA_COD = CAR_CRA_COD(+)
                                     AND INS_ASI_COD = CAR_CUR_ASI_COD(+)
                                     AND INS_GR      = CAR_CUR_NRO(+)
                                     AND CAR_DOC_NRO_IDEN = DOC_NRO_IDEN (+)

                     order by INS_ASI_COD";
 // echo  $cod_consul."mmm";   
?>

 