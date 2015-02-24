<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'fu_tipo_user.php');
fu_tipo_user(20);

//LLAMADO DE adm_actualiza_datos_est.php
$qery = OCIParse($oci_conecta, "UPDATE ACEST
								   SET EST_NRO_IDEN = :bnroiden,
									   EST_NOMBRE = :bestnom,
									   EST_DIRECCION = :bdir,
									   EST_TELEFONO = :btel,
									   EST_SEXO = :bsex,
									   EST_ZONA_POSTAL = :bzonap
								 WHERE EST_COD =".$_POST['estcod']);
		OCIBindByName($qery, ":bnroiden", $_POST['nroiden']);
		OCIBindByName($qery, ":bestnom", trim(strtoupper($_POST['estnom'])));
		OCIBindByName($qery, ":bdir", $_POST['dir']);
	 	OCIBindByName($qery, ":btel", $_POST['tel']);
		OCIBindByName($qery, ":bsex", $_POST['sex']);
	 	OCIBindByName($qery, ":bzonap", $_POST['zonap']);
	 	OCIExecute($qery);
	 	OCICommit($oci_conecta);
     
   		$qry = OCIParse($oci_conecta, "UPDATE ACESTOTR
		                                  SET EOT_FECHA_NAC = to_date(:bfecnac,'DD/MM/YYYY'),
										  	  EOT_COD_LUG_NAC = :blugnac,
										  	  EOT_EMAIL = :bmail,
	   										  EOT_TIPOSANGRE = :btisa,
	   										  EOT_RH = :brh,
											  EOT_ESTADO_CIVIL = :bestc,
											  EOT_EMAIL_INS = :bmailud
							  	   		WHERE EOT_COD =".$_POST['estcod']); 
     	OCIBindByName($qry, ":bfecnac", $_POST['fecnac']);
		OCIBindByName($qry, ":blugnac", $_POST['lugnac']);
		OCIBindByName($qry, ":bmail", trim(strtolower($_POST['mail'])));
	 	OCIBindByName($qry, ":btisa", $_POST['tisa']);
	 	OCIBindByName($qry, ":brh", $_POST['rh']);
		OCIBindByName($qry, ":bestc", $_POST['estc']);
		OCIBindByName($qry, ":bmailud", $_POST['mailud']);
	 	OCIExecute($qry);
	 	OCICommit($oci_conecta);
		cierra_bd($qery,$oci_conecta);
        cierra_bd($qry,$oci_conecta);
?>