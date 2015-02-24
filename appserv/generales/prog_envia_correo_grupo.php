<?PHP
//DECANO - CONSULTA LOS CORREOS DE LOS COORDINADORES DE PROYECTOS CURRICULARES DE LA FACULTAD.
if($nivel == 16) {
   $sqlemail = OCIParse($oci_conecta, "SELECT UNIQUE(doc_email)||','
										 FROM accra, acdocente
										WHERE cra_dep_cod = ".$_POST['faccod']."
										  AND cra_emp_nro_iden = doc_nro_iden
										  AND cra_estado = 'A'
										  AND doc_estado = 'A'
										  AND doc_email IS NOT NULL");
   OCIExecute($sqlemail) or die(Ora_ErrorCode());
   $rowemail = OCIFetch($sqlemail);
   $msg='ENVIO DE CORREO A LOS COORDINADORES DE PROYECTOS CURRICULARES DE LA FACULTAD.';
}
//COORDINADOR - CONSULTA LOS CORREOS DE LOS DOCENTES CON CARGA ACADEMICA.
//print $cracod; exit;
if($nivel == 4) {
   $sqlemail = OCIParse($oci_conecta, "SELECT UNIQUE(doc_email)||','
										 FROM acasperi, accra, accarga, acdocente
										WHERE cra_cod = ".$_SESSION['carrera']."
										  AND ape_estado = 'A'
										  AND ape_ano = car_ape_ano
										  AND car_ape_per = car_ape_per
										  AND cra_cod = car_cra_cod
										  AND car_estado = 'A'
										  AND car_doc_nro_iden = doc_nro_iden
										  AND doc_estado = 'A'
										  AND doc_email IS NOT NULL");
   OCIExecute($sqlemail) or die(Ora_ErrorCode());
   $rowemail = OCIFetch($sqlemail);
   $msg='ENVIO DE CORREO A LOS DOCENTES CON ASIGNACIÓN ACADÉMICA.';
}
//COORDINADOR - CONSULTA LOS CORREOS DE LOS ESTUDIANTES DE UN PROYECTO CURRICULAR.
if($nivel == 4) {
   $sqlemail = OCIParse($oci_conecta, "SELECT UNIQUE(EOT_EMAIL)||','
										 FROM ACESTOTR,ACINS,ACEST
										WHERE INS_ANO = $ano
										  AND INS_PER = $per
										  AND INS_EST_COD = EOT_COD
										  AND INS_CRA_COD = ".$_SESSION['carrera']."
										  AND EOT_COD = EST_COD
										  AND EOT_EMAIL IS NOT NULL
										  AND EST_ESTADO_EST IN('A','B','H','J','L','T','V')");
   OCIExecute($sqlemail) or die(Ora_ErrorCode());
   $rowemail = OCIFetch($sqlemail);
   $msg='ENVIO DE CORREO A LOS ESTUDIANTES DEL PROYECTO CURRICULAR.';
}
//DOCENTE - CONSULTA LOS CORREOS DE LOS ESTUDIANTES INSCRITOS EN UN CURSO.
if($nivel == 30) {
   $sqlemail = OCIParse($oci_conecta, "SELECT UNIQUE(EOT_EMAIL)||','
										 FROM ACESTOTR,ACINS,ACEST
										WHERE INS_ANO = $ano
										  AND INS_PER = $per
										  AND INS_EST_COD = EOT_COD
										  AND INS_ASI_COD = ".$_POST['asicod']."
										  AND INS_GR = ".$_POST['asigru']."
										  AND INS_CRA_COD = EST_CRA_COD
										  AND EST_COD = EOT_COD 
										  AND EST_ESTADO_EST IN('A','B','H','J','L','T','V')
										  AND EOT_EMAIL IS NOT NULL");
   OCIExecute($sqlemail) or die(Ora_ErrorCode());
   $rowemail = OCIFetch($sqlemail);
   $msg='ENVIO DE CORREO A LOS ESTUDIANTES INSCRITOS EN EL CURSO.';
}
?>