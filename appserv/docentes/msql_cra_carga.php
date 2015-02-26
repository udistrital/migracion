
<?PHP
//LLAMADO DE doc_adm_correos.php
$Qry_CraCarga = "SELECT DISTINCT(CRA_COD),CRA_NOMBRE
		FROM ACCRA
                INNER JOIN accursos ON cur_cra_cod=cra_cod
                INNER JOIN achorarios ON hor_id_curso=cur_id
                INNER JOIN accargas ON car_hor_id=hor_id
                INNER JOIN acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per
		WHERE car_doc_nro = ".$_SESSION['usuario_login']."
		AND APE_ESTADO = 'A'
		AND CAR_ESTADO = 'A'";
?>