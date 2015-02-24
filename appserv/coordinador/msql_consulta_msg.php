<?PHP
//Llamado de los arcchivos coor_index_msg.php y coor_admin_msg.php 
$qry_msg = "SELECT CME_CODIGO, 
	CME_CRA_COD,
	CRA_NOMBRE,
	CME_AUTOR, 
	CME_TITULO, 
	TO_CHAR(CME_FECHA_INI,'dd/Mon/yyyy'),
	CME_HORA_INI, 
	TO_CHAR(CME_FECHA_FIN,'dd/Mon/yyyy'), 
	CME_MENSAJE,
	CME_TIPO_USU
	FROM accoormensaje, accra
	WHERE CME_CRA_COD = ".$_SESSION['carrera']."
	AND CME_CRA_COD = CRA_COD
	ORDER by 1 DESC,6 DESC"; 
?>