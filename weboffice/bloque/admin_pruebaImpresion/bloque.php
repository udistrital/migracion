<?


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"oracle2");
$enlace=$accesoOracle->conectar_db();

//$cadena_sql="SELECT * FROM ACDOCENTE WHERE doc_nombre LIKE '%JULIAN%'";

//$cadena_sql="SELECT * FROM acestmat,acrefest where ema_est_cod=20031001104";
//$cadena_sql="SELECT * FROM acestmat where ema_secuencia>25849 and ema_ano=2009";
//$cadena_sql="SELECT deu_est_cod, deu_cpto_cod, deu_material, deu_ano, deu_per, deu_estado FROM acdeudores WHERE deu_est_cod =20081005044 ";


// $registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle);	

// $cadena_sql="SELECT * FROM acestmat where ema_est_cod=20031001104";
// $cadena_sql="SELECT ";
// $cadena_sql.="est_cod, ";
// $cadena_sql.="est_nro_iden, ";
// $cadena_sql.="est_nombre, ";
// $cadena_sql.="est_cra_cod, ";
// $cadena_sql.="est_diferido, ";
// $cadena_sql.="est_estado_est, ";
// $cadena_sql.="emb_valor_matricula vr_mat, ";
// $cadena_sql.="cra_abrev, ";
// $cadena_sql.="est_exento, ";
// $cadena_sql.="est_motivo_exento, ";
// $cadena_sql.="cra_dep_cod ";						
// $cadena_sql.="FROM ";
// $cadena_sql.="acest, ";
// $cadena_sql.="V_ACESTMATBRUTO, ";
// $cadena_sql.="ACCRA ";
// 
// $cadena_sql.="WHERE ";
// $cadena_sql.="est_cod IN (20042005005, 20051005005) ";
// $cadena_sql.="AND ";
// $cadena_sql.="emb_est_cod = est_cod ";
// $cadena_sql.="AND ";
// $cadena_sql.="cra_cod = est_cra_cod";

$cadena_sql="SELECT cla_codigo, cla_clave, cla_tipo_usu, cla_estado, cla_estado  FROM geclaves WHERE cla_codigo IN (79412539)";
//$cadena_sql="SELECT CRA_EMP_NRO_IDEN,CRA_NOMBRE,cla_clave FROM ACCRA,geclaves WHERE CRA_EMP_NRO_IDEN=cla_codigo";
//$cadena_sql="SELECT * from acestmat where ema_secuencia >25780 and  ema_secuencia <70000 and ema_ano=2009";

//$cadena_sql="select * from acdeudores";
// $cadena_sql="SELECT * FROM ACESTMAT WHERE EMA_EST_COD = 20022005144 AND EMA_ANO = 2009 AND EMA_PER = 1";
// 
// $cadena_sql="SELECT * FROM ACREFEST";
// $cadena_sql="SELECT * FROM ACCRA order by cra_cod ASC";
/*
for($i=26;$i<32;$i++)
{
	$cadena_sql="SELECT ";
	$cadena_sql.="ema_secuencia, ";
	$cadena_sql.="ema_est_cod, ";
	$cadena_sql.="ema_cra_cod, ";
	$cadena_sql.="ema_valor, ";
	$cadena_sql.="ema_ext, ";
	$cadena_sql.="ema_ano, ";
	$cadena_sql.="ema_per, ";
	$cadena_sql.="ema_cuota, ";
	$cadena_sql.="ema_fecha, ";
	$cadena_sql.="ema_estado, ";
	$cadena_sql.="TO_CHAR(EMA_FECHA_ORD, 'YYYYMMDD'), ";
	$cadena_sql.="TO_CHAR(EMA_FECHA_EXT, 'YYYYMMDD'), ";
	$cadena_sql.="est_nro_iden, ";
	$cadena_sql.="est_nombre, ";
	$cadena_sql.="cra_abrev ";
	$cadena_sql.="FROM ";
	$cadena_sql.="ACESTMAT, ";
	$cadena_sql.="ACEST, ";
	$cadena_sql.="ACCRA ";
	$cadena_sql.="WHERE ";
	$cadena_sql.="EMA_SECUENCIA = 144 ";
	$cadena_sql.="AND ";
	$cadena_sql.="EMA_ANO = 2009 ";
	$cadena_sql.="AND ";
	$cadena_sql.="EMA_PER = 1 ";
	$cadena_sql.="AND ";
	$cadena_sql.="EMA_ESTADO='A' ";	
	$cadena_sql.="AND ";
	$cadena_sql.="ema_est_cod = est_cod ";
	$cadena_sql.="AND ";
	$cadena_sql.="ema_cra_cod = cra_cod";
			
*/
$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle);	
if(is_array($registro))
{	
	$i=0;
	$j=0;
	echo "<table border=1>";
	
	while(isset($registro[$i][$j]))
	{
		echo "<tr>";
		while(isset($registro[$i][$j]))
		{
			echo "<td>";
			echo $registro[$i][$j]."(".$j.")"; 
			$j++;
			echo "</td>";
		}
		$j=0;
		$i++;
		echo "</tr>";
	
	}
	
	echo "</table>";

}


function ejecutar_admin_recibo($cadena_sql,$acceso_db)
{
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	return $registro;
}

?>
