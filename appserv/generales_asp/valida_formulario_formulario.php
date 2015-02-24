<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
include_once("../clase/multiConexion.class.php");


$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);


	if(isset($_REQUEST['opcion'])&&$_REQUEST['opcion']='consultar'){
		header("Location: ../instructivo/consideraciones.php");
	}
		   
		   
$QryCredNormal = "SELECT rba_nro_iden, rba_clave,DECODE(asp_cred,NULL,'N','S'),asp_cred 
		FROM acasperiadm, acrecbanasp, acaspw
		WHERE ape_ano = rba_ape_ano
		   AND ape_per = rba_ape_per
		   AND ape_estado = 'X'
		   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
		   and rba_clave = '".$_SESSION["usuario_password"]."'
		   AND rba_ape_ano = asp_ape_ano 
		   AND rba_ape_per = asp_ape_per
		   AND rba_asp_cred = asp_cred ";


$QryCredReingreso = "SELECT rba_nro_iden,
		   rba_clave,
		   DECODE(are_cred,NULL,'N','S'),are_cred 
		FROM acasperiadm, acrecbanasp, acaspreingreso
		WHERE ape_ano = rba_ape_ano
		   AND ape_per = rba_ape_per
		   AND ape_estado = 'X'
		   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
		   and rba_clave = '".$_SESSION["usuario_password"]."'
		   AND rba_ape_ano = are_ape_ano
		   AND rba_ape_per = are_ape_per 
		   AND rba_asp_cred = are_cred ";
	
	
$QryCredTransferencia = "SELECT rba_nro_iden,
		   rba_clave,
		   DECODE(atr_cred,NULL,'N','S'),atr_cred 
		FROM acasperiadm, acrecbanasp, acasptransferencia
		WHERE ape_ano = rba_ape_ano
		   AND ape_per = rba_ape_per
		   AND ape_estado = 'X'
		   AND rba_nro_iden = ".$_SESSION["usuario_login"]."
		   and rba_clave = '".$_SESSION["usuario_password"]."'
		   AND rba_ape_ano = atr_ape_ano 
		   AND rba_ape_per = atr_ape_per 
		   AND rba_asp_cred = atr_cred "; 
		   

		   
	$registroN=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCredNormal,"busqueda");

		if(isset($registroN) && $registroN[0][2]=="S"){
			$cadena_sql="select * from acasp,acasperiadm where asp_ape_ano= ape_ano and asp_ape_per= ape_per and ape_estado='X' and asp_cred=".$registroN[0][3];
			$busqueda=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");	
				if(is_array($busqueda)){
					header("Location: ../generales_asp/imprime_colilla_general.php");
				}
				else{	
					header("Location: ../generales_asp/imprime_colilla_acasp.php");
				}
			exit;
		}
		
		
	$registroR=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCredReingreso,"busqueda");

		if(isset($registroR) && $registroR[0][2]=="S"){
			header("Location: ../generales_asp/imprime_colilla_reingreso.php");
			exit;
		}
		
		
	$registroT=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCredTransferencia,"busqueda");

//var_dump($registroT);
		if(isset($registroT) && $registroT[0][2]=="S"){
		
			$cadena_sql="select * from acasp,acasperiadm where asp_ape_ano= ape_ano and asp_ape_per= ape_per and ape_estado='X' and  asp_cred=".$registroT[0][3];
			$busqueda=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");	
				if(is_array($busqueda)){
				//o "1";
					header("Location: ../generales_asp/imprime_colilla_general.php");
				}
				else{	
				//echo "2";
					header("Location: ../generales_asp/imprime_colilla_transferencia.php");
				}
			exit;		

		}




?>
