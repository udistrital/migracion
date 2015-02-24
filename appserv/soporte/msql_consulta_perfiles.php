<?PHP
$datos = "SELECT CLA_CODIGO,
	CLA_CLAVE,
	USUTIPO_TIPO,
	CLA_TIPO_USU,
	CLA_ESTADO
	FROM geclaves,geusutipo
	WHERE USUTIPO_COD = cla_tipo_usu
	AND CLA_CODIGO  = ".$_REQUEST["usuario"];
?>