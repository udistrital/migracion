<?PHP
class CuentaEst{
	function CuentaEstados($cracod, $estado){
		
		include_once("../clase/multiConexion.class.php");
		$esta_configuracion=new config();
		$configuracion=$esta_configuracion->variable("../");
		
		$conexion=new multiConexion();
		$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
		
		$qry_est = "SELECT coalesce(count(est_cod), 0)
			FROM acest x
			WHERE est_cra_cod = $cracod
			AND est_estado_est IN('$estado')
			AND exists(SELECT ins_est_cod
			FROM acasperi,acins
			WHERE ins_est_cod = x.est_cod
			AND ins_cra_cod = x.est_cra_cod
			AND ape_ano = ins_ano
			AND ape_per = ins_per
			AND ape_estado = 'A')";
		//echo $qry_est."<br>";
		$row_est = $conexion->ejecutarSQL($configuracion,$accesoOracle,$qry_est,"busqueda");
		
		$Tot = $row_est[0][0];

		return $Tot;
	}
}
/* * CONSTRUCTOR DE LA CLASE
 * * require_once('class_cuenta_est.php');
 * * $nom = new CuentaEst;
 * * echo $nom->CuentaEstados($cracod, $estado);*/
?>