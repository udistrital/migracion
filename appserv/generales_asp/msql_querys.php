<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);



	$QryMed="SELECT med_cod, med_nombre FROM acmedio WHERE med_estado = 'A' ORDER BY med_nombre"; 
	$RowMed=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryMed,"busqueda");

	$QryCra="SELECT cra_cod, cra_nombre FROM accra WHERE cra_estado = 'A' AND cra_se_ofrece = 'S' ORDER BY cra_dep_cod, cra_cod, cra_nombre"; 
	$RowCra=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCra,"busqueda");

	$QryTCra="SELECT cra_cod, cra_nombre FROM accra WHERE cra_estado = 'A' AND cra_se_ofrece = 'S' ORDER BY cra_nombre"; 
	$RowTCra=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTCra,"busqueda");

	$QryLoc="SELECT loc_nro, loc_nombre
		FROM aclocalidad,acasperiadm
		WHERE ape_ano = loc_ape_ano
		  AND ape_per = loc_ape_per
		  AND ape_estado = 'X'
		  AND loc_estado = 'A'
		ORDER BY loc_nombre"; 

	$RowLoc=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryLoc,"busqueda");

	$QryEstrato="SELECT str_nro, str_nombre
		FROM acestrato,acasperiadm
		WHERE ape_ano = str_ape_ano
		  AND ape_per = str_ape_per
		  AND ape_estado = 'X'
		  AND str_estado = 'A'
		ORDER BY str_nombre"; 
	$RowEstrato=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryEstrato,"busqueda");


	$QryLocCol="SELECT loc_nro, loc_nombre
		FROM aclocalidad,acasperiadm
		WHERE ape_ano = loc_ape_ano
		  AND ape_per = loc_ape_per
		  AND ape_estado = 'X'
		  AND loc_estado = 'A'
		ORDER BY loc_nombre"; 

	$RowLocCol=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryLocCol,"busqueda");

	$QryTipIns="SELECT ti_cod, ti_nombre FROM actipins WHERE ti_cod IN(25,26) AND ti_estado = 'A' ORDER BY ti_nombre"; 
	$RowTipIns=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTipIns,"busqueda");

	$QryTipInsEx="SELECT ti_cod, ti_nombre FROM actipins WHERE ti_cod = 20 AND ti_estado = 'A' ORDER BY ti_nombre"; 
	$RowTipInsEx=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTipInsEx,"busqueda");
	
	$QryTipDis="select discap_code, discap_descr from gediscapacidad";
	$RowTipDis=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryTipDis,"busqueda");
?>
