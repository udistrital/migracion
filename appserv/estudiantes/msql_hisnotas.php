<?php
$cod_consul = "SELECT est_cod,
  					  est_nombre,
  					  est_nro_iden,
  					  cra_cod,
  					  cra_nombre,
  					  trunc(fa_promedio_nota(est_cod)::numeric,2),
  					  v_acnot.NOT_ASI_COD,
  					  v_acnot.ASI_NOMBRE,
  					  coalesce(acnot.not_gr,0),  
  					  v_acnot.not_sem, 
                                            (CASE WHEN LENGTH(v_acnot.NOT_ASI_COD)=2 THEN SUBSTR(ultima,3,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=3 THEN SUBSTR(ultima,4,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=4 THEN SUBSTR(ultima,5,4)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=5 THEN SUBSTR(ultima,6,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=6 THEN SUBSTR(ultima,7,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=7 THEN SUBSTR(ultima,8,4)  
                                            ELSE SUBSTR(ultima,9,4)) 
                                            END) ano,	  					  
                                            (CASE WHEN LENGTH(v_acnot.NOT_ASI_COD)=2 THEN SUBSTR(ultima,7,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=3 THEN SUBSTR(ultima,8,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=4 THEN SUBSTR(ultima,9,1)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=5 THEN SUBSTR(ultima,10,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=6 THEN SUBSTR(ultima,11,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=7 THEN SUBSTR(ultima,12,1)  
                                            ELSE SUBSTR(ultima,13,1)) 
                                            END) per,	  					  
  					  not_nota,
  					  DECODE(nob_cod,0,' ',nob_nombre) nob_nombre,
  					  v_acnot.cursada
				 FROM acest, ACMONTO, ACNOT, v_acnot, acnotobs, accra
				WHERE est_cod = v_acnot.not_est_cod
  				  AND est_cra_cod = cra_cod
  				  AND (acnot.NOT_NOTA = ACMONTO.MON_COD)
  				  AND v_acnot.not_asi_cod = acnot.not_asi_cod
  				  AND v_acnot.not_est_cod = acnot.not_est_cod
  				  AND v_acnot.not_cra_cod = acnot.not_cra_cod
  				  AND acnot.not_ano = TO_NUMBER((CASE WHEN LENGTH(v_acnot.NOT_ASI_COD)=2 THEN SUBSTR(ultima,3,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=3 THEN SUBSTR(ultima,4,4) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=4 THEN SUBSTR(ultima,5,4)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=5 THEN SUBSTR(ultima,6,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=6 THEN SUBSTR(ultima,7,4)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=7 THEN SUBSTR(ultima,8,4)  
                                            ELSE SUBSTR(ultima,9,4)) 
                                            END))
  				  AND acnot.not_per  =   TO_NUMBER((CASE WHEN LENGTH(v_acnot.NOT_ASI_COD)=2 THEN SUBSTR(ultima,7,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=3 THEN SUBSTR(ultima,8,1) 
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=4 THEN SUBSTR(ultima,9,1)  					  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=5 THEN SUBSTR(ultima,10,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=6 THEN SUBSTR(ultima,11,1)  
                                            WHEN LENGTH(v_acnot.NOT_ASI_COD)=7 THEN SUBSTR(ultima,12,1)  
                                            ELSE SUBSTR(ultima,13,1)) 
                                            END))
  				  AND acnot.not_obs = acnotobs.nob_cod
  				  AND acnot.not_obs NOT IN(2,16,7,12)
  				  AND not_est_reg != 'I'
  				  AND v_acnot.not_est_cod = $estcod
			 ORDER BY v_acnot.NOT_SEM, v_acnot.not_asi_cod";
require(dir_conect.'conexion.php');
$consulta = OCIParse($oci_conecta,$cod_consul);
OCIExecute($consulta, OCI_DEFAULT);
$row = OCIFetch($consulta);
?>
