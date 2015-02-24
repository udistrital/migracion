<?php
/*
 * Created on 17/12/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
$cripto=new encriptar();


$datoBasico=new datosGenerales();

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");

$sesion=new sesiones($configuracion);
$sesion->especificar_enlace($enlace);
$usuario = $sesion->rescatar_valor_sesion($configuracion,"id_usuario");
$permisos = $datoBasico->rescatarDatoGeneral($configuracion, "permisos_cra", "", $acceso_db);

$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"oracle");
$enlace=$accesoOracle->conectar_db();


if($usuario[0][0] == 1){
	$variable="pagina=administrador";
	$variable=$cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$indice.$variable."')</script>";	
	//echo "Administrador";
}else{
	$carreras = $datoBasico->rescatarDatoGeneral($configuraci�n, "carrerasCoordinador", $usuario[0][0], $accesoOracle);
	$bandera = false;
	foreach ( $carreras as $value ) {
       $registro = $datoBasico->rescatarDatoGeneral($configuracion, "nivelCarrera", $value[0], $accesoOracle);
       $bandera = false;
       switch ( $registro ) {
			case "POSGRADO":
				if($permisos[0][0]==1){
					$bandera = true;
				}
				break;
			case "PREGRADO":
				if($permisos[1][0]==1){
					$bandera = true;
				}
				break;
			case "MAESTRIA":
				if($permisos[2][0]==1){
					$bandera = true;
				}
				break;
			case "ESPECIALIZACION":
				if($permisos[3][0]==1){
					$bandera = true;
				}
				break;
					
			default:
				break;
		}
		if($bandera){break;}
	}
	if($bandera){
		$variable="pagina=administrador";
		$variable=$cripto->codificar_url($variable,$configuracion);
		echo "<script>location.replace('".$indice.$variable."')</script>";	
	}else{
		echo "No tiene Acceso";
	}
}
?>
