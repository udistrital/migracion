<?PHP
//Llamado de coor_actualiza_datos_doc.php
$qry="UPDATE ";
$qry.="acdocente ";
$qry.="SET ";
$qry.="DOC_NOMBRE ='".trim(strtoupper($_REQUEST['nombre']))."', ";
$qry.="DOC_APELLIDO ='".trim(strtoupper($_REQUEST['apellido']))."', ";
$qry.="DOC_DIRECCION ='".trim(strtoupper($_REQUEST['dir']))."', ";
$qry.="DOC_TELEFONO ='".$_REQUEST['tel']."', ";
$qry.="DOC_TELEFONO_ALT='".$_REQUEST['tela']."', ";
$qry.="DOC_SEXO='".$_REQUEST['sex']."', ";
$qry.="DOC_ESTADO_CIVIL='".$_REQUEST['estc']."', ";
$qry.="DOC_TIPO_SANGRE='".$_REQUEST['tisa']."', ";
$qry.="DOC_CELULAR='".$_REQUEST['cel']."', ";
$qry.="DOC_EMAIL='".trim(strtolower($_REQUEST['mail']))."' ";
$qry.="WHERE ";
$qry.="doc_nro_iden ='".$_REQUEST['ced']."' ";
$row_qry = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry,"busqueda");
?>
