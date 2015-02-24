<?PHP
//Llamado de coor_actualiza_datos_doc.php
if($_REQUEST['cedula'] != "")
{
	$cedula = $_REQUEST['cedula'];
}
if($_REQUEST['ced'] != "")
{
	$cedula = $_REQUEST['ced'];
}

$consulta = "SELECT DOC_NRO_IDEN,
	DOC_NOMBRE,
	DOC_APELLIDO,
	DOC_DIRECCION,
	DOC_TELEFONO,
	DOC_TELEFONO_ALT,
	DOC_CELULAR,
	DOC_SEXO,
	DOC_ESTADO_CIVIL,
	TEC_NOMBRE,
	DOC_TIPO_SANGRE,
	DOC_EMAIL
	FROM ACDOCENTE,GETIPESCIVIL
	WHERE DOC_NRO_IDEN = $cedula
	AND DOC_ESTADO_CIVIL = TEC_CODIGO(+)
	AND DOC_ESTADO = 'A'";
?>
