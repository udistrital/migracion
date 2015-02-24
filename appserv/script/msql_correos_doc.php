<?PHP
//LLAMADO DE est_adm_correos_doc.php, doc_adm_correos_doc.php
$Qry_EmDoc = "SELECT DISTINCT (DOC_NOMBRE||' '||DOC_APELLIDO),DOC_EMAIL,DOC_NRO_IDEN
                   FROM accursos
                   INNER JOIN achorarios ON hor_id_curso=cur_id
                   INNER JOIN accargas  ON car_hor_id=hor_id
                   INNER JOIN acdocente on car_doc_nro=doc_nro_iden
                   INNER JOIN acasperi ON cur_ape_ano=ape_ano AND cur_ape_per=ape_per
                   WHERE ape_estado='A'
                   AND car_estado = 'A'
                   and cur_cra_cod=$carrera
                   AND doc_email IS NOT NULL
                  AND doc_estado = 'A'";
?>