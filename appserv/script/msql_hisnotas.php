<?php
$cod_consul = "
			 SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod)::numeric,2),
  					  v_acnot.NOT_ASI_COD,
  					  v_acnot.ASI_NOMBRE,
  					  coalesce(acnot.not_gr,0),  
  					  v_acnot.not_sem, 
					(CASE WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=2 THEN SUBSTR(ultima::text,3,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=3 THEN SUBSTR(ultima::text,4,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=4 THEN SUBSTR(ultima::text,5,4)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=5 THEN SUBSTR(ultima::text,6,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=6 THEN SUBSTR(ultima::text,7,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=7 THEN SUBSTR(ultima::text,8,4)  
                                            ELSE SUBSTR(ultima::text,9,4) 
                                            END)  ano,
  					  (CASE WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=2 THEN SUBSTR(ultima::text,7,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=3 THEN SUBSTR(ultima::text,8,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=4 THEN SUBSTR(ultima::text,9,1)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=5 THEN SUBSTR(ultima::text,10,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=6 THEN SUBSTR(ultima::text,11,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=7 THEN SUBSTR(ultima::text,12,1)  
                                            ELSE SUBSTR(ultima::text,13,1) 
                                            END) per,
  					  not_nota,
  					  (CASE WHEN nob_cod=0 THEN ' ' else nob_nombre END) nob_nombre,
  					  v_acnot.cursada
				 FROM acest, mntac.ACMONTO, ACNOT, mntac.v_acnot, mntac.acnotobs,accra
				WHERE est_cod = v_acnot.not_est_cod
  				  AND est_cra_cod = cra_cod
  				  AND (acnot.NOT_NOTA = ACMONTO.MON_COD)
  				  AND v_acnot.not_asi_cod = acnot.not_asi_cod
  				  AND v_acnot.not_est_cod = acnot.not_est_cod
  				  AND v_acnot.not_cra_cod = acnot.not_cra_cod
  				  AND acnot.not_ano::text = (CASE WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=2 THEN SUBSTR(ultima::text,3,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=3 THEN SUBSTR(ultima::text,4,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=4 THEN SUBSTR(ultima::text,5,4)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=5 THEN SUBSTR(ultima::text,6,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=6 THEN SUBSTR(ultima::text,7,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=7 THEN SUBSTR(ultima::text,8,4)  
                                            ELSE SUBSTR(ultima::text,9,4) 
                                            END)
  				  AND acnot.not_per::text  =   (CASE WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=2 THEN SUBSTR(ultima::text,7,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=3 THEN SUBSTR(ultima::text,8,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=4 THEN SUBSTR(ultima::text,9,1)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=5 THEN SUBSTR(ultima::text,10,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=6 THEN SUBSTR(ultima::text,11,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD::text)=7 THEN SUBSTR(ultima::text,12,1)  
                                            ELSE SUBSTR(ultima::text,13,1) 
                                            END)
  				  AND acnot.not_obs = acnotobs.nob_cod
  				  AND acnot.not_obs NOT IN(2,16,7,12)
  				  AND not_est_reg != 'I'
  				  AND v_acnot.not_est_cod = $estcod
			 ORDER BY v_acnot.NOT_SEM, v_acnot.not_asi_cod	";

?>
