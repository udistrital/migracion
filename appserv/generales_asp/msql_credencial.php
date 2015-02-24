<?PHP
require_once('dir_relativo.cfg');
include_once("../clase/multiConexion.class.php");
require_once(dir_conect.'fu_tipo_user.php');


fu_tipo_user(50);

		$conexion=new multiConexion();
		$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

//TRAE LA CREDENCIAL
		$QryCred ="SELECT rba_asp_cred
			 FROM acrecbanasp, acasperiadm
		   	 WHERE ape_ano = rba_ape_ano
			  AND ape_per = rba_ape_per
			  AND ape_estado = 'X'
			  AND rba_nro_iden =  ".$_SESSION["usuario_login"]."
			  AND rba_clave = '".$_SESSION["usuario_password"]."'"; 

		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCred,"busqueda");
		
		//echo $QryCred;
		//var_dump($registro);
		
if(!is_array($registro )){
	die('<p>&nbsp;</p><p align="center"><b><font color="#FF0000">No se ha generado una credencial.</font></b></p>');
	exit;
}
else{
	$cred=$registro[0][0];
}
//$cred=123458;

?>
